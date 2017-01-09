<?php

// Add in shared based settings and optionally environment specific settings.
require __DIR__ . '/settings.base.php';
require DRUPAL_ROOT . '/sites/settings.environment.php';


// Settings are handled via environment.json.
if (empty($env_settings_active)) {
  throw new Exception('Missing environment settings.');
}

// Ususally, there are no development settings on production environments.
// include __DIR__ . '/../settings.devel.php';
