<?php

/**
 * If not on Pantheon - load our .env file.
 */
if (!defined('PANTHEON_ENVIRONMENT')) {
  $dotenv = new Dotenv\Dotenv(__DIR__ . '/../../../');
  $dotenv->overload();
}

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all environments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to insure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

// If we have a default hash salt value (from PRESSFLOW_SETTINGS) use it.
if (!empty($drupal_hash_salt)) {
  $settings['hash_salt'] = $drupal_hash_salt;
}

// If we're on pantheon we don't overwrite the sync directory.
if (!defined('PANTHEON_ENVIRONMENT')) {
  $config_directories[CONFIG_SYNC_DIRECTORY] = $config_directory_name;
}

// Check for custom DRUPAL_INSTALL environment variable (set in
// drush/SITE.drush.inc) OR PANTHEON_ENVIRONMENT to determine if we should
// enable file base config. We only use file based for local development.
// We could use a module like config_devel locally as well.
if (!(getenv('DRUPAL_INSTALL') || defined('PANTHEON_ENVIRONMENT'))) {
  // Bootstrap file based config.
  $settings['bootstrap_config_storage'] = array(
    'Drupal\Core\Config\BootstrapConfigStorageFactory',
    'getFileStorage'
  );

  $config_directories[CONFIG_ACTIVE_DIRECTORY] = $config_directories[CONFIG_SYNC_DIRECTORY];

  $settings['container_yamls'][] = DRUPAL_ROOT . '/sites/default/services.config.yml';
}

// If we are not installing and have a redis host defined, setup redis.
if (!(getenv('DRUPAL_INSTALL') || $is_installer_url) && !empty($conf['redis_client_host'])) {

  $settings['container_yamls'][] = DRUPAL_ROOT . '/sites/default/services.redis.yml';

  $settings['redis.connection']['interface'] = 'PhpRedis'; // Can be "Predis".
  $settings['redis.connection']['host'] = $conf['redis_client_host'];
  $settings['redis.connection']['port'] = $conf['redis_client_port'];
  $settings['redis.connection']['password'] = $conf['redis_client_password'];
  $settings['cache']['default'] = 'cache.backend.redis';
  // Set a cache_prefix per https://github.com/md-systems/redis/issues/8
  $env_name = (defined('PANTHEON_ENVIRONMENT')) ? PANTHEON_ENVIRONMENT : getenv('TERMINUS_ENV');
  $settings['cache_prefix'] = 'SITE_' . $env_name;

  // Always set the fast backend for bootstrap, discover and config, otherwise
  // this gets lost when redis is enabled.
  $settings['cache']['bins']['bootstrap'] = 'cache.backend.chainedfast';
  $settings['cache']['bins']['discovery'] = 'cache.backend.chainedfast';
  $settings['cache']['bins']['config'] = 'cache.backend.chainedfast';
}

/**
 * If there is a dev settings file, include it - but only when not in any of the
 * "prod" environments.
 */
$prod_envs = ['test', 'live', 'develop'];
if (!defined('PANTHEON_ENVIRONMENT') || (defined('PANTHEON_ENVIRONMENT') && !in_array(PANTHEON_ENVIRONMENT, $prod_envs))) {
  $dev_settings = __DIR__ . "/settings.dev.php";
  if (file_exists($dev_settings)) {
    include $dev_settings;
  }
}

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

$settings['install_profile'] = 'minimal';

// Require HTTPS.
// Check if Drupal is running via command line
if (isset($_SERVER['PANTHEON_ENVIRONMENT']) &&
  ($_SERVER['HTTPS'] === 'OFF') &&
  (php_sapi_name() != "cli")) {
  if (!isset($_SERVER['HTTP_X_SSL']) ||
    (isset($_SERVER['HTTP_X_SSL']) && $_SERVER['HTTP_X_SSL'] != 'ON')) {
    header('HTTP/1.0 301 Moved Permanently');
    header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
  }
}