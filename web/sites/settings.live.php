<?php

/**
 * @file
 * Production-specific configuration settings.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

$settings['trusted_host_patterns'] = array(
  '^live-MYSITE\.at\.kalamuna\.com$',
  '^live-MYSITE\.pantheonsite\.io$',
  '^(www\.)?MYSITE\.com$',
);

// Just in case the Stage File Proxy module gets enabled in production (which it
// shouldn't), neuter it by wiping the "origin URL".
$config['stage_file_proxy.settings']['origin'] = '';
