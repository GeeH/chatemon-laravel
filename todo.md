 - Add persistence so we can play out a game [X]
 - Use Sync to update the web front end automatically [X]
  - Create token endpoint in Laravel [X]
  
 - Write some controller tests []
  - Refactor Controller abstract class to remove inline "new" call [X]
  - Write a Service Provider so that the Laravel container can create a client [X]
  - Write a test that mocks the client and request/logger and assert some stuff
  
  !!! DO WE MOVE COMBAT TO BE A DEPENDENCY OF THE CONTROLLER AND WRITE A SERVICE PROVIDER FOR IT???????? FRIDAY
 
 - Add ability to catch Chatemon? []
 - Add type system []
 - Add multiple Chatemon to system
 
 - Add first handler so we can play the game remotely via
    - SMS [X]
    - Twitch Chat []
    - Email []
    - WhatsApp []
    - Anything else? []
 
 
 
  - Deployment Stuff
   - Add assets folder as a config/env value
   - Automate deployments from scratch
   - PHP
     - Checkout blank version of branch
     - Composer install
     - sls deploy
   - Static
    - Checkout blank version of branch
    - npm install
    - npm build (or whatever correct command is)
    - aws s3 sync command
   - Run e2e test?
