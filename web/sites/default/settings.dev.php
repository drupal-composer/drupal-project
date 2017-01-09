<?php

// Add in shared based settings and optionally environment specific settings.
require __DIR__ . '/settings.base.php';
require DRUPAL_ROOT . '/sites/settings.environment.php';

// Support drunomics ci-environment.
if (empty($env_settings_active) && getenv('DRUNOMICS_CI')) {
  $settings['trusted_host_patterns'][] = '^(.+_)?{{ project }}(--.+)?\.ci\.drunomics\.com$';

  $databases['default']['default'] = array(
    'database' => '{{ project_underscore }}',
    'username' => 'root',
    'password' => '',
    'prefix' => '',
    'host' => 'localhost',
    'port' => '3306',
    'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    'driver' => 'mysql',
  );

  $settings['reverse_proxy'] = TRUE;
  $settings['reverse_proxy_addresses'] = array('10.0.42.1', '172.17.42.1');
  if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' && in_array($_SERVER['REMOTE_ADDR'], $settings['reverse_proxy_addresses'])) {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
  }

  $settings['file_chmod_directory'] = octdec(2770);
  $settings['file_public_path'] = 'files';
  $settings['file_private_path'] = '../private';
}

// Show errors on dev or test environments.
$config['system.logging']['error_level'] = 'verbose';

// We usually run CI sites in production mode.
// include __DIR__ . '/../settings.devel.php';
