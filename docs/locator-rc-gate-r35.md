Locator R35 – RC gate

This document describes the minimal RC gate for the Locator component.

RC gate goals:
- Ensure code compiles and autoload is consistent.
- Ensure unit and integration tests are green.
- Optionally, run an SLO smoke test (if k6 is available).

Script:
- tools/locator-rc-gate.sh

Usage:
1. Make sure dependencies are installed (composer install).
2. Run:

   ./tools/locator-rc-gate.sh

Steps performed by the script:
- composer dump-autoload -a (if composer is present).
- phpunit (vendor/bin/phpunit if available, otherwise global phpunit).
- Optional: k6 SLO smoke using tools/locator-k6-slo-smoke.js if both the file and k6 are present.

Exit criteria:
- Exit code 0: RC gate passed, the build is a candidate for an RC tag.
- Non-zero exit code: RC gate failed, do not create or promote an RC tag.
