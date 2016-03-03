<?php

$databases['default']['default'] = array (
  'database' => '{{ project }}',
  'username' => 'root',
  'password' => '',
  'prefix' => '',
  'host' => '{{ project }}.local',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['trusted_host_patterns'][] = '^{{ project }}.local$';

$config_directories = array(
  CONFIG_SYNC_DIRECTORY => '../config',
);

# $settings['deployment_identifier'] = \Drupal::VERSION;
#$settings['hash_salt'] = 'HQ22SkTOCw4Cw8GF8eC__Byzer-4LUpEaBAYZJ-X3JOi49k0OsUmhp6NMJL1YavlzLL-J8F7Dg';
#$settings['install_profile'] = 'standard';

# $settings['reverse_proxy'] = TRUE;
# $settings['reverse_proxy_addresses'] = array('a.b.c.d', ...);
# $settings['reverse_proxy_header'] = 'HTTP_X_CLUSTER_CLIENT_IP';
# $settings['omit_vary_cookie'] = TRUE;
# $settings['class_loader_auto_detect'] = FALSE;

$settings['allow_authorize_operations'] = FALSE;

# $settings['file_chmod_directory'] = 0775;
# $settings['file_chmod_file'] = 0664;
# $settings['file_public_base_url'] = 'http://downloads.example.com/files';

$settings['file_public_path'] = 'files';
$settings['file_private_path'] = '../private';

# $settings['session_write_interval'] = 180;
# $settings['locale_custom_strings_en'][''] = array(
#   'forum'      => 'Discussion board',
#   '@count min' => '@count minutes',
# );

# $settings['maintenance_theme'] = 'bartik';

# $config['system.performance']['fast_404']['exclude_paths'] = '/\/(?:styles)|(?:system\/files)\//';
# $config['system.performance']['fast_404']['paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
# $config['system.performance']['fast_404']['html'] = '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

$settings['container_yamls'][] = __DIR__ . '/services.yml';
include __DIR__ . '/../settings.devel.php';
