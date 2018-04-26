#!/usr/bin/env bash

cd `dirname $0`/..
set -e

# Only do something if explicitly enabled.
if [[ -z $PROJECT_ADD_DRUNOMICS_DEVSETUP ]]; then
  echo "Variable PROJECT_ADD_DRUNOMICS_DEVSETUP is not set, skipping..."
  exit 0
fi

mkdir devsetup-tmp
git clone git@bitbucket.org:drunomics/project-devsetup.git devsetup-tmp
rm -rf devsetup-tmp/.git
rm devsetup-tmp/README.md
cp -rf devsetup-tmp/* .
rm -rf devsetup-tmp
