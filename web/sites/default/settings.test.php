<?php

// Add in shared based settings and optionally environment specific settings.
require __DIR__ . '/base.settings.php';
require DRUPAL_ROOT . '/sites/environment.settings.php';


// Settings are handled via environment.json.
if (empty($env_settings_active)) {
  throw new Exception('Missing environment settings.');
}

// Show errors on dev or test environments.
$config['system.logging']['error_level'] = 'verbose';

// Add production settings.
include __DIR__ . '/../production.settings.php';

