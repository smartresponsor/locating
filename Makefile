PHP ?= php
COMPOSER ?= composer

.PHONY: vendor test smoke

vendor:
	@if [ ! -f vendor/autoload.php ]; then \
		$(COMPOSER) install --no-interaction --prefer-dist; \
	fi

test: vendor
	$(PHP) vendor/bin/phpunit -c phpunit.xml.dist

smoke: vendor
	./tools/run-tests.sh
