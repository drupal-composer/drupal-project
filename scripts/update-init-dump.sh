#!/usr/bin/env bash

# Script updates the init dump for the project.

# Optionally, this can be done in two steps:
# ./scripts/update-init-dump.sh --abort-before-dump
# adjust the dump then finish:
# ./scripts/update-init-dump.sh --skip-preparation

set -e
cd $(dirname $0)/..
source dotenv/loader.sh

# Call with `VERBOSE=1 SCRIPT to see commands printed.
if [[ $VERBOSE = "1" ]]; then
  set -x
fi

# A file used to temporarily force production mode.
MODE_SWITCH_FILE=dotenv/$PHAPP_ENV.force-prod.env

if [[ ! $1 = '--skip-preparation' ]]; then
  echo "Forcing environment to production mode..."
  echo "PHAPP_ENV_MODE=production" > $MODE_SWITCH_FILE
 
  echo "Initializing app in production mode..."
  git checkout origin/develop -- dumps/init.sql.gz
  ([ ! -f web/sites/default/phapp.yml ] || cd web/sites/default && phapp init)
else
  echo "WARNING: Skipping preparation, dumping current database!"
fi

if [[ $1 = '--abort-before-dump' ]]; then
  echo "Aborting before dumping, site is forced into production mode."
  exit
fi

echo "Creating new init dump..."
drush sql-dump --result-file=../dumps/init.sql --gzip

echo "Stopping to force the site into production mode..."
rm -f $MODE_SWITCH_FILE

echo "Done."
echo "Note: Run 'phapp update' to apply current config again."
