<?php

/**
 * @file
 * Holds project specific drush settings.
 */

// Add-in drunomics drush config.
$options['config'][] = dirname(__FILE__) . '/contrib/drudrush/';

/**
 * List of tables whose *data* is skipped by the 'sql-dump' and 'sql-sync'
 * commands when the "--structure-tables-key=common" option is provided.
 * You may add specific tables to the existing array or add a new element.
 */
$options['structure-tables']['common'] = array(
  'cache*',
  'history',
  'sessions',
  'watchdog',
);

/**
 * List of tables to be omitted entirely from SQL dumps made by the 'sql-dump'
 * and 'sql-sync' commands when the "--skip-tables-key=common" option is
 * provided on the command line.  This is useful if your database contains
 * non-Drupal tables used by some other application or during a migration for
 * example.  You may add new tables to the existing array or add a new element.
 */
# $options['skip-tables']['common'] = array('migration_data1', 'migration_data2');

/**
 * Global default dump directories on the source and target site.
 */
$options['target-dump-dir'] = '/tmp';
$options['source-dump-dir'] = '/tmp';

// Use dump directory next to the web-dir.
$options['drunomics-dump-dir'] = '../dumps';

// Adjust deployment.
$options['shell-aliases']['dply'] = "!drush deploy";
$options['shell-aliases']['deploy'] = "!drush composer install && drush updatedb -y && drush cim -y && drush cr";
$options['shell-aliases']['deploy-all'] = "!drush deploy";
$options['shell-aliases']['composer'] = "!composer --working-dir=$(drush dd)/../ ";
$options['shell-aliases']['dbup'] = "!drush status --fields=bootstrap | grep 'bootstrap *: *Successful' 2>/dev/null || { drush dimport -y && echo Database imported.; }";

$dsi_base = 'drush site-install --account-name=dru_admin --account-pass=dru_admin -y --config-dir=../config minimal';
$options['shell-aliases']['dsi'] = "!chmod +w sites/default/settings.php; drush sql-create -y && $dsi_base";
