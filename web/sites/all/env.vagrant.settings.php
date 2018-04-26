<?php

/**
 * @file
 * Some settings.php defaults that get included depending on the active environment.
 */

$settings['trusted_host_patterns'][] = '^(.+\.)?drupal-project.local$';

// Default database connection.
$databases['default']['default'] = array(
  'database' => 'drupal-project_' . $site_prefix,
  'username' => 'root',
  'password' => '',
  'prefix' => '',
  'host' => 'drupal-project.local',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['file_chmod_directory'] = octdec(2770);
