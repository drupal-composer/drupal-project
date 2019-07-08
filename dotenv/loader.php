<?php

/**
 * @file
 * Loads dotenv files.
 *
 * This file is included very early with the composer autoloader or invoked
 * via CLI.
 *
 * See loader.sh and autoload.files in composer.json.
 */

use Symfony\Component\Dotenv\Dotenv;

/**
 * Takes care of initializes the phapp environment.
 */
class PhappEnvironmentLoader {

  /**
   * Gets the content of all .*env files to load.
   *
   * @return string
   *   The content of all files. Can be sourced by bash or a dotenv parser.
   */
  public static function getDotenvFiles() {
    $files = [];

    foreach (glob(__DIR__ . '/../.*env') as $filename) {
      $files[] = file_get_contents($filename);
    }
    return implode("\n", $files);
  }

  /**
   * Determines the id of the currently active environment.
   *
   * After parsing the found dotenv files, the currently active environment gets
   * determined. Usually, this is defined by the PHAPP_ENV variable which is
   * defined in some .env files. However, this method can be used to detect
   * other non-standard environments based on some other criteria.
   *
   * @return string|null
   *   A key-value assignmenet as suiting for putenv() or NULL.
   */
  public static function determineEnvironment() {
    // For Acquia we don't have a per environment .env file, but need to parse
    // the AH_SITE_ENVIRONMENT variable.
    if ($siteEnvironment = getenv('AH_SITE_ENVIRONMENT')) {
      // Make use of live - test - dev names.
      $phapp_env = $siteEnvironment == 'prod' ? 'live' : ($siteEnvironment == 'stg' ? 'test' : $siteEnvironment);
      return "PHAPP_ENV=$phapp_env";
    }

    // Support amazee.io lagoon.
    if ($lagoon_safe_branch = getenv('LAGOON_GIT_SAFE_BRANCH')) {
      return "PHAPP_ENV=amazeeio.$lagoon_safe_branch";
    }
  }

  /**
   * Prepares the determined environment by loading any env-specific files.
   *
   * @return string
   *   The content of all files. Can be sourced by bash or a dotenv parser.
   */
  public static function prepareDeterminedEnvironment() {
    $phapp_env = getenv('PHAPP_ENV');
    $result = '';
    // Support dots in the environment name and load files by prefix.
    // This allows grouping environments by host having shared settings.
    if (strpos($phapp_env, '.') !== FALSE) {
      $parts = explode('.', $phapp_env, 2);
      $file = __DIR__ . '/' . $parts[0] . '.env';
      if (file_exists($file)) {
        $result .= file_get_contents($file) . "\n";
      }
    }
    if (file_exists(__DIR__ . '/' . $phapp_env . '.env')) {
      $result .= file_get_contents(__DIR__ . '/' . $phapp_env . '.env');
    }
    return $result;
  }

  /**
   * Prepare app environment with project-specific dotenv files.
   *
   * Respects the SITE variable as set.
   *
   * @return string
   *   The content of all files. Can be sourced by bash or a dotenv parser.
   */
  public static function prepareAppEnvironment() {
    $site = static::determineActiveSite();
    $vars = '';
    $phapp_env = getenv('PHAPP_ENV');
    // Make sure CLI invocations get the variables from the request matcher
    // assigned also. In addition, always add site variables for shell scripts
    // to ease debugging.
    if (!getenv('SITE_MAIN_HOST') || !empty($GLOBALS['argv'][0])) {
      foreach (static::getSiteVariables() as $variable => $value) {
        $vars .= "$variable=$value\n";
      }
    }

    // Parse the site-specific dotenv files.
    if (file_exists(__DIR__ . '/sites/all.env')) {
      $vars .= file_get_contents(__DIR__ . '/sites/all.env') . "\n";
    }
    // Support per-environment all.env files.
    if (file_exists(__DIR__ . '/sites/all.env-' . $phapp_env . '.env')) {
      $vars .= file_get_contents(__DIR__ . '/sites/all.env-' . $phapp_env . '.env') . "\n";
    }
    if (file_exists(__DIR__ . '/sites/' . $site . '.env')) {
      $vars .= file_get_contents(__DIR__ . '/sites/' . $site . '.env') . "\n";
    }
    return $vars;
  }

  /**
   * Determines the currently active site.
   *
   * Copy of
   * \drunomics\MultisiteRequestMatcher\RequestMatcher::determineActiveSite()
   *
   * @return string
   *   The active site's name.
   */
  public static function determineActiveSite() {
    $site = getenv('SITE') ?: getenv('APP_DEFAULT_SITE');
    if (!$site) {
      $sites = explode(' ', getenv('APP_SITES'));
      $site = reset($sites);
    }
    return $site;
  }

  /**
   * Determines the currently active site variant.
   *
   * Copy of
   * \drunomics\MultisiteRequestMatcher\RequestMatcher::determineActiveSiteVariant()
   *
   * @return string
   *   The active site variant, '' for the default variant.
   */
  public static function determineActiveSiteVariant() {
    return getenv('SITE_VARIANT') ?: '';
  }

  /**
   * Gets the same site variables as set during request matching.
   *
   * Copy of
   * \drunomics\MultisiteRequestMatcher\RequestMatcher::getSiteVariables()
   * to ensure it's available before vendors are installed.
   */
  public static function getSiteVariables($site = NULL, $site_variant = '') {
    $site = $site ?: static::determineActiveSite();
    $vars = [];
    $vars['SITE'] = $site;
    $vars['SITE_VARIANT'] = $site_variant ?: static::determineActiveSiteVariant();
    if ($domain = getenv('APP_MULTISITE_DOMAIN')) {
      $host = $site . getenv('APP_MULTISITE_DOMAIN_PREFIX_SEPARATOR') . $domain;
    }
    else {
      $host = getenv('APP_SITE_DOMAIN__' . str_replace('-', '_', $site));
    }
    if ($vars['SITE_VARIANT']) {
      $separator = getenv('APP_SITE_VARIANT_SEPARATOR') ?: '--';
      $host = $vars['SITE_VARIANT'] . $separator . $host;
    }
    $vars['SITE_HOST'] = $host;
    $vars['SITE_MAIN_HOST'] = $host;
    return $vars;
  }

}

// Allow using the loader via direct CLI execution.
// @see loader.sh
if (php_sapi_name() == "cli" && isset($argv[0]) && strpos($argv[0], '/loader.php') !== 0) {
  if (!method_exists(PhappEnvironmentLoader::class, $argv[1])) {
    die('Unable to find method ' . $argv[1]);
  }
  echo call_user_func(array(PhappEnvironmentLoader::class, $argv[1]));
}
// Else we are loaded via the composer autoloader.
else {

  // The following process must follow the same logic as loader.sh, but instead
  // evaluating .env content with bash we use dotenv to parse it.
  $dotenv = new Dotenv();
  $dotenv->populate($dotenv->parse(PhappEnvironmentLoader::getDotenvFiles()));
  $dotenv->populate($dotenv->parse(PhappEnvironmentLoader::determineEnvironment()));
  if (!getenv('PHAPP_ENV')) {
    die("Missing .env file or PHAPP_ENV environment variable. Did you run phapp setup?");
  }
  $dotenv->populate($dotenv->parse(PhappEnvironmentLoader::prepareDeterminedEnvironment()));

  // Match the request and prepare site-specific dotenv vars.
  $site = drunomics\MultisiteRequestMatcher\RequestMatcher::getInstance()
    ->match();
  $dotenv->populate($dotenv->parse(PhappEnvironmentLoader::prepareAppEnvironment()));
}
