FROM wodby/drupal-php:latest

WORKDIR /var/www/html

COPY --chown=wodby:wodby . /var/www/html
RUN composer install
RUN ls -la web
