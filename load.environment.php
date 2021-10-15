<?php

/**
 * This file is included very early. See autoload.files in composer.json and
 * https://getcomposer.org/doc/04-schema.md#files
 */

use Dotenv\Dotenv;

/**
 * Load any .env file. See /.env.example.
 *
 * Drupal has no official method for loading environment variables and uses
 * getenv() in some places.
 * 
 * Check for the method to ensure backward compatibility.
 */
if (method_exists('Dotenv', 'createUnsafeImmutable')) {
  $dotenv = Dotenv::createUnsafeImmutable(__DIR__);
elseif (method_exists('Dotenv', 'createImmutable')) {
  $dotenv = Dotenv::createImmutable(__DIR__);
}  
$dotenv->safeLoad();
