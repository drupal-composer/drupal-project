<?php

/**
 * @file
 * The main Drupal configuration file with settings common to all environments.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

// Disallow running updates without root access.
$settings['update_free_access'] = FALSE;

// Indicate the active installation profile.
$settings['install_profile'] = 'config_installer';

// Load the services definition file. Note that services.yml was moved up one
// directory from the default location (`/sites` instead of `/sites/default`).
$settings['container_yamls'][] = "$app_root/sites/services.yml";

// Define the default list of folders that will be ignored by Drupal's file API.
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

// Define the version-controlled configuration directory.
// @see http://dgo.to/2431247
$config_directories[CONFIG_SYNC_DIRECTORY] = '../config';

// A local file system path where private files will be stored.
$settings['file_private_path'] = '../private';

// Include settings specific to sites hosted on Pantheon.
if (file_exists("$app_root/sites/default/settings.pantheon.php")) {
  // n.b. The settings.pantheon.php file makes some changes that affect all
  // envrionments that this site exists in. Always include this file, even in a
  // local development environment, to insure that the site settings remain
  // consistent.
  include "$app_root/sites/default/settings.pantheon.php";
}

// Include settings specific to sites hosted on Acquia.
elseif (isset($_SERVER['AH_SITE_ENVIRONMENT'])) {
  // When using Acquia, update "sitename" in the path below (two places).
  include '/var/www/site-php/sitename/sitename-settings.inc';
}

// Include settings specific to sites hosted on Platform.sh.
// @see https://github.com/platformsh/platformsh-example-drupal8/blob/master/web/sites/default/settings.platformsh.php
elseif (file_exists("$app_root/sites/default/settings.platformsh.php")) {
  include "$app_root/sites/default/settings.platformsh.php";
}
