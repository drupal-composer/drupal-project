<?php

/**
 * @file
 * The main behat context.
 */

use Behat\Mink\Exception\ExpectationException;
use Drupal\DrupalExtension\Context\DrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends DrupalContext implements SnippetAcceptingContext {

  /**
   * @Then I should be redirected to :url.
   */
  public function iShouldBeRedirectedTo($path) {
    if ($this->getSession()->getCurrentUrl() != $this->locatePath($path)) {
      throw new ExpectationException("URL does not match expected path.", $this->getSession());
    }
  }

}
