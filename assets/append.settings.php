/**
 * Appended by drupal-project to settings.php provided by the pantheon scaffold.
 */

// Specify the location of our config sync directory.
$settings['config_sync_directory'] = '../config/sync';

// Make sure that only the live environment can send out emails.
if (!isset($_ENV['PANTHEON_ENVIRONMENT']) || $_ENV['PANTHEON_ENVIRONMENT'] !== 'live') {
  $conf['mail_system'] = array(
    'default-system' => 'DevelMailLog',
  );
}

/**
 * WARNING: ADDITIONS OR CHANGES TO THIS FILE WILL BE OVERWRITTEN BY COMPOSER.
 *
 * Before making custom changes, remove "[web-root]/sites/default/settings.php" from the "file-mapping" array in composer.json.
 */
