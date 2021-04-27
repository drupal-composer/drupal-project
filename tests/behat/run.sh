#!/usr/bin/env bash

# Usage:
# ./tests/behat/run.sh --tags=example

# Supported environment variables:
# - BASE_URL (Defaults to $PHAPP_BASE_URL)
# - HTTP_AUTH_USER
# - HTTP_AUTH_PASSWORD
# - CHROME_HOST (Defaults to localhost)
# - CHROME_PORT (Defaults to 9222)
# - BEHAT_PARAMETERS (can override default as suiting for an environment). Any
#   additional arguments are appended also.

cd `dirname $0`
VCS_DIR=../..
BEHAT_DIR=tests/behat

source $VCS_DIR/dotenv/loader.sh

# Get IP address of docker host.
if [ $PHAPP_ENV = localdev ]; then
  CHROME_HOST=`$VCS_DIR/scripts/util/exec.sh /sbin/ip route|awk '/default/ { print $3 }'`
fi

BEHAT_PARAMETERS=${BEHAT_PARAMETERS:-"--colors"}
# Build up variables that can be used for replacing behat.yml config.
export CHROME_URL=http://${CHROME_HOST:-localhost}:${CHROME_PORT:-9222}
export BASE_URL=${BASE_URL:-$PHAPP_BASE_URL}

# Prepare config
$VCS_DIR/scripts/util/replace-vars.sh < behat.envsubst.yml > behat.yml

# Output some debug information.
echo "Running behat tests with chrome URL $CHROME_URL and base URL $BASE_URL."

# We use curl to warm up caches via a regular HTTP request.
echo "Warming up $BASE_URL..."

if [[ $HTTP_AUTH_USER ]]; then
  ARG_HTTP_AUTH="--user $HTTP_AUTH_USER:$HTTP_AUTH_PASSWORD"
  echo "Using HTTP authentication for user $HTTP_AUTH_USER"
fi

curl -sL --compressed $BASE_URL ${ARG_HTTP_AUTH:-} | grep "$BEHAT_WARMUP_REQUIRED_CONTENT:" -q

if [[ $? -ne 0 ]]; then
  echo Unable to access site.
  echo BEHAT FAILED.
  exit 1
fi

# Ease running behat from the vagrant environment by launching chrome.
if [[ $PHAPP_ENV = vagrant ]] || [[ $PHAPP_ENV = localdev ]]; then
  (google-chrome-stable --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222 ) &
fi

set -x
$VCS_DIR/scripts/util/exec.sh ./vendor/bin/behat -c $BEHAT_DIR/behat.yml $BEHAT_PARAMETERS $BEHAT_EXTRA_ARGUMENTS $@
EXIT_CODE=$?
set +x

if [[ $PHAPP_ENV = vagrant ]] || [[ $PHAPP_ENV = localdev ]]; then
  # End with stopping all sub-process; i.e. chrome.
  [[ -z "$(jobs -p)" ]] || kill $(jobs -p)
fi

[[ $EXIT_CODE -eq 0 ]] && echo BEHAT PASSED. || echo BEHAT FAILED.
exit $EXIT_CODE
