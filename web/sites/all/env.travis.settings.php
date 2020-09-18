<?php

/**
 * @file
 * Some settings.php defaults that get incldued depending on the active environment.
 */

$settings['trusted_host_patterns'][] = '^localhost$';
$settings['trusted_host_patterns'][] = '^(.+\.)?{{ project }}.local(:([0-9])*)?$';

// We only ran a single site for testing on travis.
$databases['default']['default'] = [
  'database' => '{{ project }}_' . $site_prefix,
  'username' => 'root',
  'password' => '',
  'prefix' => '',
  'host' => '127.0.0.1',
  'port' => 3306,
  'namespace' => 'Drupal\Core\Database\Driver\mysql',
  'driver' => 'mysql',
];
