<?php

/**
 * @file
 * The main Drupal configuration file with settings common to all environments.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

// Include a hosting provider-specific settings file here (Pantheon, Acquia,
// etc.). Refer to the provider's documentation or canonical upstream (e.g.,
// Pantheon's "drops8" repository) for sample code.

// Determine the environment; one of "dev", "test", or "live".
// E.g., for Pantheon, use "dev" settings on all sites except TEST and LIVE:
$env = defined('PANTHEON_ENVIRONMENT') && in_array(PANTHEON_ENVIRONMENT, ['test', 'live'])
  ? PANTHEON_ENVIRONMENT
  : 'dev';

// Restrict access to the update page by default.
$settings['update_free_access'] = FALSE;

// Indicate the active installation profile. Even if the site was installed with
// Config Installer, specify here the profile used to install originally.
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
