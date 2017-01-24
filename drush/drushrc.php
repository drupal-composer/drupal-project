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
// Note that the trailing whitespace is necessary to make it differnt to "dply".
// This seems necessary to work-a-round some drush bug not finding it else.
$options['shell-aliases']['deploy-all'] = "!drush deploy" . " ";
$options['shell-aliases']['deploy'] = "!drush composer install && drush entity-updates -y && drush updatedb -y && drush cim -y && drush cr";
$options['shell-aliases']['composer'] = "!composer --working-dir=$(drush dd)/../ ";

# Install command for "dsi" and "dbup" aliases:
$dsi_base = 'drush site-install --account-name=dru_admin --account-pass=dru_admin -y standard';
# Variant once config is exported:
#$dsi_base = 'drush site-install --account-name=dru_admin --account-pass=dru_admin -y --config-dir=../config standard';

$options['shell-aliases']['dsi'] = "!chmod +w sites/default/settings.php; drush sql-create -y && $dsi_base";
$options['shell-aliases']['dbup'] = "!drush status --fields=bootstrap | grep 'bootstrap *: *Successful' 2>/dev/null || ( {$options['shell-aliases']['dsi']} )";
