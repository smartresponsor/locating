#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
LOG_DIR="${ROOT_DIR}/var/log"
PORT="${PORT:-9080}"
HOST="${HOST:-127.0.0.1}"
CHROMEDRIVER_PORT="${CHROMEDRIVER_PORT:-9515}"
CHROMEDRIVER_LOG="${LOG_DIR}/chromedriver.log"
SERVER_LOG="${LOG_DIR}/panther-server.log"

mkdir -p "${LOG_DIR}"

cleanup() {
  if [[ -n "${SERVER_PID:-}" ]] && kill -0 "${SERVER_PID}" 2>/dev/null; then
    kill "${SERVER_PID}" 2>/dev/null || true
    wait "${SERVER_PID}" 2>/dev/null || true
  fi
  if [[ -n "${CHROMEDRIVER_PID:-}" ]] && kill -0 "${CHROMEDRIVER_PID}" 2>/dev/null; then
    kill "${CHROMEDRIVER_PID}" 2>/dev/null || true
    wait "${CHROMEDRIVER_PID}" 2>/dev/null || true
  fi
}
trap cleanup EXIT

cd "${ROOT_DIR}"

php -S "${HOST}:${PORT}" router.php >"${SERVER_LOG}" 2>&1 &
SERVER_PID=$!

npx chromedriver --port="${CHROMEDRIVER_PORT}" >"${CHROMEDRIVER_LOG}" 2>&1 &
CHROMEDRIVER_PID=$!

sleep 2

PANTHER_EXTERNAL_BASE_URI="http://${HOST}:${PORT}" \
PANTHER_CHROME_BINARY="${PANTHER_CHROME_BINARY:-/usr/bin/google-chrome}" \
PANTHER_NO_SANDBOX=1 \
php vendor/bin/phpunit -c phpunit.xml.dist tests/Panther --group panther
