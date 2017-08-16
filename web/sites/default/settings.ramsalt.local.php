<?php

$is_docker = TRUE;
$your_email = 'your_username@ramsalt.com';

/******* Stage-File-Proxy configuration  *******/
// Set the real hostname of the site, it will be used for `stage_file_proxy` set it to any "FALSE" value to disable it.
$real_host = 'example.com';
// This is used if the files are not in the typical  structure: sites/{$real_host}/files but in a sites/{$real_host_directory}/files
$real_host_directory = 'default'
// if your online host (in this config: $real_host) has an authentication you sould set here the info using the format: username:password
// $real_host_access = 'username:password';

/* MySQL settings. Non-docker users wll need to edit the following settings: */
/*
$db_host      = 'mariadb';
$db_port      = '3306';
$db_name      = 'drupal';
$db_user      = 'drupal';
$db_password  = 'drupal';
*/

/*****************************************************************/
/**                                                             **/
/**     You should not need change anything below this line     **/
/**                                                             **/
/*****************************************************************/

$conf['site_mail'] = $your_email;

if ($real_host ) {
  $real_host_directory = isset($real_host_directory) ? $real_host_directory : $real_host;
  if (isset($real_host_access) )
    $config['stage_file_proxy.settings']['origin'] = 'http://'.$real_host_access.'@'.$real_host;
  else
    $config['stage_file_proxy.settings']['origin'] = 'http://'.$real_host;

  $config['stage_file_proxy.settings']['hotlink'] = FALSE;
  $config['stage_file_proxy.settings']['origin_dir'] = 'sites/'.$real_host_directory.'/files';
}

$config['system.site']['mail'] = $your_email;

$update_free_access = TRUE;

$settings['hash_salt'] = 'UqHbmo0YHN-uYCwhRPnV2nxNNnVOrddPEQwk5U2AXVZiXsjC5A_WDPdxX4F9IIqG2x0FS4wjKA';
$settings['install_profile'] = 'standard';

if ($is_docker) {
  $config['smtp.settings']['smtp_port'] = '1025';
  $config['smtp.settings']['smtp_host'] = 'mailhog';
  $config['smtp.settings']['smtp_from'] = $your_email;
}
else {
  $conf['smtp_reroute_address'] = $your_email;
}

$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
$config['system.logging']['error_level'] = 'verbose';

$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
$settings['extension_discovery_scan_tests'] = TRUE;
$settings['rebuild_access'] = TRUE;
$settings['skip_permissions_hardening'] = TRUE;

$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
  '.sass-cache',
];

$default_db_host      = 'mariadb';
$default_db_port      = '3306';
$default_db_name      = 'drupal';
$default_db_user      = 'drupal';
$default_db_password  = 'drupal';
if (empty($db_user)) {
  $db_user = $default_db_user;
  $db_password = $default_db_password;
}
if (empty($db_name)) {
  $db_name = $default_db_name;
}
if (empty($db_host)) {
  $db_host = $default_db_host;
}
if (empty($db_port)) {
  $db_port = $default_db_port;
}

$databases['default']['default'] = array(
  'database' => $db_name,
  'username' => $db_user,
  'password' => $db_password,
  'prefix' => '',
  'host' => $db_host,
  'port' => $db_port,
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);
