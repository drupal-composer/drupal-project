<?php

$config_directories = array(
  CONFIG_SYNC_DIRECTORY => '../config',
);

$settings['hash_salt'] = '{{ hash_salt }}';
# $settings['deployment_identifier'] = \Drupal::VERSION;
# $settings['install_profile'] = 'standard';

# $settings['reverse_proxy'] = TRUE;
# $settings['reverse_proxy_addresses'] = array('a.b.c.d', ...);
# $settings['reverse_proxy_header'] = 'HTTP_X_CLUSTER_CLIENT_IP';
# $settings['omit_vary_cookie'] = TRUE;
# $settings['class_loader_auto_detect'] = FALSE;

$settings['allow_authorize_operations'] = FALSE;

# $settings['file_chmod_directory'] = 0775;
# $settings['file_chmod_file'] = 0664;
# $settings['file_public_base_url'] = 'http://downloads.example.com/files';
# $settings['file_public_path'] = 'files';
# $settings['file_private_path'] = '../files-private';

$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

# $settings['cache_ttl_4xx'] = 3600;
# $settings['class_loader_auto_detect'] = FALSE;
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
