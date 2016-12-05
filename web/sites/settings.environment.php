<?php

/**
 * @file
 * Adds in environment specific settings fron environment.json, if existing.
 */

$filepath = realpath(DRUPAL_ROOT) . '/../../environment.json';

if (file_exists($filepath)) {
  $env_settings_active = TRUE;
  $env = json_decode(file_get_contents($filepath), TRUE);

  // Setup reverse-proxies and varnish.
  if (!empty($env['reverse_proxy'])) {
    $settings['reverse_proxy'] = TRUE;
    $settings['reverse_proxy_addresses'] = isset($env['reverse_proxy_ips']) ? $env['reverse_proxy_ips'] : ['127.0.0.1'];
    // Support reverse-proxies that take care of HTTPS.
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
      $_SERVER['HTTPS'] = 'on';
    }
  }

  $is_https = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on';
  $base_url = ($is_https ? 'https://' : 'http://') . $env['base_url'];
  $settings['trusted_host_patterns'][] = '^' . preg_quote($env['base_url']) . '$';

  // Setup databases.
  foreach ($env['mysql'] as $name => $env_settings) {
    $databases[$name]['default'] = $env_settings + [
      'driver' => 'mysql',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
      'prefix' => '',
    ];
  }
  // Setup redis.
  if (!empty($env['redis'])) {
    // @todo: Port to Drupal 8.
    $conf['redis_client_host'] = $env['redis']['host'];
    $conf['redis_client_port'] = $env['redis']['port'];
  }
  // Setup solr.
  if (!empty($env['solr'])) {
    // @todo: Port to Drupal 8.
    $conf['dru_overrides']['search_api_server']['options']['host'] = $env['solr']['host'];
    $conf['dru_overrides']['search_api_server']['options']['port'] = $env['solr']['port'];
    $conf['dru_overrides']['search_api_server']['options']['path'] = $env['solr']['path'];
  }
  if (!empty($env['varnish'])) {
    // @todo: Port to Drupal 8.
    $conf['varnish_control_key'] = $env['varnish']['secret'];
    $conf['varnish_control_terminal'] = $env['varnish']['admin_host'] . ':' . $env['varnish']['admin_port'];
  }
  // Allow configuring arbitrary other configuration and settings.
  if (!empty($env['drupal_config'])) {
    $config = $env['drupal_config'] + $config;
  }
  if (!empty($env['drupal_settings'])) {
    $settings = $env['drupal_settings'] + $settings;
  }
}
