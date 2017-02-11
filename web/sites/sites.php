<?php

/**
 * @file
 * Configuration file for multi-site support and directory aliasing feature.
 */

// Send the staging and live sites to the "production" sites directory.
$sites['example.com']
  = $sites['stage.example.com']
    = 'production';

/**
 * Includes the settings files appropriate to the environment.
 *
 * @param array $environments
 *   A list of environments with matching settings files to include.
 */
function _include_settings(array $environments = array()) {

  // Always include the "common" settings first.
  include DRUPAL_ROOT . '/sites/settings.common.php';

  // Include environmental settings next.
  foreach ($environments as $environment) {
    include DRUPAL_ROOT . "/sites/settings.$environment.php";
  }

  // Always include local environment settings last, if the file exists.
  $local_settings = DRUPAL_ROOT . '/sites/default/settings.local.php';
  if (file_exists($local_settings)) {
    include $local_settings;
  }
}
