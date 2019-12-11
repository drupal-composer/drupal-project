<?php

/**
 * @file
 * Default settings for cd.
 */

$databases['default']['default'] = [
  'prefix' => '',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
];


$databases['default']['default']['database'] = 'drupal';
$databases['default']['default']['username'] = 'root';
$databases['default']['default']['password'] = '';
$databases['default']['default']['host'] = getenv('DOCKER_HOST') ? 'docker' : 'mysql';

$settings['install_profile'] = 'minimal';
$config_directories['sync'] = '../config/sync';

// $settings['file_public_path'] = '/sites';
$settings['file_private_path'] = '../private';

$settings['trusted_host_patterns'] = ['.*'];

$config['system.logging']['error_level'] = 'verbose';

/**
 * Disable CSS and JS aggregation.
 */
$config['system.performance']['css']['preprocess'] = TRUE;
$config['system.performance']['js']['preprocess'] = TRUE;

// Disable AdvAgg.
$config['advagg.settings']['enabled'] = TRUE;

$config['seckit.settings']['seckit_csrf']['origin'] = FALSE;

// No config ignorance on test-environments.
$settings['config_ignore_deactivate'] = TRUE;
