include .env

.PHONY: up down stop prune ps shell dbdump dbrestore uli cim cex

default: up

up:
	@echo "Starting up containers for for $(PROJECT_NAME)..."
	docker-compose pull
	docker-compose up -d --remove-orphans

down:
	@echo "Removing containers."
	docker-compose down

stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	@docker-compose stop

prune:
	@echo "Removing containers for $(PROJECT_NAME)..."
	@docker-compose down -v

ps:
	@docker ps --filter name="$(PROJECT_NAME)*"

shell:
	docker exec -ti $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") sh

dbdump:
	@echo "Creating Database Dump for $(PROJECT_NAME)..."
	docker-compose run php drupal database:dump --file=../db/restore.sql --gz

dbrestore:
	@echo "Restoring database..."
	docker-compose run php drupal database:restore --file='/var/www/html/db/restore.sql.gz'

uli:
	@echo "Getting admin login"
	docker-compose run php drush user:login --uri="$(PROJECT_BASE_URL)":8000

cim:
	@echo "Importing Configuration"
	docker-compose run php drupal config:import -y

cex:
	@echo "Exporting Configuration"
	docker-compose run php drupal config:export -y

gm:
	@echo "Displaying Generate Module UI"
	docker-compose run php drupal generate:module

install-source:
	@echo "Installing dependencies"
	docker-compose run php composer install --prefer-source

install:
	@echo "Installing dependencies"
	docker-compose run php composer install

cr:
	@echo "Clearing Drupal Caches"
	docker-compose run php drupal cache:rebuild all

logs:
	@echo "Displaying past containers logs"
	docker-compose logs

logsf:
	@echo "Follow containers logs output"
	docker-compose logs -f

dbclient:
	@echo "Opening DB client"
	docker-compose run php drupal database:client

behat:
	@echo "Running behat tests"
	docker-compose run php vendor/bin/behat

phpcs:
	@echo "Running coding standards on custom code"
	docker-compose run php vendor/bin/phpcs --standard=vendor/drupal/coder/coder_sniffer/Drupal web/modules/custom --ignore=*.min.js --ignore=*.min.css

phpcbf:
	@echo "Beautifying custom code"
	docker-compose run php vendor/bin/phpcbf --standard=vendor/drupal/coder/coder_sniffer/Drupal web/modules/custom --ignore=*.min.js --ignore=*.min.css
