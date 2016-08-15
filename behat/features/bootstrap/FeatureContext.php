<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Define application features from the specific context.
 */
class FeatureContext extends RawDrupalContext implements Context, SnippetAcceptingContext {
  const DEFAULT_PASSWORD = "admin";

  /**
   * @var int
   *   The ID of the student currently being used by a test.
   */
  private $current_student_id;

  /**
   * Initializes context.
   * Every scenario gets its own context object.
   *
   * @param array $parameters
   *   Context parameters (set them in behat.yml)
   */
  public function __construct(array $parameters = []) {
    // Initialize your context here
  }

  /**
   * @AfterScenario @migration
   */
  public function afterScenario(\Behat\Behat\Hook\Scope\AfterScenarioScope $scope) {
    $driver = $this->getDriver('drush');
    $driver->mr('--all');
  }

  /**
   * @Given I am logged in as user with email :email
   */
  public function assertLoggedInAsUser($email) {
    $this->getSession()->visit($this->locatePath('/user'));

    $page_element = $this->getSession()->getPage();
    $page_element->fillField($this->getDrupalText('username_field'), $email);
    $page_element->fillField($this->getDrupalText('password_field'), self::DEFAULT_PASSWORD);
    $submit = $page_element->findButton($this->getDrupalText('log_in'));
    if (!$submit) {
      throw new \Exception('No submit button on "' . $this->getSession()->getCurrentUrl() . '".');
    }
    $submit->click();

    if ($this->loggedIn()) {
      return;
    }

    throw new \Exception('Not logged in.');
  }

  /**
   * @Given I am testing student :name
   */
  public function assertTestingStudent($name) {
    $this->current_student_id = $this->getStudentIdFromName($name);
  }

  /**
   * @When I visit student path :path
   */
  public function assertViewingStudentPath($path) {
    $this->getSession()->visit($this->locatePath('/student/' . $this->current_student_id . '/' . $path));
  }

  /**
   * @When I view student :name
   */
  public function assertViewingStudentOverview($name)
  {
    $student_id = $this->getStudentIdFromName($name);

    $this->getSession()->visit($this->locatePath('/student/' . $student_id));
  }

  /**
   * @When I view :tab for student :name
   */
  public function assertViewingStudentTab($tab, $name) {
    $student_id = $this->getStudentIdFromName($name);

    $map = [
      'add-placement' => 'student-info/add-placement',
      'add-sibling' => 'student-info/add-sibling',
      'placements' => 'student-info#edit-group-placement',
      'siblings' => 'student-info#edit-group-siblings',
    ];

    if (array_key_exists($tab, $map)) {
      $url = $map[$tab];
    }
    else {
      $url = $tab;
    }

    $this->getSession()->visit($this->locatePath('/student/' . $student_id . '/' . $url));
  }

  /**
   * Checks that access was denied for a page based on either status code or
   * "Access Denied." in the error message.
   *
   * @Then I should get an access denied error
   */
  public function assertAccessDenied() {
    $status_code = $this->getSession()->getStatusCode();
    if ($status_code != 403) {
      // Look for the error message div.
      $errorNode = $this->getSession()
        ->getPage()
        ->find('css', '.messages--error');
      if ($errorNode) {
        if (strpos($errorNode->getText(), 'Access denied.') === FALSE) {
          throw new Exception("No access denied message displayed.");
        }
      }
      else {
        throw new Exception("No error message displayed.");
      }
    }
  }

  /**
   * @Given I wait for the progress bar to finish
   */
  public function iWaitForTheProgressBarToFinish() {
    $this->iFollowMetaRefresh();
  }

  /**
   * @Given I follow meta refresh
   *
   * https://www.drupal.org/node/2011390
   */
  public function iFollowMetaRefresh() {
    while ($refresh = $this->getSession()->getPage()->find('css', 'meta[http-equiv="Refresh"]')) {
      $content = $refresh->getAttribute('content');
      $url = str_replace('0; URL=', '', $content);
      $this->getSession()->visit($url);
    }
  }

