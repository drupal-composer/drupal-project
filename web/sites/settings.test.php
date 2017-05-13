<?php

/**
 * @file
 * Production-specific configuration settings.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

$settings['trusted_host_patterns'] = array(
  '^test-MYSITE\.at\.kalamuna\.com$',
  '^test-MYSITE\.pantheonsite\.io$',
  '^test\.MYSITE\.com$',
);

// Set the Stage File Proxy origin URL for pulling images, files, etc.
$config['stage_file_proxy.settings']['origin'] = 'https://live-MYSITE.pantheonsite.io';
