[![Build Status](https://travis-ci.org/LeadPages/php_auth_package.svg?branch=master)](https://travis-ci.org/LeadPages/php_auth_package)

## Synopsis

Leadpages Auth is meant to make it simple to get your integration into Leadpages off the ground quickly.
* Abstracts away the required methods to call Leadpages to retrieve your security token.
* Built in minimal storage abstraction to allow Leadpages extensions to follow known sets of standards.
* Uses Guzzle5 to allow a consistant Http abstraction layer across all platforms. Guzzle5 chosen for PHP 5.4 support

## Code Example - WordPress

```
<?php

/**
 * require composer autoload this path will probably change for your implementation
 * wordpress would suggest using plugin_dir_path
 * example require plugin_dir_path(dirname(dirname(dirname(__FILE__))))."/vendor/autoload.php";
*/

require dirname(dirname(dirname(__FILE__))) . "/vendor/autoload.php";

use GuzzleHttp\Client;
use Leadpages\Auth\LeadpagesLogin;

/**
 * Extend the Leadpages login abstract class
 * and fill out the required methods to store and retrieve your token
 * Class WordPressLeadpagesLogin
 */
class WordPressLeadpagesLogin extends LeadpagesLogin
{

    /**
     * store token in Wordpress Database
     *
     * @return mixed
     */
    public function storeToken()
    {
        update_option($this->tokenLabel, $this->token);
    }


    /**
     * get token form WordPress Database and set the $this->token
     * $this->token needs to be set on this method
     */
    public function getToken()
    {
        $this->token = get_option($this->tokenLabel);
    }

    /**
     * Delete token from WordPress Database
     * @return mixed
     */
     
    public function deleteToken()
    {
        delete_options($this->tokenLabel);
    }

    /**
     * method to check if token is empty
     *
     * @return mixed
     */
    public function checkIfTokenIsEmpty()
    {
        // TODO: Implement checkIfTokenIsEmpty() method.
    }
}

//instantiate Class
$leadpagesLogin = new WordPressLeadpagesLogin(new Client());
//call get user pipe into parseResponse
$response = $leadpagesLogin->getUser('example@example.com', 'password')->parseResponse();

if($response == 'success'){
    $this->storeToken();
}else{
    //this->response holds a json object with response codes
    return $this->response;
}

//check if a user is logged in
$isLoggedIn = $leadpagesLogin->checkIfUserIsLoggedIn();


```


## Installation

Package can be installed via [Composer](https://getcomposer.org/)

```
#install composer
curl -sS https://getcomposer.org/installer | php
```

Run composer to require the package

```
php composer.phar require leadpages/leadpages-auth
```

Next insure that you are included the composer autoloader into your project. Package uses PSR-4 Autoloading
```
require 'vendor/autoload.php';
```

## API Reference

Docs to come

## Tests

Tests are run via PHPUnit

## Contributors

## License

