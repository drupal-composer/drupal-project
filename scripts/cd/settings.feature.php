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
$config_directories['sync'] = '../d8config/sync';

// $settings['file_public_path'] = '/sites';
$settings['file_private_path'] = '../private';
if (isset($settings['trusted_host_patterns'])) {
  unset($settings['trusted_host_patterns']);
}

$config['system.logging']['error_level'] = 'verbose';

/**
 * Disable CSS and JS aggregation.
 */
$config['system.performance']['css']['preprocess'] = TRUE;
$config['system.performance']['js']['preprocess'] = TRUE;

// Disable AdvAgg.
$config['advagg.settings']['enabled'] = TRUE;


$config['seckit.settings']['seckit_csrf']['origin'] = FALSE;

$config['commerce_payment.commerce_payment_gateway.buckaroo']['configuration']['mode'] = 'test';

$config['pluimen_bo.config_form']['mode'] = '0';

// No config ignorance on test-environments.
$settings['config_ignore_deactivate'] = TRUE;

// Disable Redis caching.
unset($settings['redis.connection']);

unset($config['redis_cache_socket']);

$settings['cache']['default'] = 'cache.backend.database';

if (isset($settings['container_yamls']) && ($key = array_search('modules/contrib/redis/example.services.yml', $settings['container_yamls'])) !== FALSE) {
  unset($settings['container_yamls'][$key]);
}

$settings['default_content_deploy_content_directory'] = '../vendor/pluimen/fixtures/dcd';
