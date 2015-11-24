#!/bin/bash

ROOT=$(pwd)
DRUSH=$ROOT/vendor/bin/drush
VERSION_FILE=$ROOT/vendor/.drupal-version
DRUPAL_ROOT=$ROOT/web

if [ -f $VERSION_FILE ]
then

  # Get the Drupal version after `composer update`, and compare it with
  # the version we recorded prior to the update running
  DRUPAL_VERSION=$($DRUSH --root=$DRUPAL_ROOT status "Drupal version" --format=yaml | awk '{print $2}')
  PREVIOUS_VERSION=$(cat $VERSION_FILE)

  if [ $DRUPAL_VERSION != $PREVIOUS_VERSION ]
  then
    echo "Updating scaffold files to $DRUPAL_VERSION"
    $ROOT/scripts/drupal/update-scaffold "drupal-$DRUPAL_VERSION"
  else
    echo "Drupal version did not change; skipping scaffold update."
  fi

fi
