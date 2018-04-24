#!/usr/bin/env bash
#
# Base setup script. Takes care of setting up files directories.
#
# This will be invoked via the phapp setup command when a site is setup.
cd `dirname $0`/..

# Copy example settings.
cp web/sites/example.local.services.yml web/sites/local.services.yml
cp web/sites/example.local.settings.php web/sites/local.settings.php

# For drunomics-CI generate additional variables that can be used in its
# dotenv file.
if [[ $PHAPP_ENV = "drunomics-ci" ]]; then
  echo "CONTAINER_HOST=$(hostname -f)" > .container.env
fi

# Default to first sub-site during development.
if [ ! -L web/sites/default ]; then
   rm -rf web/sites/default
   DIR=$(ls -d */ | head -n 1)
   ln -s $DIR web/sites/default
fi

# Load dotenv.
source dotenv/loader.sh

# Be sure files directories are setup.
for SITE in `ls -d web/sites/*/`; do
  SITE=`basename $SITE`

  if [[ ! -d web/sites/$SITE/files ]]; then
    mkdir web/sites/$SITE/files
    # Ensure sub-directory and files are always webserver writable.
    # We enable the setgid bit to ensure the right group is propagated down.
    chmod 2775 web/sites/$SITE/files
    if [ -n $ENV_UNIX_GROUP_WEBSERVER ]; then
      chown :$ENV_UNIX_GROUP_WEBSERVER web/sites/$SITE/files
    fi
  fi
  if [[ ! -d web/sites/$SITE/files-private ]]; then
    mkdir web/sites/$SITE/files-private
    # Ensure sub-directory and files are always webserver writable.
    # We enable the setgid bit to ensure the right group is propagated down.
    chmod 2775 web/sites/$SITE/files-private
    if [ -n $ENV_UNIX_GROUP_WEBSERVER ]; then
      chown :$ENV_UNIX_GROUP_WEBSERVER web/sites/$SITE/files-private
    fi
  fi
done

# Be sure - the shared - translations directory is setup.
if [[ ! -d web/sites/default/files/translations ]]; then
  mkdir web/sites/default/files/translations
  chmod 775 web/sites/default/files/translations
fi
