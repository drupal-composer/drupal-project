#!/usr/bin/env bash

PROJECT_NAME=$(basename "$(pwd)")
WEBSERVER=$PROJECT_NAME"_webserver_1"
docker exec -i $WEBSERVER bash -c "cd /var/www/app \
 && composer create-project"

# Attempt to run drush si currently not working because
# drush tries to call mysql, which doesn't exist on the server container
# docker exec -i $WEBSERVER bash -c "cd /var/www/app/web \
# && ../vendor/bin/drush site-install --db-url=mysql://root:password@localhost:3306/drupal"
