<?php

/**
 * @file
 * The main Drupal configuration file with settings common to all environments.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

// Include a hosting provider-specific settings file here (Pantheon, Acquia,
// etc.). Refer to the provider's documentation or canonical upstream for help.
// @see https://github.com/pantheon-systems/example-drops-8-composer/blob/master/composer.json
// @see https://github.com/platformsh/platformsh-example-drupal8/blob/master/web/sites/default/settings.php
# include "$app_root/sites/settings.platformsh.php";

// Determine the environment; one of "dev", "test", or "live".
// E.g., for Pantheon, use "dev" settings on all sites except TEST and LIVE:
# $env = defined('PANTHEON_ENVIRONMENT') && in_array(PANTHEON_ENVIRONMENT, ['test', 'live'])
#   ? PANTHEON_ENVIRONMENT
#   : 'dev';
// E.g., for Platform.sh, use the current branch to determine environment.
$env = (function ($branch) {
  switch ($branch) {
    case 'production':
      return 'live';

    case 'staging':
      return 'test';

    default:
      return 'dev';
  }
})($_ENV['PLATFORM_BRANCH'] ?? NULL);

// Restrict access to the update page by default.
$settings['update_free_access'] = FALSE;

// Indicate the active installation profile. Even if Config Installer was used,
// specify here the profile used for the original installation. Note that you
// may need to comment-out this line when performing a configuration install.
$settings['install_profile'] = 'standard';

// Load the services definition file. Note that services.yml was moved up one
// directory from the default location (`/sites` instead of `/sites/default`).
$settings['container_yamls'][] = "$app_root/sites/services.yml";

// Define the default list of folders that will be ignored by Drupal's file API.
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

// Define the directory for "staged" (aka "sync") configuration.
// @see http://dgo.to/2431247
$config_directories[CONFIG_SYNC_DIRECTORY] = '../config/sync';

// Define the private settings directory.
$settings['file_private_path'] = '../private';

// Disable configuration splits by default on all environments.
foreach (['local', 'dev', 'test', 'live'] as $split) {
  $config["config_split.config_split.$split"]['status'] = FALSE;
}

// Set a hash salt if the hosting provider settings have not already done so.
// Consider setting this via an environment variable for added security.
if (empty($settings['hash_salt'])) {
  $settings['hash_salt'] = 'CHANGE-ME-PER-PROJECT-OR-ENVIRONMENT';
}
