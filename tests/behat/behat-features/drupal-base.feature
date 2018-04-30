@api
@smoke
Feature: Drupal basically works.

  Make sure Drupal generates the front page, error pages as well as logging in
  and out.

  Scenario: Drupal generates a page
    Given I am on "/"
    Then the response should contain "Drupal 8 ("

  Scenario: Drupal generates a 404 response
    Given I am an anonymous user
    And I am on "some-not-existing-page"
    Then the response status code should be 404

  Scenario: Drupal generates a 403 response
    Given I am an anonymous user
    And I am on "/admin"
    Then the response status code should be 403

  Scenario: I can log in and logout.
    Given I am an anonymous user
    Then I should not be logged in.

    Given I am logged in as a user with the "authenticated user" role
    Then I should be logged in.
    When I go to "/user/logout"
    Then I should not be logged in.

  @javascript
  Scenario: Frontend assets are loaded.
    Given I am on "/"
    Then I should see Element "h1" with the Css Style Property "font-size" matching "28px"

 # Add js check to enable.
 # @javascript
 # Scenario: No javascript errors are generated.
 #   Given I am on "/"
 #   Then I should not see any javascript errors in the console
