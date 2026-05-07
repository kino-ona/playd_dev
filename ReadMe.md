# Playd Dev

## 사전 요구사항

| 도구 | 용도 |
|------|------|
| [Docker Desktop](https://www.docker.com/products/docker-desktop/) | 웹서버(PHP/Apache) + DB(MariaDB) 실행 |
| [Node.js](https://nodejs.org/) (v18+) | SCSS 컴파일 |
| [VS Code](https://code.visualstudio.com/) | 권장 에디터 |
| VS Code 확장: [Live Sass Compiler](https://marketplace.visualstudio.com/items?itemName=glenn2223.live-sass) | SCSS 자동 컴파일 |

---

## 최초 설치

### 1. 패키지 설치 (SCSS 컴파일러)

```bash
npm install
```

### 2. 서버 실행

```bash
docker compose up
```

최초 실행 시 Docker 이미지 pull + PHP 확장 설치(mysqli)가 진행됩니다.  
완료 후 브라우저에서 확인:

- **사용자**: http://localhost:8080
- **관리자**: http://localhost:8080/adm

### 3. DB

`docker/mysql/init/` 안의 SQL 파일이 최초 기동 시 자동 실행됩니다.  
(볼륨이 이미 존재하면 재실행되지 않습니다)

접속 정보:

| 항목 | 값 |
|------|----|
| Host | `127.0.0.1` |
| Port | `3307` |
| DB | `playd` |
| User | `root` |
| Password | `root` |

---

## SCSS 작업

SCSS 파일은 `assets/scss/` 아래에서 작업하면 `assets/css/`로 자동 출력됩니다.

```
assets/scss/m/  →  assets/css/m/
assets/scss/w/  →  assets/css/w/
```

### 방법 A — VS Code Live Sass Compiler (권장)

VS Code 하단 상태바의 **Watch Sass** 버튼 클릭  
→ 파일 저장 시 자동 컴파일

### 방법 B — 터미널

```bash
npm run sass:watch   # 실시간 감지
npm run sass:build   # 1회 빌드
```

---

## 주요 구조

```
assets/
  scss/          # SCSS 소스 (여기서 작업)
    m/           # 모바일
    w/           # PC
  css/           # 컴파일된 CSS 출력 (git 미추적)
docker/
  mysql/init/    # DB 초기 시드 SQL
  php/           # Apache 설정, entrypoint
include/         # PHP 공통 헤더/푸터
adm/             # 관리자 페이지
```

---

## 자주 쓰는 명령어

```bash
docker compose up          # 서버 시작
docker compose up -d       # 백그라운드 실행
docker compose down        # 서버 종료
docker compose down -v     # 종료 + DB 볼륨 삭제 (초기화)
docker compose logs -f web # 웹서버 로그 확인
```
