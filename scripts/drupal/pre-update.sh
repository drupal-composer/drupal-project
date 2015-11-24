#!/bin/bash

ROOT=$(pwd)
DRUSH=$ROOT/vendor/bin/drush
VERSION_FILE=$ROOT/vendor/.drupal-version
DRUPAL_ROOT=$ROOT/web

# Save the Drupal version prior to running `composer update`
$DRUSH --root=$DRUPAL_ROOT status "Drupal version" --format=yaml | awk '{print $2}' > $VERSION_FILE 2> /dev/null
