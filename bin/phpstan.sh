#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

cd "${ROOT_DIR}"

php vendor/bin/phpstan analyse -c phpstan.neon \
  src/Bundle/DependencyInjection/Configuration.php \
  src/Bundle/DependencyInjection/SmartResponsorLocatorExtension.php \
  src/Controller \
  src/Infrastructure/InMemoryMetricRecorder.php \
  public/index.php \
  router.php
