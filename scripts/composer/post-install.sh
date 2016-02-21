#!/bin/sh

DOCUMENTROOT=web

# Prepare the scaffold files if they are not already present
if [ ! -f $DOCUMENTROOT/autoload.php ]
  then
    composer drupal-scaffold
    mkdir -p $DOCUMENTROOT/modules
    mkdir -p $DOCUMENTROOT/themes
    mkdir -p $DOCUMENTROOT/profiles
fi

# Prepare the settings file for installation
if [ ! -f $DOCUMENTROOT/sites/default/settings.php ]
  then
    sed \
      -e "s/\(\$settings\['hash_salt'\] = \)'';/\1'$(php -r 'print bin2hex(openssl_random_pseudo_bytes(32));')';/" \
      $DOCUMENTROOT/sites/default/default.settings.php \
      > $DOCUMENTROOT/sites/default/settings.php
    chmod 666 $DOCUMENTROOT/sites/default/settings.php
    echo "Create a sites/default/settings.php file with chmod 666"
fi

# Prepare the services file for installation
if [ ! -f $DOCUMENTROOT/sites/default/services.yml ]
  then
    cp $DOCUMENTROOT/sites/default/default.services.yml $DOCUMENTROOT/sites/default/services.yml
    chmod 666 $DOCUMENTROOT/sites/default/services.yml
    echo "Create a sites/default/services.yml services.yml file with chmod 666"
fi

# Prepare the files directory for installation
if [ ! -d $DOCUMENTROOT/sites/default/files ]
  then
    mkdir $DOCUMENTROOT/sites/default/files
    chmod 777 $DOCUMENTROOT/sites/default/files
    echo "Create a sites/default/files directory with chmod 777"
fi
