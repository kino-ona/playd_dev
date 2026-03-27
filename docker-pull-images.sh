#!/bin/sh
# Docker Hub 레이어 수신 중 EOF 나올 때: 끊길 때마다 재시도
cd "$(dirname "$0")" || exit 1
export DOCKER_CONFIG="$(pwd)/.docker-cli"

retry_pull() {
  img=$1
  max=${2:-15}
  n=0
  while [ "$n" -lt "$max" ]; do
    n=$((n + 1))
    echo "=== pull ($n/$max): $img ==="
    if docker pull "$img"; then
      echo "OK: $img"
      return 0
    fi
    w=$((n * 5))
    echo "실패. ${w}초 후 재시도..."
    sleep "$w"
  done
  echo "포기: $img" >&2
  return 1
}

retry_pull "php:8.2-apache" || exit 1
retry_pull "mariadb:10.11" || exit 1
echo "모든 이미지 pull 완료. 이제: ./docker-up.sh up -d"
