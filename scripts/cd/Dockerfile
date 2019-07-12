# from https://www.drupal.org/docs/8/system-requirements/drupal-8-php-requirements
FROM gitlab.frmwrk.nl:5005/frmwrk-general/drupal-stack:drupal8-base

COPY ./ /var/www/html/

RUN chown -R www-data:www-data web/sites web/modules web/themes
