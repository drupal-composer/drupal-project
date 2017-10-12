<?php

// Add in shared based settings and optionally environment specific settings.
require __DIR__ . '/base.settings.php';
require DRUPAL_ROOT . '/sites/environment.settings.php';

// Define custom settings if no environment.json is present.
if (empty($env_settings_active)) {
  // Default database connection.
  $databases['default']['default'] = array(
    'database' => '{{ project_underscore }}',
    'username' => 'root',
    'password' => '',
    'prefix' => '',
    'host' => '{{ project }}.local',
    'port' => '3306',
    'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    'driver' => 'mysql',
  );

  $settings['trusted_host_patterns'][] = '^{{ project }}.local$';

  $settings['file_chmod_directory'] = octdec(2770);
  $settings['file_public_path'] = 'files';
  $settings['file_private_path'] = '../files-private';
}

include __DIR__ . '/../development.settings.php';
