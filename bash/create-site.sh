#!/usr/bin/env bash

PROJECT_NAME=$(basename "$(pwd)")
WEBSERVER=$PROJECT_NAME"_webserver_1"
docker exec -i $WEBSERVER bash -c "cd /var/www/app \
 && composer create-project \
 && ./vendor/bin/drush site-install --db-url=mysql://root:password@localhost:3306/drupal"
