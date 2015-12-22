#!/bin/sh

DOCUMENTROOT=web

# Prepare the scaffold files if they are not already present
if [ ! -f $DOCUMENTROOT/index.php ]
  then
    composer drupal-scaffold
    mkdir -p $DOCUMENTROOT/modules
    mkdir -p $DOCUMENTROOT/themes
    mkdir -p $DOCUMENTROOT/profiles
fi

# Prepare the settings file for installation
if [ ! -f $DOCUMENTROOT/sites/default/settings.php ]
  then
    cp $DOCUMENTROOT/sites/default/default.settings.php $DOCUMENTROOT/sites/default/settings.php
    chmod 777 $DOCUMENTROOT/sites/default/settings.php
fi

# Prepare the services file for installation
if [ ! -f $DOCUMENTROOT/sites/default/services.yml ]
  then
    cp $DOCUMENTROOT/sites/default/default.services.yml $DOCUMENTROOT/sites/default/services.yml
    chmod 777 $DOCUMENTROOT/sites/default/services.yml
fi

# Prepare the files directory for installation
if [ ! -d $DOCUMENTROOT/sites/default/files ]
  then
    mkdir -m777 $DOCUMENTROOT/sites/default/files
fi
