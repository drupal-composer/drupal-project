<?php

/**
 * @file
 * Includes the settings files appropriate for development environments.
 */

// Always include the "common" settings first.
require "$app_root/sites/settings.common.php";

// Include environmental settings next.
require "$app_root/sites/settings.development.php";

// Load local environment override configuration, if available.
// Keep this code block at the end of this file to take full effect.
if (file_exists("$app_root/$site_path/settings.local.php")) {
  include "$app_root/$site_path/settings.local.php";
}
