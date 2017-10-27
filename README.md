# RTD Drupal Composer Template #
This project is a fork of https://github.com/drupal-composer/drupal-project.

## Docker ready ##
A `docker-compose.yml` file and a `.docker` directory is included in this repo to allow you to quickly create a container-based LAMP stack.

### Docker lingo ###
The following definitions may not be 100% accurate, but they will help you understand how Docker works.

- Docker: The software that allows you to build or fetch images; create containers; and execute programs provided by the containers.
- Docker compose: A tool bundled with Docker that allows you to orchestrate containers.
- Container: A lightweight 

instance. Think of it as a strip-down version of CentOS.
- Image: The footprint of a container.
- Base image: An image can be built on top a base image.
- Dockerfile: A file that tells Docker how to create an image.
- docker-compose.yml: A file that tells docker how to link services together.
- Service: A name to reference a container. The service that a container provides.

### Spin up a site ###
1. Install [docker](https://store.docker.com/editions/community/docker-ce-desktop-mac).
2. Run `docker-compose up -d` to build docker images and to create the webserver and database containers.
3. Run `docker-compose ps` to find out what the **webserver's name** and **port numbers** are (e.g. 32827->80).
4. Run `bash .docker/create-site.sh` to create drupal project via composer.
5. Optionally run `bash .docker/install-site.sh drupal` to install a site by skipping the web-based wizard.
6. Open a web browser and go to http://localhost:[port-number]

### Database Information ###
- Host: database
- Database: drupal (created by default).
- UN: root
- PW: password
- Port: The port is assigned randomly every time the the mysql container is started. Follow step 3 on **Spin up a site** to find out how to check the port number for a service.

### XDebug ###
XDebug is already installed, but it's disabled by default as it slows down the site. To enable it run the following commands:
- Run `docker exec -it [webserver-name] /bin/bash` to ssh into the webserver container.
- Run `vi /etc/php.d/xdebug.ini` and remove the leading ';' on line 1.
- Run `docker-compose restart`.
- Run `docker-compose ps` to see the containers new port numbers.

## Drush ##
Sample command to get a URL to log in as user 1:
`docker exec -i [webserver-name] bash -c "cd web && ../vendor/bin/drush uli"`

## TODO ##
- Write script to automate enabling and disabling xdebug.
- Switch from using **volumes** to **bind mounts** per https://docs.docker.com/engine/admin/volumes/#choose-the-right-type-of-mount.

