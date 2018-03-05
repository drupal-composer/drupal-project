<?php

/**
 * @file
 * Production-specific configuration settings.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

// Force users to use the the official domain for the production environment.
$url = '<MYPROJECT>.com';
if ($_SERVER['HTTP_HOST'] !== $url) {
  // KalaUtil::redirect('https', $url);
}

// Redirect HTTP to HTTPS.
// KalaUtil::enforceSSL();

// Set the Drupal 8 "Trusted Host Patterns" for added security.
$settings['trusted_host_patterns'] = [
  // '^MYPROJECT\.com$',
];

// Just in case the Stage File Proxy module gets enabled in production (which it
// shouldn't), neuter it by wiping the "origin URL".
$config['stage_file_proxy.settings']['origin'] = '';

// Enable live environment-specific settings via a config split. Right now, this
// just enables the Config Tools module for tracking the active configuration in
// code and automatically committing it to a git repository.
$config['config_split.config_split.live']['status'] = TRUE;
