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
 
  - PSR4 Autoloading deprecation notice on CI
 
  - Deployment Stuff
   - Add assets folder as a config/env value [X]
   - Automate deployments from scratch [X]
   - PHP
     - Checkout blank version of branch [X]
     - Composer install [X]
     - sls deploy [X]
   - Static
    - Checkout blank version of branch [X]
    - npm install [X]
    - npm build (or whatever correct command is) [X]
    - aws s3 sync command [X]
 
 MONDAY 23rd MARCH
  - FIX Github action deploys - it doesn't appear to be reading the serverless.yml file properly
  [20-Mar-2020 15:42:59] WARNING: [pool default] child 10 said into stderr: "NOTICE: PHP message: PHP Fatal error:  Uncaught InvalidArgumentException: Please provide a valid cache path. in /var/task/vendor/laravel/framework/src/Illuminate/View/Compilers/Compiler.php:36"
