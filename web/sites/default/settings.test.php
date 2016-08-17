<?php

// Add in shared based settings and optionally environment specific settings.
require_once __DIR__ . '/settings.base.php';
require_once __DIR__ . '/../settings.environment.php';


// Settings are handled via environment.json.
if (empty($env_settings_active)) {
  throw new Exception('Missing environment settings.');
}

// Show errors on dev or test environments.
$config['system.logging']['error_level'] = 'verbose';

// Ususally, there are no development settings on production environments.
// include __DIR__ . '/../settings.devel.php';
