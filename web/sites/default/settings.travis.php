<?php

// Add in shared based settings and optionally environment specific settings.
require __DIR__ . '/base.settings.php';

// Set temporary folder.
$config['system.file']['path']['temporary'] = '../tmp';

// Set private folder.
$settings['file_private_path'] = '../private';

$settings['trusted_host_patterns'][] = '^localhost$';

$databases['default']['default'] = array(
    'database' => 'drupal',
    'username' => 'root',
    'password' => '',
    'prefix' => '',
    'host' => '127.0.0.1',
    'port' => 3306,
    'namespace' => 'Drupal\Core\Database\Driver\mysql',
    'driver' => 'mysql',
);

// Show errors on dev or test environments.
$config['system.logging']['error_level'] = 'verbose';

// Neither add production or development setting overrides on CI.
// include __DIR__ . '/../development.settings.php';
// include __DIR__ . '/../production.settings.php';
