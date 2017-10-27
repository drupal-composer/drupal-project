#!/usr/bin/env bash

PROJECT_NAME=$(basename "$(pwd)")
WEBSERVER=$PROJECT_NAME"_webserver_1"
DBNAME=$1

docker exec -i $WEBSERVER bash -c "cd web \
&& ../vendor/bin/drush site-install --db-url=mysql://root:password@database:3306/$DBNAME -y"
