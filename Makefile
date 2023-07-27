TRACKER := docker compose exec php_tracker
TRACKERCONSOLE := docker compose exec php_tracker php bin/console
TRACKERCOMPOSER := docker compose exec php_tracker /userland/path/composer
TRACKERCODECEPT := $(TRACKER) vendor/bin/codecept

rebuild:
	docker compose build --no-cache
	docker compose up --remove-orphans

open:
	$(TRACKER) /bin/bash

start_app:
	docker compose build
	docker compose up

init_app:
	cp  app/.env app/.env.local
	$(TRACKERCOMPOSER) install
	$(TRACKERCONSOLE) make:migration
	$(TRACKERCONSOLE) doctrine:migrations:migrate
	$(TRACKERCONSOLE) doctrine:fixtures:load

migrate:
	$(TRACKERCONSOLE) make:migration
	$(TRACKERCONSOLE) doctrine:migrations:migrate

init_test:
	$(TRACKERCONSOLE) --env=test doctrine:database:create
	$(TRACKERCONSOLE) --env=test doctrine:schema:create

test:
	$(TRACKER) ./vendor/bin/phpunit

temp:
	$(TRACKER) php bin/console lexik:jwt:generate-keypair

drop_db:
	$(TRACKERCONSOLE)

composer_install:
	$(TRACKERCOMPOSER) require miladrahimi/php-jwt "2.*"

cache:
	$(TRACKERCOMPOSER) clear-cache
	$(TRACKERCONSOLE) doctrine:cache:clear-metadata
	$(TRACKERCONSOLE) doctrine:cache:clear-query
	$(TRACKERCONSOLE) doctrine:cache:clear-result
	rm -rf var/cache

skeleton:
	$(TRACKERCOMPOSER) create-project symfony/skeleton:"6.4.x-dev" .


sniffer:
	$(TRACKER) vendor/bin/phpcs -p --extensions=php src/
	$(TRACKER) vendor/bin/phpcs -p --extensions=php tests/App/

snifferf:
	$(TRACKER) vendor/bin/phpcbf -p --extensions=php src/
	$(TRACKER) vendor/bin/phpcbf -p --extensions=php tests/App/

phpstan:
	$(TRACKER) vendor/bin/phpstan analyse src tests

per:
	$(TRACKER) chmod a+rwx -R .

env:
	cp  app/.env app/.env.local
