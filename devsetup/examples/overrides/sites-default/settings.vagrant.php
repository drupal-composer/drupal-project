<?php

$databases = array (
  'default' =>
    array (
      'default' =>
        array (
          'database' => 'druweb8',
          'username' => 'root',
          'password' => '',
          'host' => 'druweb8.local',
          'port' => '',
          'driver' => 'mysql',
          'prefix' => '',
        ),
    ),
);

$update_free_access = FALSE;

ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.gc_maxlifetime', 200000);
ini_set('session.cookie_lifetime', 2000000);
# $cookie_domain = 'example.com';

# $conf['site_name'] = 'My Drupal site';
# $conf['theme_default'] = 'garland';
# $conf['anonymous'] = 'Visitor';

# $conf['maintenance_theme'] = 'bartik';
# $conf['reverse_proxy'] = TRUE;
# $conf['reverse_proxy_addresses'] = array('a.b.c.d', ...);
# $conf['omit_vary_cookie'] = TRUE;

# $conf['locale_custom_strings_en'][''] = array(
#   'forum'      => 'Discussion board',
#   '@count min' => '@count minutes',
# );

// Always override the file system paths.
$conf['file_private_path'] = 'sites/default/private';
$conf['file_public_path'] = 'files';
$conf['file_temporary_path'] = '/tmp';

// Always disable CSS and JS aggregation and the anonymous page cache.
$conf['cache'] = FALSE;
$conf['preprocess_css'] = FALSE;
$conf['preprocess_js'] = FALSE;

$conf['drunomics_environment'] = 'vagrant';

// Environment indicator.
$conf['environment_indicator_overwrite'] = TRUE;
$conf['environment_indicator_overwritten_color'] = 'black';
$conf['environment_indicator_overwritten_name'] = $conf['drunomics_environment'];

$base_url = "http://druweb8.local";
