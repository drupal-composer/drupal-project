<?php

/**
 * @file
 * Some settings.php defaults that get included depending on the active environment.
 */

$settings['trusted_host_patterns'][] = '^(.+_)?{{ project }}(--.+)?\.ci\.drunomics\.com$';

$databases['default']['default'] = [
  'database' => '{{ project }}_' . $site_prefix,
  'username' => 'root',
  'password' => '',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
];

$settings['reverse_proxy'] = TRUE;
$settings['reverse_proxy_addresses'] = ['10.0.42.1', '172.17.42.1'];
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' && in_array($_SERVER['REMOTE_ADDR'], $settings['reverse_proxy_addresses'])) {
  $_SERVER['HTTPS'] = 'on';
  $_SERVER['SERVER_PORT'] = 443;
}

$settings['file_chmod_directory'] = octdec(2770);
