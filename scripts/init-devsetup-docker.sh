#!/usr/bin/env bash

cd `dirname $0`/..
set -e

PROJECT_ADD_DEVSETUP_DOCKER=${PROJECT_ADD_DEVSETUP_DOCKER:-1}

if [[ $PROJECT_ADD_DEVSETUP_DOCKER = 1 ]]; then
  echo "Adding devsetup-docker from https://github.com/drunomics/devsetup-docker..."
  echo "Set PROJECT_ADD_DEVSETUP_DOCKER=0 to disable."

  git clone https://github.com/drunomics/devsetup-docker.git --branch=2.x devsetup-tmp
  rm -rf devsetup-tmp/.git devsetup-tmp/README.md
  cp -rfT devsetup-tmp .

  # Apply replacements and cleanup.
  php process-replacements.php
  rm -rf devsetup-tmp process-replacements.php
fi
