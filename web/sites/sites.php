<?php

/**
 * @file
 * Configuration file for multi-site support and directory aliasing feature.
 */

// Send the staging and live sites to the "production" sites directory.
$sites['example.com']
  = $sites['stage.example.com']
    = 'production';
