<?php

/**
 * @file
 * Includes the settings files appropriate for development environments.
 *
 * @see default.settings.php
 * @see https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */

// Always include the "common" settings first.
include "$app_root/sites/settings.common.php";

// Include development overrides next.
include "$app_root/sites/settings.dev.php";

// Local settings. These come last so that they can override anything.
if (file_exists("$app_root/$site_path/settings.local.php")) {
  include "$app_root/$site_path/settings.local.php";
}

// Add a shutdown function to help debug 500 errors.
// @see http://dropbucket.org/node/7127
register_shutdown_function(function () {
  if (($error = error_get_last())) {
    $dump = print_r($error, TRUE);
    die("<pre>$dump</pre>");
  }
});
