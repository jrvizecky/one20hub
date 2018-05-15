<?php

namespace LeadpagesWP\ServiceProviders;

use Leadpages\Auth\LeadpagesLogin;
use LeadpagesMetrics\LeadpagesSignInEvent;

class WordPressLeadpagesAuth extends LeadpagesLogin
{

    public static function getName()
    {
        return get_called_class();
    }

    /**
     * method to implement on extending class to store token in database
     *
     * @return mixed
     */
    public function storeToken()
    {
        update_option($this->tokenLabel, $this->token);
    }

	/**
	 * Store api key in datastore
	 */
    public function storeApiKey()
    {
        update_option($this->apiKeyLabel, $this->apiKey);
    }

    /**
     * method to implement on extending class to get token from datastore
     * should return token not set property of $this->token
	 *
     * @return mixed
     */
    public function getToken()
    {
		$this->token = get_option('leadpages_security_token', null);
		return $this->token;
    }

	/**
	 * Fetch api key from datastore
	 *
	 * @return string
	 */
    public function getApiKey()
    {
        $this->apiKey = get_option($this->apiKeyLabel);
        return $this->apiKey;
    }

    /**
     * method to implement on extending class to remove token from database
	 *
     * @return mixed
     */
    public function deleteToken()
    {
        delete_option($this->tokenLabel);
    }

    /**
     * Clear api key from datastore
     */
	public function deleteApiKey()
    {
        delete_option($this->apiKeyLabel); 
    }

	/**
	 * Login using POST superglobal
	 *
	 * @return boolean|mixed 
	 */
    public function login()
	{
		$response = false;
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = strtolower($_POST['username']);
			// wordpress automaticlly escapes ' so if the password has one login fails
			$password = stripslashes($_POST['password']); 
            $response = $this->getUser($username, $password)->parseResponse();
            $this->apiKey = $this->createApiKey(); 
        }

		return $response;
    }

    public function redirectOnLogin()
    {
        $response = $this->login();
        if ($response == 'success') {
            $this->storeToken();
            $this->storeApiKey();
            $this->setLoggedInCookie();

            $eventArray = [
              'email_address' => $_POST['username']
            ];

            (new LeadpagesSignInEvent())->storeEvent($eventArray);
            wp_redirect(admin_url('edit.php?post_type=leadpages_post'));
            exit;
        }

		// redirect with error code to display error message
		$response = json_decode($response, true);
		$code = sanitize_text_field($response['code']);
		wp_redirect(admin_url('admin.php?page=Leadpages&code='.$code.''));
		exit;
    }


    public function loginHook()
    {
        add_action('admin_post_leadpages_login_form', [$this, 'redirectOnLogin']);
    }

    /**
     * method to check if token is empty
     *
     * @return mixed
     */
    public function checkIfTokenIsEmpty()
    {
        $this->getToken();

        if (empty($this->token)) {
            return [
              'code'     => '500',
              'response' => 'token not set in database',
              'error'    => true
            ];
        }

		return [
			'code'     => '200',
			'response' => '',
			'error'    => false
		];
    }

    /**
     * Check if user is logged in
	 *
     * @return bool
     */
    public function isLoggedIn()
    {
        // if cookie is set and is true don't bother with http call
        // if($this->getLoggedInCookie()) return true;

        $isTokenEmpty = $this->checkIfTokenIsEmpty();
		// verify that token in database was not empty, 
		// and ensure that token gets a response from Leadpages
        if ($isTokenEmpty['error']) {
            return false;
        }
        // set cookie if they are logged in
        //$this->setLoggedInCookie();

        return true;
    }

    /**
     * Create Api key if it does not exist
     */
    public function checkAndCreateApiKey()
    {
        // make sure token is set
        $this->getToken();
        $apiKey = $this->getApiKey();
        if (!$apiKey) {
            $this->apiKey = $this->createApiKey();
            $this->storeApiKey();
        }
    }

    /**
     * Set logged in cookie if it is not already set
     */
    public function setLoggedInCookie()
    {
        if (!$this->getLoggedInCookie()) {
            setcookie('LeadpagesWordPress', true, time() + 3600);
        }
    }

    /**
     * Attempt to fetch login cookie for Leadpages
	 *
     * @return bool
     */
    public function getLoggedInCookie()
    {
        return (isset($_COOKIE['LeadpagesWordPress']) && $_COOKIE['LeadpagesWordPress'] == true);
    }
}
