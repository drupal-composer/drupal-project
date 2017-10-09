<?php

/**
 * @file
 * The main Drupal configuration file with settings common to all environments.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

// Define a utility function for issuing redirects.
/**
 * Issues an HTTP Location redirect to the browser and ends the current request.
 *
 * @param string $protocol
 *   'http', 'https', or NULL to use the protocol of the current request.
 * @param string $host
 *   The destination host, or NULL to use the host of the current request.
 * @param string $path
 *   The destination URI, or NULL to use the URI of the current request.
 */
$redirect = function ($protocol = NULL, $host = NULL, $path = NULL) {

  // Don't break drush.
  if (PHP_SAPI === 'cli') {
    return;
  }

  // Prep the variables with defaults.
  if (NULL === $protocol) {
    $protocol = isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
      ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : 'http';
  }
  if (NULL === $host) {
    $host = $_SERVER['HTTP_HOST'];
  }
  if (NULL === $path) {
    $path = $_SERVER['REQUEST_URI'];
  }

  // Name transaction "redirect" in New Relic for improved reporting.
  if (extension_loaded('newrelic')) {
    newrelic_name_transaction("redirect");
  }

  // Change HTTP code to 301 after launch.
  header('HTTP/1.0 302 Temporary Redirect');
  header("Location: $protocol://$host{$path}");
  exit();
};

// Define a utility function for checking SSL.
/**
 * Forces use of SSL.
 */
$enforce_ssl = function () use ($redirect) {
  if (!isset($_SERVER['HTTP_X_SSL']) || $_SERVER['HTTP_X_SSL'] !== 'ON') {
    $redirect('https');
  }
};

// Include a hosting provider-specific settings file here (Pantheon, Acquia,
// etc.). Refer to the provider's documentation or canonical upstream (e.g.,
// Pantheon "drops") for sample code.

// Determine the environment; one of "dev", "test", or "live".
// E.g., for Pantheon, use "dev" settings on all sites except TEST and LIVE:
$env = defined('PANTHEON_ENVIRONMENT') && in_array(PANTHEON_ENVIRONMENT, ['test', 'live'])
  ? PANTHEON_ENVIRONMENT
  : 'dev';

// Redirect wildcards (including root) to a single path on the same host.
$wildcard_to_path = [];
foreach ($wildcard_to_path as $wildcard => $path) {
  if (stripos($_SERVER['REQUEST_URI'], $wildcard) === 0) {
    $protocol = in_array($env, ['test', 'live']) ? 'https' : NULL;
    $redirect($protocol, $host = NULL, $path);
  }
}

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

// Define the directory for "staged" (aka "sync") configuration.
// @see http://dgo.to/2431247
$config_directories[CONFIG_SYNC_DIRECTORY] = '../config/staged';

// Disable configuration splits by default on all environments.
$config['config_split.config_split.dev']['status'] = FALSE;
$config['config_split.config_split.test']['status'] = FALSE;
$config['config_split.config_split.live']['status'] = FALSE;

// Set a hash salt. Consider changing this via an environment variable for added
// security.
$settings['hash_salt'] = 'CHANGE-ME-PER-PROJECT-OR-ENVIRONMENT';
