<?php

/*
 * Customize this file as desired. See https://github.com/drush-ops/drush/blob/8.x/examples/policy.drush.inc for documentation.
 */

/**
 * Implements drush_hook_COMMAND_validate().
 *
 * Encourage folks to use `composer` instead of Drush pm commands
 */
function drush_policy_pm_updatecode_validate() {
  return _deny_message();
}

function drush_policy_pm_update_validate() {
  return _deny_message();
}

function drush_policy_pm_download_validate() {
  return _deny_message();
}

function _deny_message() {
  if (!drush_get_option('pm-force')) {
    $msg = 'This codebase is assembled with Composer instead of Drush. Use `composer update` and `composer require` instead of `drush pm-updatecode` and `drush pm-download`. You may override this error by using the --pm-force option.';
    return drush_set_error('POLICY_PM_DENY', dt($msg));
  }
}

/**
 * Implements hook_drush_help_alter().
 */
function policy_drush_help_alter(&$command) {
  if (in_array($command['command'], array('pm-updatecode', 'pm-update', 'pm-download'))) {
    $command['options']['pm-force'] = 'Override site policy and allow Drush codebase management (pm-* commands)';
  }
}
