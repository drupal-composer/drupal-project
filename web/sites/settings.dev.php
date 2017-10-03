<?php

/**
 * @file
 * Local development override configuration feature.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

use Drupal\Component\Assertion\Handle;

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

// Set the Stage File Proxy source to fetch files from an upstream environment.
$config['stage_file_proxy.settings']['origin'] = 'https://EXAMPLE.com';

// Enable dev environment-specific settings via a config split.
$config['config_split.config_split.dev']['status'] = TRUE;

// Don't commit config changes in DEV environments to the configuration files
// respository.
$config['config_tools.settings']['disabled'] = 1;

// Prevent Kint from loading too much debug output and crashing the request.
if (file_exists("$app_root/modules/contrib/devel/kint/kint/Kint.class.php")) {
  require_once "$app_root/modules/contrib/devel/kint/kint/Kint.class.php";
  Kint::$maxLevels = 5;
}

// Provide sane defaults for local development.
if (empty($databases['default']['default'])) {
  $databases['default']['default'] = [
    'database' => 'drupal',
    'username' => 'drupal',
    'password' => 'drupal',
    'prefix' => '',
    'host' => 'localhost',
    'port' => '3306',
    'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    'driver' => 'mysql',
  ];
}

// Add a shutdown function to help debug 500 errors.
// @see http://dropbucket.org/node/7127
register_shutdown_function(function () {
  if (($error = error_get_last())) {
    $dump = print_r($error, TRUE);
    die("<pre>$dump</pre>");
  }
});
