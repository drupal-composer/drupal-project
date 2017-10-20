#!/bin/bash
cd `dirname $0`/..

DIR=`pwd`
DRUSH_BIN=$DIR/vendor/bin/drush
DRUSH_PHP=$PHP_BIN $DRUSH_BIN cron --quiet
