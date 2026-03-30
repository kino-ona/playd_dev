#!/usr/bin/env python3
"""
운영(playd.com)에서 W 사이트 정적 자원을 내려받습니다.

- assets/css/w/*.css 안의 url(...) → 이미지·폰트 등
- w/*.html 의 src/href 가 가리키는 ../assets/... 경로
- 공통으로 쓰는 W 전용·서브페이지 JS (swiper, w/ui.js, jquery-ui 등)

사용:
  python3 scripts/sync_assets_from_production.py
  python3 scripts/sync_assets_from_production.py --base https://www.playd.com
"""

from __future__ import annotations

import argparse
import re
import sys
import urllib.error
import urllib.request
from pathlib import Path
from urllib.parse import unquote, urljoin, urlparse

URL_IN_CSS = re.compile(r"url\(\s*([^)]+)\s*\)")
# src="../assets/..." or href (영상·파일)
HTML_ASSET = re.compile(
    r"""src\s*=\s*(["'])((?:\.\./)+assets/[^'"]+)\1""",
    re.IGNORECASE,
)
HTML_ASSET_HREF = re.compile(
    r"""href\s*=\s*(["'])((?:\.\./)+assets/[^'"]+)\1""",
    re.IGNORECASE,
)

DEFAULT_EXTRA_JS = [
    "assets/js/w/swiper.min.js",
    "assets/js/w/ui.js",
    "assets/js/jquery-ui-1.13.1.js",
    "assets/js/datepicker.js",
]


def _find_repo(start: Path) -> Path:
    cur = start.resolve()
    if cur.is_file():
        cur = cur.parent
    for _ in range(16):
        if (cur / "assets").is_dir() and (cur / "include").is_dir():
            return cur
        if cur.parent == cur:
            break
        cur = cur.parent
    raise ValueError(f"프로젝트 루트를 찾을 수 없습니다: {start}")


def _strip_url_arg(raw: str) -> str:
    s = raw.strip().strip('"').strip("'").strip()
    if "?" in s and not s.startswith("data:"):
        s = s.split("?", 1)[0]
    return s


def _urls_from_css(css_path: Path, base: str) -> list[tuple[str, Path]]:
    """Return (absolute_url, local_path_under_repo)."""
    repo_root = _find_repo(css_path)

    rel_css = css_path.relative_to(repo_root).as_posix()
    web_css = f"{base.rstrip('/')}/{rel_css}"
    text = css_path.read_text(encoding="utf-8", errors="ignore")
    out: list[tuple[str, Path]] = []
    for m in URL_IN_CSS.finditer(text):
        raw = _strip_url_arg(m.group(1))
        if not raw or raw.startswith("data:"):
            continue
        if raw.startswith(("http://", "https://", "//")):
            continue
        if raw.startswith("#"):
            continue
        abs_u = urljoin(web_css, raw)
        parsed = urlparse(abs_u)
        if parsed.netloc and parsed.netloc != urlparse(base).netloc:
            continue
        path_part = unquote(parsed.path.lstrip("/"))
        if not path_part.startswith("assets/"):
            continue
        local = repo_root / path_part
        try:
            local.relative_to(repo_root)
        except ValueError:
            continue
        out.append((abs_u, local))
    return out


def _paths_from_html(html_path: Path) -> list[Path]:
    repo_root = _find_repo(html_path)

    text = html_path.read_text(encoding="utf-8", errors="ignore")
    rels: list[str] = []
    for rx in (HTML_ASSET, HTML_ASSET_HREF):
        rels += [m.group(2) for m in rx.finditer(text)]
    out: list[Path] = []
    for rel in rels:
        if "?" in rel:
            rel = rel.split("?", 1)[0]
        p = (html_path.parent / rel).resolve()
        try:
            p.relative_to(repo_root)
        except ValueError:
            continue
        if str(p).startswith(str(repo_root / "assets")):
            out.append(p)
    return out


def fetch(url: str, dest: Path, timeout: int = 300) -> bool:
    dest.parent.mkdir(parents=True, exist_ok=True)
    req = urllib.request.Request(
        url,
        headers={"User-Agent": "playd-dev-asset-sync/1.0"},
    )
    try:
        with urllib.request.urlopen(req, timeout=timeout) as r:
            dest.write_bytes(r.read())
    except urllib.error.HTTPError as e:
        print(f"  HTTP {e.code} {url}", file=sys.stderr)
        return False
    except (urllib.error.URLError, TimeoutError, OSError) as e:
        print(f"  ERR {url} → {e}", file=sys.stderr)
        return False
    return True


def main() -> int:
    ap = argparse.ArgumentParser(description=__doc__)
    ap.add_argument("--base", default="https://www.playd.com", help="운영 오리진")
    ap.add_argument(
        "--root",
        type=Path,
        default=None,
        help="프로젝트 루트 (기본: 이 스크립트 상위 디렉터리)",
    )
    ap.add_argument(
        "--with-large-media",
        action="store_true",
        help="assets/files/*.mp4 등 대용량 영상도 받기 (기본: 건너뜀)",
    )
    ap.add_argument(
        "--ignore-errors",
        action="store_true",
        help="일부 404·네트워크 오류가 있어도 종료 코드 0",
    )
    args = ap.parse_args()
    base = args.base.rstrip("/")
    root = (args.root or Path(__file__).resolve().parent.parent).resolve()

    pairs: list[tuple[str, Path]] = []
    seen: set[Path] = set()

    for css_rel in ("assets/css/w/ui.css", "assets/css/w/index.css"):
        p = root / css_rel
        if p.is_file():
            for u, loc in _urls_from_css(p, base):
                if loc not in seen:
                    seen.add(loc)
                    pairs.append((u, loc))

    w_dir = root / "w"
    if w_dir.is_dir():
        for html in sorted(w_dir.glob("*.html")):
            for loc in _paths_from_html(html):
                if loc in seen:
                    continue
                seen.add(loc)
                rel = loc.relative_to(root).as_posix()
                pairs.append((f"{base}/{rel}", loc))

    for js_rel in DEFAULT_EXTRA_JS:
        loc = root / js_rel
        if loc not in seen:
            seen.add(loc)
            pairs.append((f"{base}/{js_rel}", loc))

    skip_large = re.compile(
        r"(?i)/assets/files/.*\.(mp4|webm)$",
    )

    ok = skip = fail = 0
    for url, loc in sorted(pairs, key=lambda x: str(x[1])):
        rel_s = "/" + loc.relative_to(root).as_posix()
        if not args.with_large_media and skip_large.search(rel_s):
            skip += 1
            print(f"SKIP (대용량) {loc.relative_to(root)}", file=sys.stderr)
            continue
        if fetch(url, loc):
            ok += 1
            print(f"OK  {loc.relative_to(root)}")
        else:
            fail += 1
    print(
        f"\n완료: 성공 {ok}, 건너뜀 {skip}, 실패 {fail}",
        file=sys.stderr,
    )
    if fail and not args.ignore_errors:
        return 1
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
