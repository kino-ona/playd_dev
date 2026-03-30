#!/bin/sh
# Docker MySQL(playd-mysql, 호스트 포트 3307)가 떠 있는 상태에서 PHP 내장 서버 실행
set -e
cd "$(dirname "$0")/.."
export PLAYD_DB_HOST="${PLAYD_DB_HOST:-127.0.0.1}"
export PLAYD_DB_PORT="${PLAYD_DB_PORT:-3307}"
export PLAYD_DB_USER="${PLAYD_DB_USER:-root}"
export PLAYD_DB_PASSWORD="${PLAYD_DB_PASSWORD:-root}"
export PLAYD_DB_NAME="${PLAYD_DB_NAME:-playd}"
exec php -S localhost:8000 router.php
