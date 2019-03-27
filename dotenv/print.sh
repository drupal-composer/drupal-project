#!/usr/bin/env bash
### A bash file that outputs all dotenv variables.
#
# Optional:
# SITE=example - Use the respective site instead of the default site while
#   preparing the app environment.

# Determine current dir and load .env file so PHAPP_ENV can be initialized.
DIR=$( dirname $0)

## Source the loader first, so PHAPP_ENV is set and can be applied by steps after determineEnvironment().

source $DIR/loader.sh

php $DIR/loader.php getDotenvFiles
php $DIR/loader.php determineEnvironment
php $DIR/loader.php prepareDeterminedEnvironment
php $DIR/loader.php prepareAppEnvironment
