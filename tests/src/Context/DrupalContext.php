<?php

/**
 * @file
 * Contains \Drupal\DrupalProject\Context\DrupalContext.
 */

namespace Drupal\DrupalProject\Context;

use Drupal\DrupalExtension\Context\DrupalContext as DrupalExtensionDrupalContext;

/**
 * Provides step definitions for interacting with Drupal.
 */
class DrupalContext extends DrupalExtensionDrupalContext {

  /**
   * {@inheritdoc}
   *
   * @Given I am logged in as a/an :role
   */
  public function assertAuthenticatedByRole($role) {
    return parent::assertAuthenticatedByRole($role);
  }

}
