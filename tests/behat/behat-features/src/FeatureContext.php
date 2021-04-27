<?php

/**
 * @file
 * The main behat context.
 */

use Drupal\DrupalExtension\Context\DrupalContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends DrupalContext {

  /**
   * Apply basic auth.
   *
   * @BeforeScenario
   */
  public function beforeScenario() {
    // HTTP basic auth.
    $this->doBasicAuth();
  }

  /**
   * Perform HTTP basic authentication.
   */
  private function doBasicAuth() {
    // Basic auth.
    $user = getenv('HTTP_AUTH_USER');
    $password = getenv('HTTP_AUTH_PASSWORD');
    if ($user && $password) {
      // Basic auth can only be applied after session started.
      if (!$this->getSession()->isStarted()) {
        $this->getSession()->start();
      }
      $this->getSession()->setBasicAuth($user, $password);
    }
  }

}
