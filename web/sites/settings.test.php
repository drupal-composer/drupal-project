<?php

/**
 * @file
 * Production-specific configuration settings.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

// Redirect HTTP to HTTPS.
// KalaUtil::enforceSSL();

$settings['trusted_host_patterns'] = array(
  // '^test\.EXAMPLE\.COM$',
);

// Set the Stage File Proxy source to fetch files from an upstream environment.
$config['stage_file_proxy.settings']['origin'] = 'https://EXAMPLE.com';

// Enable test environment-specific settings via a config split. Usually, the
// only differences between TEST & LIVE are API keys and the Stage File Proxy
// module status.
$config['config_split.config_split.test']['status'] = TRUE;

// Don't commit config changes in the TEST environment to the configuration
// files repo. This is just a precautionary step, as the Config Tools modules
// should actually get disabled in all but the live environment via configsplit.
$config['config_tools.settings']['disabled'] = 1;
