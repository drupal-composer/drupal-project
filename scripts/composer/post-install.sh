#!/bin/sh

# Prepare the settings file for installation
if [ ! -f web/sites/default/settings.php ]
  then
    cp web/sites/default/default.settings.php web/sites/default/settings.php
    chmod 666 web/sites/default/settings.php
    echo "Create a sites/default/settings.php file with chmod 666"
fi

# Prepare the services file for installation
if [ ! -f web/sites/default/services.yml ]
  then
    cp web/sites/default/default.services.yml web/sites/default/services.yml
    chmod 666 web/sites/default/services.yml
    echo "Create a sites/default/services.yml services.yml file with chmod 666"
fi

# Prepare the files directory for installation
if [ ! -d web/sites/default/files ]
  then
    mkdir web/sites/default/files
    chmod 777 web/sites/default/files
    echo "Create a sites/default/files directory with chmod 777"
fi