  /**
   * @Given I have wiped the site
   */
  public function iHaveWipedTheSite()
  {
    $site = getenv('PSITE');
    $env = getenv('PENV');

    passthru("terminus --yes --site=$site --env=$env site wipe");
  }

  /**
   * @Given I have reinstalled :arg1
   */
  public function iHaveReinstalled($arg1)
  {
    $site = getenv('PSITE');
    $env = getenv('PENV');
    passthru("drush @pantheon.$site.$env --strict=0 site-install --yes standard --site-name='$arg1' --account-name=admin");
  }

  /**
   * @Given I have run the migration
   */
  public function iHaveRunMigration() {
    $driver = $this->getDriver('drush');
    // Revert migration first so migrated contacts connect with new users.
    $driver->mr('--all');
    $this->drushOutput = $driver->mi('--all');
    if (!isset($this->drushOutput)) {
      $this->drushOutput = TRUE;
    }
  }

  /**
   * @Given I have committed my changes with comment :arg1
   */
  public function iHaveCommittedMyChangesWithComment($arg1)
  {
    $site = getenv('PSITE');
    $env = getenv('PENV');

    passthru("terminus --yes --site=$site --env=$env site code commit --message='$arg1'");
  }

  /**
   * @Given I have exported configuration
   */
  public function iHaveExportedConfiguration()
  {
    $site = getenv('PSITE');
    $env = getenv('PENV');
    passthru("drush @pantheon.$site.$env --strict=0 config-export --yes");
  }

  /**
   * @Given I wait :seconds seconds
   */
  public function iWaitSeconds($seconds)
  {
    sleep($seconds);
  }

  /**
   * @Given I wait :seconds seconds or until I see :text
   */
  public function iWaitSecondsOrUntilISee($seconds, $text)
  {
    $errorNode = $this->spin( function($context) use($text) {
      $node = $context->getSession()->getPage()->find('named', array('content', $text));
      if (!$node) {
        return false;
      }
      return $node->isVisible();
    }, $seconds);

    // Throw to signal a problem if we were passed back an error message.
    if (is_object($errorNode)) {
      throw new Exception("Error detected when waiting for '$text': " . $errorNode->getText());
    }
  }

  // http://docs.behat.org/en/v2.5/cookbook/using_spin_functions.html
  // http://mink.behat.org/en/latest/guides/traversing-pages.html#selectors
  public function spin ($lambda, $wait = 60)
  {
    for ($i = 0; $i <= $wait; $i++)
    {
      if ($i > 0) {
        sleep(1);
      }

      $debugContent = $this->getSession()->getPage()->getContent();
      file_put_contents("/tmp/mink/debug-" . $i, "\n\n\n=================================\n$debugContent\n=================================\n\n\n");

      try {
        if ($lambda($this)) {
          return true;
        }
      } catch (Exception $e) {
        // do nothing
      }

      // If we do not see the text we are waiting for, fail fast if
      // we see a Drupal 8 error message pane on the page.
      $node = $this->getSession()->getPage()->find('named', array('content', 'Error'));
      if ($node) {
        $errorNode = $this->getSession()->getPage()->find('css', '.messages--error');
        if ($errorNode) {
          return $errorNode;
        }
        $errorNode = $this->getSession()->getPage()->find('css', 'main');
        if ($errorNode) {
          return $errorNode;
        }
        return $node;
      }
    }

    $backtrace = debug_backtrace();

    throw new Exception(
      "Timeout thrown by " . $backtrace[1]['class'] . "::" . $backtrace[1]['function'] . "()\n" .
      $backtrace[1]['file'] . ", line " . $backtrace[1]['line']
    );

    return false;
  }

  private function getStudentIdFromName($name) {
    /** @var \Drupal\Driver\DrushDriver $drush */
    $drush = $this->getDrupal()->getDriver('drush');

    $conditions = array(
      array(
        'field' => 'name',
        'operator' => '=',
        'value' => $name,
      ),
    );

    $student_ids = $drush->entityQuery('redhen_org', $conditions);
    if (!empty($student_ids)) {
      // Limit to first student found.
      /** @var \Drupal\redhen_org\Entity\Org $student */
      return current($student_ids);
    }

    return NULL;
  }
}
