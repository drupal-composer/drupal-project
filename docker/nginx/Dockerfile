FROM wodby/nginx:1.15-5.0.3

COPY --from=project_php:latest --chown=nginx:nginx /var/www/html /var/www/html
