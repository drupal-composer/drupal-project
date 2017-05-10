<?php

/**
 * @file
 * Includes the settings files appropriate for production environments.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

// Always include the "common" settings first.
include "$app_root/sites/settings.common.php";

// Include environmental settings next.
include "$app_root/sites/settings.live.php";

// Local settings. These come last so that they can override anything.
if (file_exists("$app_root/$site_path/settings.local.php")) {
  include "$app_root/$site_path/settings.local.php";
}
