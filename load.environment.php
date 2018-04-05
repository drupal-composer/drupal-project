<?php

/**
 * This file is included very early. See autoload-dev.files in composer.json and
 * https://getcomposer.org/doc/04-schema.md#files
 *
 * For performance reasons, this file is NOT included in --no-dev installs.
 *
 * If you would like to remove phpdotenv entirely from your project, run the
 * following:
 *
 * 1. Run 'composer remove --dev vlucas/phpdotenv'
 * 2. Remove this file and .env.example: 'rm load.environment.php .env.example'
 * 3. Remove "files": ["load.environment.php"] from composer.json
 * 4. Run 'composer dump-autoload' to regenerate the autoloader.
 * 5. Run 'composer update --lock' to update the lock hash, since composer.json
 *    was manually edited.
 */

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

/**
 * Load any .env file. See /.env.example.
 */
$dotenv = new Dotenv(__DIR__);
try {
  $dotenv->load();
}
catch (InvalidPathException $e) {
  // Do nothing. Production environments rarely use .env files.
}
