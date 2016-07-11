<?php

/**
 * Implements drush_HOOK_pre_site_install().
 */
function drush_ncyl_pre_site_install() {
  // Set an environment variable so we know we're performing an install.
  putenv("DRUPAL_INSTALL=TRUE");
}