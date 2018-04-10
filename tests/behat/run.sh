#!/usr/bin/env bash

ARGS=""

if [[ $HEADLESS = "1" ]]; then
  ARGS="--disable-gpu --headless"
fi

(google-chrome-stable $ARGS --remote-debugging-address=127.0.0.1 --remote-debugging-port=9222 )&

cd `dirname $0`/../..
./vendor/bin/behat -c tests/behat/behat.yml -p vagrant --colors $@

# End with stopping all sub-process; i.e. chrome.
[[ -z "$(jobs -p)" ]] || kill $(jobs -p)
