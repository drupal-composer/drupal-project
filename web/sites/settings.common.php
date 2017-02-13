<?php

/**
 * @file
 * The main Drupal configuration file with settings common to all environments.
 */

// Load services definition file.
$settings['container_yamls'][] = "$app_root/sites/services.yml";

// Define the default list of folders that will be ignored by Drupal's file API.
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

// Include settings specific to sites hosted on Pantheon.
if (file_exists("$app_root/sites/default/settings.pantheon.php")) {
  // n.b. The settings.pantheon.php file makes some changes that affect all
  // envrionments that this site exists in. Always include this file, even in a
  // local development environment, to insure that the site settings remain
  // consistent.
  require "$app_root/sites/default/settings.pantheon.php";
}

// Include settings specific to sites hosted on Acquia.
elseif (isset($_SERVER['AH_SITE_ENVIRONMENT'])) {
  // When using Acquia, update "sitename" in the path below (two places).
  require '/var/www/site-php/sitename/sitename-settings.inc';
}
