<?php

/**
 * @file
 * Local development override configuration feature.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

use \Drupal\Component\Assertion\Handle;

// Fail when incorrect API calls are made by code under development.
assert_options(ASSERT_ACTIVE, TRUE);
Handle::register();

// Enable local development services.
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

// Show all error messages, with backtrace information.
$config['system.logging']['error_level'] = 'verbose';

// Disable CSS and JS aggregation.
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

// Disable the render cache (this includes the page cache).
// Do not use this setting until after the site is installed.
// $settings['cache']['bins']['render'] = 'cache.backend.null';

// Disable Dynamic Page Cache.
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

// Allow test modules and themes to be installed.
$settings['extension_discovery_scan_tests'] = TRUE;

// Enable access to rebuild.php.
$settings['rebuild_access'] = TRUE;

// Skip file system permissions hardening.
$settings['skip_permissions_hardening'] = TRUE;

// Set the Stage File Proxy origin URL for pulling images, files, etc.
$config['stage_file_proxy.settings']['origin'] = 'https://live-MYSITE.pantheonsite.io';
