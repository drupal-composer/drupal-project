<?php

/**
 * @file
 * Configuration file for multi-site support and directory aliasing feature.
 *
 * @see example.sites.php
 * @see https://api.drupal.org/api/drupal/sites!example.sites.php/8
 */

// Send the various "live" URLs to the "production" sites directory.
$sites['MYPROJECT.com']
  = $sites['www.MYRPOJECT.com']
    = $sites['live-MYPROJECT.at.kalamuna.com']
      = $sites['live-MYPROJECT.pantheonsite.io']
        = 'live';

// Send the test and staging URLs to their own sites directory.
$sites['test-MYPROJECT.at.kalamuna.com']
  = $sites['test-MYPROJECT.pantheonsite.io']
    = 'test';
