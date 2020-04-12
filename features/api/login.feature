Feature: Test to get JWT-token
  Scenario: Get JWT-token
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/login" with body:
        """
        {
          "username": "admin@example.com",
          "password": "test"
        }
        """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    And the JSON node "token" should exist
    And the JSON node "refresh_token" should exist
