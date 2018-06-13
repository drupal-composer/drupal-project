<?php

/**
 * @file
 * Some settings.php defaults that get included depending on the active environment.
 */

// Default database connection.
$databases['default']['default'] = array(
  'database' => 'test_' . $site_prefix,
  'username' => 'root',
  'password' => '',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['file_chmod_directory'] = octdec(2770);
