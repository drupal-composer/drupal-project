#!/bin/sh

DOCUMENTROOT=web

# Prepare the settings file for installation
if [ ! -f $DOCUMENTROOT/sites/default/settings.php ]
  then
    cp $DOCUMENTROOT/sites/default/default.settings.php $DOCUMENTROOT/sites/default/settings.php
    chmod 666 $DOCUMENTROOT/sites/default/settings.php
    echo "Create a sites/default/settings.php file with chmod 666"
fi

# Prepare the files directory for installation
if [ ! -d $DOCUMENTROOT/sites/default/files ]
  then
    mkdir $DOCUMENTROOT/sites/default/files
    chmod 777 $DOCUMENTROOT/sites/default/files
    echo "Create a sites/default/files directory with chmod 777"
fi
