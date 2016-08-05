Feature: User authentication
  In order to protect the integrity of the website
  As a product owner
  I want to make sure only authenticated users can access the site administration

  Scenario: Anonymous user can see the user login page
    Given I am not logged in
    When I visit "user"
    Then I should see the text "Log in"
    And I should see the text "Reset your password"
    And I should see the text "Username"
    And I should see the text "Password"
    And I click "Reset your password"
    Then I should see "Password reset instructions will be sent"

  Scenario Outline: Anonymous user cannot access site administration
    Given I am not logged in
    When I go to "<path>"
    Then I should get an access denied error

    Examples:
      | path            |
      | admin           |
      | admin/config    |
      | admin/content   |
      | admin/people    |
      | admin/structure |
      | node/add        |

#  Scenario Outline: Anonymous user is redirected to login page when they hit 403.
#    Given I am not logged in
#    When I go to "<path>"
#    Then I should see the text "User login"
#    And I should see the text "Reset your password"
#    And I should see the text "Username"
#    And I should see the text "Password"
#
#    Examples:
#      | path      |
#      | students  |

  Scenario Outline: Anonymous user sees Page not found when they hit a 404.
    Given I am not logged in
    When I go to "<path>"
    Then I should see the text "Page not found"

    Examples:
      | path |
      | foo  |
