#!/usr/bin/env bash

cd `dirname $0`/..
set -e

mkdir devsetup-tmp
git clone git@bitbucket.org:drunomics/project-devsetup.git devsetup-tmp
rm -rf devsetup-tmp/.git
rm devsetup-tmp/README.md
cp -rf devsetup-tmp/* .
rm -rf devsetup-tmp
