#!/bin/sh
# Script for setting up Git config.
# This is used for setting shared git configuration parameters per project.
# Git hooks can be configured using the "git" overrides directory

cd `dirname $0`/..
[ `basename $PWD` = 'vcs' ] || cd vcs

MASTER=master
DEVELOP=develop

# Ensure required branches exist.
git branch | grep $MASTER || git branch $MASTER
git branch | grep $DEVELOP || git branch $DEVELOP

git flow init -fd
git config --replace-all gitflow.branch.master $MASTER
git config --replace-all gitflow.branch.develop $DEVELOP
git config --replace-all gitflow.prefix.feature feature/
git config --replace-all gitflow.prefix.release release/
git config --replace-all gitflow.prefix.hotfix hotfix/
git config --replace-all gitflow.prefix.support support/
git config --replace-all gitflow.prefix.versiontag version/
