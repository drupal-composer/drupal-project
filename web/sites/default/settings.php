<?php

/**
 * Include Wodby settings if required.
 */
isset($_SERVER['WODBY_CONF']) && include $_SERVER['WODBY_CONF'] . '/wodby.settings.php';

$config_directories = array(
  CONFIG_SYNC_DIRECTORY => DRUPAL_ROOT . '/../config/sync/',
);

$ENV_TYPE = isset($_SERVER['WODBY_ENVIRONMENT_TYPE']) ? $_SERVER['WODBY_ENVIRONMENT_TYPE'] : 'local';
$filename = __DIR__ . "/settings.ramsalt.{$ENV_TYPE}.php";

if(file_exists($filename))
  include_once $filename;
