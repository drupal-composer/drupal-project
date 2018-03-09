<?php

namespace Drupal\kalamuna;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Various helpful functions for use mainly by Drupal settings files.
 */
class KalaUtil {

  /**
   * The default status code to use across all the redirect functions.
   *
   * @var int
   */
  public static $defaultStatusCode = 302;

  /**
   * The default protocol to use across all the redirect functions.
   *
   * @var string
   */
  public static $defaultProtocol;

  /**
   * Issues an HTTP Location response to the browser and ends current request.
   *
   * @param string $protocol
   *   'http', 'https', or NULL to use the protocol of the current request.
   * @param string $host
   *   The destination host, or NULL to use the host of the current request.
   * @param string $path
   *   The destination URI, or NULL to use the URI of the current request.
   * @param int $status
   *   The HTTP status code to use in the redirection response; defaults to 302.
   */
  public static function redirect($protocol = NULL, $host = NULL, $path = NULL, $status = NULL) {

    // Don't break drush.
    if (PHP_SAPI === 'cli') {
      return;
    }

    // Prep the variables with defaults.
    foreach (array_keys(get_defined_vars()) as $var) {
      if (is_null($$var)) {
        switch ($var) {

          case 'protocol':
            $protocol = isset(static::$defaultProtocol)
              ? static::$defaultProtocol
              : (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
                ? $_SERVER['HTTP_X_FORWARDED_PROTO']
                : 'http');
            break;

          case 'host':
            $host = $_SERVER['HTTP_HOST'];
            break;

          case 'path':
            $path = $_SERVER['REQUEST_URI'];
            break;

          case 'status':
            $status = static::$defaultStatusCode;
            break;
        }
      }
    }

    // Name transaction "redirect" in New Relic for improved reporting.
    if (extension_loaded('newrelic')) {
      newrelic_name_transaction("redirect");
    }

    // Let Symfony issue the redirect.
    $response = new RedirectResponse("$protocol://$host{$path}", $status);
    $response->send();
  }

  /**
   * Forces use of SSL.
   */
  // @codingStandardsIgnoreStart
  public static function enforceSSL() {
  // @codingStandardsIgnoreEnd
    if (!isset($_SERVER['HTTP_X_SSL']) || $_SERVER['HTTP_X_SSL'] !== 'ON') {
      static::redirect('https', NULL, NULL, 301);
    }
  }

  /**
   * Redirects wildcards (including root) to a single path on the same host.
   *
   * @param array $wildcard_to_path
   *   An array of destinations keyed by the wildcard paths to match.
   */
  public static function redirectWildcards(array $wildcard_to_path) {
    foreach ($wildcard_to_path as $wildcard => $path) {
      if (stripos($_SERVER['REQUEST_URI'], $wildcard) === 0) {
        static::redirect(NULL, NULL, $path);
      }
    }
  }

}
