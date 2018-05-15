<?php

use Leadpages\Auth\LeadpagesLogin;


class LeadpagesLoginTestSuccess extends PHPUnit_Framework_TestCase
{
    public $stub;
    public $username;
    public $password;
    public $testToken;

    public function setUp()
    {

        $this->username  = getenv('username');
        $this->password  = getenv('password');
        $this->testToken;

        $this->stub = $this->getMockForAbstractClass(LeadpagesLogin::class, [new GuzzleHttp\Client()]);

        //set to true to simulate getting back a true response from api call

        $this->stub->expects($this->any())
                   ->method('storeToken')
                   ->will($this->returnValue(true));

        $this->stub->expects($this->any())
                   ->method('getToken')
                   ->will($this->returnValue(true));

        $this->stub->expects($this->any())
                   ->method('deleteToken')
                   ->will($this->returnValue(true));

    }

    /**
     * test getting actual user and parsing the response. This should set $this->token to
     * Leadpages security token
     *
     * @group login-success
     */
    public function test_get_user()
    {
        //call to actual service, choose not to mock this out as I want to
        //make sure the service itself is returning the correct data

        //get a response from Leadpages

        $this->stub->getUser($this->username, $this->password)->parseResponse();
        $this->testToken = $this->stub->token;
        //if all succeeded the token should not be empty and should be a string
        $this->assertEquals($this->testToken, $this->stub->token);
        $this->assertNotEmpty($this->stub->token);
        $this->assertInternalType('string', $this->stub->token);

    }

    /**
     * @group login-success
     */

    public function test_current_user_token_is_good()
    {
        //get a user token
        $this->stub->getUser($this->username, $this->password)->parseResponse();

        $isTokenGood = $this->stub->checkCurrentUserToken();
        $this->assertTrue($isTokenGood);
    }

    public function test_set_leadpages_response()
    {
        $this->stub->setLeadpagesResponse('this is a test response');
        $this->assertEquals('this is a test response', $this->stub->response);
    }

    /**
     * User testing should have a Leadpages and Center Account
     */
    public function test_check_users_current_session()
    {
        //get a user token
        $this->stub->getUser($this->username, $this->password)->parseResponse();

        $response = $this->stub->checkCurrentUserSession();

        //account has leadpages account
        $this->assertArrayHasKey('LEADPAGES_20160216', $response['profiles']);
        //account has center account
        $this->assertArrayHasKey('CENTER_20160216', $response['profiles']);
    }

    public function test_generate_new_token()
    {
        //get a user token
        $this->stub->getUser($this->username, $this->password)->parseResponse();

        $response = $this->stub->refreshUserToken();
        $newToken = $response['securityToken'];
        $this->assertNotEmpty($newToken);
        $this->assertNotEquals($this->testToken, $newToken);
    }

}

class LeadpagesLoginTestFail extends PHPUnit_Framework_TestCase
{
    public $stub;
    public $username;
    public $password;

    public function setUp()
    {

        $this->username = 'test@unitest.net';
        $this->password = 'example';

        $this->stub = $this->getMockForAbstractClass(LeadpagesLogin::class, [new GuzzleHttp\Client()]);

        $this->stub->expects($this->any())
                   ->method('storeToken')
                   ->will($this->returnValue(true));

        $this->stub->expects($this->any())
                   ->method('getToken')
                   ->will($this->returnValue('123abc'));

        $this->stub->expects($this->any())
                   ->method('deleteToken')
                   ->will($this->returnValue(true));
    }

    /**
     * test getting actual user and parsing the repsonse. This should set $this->token to
     * Leadpages security token
     *
     * @group login-fail
     */
    public function test_get_user_fail()
    {
        //call to actual service, chose not to mock this out as I want to
        //make sure the service itself is returning the correct data

        //get a response from Leadpages
        $this->stub->getUser($this->username, $this->password)->parseResponse(true);


        $responseArray = json_decode($this->stub->getLeadpagesResponse(), true);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertEquals('401', $responseArray['code']);

    }

    /**
     * @group login-fail
     */

    public function test_current_user_is_logged_in_fail()
    {
        $this->stub->token = 'badtoken';
        $isTokenGood = $this->stub->checkCurrentUserToken();
        $this->assertFalse($isTokenGood);
    }


}