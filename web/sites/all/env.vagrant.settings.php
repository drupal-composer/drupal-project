<?php

/**
 * @file
 * Some settings.php defaults that get included depending on the active environment.
 */

$settings['trusted_host_patterns'][] = '^(.+\.)?{{ project }}.local$';

// Default database connection.
$databases['default']['default'] = [
  'database' => '{{ project }}_' . $site_prefix,
  'username' => 'root',
  'password' => '',
  'prefix' => '',
  'host' => '{{ project }}.local',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
];

$settings['file_chmod_directory'] = octdec(2770);
