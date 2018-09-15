FROM wodby/drupal-php:latest

WORKDIR /var/www/html

COPY --chown=wodby:wodby composer.json ./
RUN composer install --no-scripts --no-autoloader
COPY --chown=wodby:wodby . ./
RUN ls -la
RUN composer dump-autoload --optimize && \
	composer run-script post-install-cmd
RUN ls -la web/sites/default
