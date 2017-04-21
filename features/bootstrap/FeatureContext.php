<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawDrupalContext implements Context, SnippetAcceptingContext {

  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct() {
  }

  /**
   * @BeforeFeature
   */
  public static function setup() {
    shell_exec("drush sql-dump > orig.sql");
    if (file_exists('mock.sql')) {
      shell_exec('drush sql-drop -y');
      shell_exec('`drush sql-connect` < mock.sql 2>&1');
    }
  }

  /**
   * @AfterFeature
   */
  public static function teardown() {
    shell_exec('drush sql-drop -y');
    shell_exec('`drush sql-connect` < orig.sql 2>&1');
    unlink('orig.sql');
  }

}
