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
    $user = getenv('HTTP_AUTH_USER');
    $password = getenv('HTTP_AUTH_PASSWORD');
    if ($user && $password) {
      $this->getSession()->setBasicAuth($user, $password);
    }
  }

}
