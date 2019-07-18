#!/usr/bin/env bash

cd `dirname $0`/..
set -ex

PROJECT_ADD_DEVSETUP_DOCKER=${PROJECT_ADD_DEVSETUP_DOCKER:-1}

if [[ $PROJECT_ADD_DEVSETUP_DOCKER = 1 ]]; then
  echo "Adding devsetup-docker from https://github.com/drunomics/devsetup-docker..."
  echo "Set PROJECT_ADD_DEVSETUP_DOCKER=0 to disable."

  git clone https://github.com/drunomics/devsetup-docker.git --branch=2.x devsetup-tmp
  rm -rf devsetup-tmp/.git devsetup-tmp/README.md
  
  # OS specific cp operations
  case "$OSTYPE" in
    darwin*) 
      if [  -d "./devsetup-tmp" ] 
      then
        cp -rf devsetup-tmp/* . 
        cp -rf devsetup-tmp/.[^.]* .
      fi ;; 
    linux*)  cp -rfT devsetup-tmp . ;; 
    *)       cp -rfT devsetup-tmp . ;;
  esac

  # Apply replacements and cleanup.
  php process-replacements.php
  rm -rf devsetup-tmp process-replacements.php
  echo \
'COMPOSE_AMAZEEIO_VERSION=v0.22.1
COMPOSE_AMAZEEIO_PHP_VERSION=7.2
' >> .env-defaults
fi
