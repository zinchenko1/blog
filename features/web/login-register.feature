Feature: Login test
    Scenario: Login
        Given I am on the homepage
        When I go to "/login"
        And I fill in "email" with "admin@example.com"
        And I fill in "password" with "test"
        And I press "login"
        Then I should be on "/admin/"

    Scenario: Register
        Given I am on the homepage
        When I go to "/register"
        And I fill in "registration_form[first_name]" with "Test"
        And I fill in "registration_form[last_name]" with "User"
        And I fill in "registration_form[email]" with "test@example.com"
        And I fill in "registration_form[password]" with "test"
        And I press "Register"
        Then I should be on "/register"
