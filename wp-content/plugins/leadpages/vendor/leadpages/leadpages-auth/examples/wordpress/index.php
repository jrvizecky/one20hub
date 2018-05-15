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


//will return true of false if the users stored token retrieves a proper response
$isLoggedIn = $leadpagesLogin->checkCurrentUserToken();


//this will set the response for checkIfUserIsloggedIn to verify against.
//could also chain them as they are fluent $leadpagesLogin->getCurrentUserToken()->checkIfUserIsLoggedIn()
//isLoggedIn should be true if the current token call resulted in a proper response from auth api
$isLoggedIn = $leadpagesLogin->checkIfUserIsLoggedIn();

