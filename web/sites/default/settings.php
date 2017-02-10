<?php

/**
 * @file
 * The Drupal configuration file.
 */

// Load services definition file.
$settings['container_yamls'][] = __DIR__ . '/services.yml';

// Include settings specific to sites hosted on Pantheon.
if (file_exists(__DIR__ . '/settings.pantheon.php')) {
  // n.b. The settings.pantheon.php file makes some changes that affect all
  // envrionments that this site exists in. Always include this file, even in a
  // local development environment, to insure that the site settings remain
  // consistent.
  include __DIR__ . '/settings.pantheon.php';
}

// Include settings specific to sites hosted on Acquia.
elseif (isset($_SERVER['AH_SITE_ENVIRONMENT'])) {
  // When using Acquia, update "sitename" in the path below (two places).
  require '/var/www/site-php/sitename/sitename-settings.inc';
}

// Include local environment settings.
if (file_exists(__DIR__ . '/settings.local.php')) {
  include __DIR__ . '/settings.local.php';
}
