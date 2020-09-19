.PHONY: start

setup:
	composer install
	cp .env .env.local
	cp .env.test .env.test.local
	symfony console doctrine:migration:migrate -n
	yarn
	symfony run yarn encore dev
	symfony server:ca:install

start:
	symfony server:start -d

stop:
	symfony server:stop

build:
	symfony run yarn encore dev

test:
	APP_ENV=test symfony console doctrine:database:create
	APP_ENV=test symfony console doctrine:migration:migrate -n
	APP_ENV=test symfony console hautelook:fixtures:load -n
	php bin/phpunit
	APP_ENV=test symfony console doctrine:database:drop -f

.DEFAULT_GOAL := start
