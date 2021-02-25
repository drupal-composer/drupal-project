# Use a minimal base image.
FROM alpine:latest

# Create user and group wodby
RUN set -ex; \
    addgroup -g 1000 -S wodby; \
    adduser -u 1000 -D -S -s /bin/bash -G wodby wodby;

# Copy application files
COPY --chown=wodby:wodby . /var/www/html

# Run a no-op entry point and wait to host Mutagen agent processes.
ENTRYPOINT ["tail", "-f", "/dev/null"]
