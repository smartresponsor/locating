# Locator test and smoke commands

This component targets PHP 8.4 / 8.5 and Symfony 7 / 8.

## One-line smoke

```bash
./tools/run-tests.sh
```

This will install Composer dependencies if needed and run the full PHPUnit suite using `phpunit.xml.dist`.

## Make targets

```bash
make test   # composer install (if needed) + phpunit
make smoke  # same as ./tools/run-tests.sh
```

## Notes

- The test suite is split into unit, integration, functional and e2e suites in `phpunit.xml.dist`.
- All commands assume that `php`, `composer` and `vendor/bin/phpunit` are available in your PATH.
