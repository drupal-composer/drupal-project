<?php

/**
 * @file
 * Default local settings.
 */

use Drupal\Component\Assertion\Handle;

assert_options(ASSERT_ACTIVE, TRUE);
Handle::register();

$databases['default']['default'] = [
  'prefix' => '',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
  'database' => 'drupal8',
  'username' => 'drupal8',
  'password' => 'drupal8',
  'host' => 'database',
];

// $class_loader->addPsr4('Drupal\\webprofiler\\',
// [ __DIR__ . '/../../modules/contrib/devel/webprofiler/src']);
// $settings['container_base_class'] =
// '\Drupal\webprofiler\DependencyInjection\TraceableContainer';
$settings['install_profile'] = 'minimal';
$config_directories['sync'] = '../config/sync';

$settings['file_public_path'] = './files';
$settings['file_private_path'] = '../private';

$settings['trusted_host_patterns'] = [
  '^wrkdrupal\.lndo\.site$',
  '^wrkdrupal\.test$',
];

/**
 * Disable Caching.
 */
// $settings['container_yamls'][] =
// DRUPAL_ROOT . '/sites/frmwrk.development.services.yml';
// $settings['cache']['bins']['render'] = 'cache.backend.null';
// $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
// $config['system.performance']['cache']['page']['max_age'] = 3600;

/**
 * Disable CSS and JS aggregation.
 */
// $config['system.performance']['css']['preprocess'] = true;
// $config['system.performance']['js']['preprocess'] = true;
// Disable AdvAgg.
// $config['advagg.settings']['enabled'] = false;

/**
 * Show all error messages, with backtrace information.
 *
 * In case the error level could not be fetched from the database, as for
 * example the database connection failed, we rely only on this value.
 */
$config['system.logging']['error_level'] = 'verbose';

/**
 * This enables the config_split module.
 * */

$config['config_split.config_split.development']['status'] = TRUE;

// Enable stage file proxy.
$config['stage_file_proxy.settings']['origin'] = "https://base.frmwrk.nl";

$config['seckit.settings']['seckit_csrf']['origin'] = FALSE;

// Deactivate config_ignore.
$settings['config_ignore_deactivate'] = TRUE;

/**
 * Enable access to rebuild.php.
 */
// $settings['rebuild_access'] = TRUE;

/**
 * Skip file system permissions hardening.
 */
// $settings['skip_permissions_hardening'] = TRUE;
