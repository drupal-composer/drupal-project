<?php

/**
 * @file
 * This file has been generated automatically.
 *
 * Please change the configuration for correct use deploy.
 */

namespace Deployer;

// Client repository machine name.
set('application', "drupal-project");

// The recipe to use: frmwrk.drupal7.php,
// frmwrk.drupal8.php or frmwrk.drupal8.dep.php.
require 'recipe/frmwrk.drupal8.php';

// Only for projects where locale needs to be update on every release.
/*
 * after('drupal:cim', 'drupal:locale_update');
 */
