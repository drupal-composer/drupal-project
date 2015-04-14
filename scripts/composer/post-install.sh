#!/bin/sh

# Prepare the settings file for installation
if [ ! -f web/sites/default/settings.php ]
  then
    cp web/sites/default/default.settings.php web/sites/default/settings.php
    chmod 777 web/sites/default/settings.php
fi

# Prepare the services file for installation
if [ ! -f web/sites/default/services.yml ]
  then
    cp web/sites/default/default.services.yml web/sites/default/services.yml
    chmod 777 web/sites/default/services.yml
fi

# Prepare the files directory for installation
if [ ! -d web/sites/default/files ]
  then
    mkdir -m777 web/sites/default/files
fi

# Add wrapper script for launching drush with project specific config/aliases/commands.
if [ ! -d vendor/bin/dr ]
  then
    echo "#!/usr/bin/env sh
vendor/bin/drush --local --alias-path=drush/site-aliases --config=drush/config --include=drush/commands $@" > vendor/bin/dr
    chmod +x vendor/bin/dr
fi
