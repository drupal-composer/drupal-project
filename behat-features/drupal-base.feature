@api
Feature: Drupal basically works.

  Scenario: Drupal generates a page
    Given I am on "/"
    Then the response should contain "Drupal 8 (https://www.drupal.org)"

  Scenario: Drupal generates a 404 response
    Given I am an anonymous user
    And I am on "some-not-existing-page"
    Then I should see "Page not found"

  Scenario: Drupal generates a 403 response
    Given I am an anonymous user
    And I am on "/admin"
    Then I should see "Access denied"

  Scenario: I can log in and logout.
    Given I am logged in as a user with the "authenticated user" role
    Then I should see the link "ausloggen"
    When I click "ausloggen"
    Then I should not see the link "ausloggen"
