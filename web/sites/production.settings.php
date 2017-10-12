<?php
/**
 * @file
 * Drupal 8 production environment configuration file.
 *
 * This file will only be included on production environments.
 */

// Don't show any error messages on the site (will still be shown in watchdog).
$config['system.logging']['error_level'] = 'hide';

// Disabling stage file proxy on production, with that the module can be enabled even on production
$config['stage_file_proxy.settings']['origin'] = false;
