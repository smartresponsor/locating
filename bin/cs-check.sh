#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

cd "${ROOT_DIR}"

php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --dry-run --diff --verbose --path-mode=intersection -- \
  bin \
  config/routes \
  public/index.php \
  src/Bundle/DependencyInjection/Configuration.php \
  src/Bundle/DependencyInjection/SmartResponsorLocatorExtension.php \
  src/Controller \
  src/Entity/AddressData.php \
  src/Entity/AddressInput.php \
  src/Entity/AddressResult.php \
  src/Service/AddressNormalizer.php \
  src/Service/AddressParser.php \
  src/Service/AddressParserGeneric.php \
  src/Service/AddressPipeline.php \
  src/Service/AddressQuotaGuard.php \
  src/Service/AddressReverse.php \
  src/Service/AddressSuggest.php \
  src/Service/TenantQuotaManager.php \
  src/Service/TenantQuotaManagerMetricDecorator.php \
  src/ServiceInterface/TenantQuotaManagerInterface.php \
  tests/Address \
  tests/Bundle \
  tests/Contract \
  tests/Fixture \
  tests/Integration \
  tests/Smoke \
  tests/Status \
  tools/locator-fixtures-run.php
