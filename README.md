# RTD Drupal Composer Template #
Fork of https://github.com/drupal-composer/drupal-project

## Docker Instructions ##
- Install [docker](https://store.docker.com/editions/community/docker-ce-desktop-mac).
- Run `docker-compose up -d` to build docker images and to create the webserver and database containers.
- Run `docker-compose ps` and take note of the webserver's name and the port number mapped to 80 (e.g. 32827->80)
- Run `docker exec -i [webserver-name] bash -c "cd /var/www/docroot && composer create-project"` to create drupal project via composer.
- Open a web browser and go to http://localhost:[port-number]

## XDebug ##
XDebug is already installed, but it's disabled by default as it slows down the site. To enable it run the following commands:
- Run `docker exec -it [app-server-name] /bin/bash` to ssh into the webserver container.
- Run `vi /etc/php.d/xdebug.ini` and remove the leading ';' on line 1.
- Run `docker-compose restart`.
- Run `docker-compose ps` to see the containers new port numbers.

## TODO ##
- Install ssl.
- Install drush in webserver container.
- Provide drush site-install command to automate site installation (e.g drush site-install --db-url=mysql://root:password@localhost:[port]/drupal).
