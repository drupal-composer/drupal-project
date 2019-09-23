FROM wodby/nginx:1.16

COPY --from=project_php:latest --chown=nginx:nginx /var/www/html /var/www/html
