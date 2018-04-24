## A bash file that can be sourced for loading the environment.
#
# Optional:
# SITE=example.com - Use the respective site instead of the default site while
#   preparing the app environment.

# Determine current dir and load .env file so PHAPP_ENV can be initialized.
DIR=$( dirname "${BASH_SOURCE[0]}" )

# Eport all the variables by enabling -a.
set -a
eval "$(php $DIR/loader.php getDotenvFiles)"
eval "$(php $DIR/loader.php determineEnvironment)"

if [ -z "$PHAPP_ENV" ]; then
  echo "Missing .env file or PHAPP_ENV environment variable. Did you run phapp setup?"
  exit 1
fi

eval "$(php $DIR/loader.php prepareDeterminedEnvironment)"
eval "$(php $DIR/loader.php prepareAppEnvironment)"
set +a
