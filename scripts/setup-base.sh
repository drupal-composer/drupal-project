#!/usr/bin/env bash
#
# Base setup script. Takes care of setting up files directories.
#
# This will be invoked via the phapp setup command when a site is setup.
cd `dirname $0`/..
source scripts/util/os-compat-helpers.sh

# Copy example settings.
cp web/sites/example.local.services.yml web/sites/local.services.yml
cp web/sites/example.local.settings.php web/sites/local.settings.php

# For drunomics-CI generate additional variables that can be used in its
# dotenv file.
if [[ $PHAPP_ENV = "drunomics-ci" ]]; then
  echo "CONTAINER_HOST=$(hostname -f)" > .container.env
  echo "CONTAINER_NAME=$(hostname -s)" >> .container.env
fi

# Default to first sub-site during development.
if [ ! -L web/sites/default ]; then
   rm -rf web/sites/default
   DIR=$(cd web/sites/ && ls -d */ | grep -v all/ | head -n 1)
   ln -s $DIR web/sites/default
fi

# Load dotenv.
source dotenv/loader.sh

# Be sure files directories are setup.
for SITE in `ls -d web/sites/*/`; do
  SITE=`basename $SITE`

  if [[ $SITE == 'all' ]] || [[ $SITE == 'default' ]]; then
    continue;
  fi
  
  mkdir -p $PERSISTENT_FILES_DIR/$SITE/public/translations
  mkdir -p $PERSISTENT_FILES_DIR/$SITE/private

  if [ -n "$ENV_UNIX_GROUP_WEBSERVER" ]; then
    sudo chown -R :$ENV_UNIX_GROUP_WEBSERVER $PERSISTENT_FILES_DIR/$SITE/
    # When a custom group is set, ensure sub-directory and files are always
    # webserver writable via the setgid bit. This makes the right group to be
    # propagated down.
    sudo chmod 2775 $PERSISTENT_FILES_DIR/$SITE/public
    sudo chmod 2775 $PERSISTENT_FILES_DIR/$SITE/public/translations
    sudo chmod 2775 $PERSISTENT_FILES_DIR/$SITE/private
  fi

  # Move files for existing dev-installations.
  if [[ -d web/sites/$SITE/files ]] && [[ ! -L web/sites/$SITE/files ]]; then
    # Ignore errors moving something.
    mv web/sites/$SITE/files/* $PERSISTENT_FILES_DIR/$SITE/public || true
    rm -rf web/sites/$SITE/files
  fi

  # Link public files directory to persistent files.
  mkdir -p web/sites/$SITE
  os_compat_link_directory ../../../$PERSISTENT_FILES_DIR/$SITE/public web/sites/$SITE/files
done

