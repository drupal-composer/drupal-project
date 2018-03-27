FROM wodby/drupal-php:7.1-3.3.1

USER root
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

USER www-data

