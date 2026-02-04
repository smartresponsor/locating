#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

if [ ! -f "$ROOT_DIR/vendor/autoload.php" ]; then
  if command -v composer >/dev/null 2>&1; then
    (cd "$ROOT_DIR" && composer install --no-interaction --prefer-dist)
  else
    echo "Composer not found. Please run 'composer install' in the project root." >&2
    exit 1
  fi
fi

if [ -x "$ROOT_DIR/vendor/bin/phpunit" ]; then
  "$ROOT_DIR/vendor/bin/phpunit" -c "$ROOT_DIR/phpunit.xml.dist" "$@"
else
  php "$ROOT_DIR/vendor/bin/phpunit" -c "$ROOT_DIR/phpunit.xml.dist" "$@"
fi
