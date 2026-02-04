#!/usr/bin/env bash
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
# Locator RC gate script
# This script runs a minimal RC gate for the Locator component:
# - composer autoload dump (optional)
# - unit/integration tests via phpunit
# - optional SLO smoke test if k6 and the script are available

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

echo "[locator-rc-gate] Root: ${ROOT_DIR}"

if [ -f "${ROOT_DIR}/composer.json" ]; then
  echo "[locator-rc-gate] Running composer dump-autoload..."
  if command -v composer >/dev/null 2>&1; then
    (cd "${ROOT_DIR}" && composer dump-autoload -a)
  else
    echo "[locator-rc-gate] composer not found, skipping dump-autoload" >&2
  fi
fi

echo "[locator-rc-gate] Running phpunit..."
if [ -x "${ROOT_DIR}/vendor/bin/phpunit" ]; then
  (cd "${ROOT_DIR}" && php -d display_errors=1 vendor/bin/phpunit)
elif command -v phpunit >/dev/null 2>&1; then
  (cd "${ROOT_DIR}" && phpunit)
else
  echo "[locator-rc-gate] phpunit not found" >&2
  exit 1
fi

# Optional SLO smoke using k6, if available
SLO_SCRIPT="${ROOT_DIR}/tools/locator-k6-slo-smoke.js"
if [ -f "${SLO_SCRIPT}" ]; then
  echo "[locator-rc-gate] Optional SLO smoke..."
  if command -v k6 >/dev/null 2>&1; then
    k6 run "${SLO_SCRIPT}" || {
      echo "[locator-rc-gate] k6 SLO smoke failed" >&2
      exit 1
    }
  else
    echo "[locator-rc-gate] k6 not found, skipping SLO smoke" >&2
  fi
fi

echo "[locator-rc-gate] RC gate passed."
