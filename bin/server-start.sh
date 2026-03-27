#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PID_DIR="${ROOT_DIR}/var/run"
LOG_DIR="${ROOT_DIR}/var/log"
PID_FILE="${PID_DIR}/local-server.pid"
PORT="${PORT:-9080}"
HOST="${HOST:-127.0.0.1}"

mkdir -p "${PID_DIR}" "${LOG_DIR}"

if [[ -f "${PID_FILE}" ]] && kill -0 "$(cat "${PID_FILE}")" 2>/dev/null; then
  exit 0
fi

if command -v symfony >/dev/null 2>&1; then
  (
    cd "${ROOT_DIR}"
    symfony server:start -d --no-tls --port="${PORT}" --allow-http
  )
  exit 0
fi

(
  cd "${ROOT_DIR}"
  nohup php -S "${HOST}:${PORT}" router.php >"${LOG_DIR}/local-server.log" 2>&1 < /dev/null &
  echo $! > "${PID_FILE}"
)
