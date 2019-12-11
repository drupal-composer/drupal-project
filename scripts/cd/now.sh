#!/bin/bash

if [[ -z "${NOW_TOKEN}" ]]; then
  echo "NOW_TOKEN environment variable is not set. Please read the manual in /frontend for setting it."
  exit
fi

node ../scripts/cd/now-config.js $1 $2

now -t $NOW_TOKEN ${@:3}
