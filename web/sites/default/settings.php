<?php

/**
 * @file
 * Includes the settings files appropriate to the current environment.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

// Include settings common to all environments first.
include "$app_root/sites/settings.common.php";

// Include environment-specific settings.
include "$app_root/sites/settings.$env.php";

// Allow local settings to override anything specified above.
if (file_exists("$app_root/$site_path/settings.local.php")) {
  include "$app_root/$site_path/settings.local.php";
}
