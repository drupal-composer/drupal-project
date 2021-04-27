#!/usr/bin/env bash

# Runs any command as needed in the current environment; i.e. either directly or
# by passing it off to a docker container.

# The script output the command run (via set -x) if the command is passed of.
# If the command is directly executed there is no extra output.

# Set "VERBOSE" to 1 to enable verbose output.

cd `dirname $0`/../..
set +x
source ./dotenv/loader.sh
set -e

COMMANDS=${@}

if [[ ! $COMMANDS ]]; then
  echo "USAGE:"
  echo -n "$0 "
  echo 'echo Example pwd: \&\& pwd'
  echo

  echo "Via heredoc so no escaping is necessary:"
  echo "$0 - <<SCRIPT
echo Working dir: && pwd
SCRIPT"
  echo

  echo "Enable verbose output:"
  echo -n "VERBOSE=1 $0 "
  echo 'echo Example pwd: \&\& pwd'

  exit 1
fi

# Fallback to reading commands from stdin to support heredoc usage.
if [[ $COMMANDS = '-' ]]; then
  COMMANDS=$(cat -)
fi

if [[ "$PHAPP_ENV" = "drunomics-ci" ]]; then
  CONTAINER=${APP_MULTISITE_DOMAIN/\.ci\.drunomics\.com/}
elif [[ "$PHAPP_ENV_TYPE" = "lagoon" ]]; then
  CONTAINER=${COMPOSE_PROJECT_NAME}_cli_1
elif [[ "$PHAPP_ENV" = "vagrant" ]]; then
  CONTAINER=${APP_MULTISITE_DOMAIN/\.local/}
elif [[ "$PHAPP_ENV" = "travis" ]]; then
  CONTAINER=${APP_MULTISITE_DOMAIN/\.local/}_web_1
elif [[ "$PHAPP_ENV" = "localdev" ]]; then
  CONTAINER=cli
fi

# Pass through a few support variables.
VARS="HTTP_AUTH_USER=$HTTP_AUTH_USER "
VARS+="HTTP_AUTH_PASSWORD=$HTTP_AUTH_PASSWORD "

if [[ $CONTAINER ]]; then
  [[ $VERBOSE ]] && echo "Executing at container $CONTAINER: "$COMMANDS
  # Pass variables via -eARG=v -eARG2=v2
  VARS=${VARS/ / -e}
  echo $COMMANDS | docker exec -i -e$VARS $CONTAINER /bin/bash
else
  [[ $VERBOSE ]] && echo "Executing locally: "$COMMANDS
  # Note that we strip the local environment vars to unify behaviour with
  # commands run in containers.
  echo $COMMANDS | env -i $VARS /bin/bash
fi
