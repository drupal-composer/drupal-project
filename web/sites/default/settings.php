<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all environments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to ensure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * Skipping permissions hardening will make scaffolding
 * work better, but will also raise a warning when you
 * install Drupal.
 *
 * https://www.drupal.org/project/drupal/issues/3091285
 */
// $settings['skip_permissions_hardening'] = TRUE;


/**
 * Config Split Settings & Environment Indicator Settings.
 */

// Disable all config splits by default.
// $config['config_split.config_split.local']['status'] = FALSE;
// $config['config_split.config_split.stage']['status'] = FALSE;
// $config['config_split.config_split.prod']['status'] = FALSE;
// $config['config_split.config_split.dev']['status'] = FALSE;
// Set environment indicator - foreground colour.
$config['environment_indicator.indicator']['fg_color'] = '#FFFFFF';

// Dev.
if (
  (isset($_ENV['AH_SITE_ENVIRONMENT']) && $_ENV['AH_SITE_ENVIRONMENT'] == 'dev') ||
  (isset($_ENV['PANTHEON_ENVIRONMENT']) && $_ENV['PANTHEON_ENVIRONMENT'] == 'dev') ||
  (getenv('ENVIRONMENT') == 'DEV')
) {
  //$config['config_split.config_split.dev']['status'] = TRUE;
  $config['environment_indicator.indicator']['bg_color'] = '#007FAD';
  $config['environment_indicator.indicator']['name'] = 'DEV';
}
// Stage / Test.
else if (
  (isset($_ENV['AH_SITE_ENVIRONMENT']) && $_ENV['AH_SITE_ENVIRONMENT'] == 'test') ||
  (isset($_ENV['PANTHEON_ENVIRONMENT']) && $_ENV['PANTHEON_ENVIRONMENT'] == 'test') ||
  (getenv('ENVIRONMENT') == 'STAGE')
) {
  //$config['config_split.config_split.stage']['status'] = TRUE;
  $config['environment_indicator.indicator']['bg_color'] = '#CA4B02';
  $config['environment_indicator.indicator']['name'] = 'STAGE';
}
// Prod.
else if (
  (isset($_ENV['AH_SITE_ENVIRONMENT']) && $_ENV['AH_SITE_ENVIRONMENT'] == 'prod') ||
  (isset($_ENV['PANTHEON_ENVIRONMENT']) && $_ENV['PANTHEON_ENVIRONMENT'] == 'prod') ||
  (getenv('ENVIRONMENT') == 'PROD')
) {
  //$config['config_split.config_split.prod']['status'] = TRUE;
  $config['environment_indicator.indicator']['bg_color'] = '#EC0914';
  $config['environment_indicator.indicator']['name'] = 'PROD';
}
// Local.
else {
  //$config['config_split.config_split.local']['status'] = TRUE;
  $config['environment_indicator.indicator']['bg_color'] = '#007A5A';
  $config['environment_indicator.indicator']['name'] = 'LOCAL';
}

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

/**
 * Append the config directory settings to the default settings.php provided by the pantheon scaffold.
 */
$settings['config_sync_directory'] = '../config/sync';
