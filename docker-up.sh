#!/bin/sh
# Docker Desktop credsStore 오류 우회 + compose 플러그인 직접 호출(인자 오류 방지)
cd "$(dirname "$0")" || exit 1
export DOCKER_CONFIG="$(pwd)/.docker-cli"

# Docker Compose V2 CLI 플러그인 (Docker Desktop / Homebrew 공통 경로)
for plug in \
  "${HOME}/.docker/cli-plugins/docker-compose" \
  "/usr/local/lib/docker/cli-plugins/docker-compose" \
  "/opt/homebrew/lib/docker/cli-plugins/docker-compose"
do
  if [ -x "$plug" ]; then
    exec "$plug" "$@"
  fi
done

# 플러그인 경로를 못 찾으면 docker compose 서브커맨드
if docker compose version >/dev/null 2>&1; then
  exec docker compose "$@"
fi

# Compose V1
if command -v docker-compose >/dev/null 2>&1; then
  exec docker-compose "$@"
fi

echo "docker compose(플러그인) 또는 docker-compose 를 찾을 수 없습니다." >&2
echo "Docker Desktop에서 Compose가 켜져 있는지 확인하세요." >&2
exit 1
