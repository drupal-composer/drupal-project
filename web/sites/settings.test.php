<?php

/**
 * @file
 * Production-specific configuration settings.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

// Redirect HTTP to HTTPS.
// $enforce_ssl();

$settings['trusted_host_patterns'] = array(
  // '^test\.EXAMPLE\.COM$',
);

// Set the Stage File Proxy source to fetch files from an upstream environment.
$config['stage_file_proxy.settings']['origin'] = 'https://EXAMPLE.com';

// Enable test environment-specific settings via a config split. Usually, the
// only differences between TEST & LIVE are API keys and the Stage File Proxy
// module status.
$config['config_split.config_split.test']['status'] = TRUE;
