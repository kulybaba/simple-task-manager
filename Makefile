.PHONY: run
start:
	symfony server:start -d

stop:
	symfony server:stop

.PHONY: build
test:
	symfony console doctrine:database:drop -f
	symfony console doctrine:database:create
	symfony console doctrine:migration:migrate -n
	symfony console hautelook:fixtures:load -n
	php bin/phpunit

.DEFAULT_GOAL := run
