FROM wodby/drupal-php:7.1-dev-4.6.3

WORKDIR /var/www/html

COPY --chown=wodby:wodby . /var/www/html

RUN chmod +rx /var/www/html/install_drupal.sh

RUN composer install
