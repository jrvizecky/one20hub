<?php


use GuzzleHttp\Client;
use Leadpages\Pages\LeadpagesPages;

class TestLeadpagesPagesSuccess extends PHPUnit_Framework_TestCase
{

    public $pages;
    public $login;
    public $client;
    public $username;
    public $password;

    public function setUp(){


        $this->client = new Client();
        $this->login  = new fakeLogin($this->client);
        $this->pages  = new LeadpagesPages($this->client, $this->login);
        $this->pageId = getenv('pageId');
        $this->username  = getenv('username');
        $this->password  = getenv('password');

    }

    public function testGetAllPages()
    {   $startTime = microtime(true);
        $this->login->getUser($this->username, $this->password)->parseResponse();

        $response = $this->pages->getAllUserPages();
        $finishTime = microtime(true);
        $totalTime = $finishTime - $startTime;
        $this->assertNotEmpty($response['_items']);
        //a test for time the api call takes 10 seconds is probably way to high
        //$this->assertLessThan(10, $totalTime);
    }

    public function testGetSinglePageDownloadUrl()
    {
        $this->login->getUser($this->username, $this->password)->parseResponse();

        $response = $this->pages->getSinglePageDownloadUrl($this->pageId);
        $responseArray = json_decode($response['response'], true);

        $parsedUrl = parse_url($responseArray['url']);

        $this->assertEquals('https', $parsedUrl['scheme']);
        $this->assertGreaterThan(0, strpos($parsedUrl['host'], 'leadpages.co'));

    }

    public function testDownloadPageHtml()
    {
        $this->login->getUser($this->username, $this->password)->parseResponse();

        $response = $this->pages->downloadPageHtml($this->pageId);
        $this->assertEquals('200', $response['code']);
        $this->assertContains('This beautiful and lightning fast landing page was proudly created with Leadpages', $response['response']);
    }

    /**
     * @test
     */
    public function is_a_leadpages_splittested()
    {
        $this->login->getUser($this->username, $this->password)->parseResponse();

        $response = $this->pages->isLeadpageSplittested($this->pageId);
        $this->assertFalse($response['response']);
    }

    /**
     * @test
     */
    public function is_a_leadpages_splittested_fail()
    {
        $this->login->getUser($this->username, $this->password)->parseResponse();

        $response = $this->pages->isLeadpageSplittested('abc123');
        $this->assertEquals('500', $response['code']);    }

    public function test_download_page_html_fail_bad_id()
    {
        $this->login->getUser($this->username, $this->password)->parseResponse();

        $response = $this->pages->downloadPageHtml('badid');
        $this->assertEquals('500', $response['code']);
        $this->assertTrue($response['error']);
    }


    public function test_download_page_html_fail_numeric_id()
    {
        //this would happen if a page had been deleted from the Leadpages Admin
        $this->login->getUser($this->username, $this->password)->parseResponse();

        $response = $this->pages->downloadPageHtml('5683582030839808');
        $this->assertEquals('404', $response['code']);
        $this->assertTrue($response['error']);
    }
    public function test_download_page_html_fail_no_token()
    {
        $this->login->token = '';
        $response = $this->pages->downloadPageHtml('badid');
        $this->assertEquals('401', $response['code']);
        $this->assertTrue($response['error']);
    }

}