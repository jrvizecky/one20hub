<?php

namespace Leadpages\Pages;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

use Leadpages\Auth\LeadpagesLogin;

class LeadpagesPages
{

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var \Leadpages\Auth\LeadpagesLogin
     */
    private $login;

    /**
     * @var \Leadpages\Auth\LeadpagesLogin
     */
    public $response;

    public $certFile;


    public function __construct(Client $client, LeadpagesLogin $login)
    {
        $this->client = $client;
        $this->login = $login;
        $this->PagesUrl = "https://my.leadpages.net/page/v1/pages";
        $this->certFile = ABSPATH . WPINC . '/certificates/ca-bundle.crt';
    }

    /**
     * Base function get call get users pages
     *
     * @param bool $cursor default false
     *
     * @return array
     */
    public function getPages($cursor = false)
    {
        $queryArray = ['pageSize' => 200];
        if ($cursor) {
            $queryArray['cursor'] = $cursor;
        }

        try {
            $response = $this->client->get(
                $this->PagesUrl, [
                    'headers' => ['Authorization' => 'Bearer '. $this->login->apiKey],
                    'verify' => $this->certFile,
                    'query' => $queryArray
            ]);

            $response = [
                'code' => '200',
                'response' => $response->getBody(),
                'error' => false
            ];

        } catch (ClientException $e) {
            $response = $this->parseException($e);

        } catch (ServerException $e) {
            $response = $this->parseException($e);

        } catch (ConnectException $e) {
            $message = 'Can not connect to Leadpages Server:';
            $response = $this->parseException($e, $message);

        } catch (RequestException $e) {
            $response = $this->parseException($e);
        }

        return $response;
    }

    /**
     * Recursive function to get all of a users pages
     *
     * @param array $returnResponse
     * @param bool  $cursor
     *
     * @return mixed
     */
    public function getAllUserPages($returnResponse = array(), $cursor = false)
    {

        if (empty($this->login->apiKey)) {
            $this->login->getApiKey();
        }

        //get & parse response
        $response = $this->getPages($cursor);
        $response = json_decode($response['response'], true);

        if (empty($returnResponse) && empty($response['_items'])) {
            echo '<p><strong>You appear to have no Leadpages created yet.</strong></p>
                <p> Please login to <a href="https://my.leadpages.net" target="_blank">Leadpages</a>
                and create a Leadpage to continue.</p>';
            die();
        }

        //if we have more pages add these pages to returnResponse and pass it back into this method
        //to run again
        if ($response['_meta']['hasMore'] == 'true') {
            $returnResponse[] = $response['_items'];
            return $this->getAllUserPages($returnResponse, $response['_meta']['nextCursor']);
        }

        //once we run out of hasMore pages return the response with all pages returned
        if (!$response['_meta']['hasMore']) {
            /**
             * add last result to return response
             */
            $returnResponse[] = $response['_items'];

            /**
             * this maybe a bit hacky but for recursive and compatibility with other functions
             * needed all items to be under one array under _items array
             */
            if (isset($returnResponse) && count($returnResponse) > 0) {
                $pages = array(
                    '_items' => array()
                );
                foreach ($returnResponse as $subarray) {
                    $pages['_items'] = array_merge($pages['_items'], $subarray);
                }

                //strip out unpublished pages
                //sort pages asc by name
                return $this->sortPages($this->stripB3NonPublished($pages));
            }
        }
    }


    /**
     * Remove non published B3 pages
     *
     * @param mixed $pages
     *
     * @return mixed
     */
    public function stripB3NonPublished($pages)
    {
        foreach ($pages['_items'] as $index => $page) {
            if ($page['isBuilderThreePage'] && !$page['isBuilderThreePublished']) {
                unset($pages['_items'][$index]);
            }
        }

        return $pages;
    }

    /**
     * sort pages in alphabetical user
     *
     * @param mixed $pages
     *
     * @return mixed
     */
    public function sortPages($pages)
    {
        usort($pages['_items'], function ($a, $b) {
            //need to convert them to lowercase strings for equal comparison
            return strcmp(strtolower($a["name"]), strtolower($b["name"]));
        });

        return $pages;
    }

    /**
     * Get the url to download the page url from
     *
     * @param string $pageId
     *
     * @return array
     */
    public function getSinglePageDownloadUrl($pageId)
    {
        try {
            $response = $this->client->get($this->PagesUrl . '/' . $pageId, [
                'headers' => ['Authorization' => 'Bearer '. $this->login->apiKey],
                'verify' => $this->certFile,
            ]);

            $body = json_decode($response->getBody(), true);
            $url = $body['_meta']['publishUrl'];
            $responseText = ['url' => $url];

            $response = [
                'code' => '200',
                'response' => json_encode($responseText),
                'error' => false
            ];

        } catch (ClientException $e) {
            $httpResponse = $e->getResponse();
            // 404 means their Leadpage in their account probably got deleted
            if ($httpResponse->getStatusCode() == 404) {
                $response = [
                    'code' => $httpResponse->getStatusCode(),
                    'response' => "
                    Your Leadpage could not be found! Please make sure it is published in your Leadpages Account <br />
                    <br />
                    Support Info:<br />
                    <strong>Page id:</strong> {$pageId} <br />
                    <strong>Page url:</strong> {$this->PagesUrl}/{$pageId}",
                    'error' => true
                ];

            } else {
                $message = 'Something went wrong, please contact Leadpages support.';
                $response = $this->parseException($e);
            }

        } catch (ServerException $e) {
            $response = $this->parseException($e);

        } catch (ConnectException $e) {
            $message = 'Can not connect to Leadpages Server:';
            $response = $this->parseException($e, $message);

        } catch (RequestException $e) {
            $response = $this->parseException($e);
        }

        return $response;
    }

    /**
     * get url for page, then use a get request to get the html for the page
     *
     * @todo refactor this
     * @todo at sometime this should be replaced with a single call to get the html this requires two calls
     *
     * @param string $pageId  Leadpages Page id not wordpress post_id
     * @param bool   $isRetry true downgrades to http
     *
     * @return mixed
     */
    public function downloadPageHtml($pageId, $isRetry = false)
    {

        if (is_null($this->login->apiKey)) {
            $this->login->apiKey = $this->login->getApiKey();
        }

        $response = $this->getSinglePageDownloadUrl($pageId);

        if ($response['error']) {
            return $response;
        }

        $responseArray = json_decode($response['response'], true);
        $url = $responseArray['url'];

        if ($isRetry) {
            $url = str_replace('https:', 'http:', $url);
        }

        $options = [
            'verify' => !$isRetry ? $this->certFile : false,
        ];

        foreach ($_COOKIE as $index => $value) {
            if (strpos($index, 'splitTestV2URI') !== false) {
                $options['cookies'] = [$index => $value];
            }
        }

        try {
            $html = $this->client->get($url, $options);
            $response = [
                'code' => 200,
                'response' => $html->getBody()->getContents(),
            ];

            if (count($this->getPageSplitTestCookie($html)) > 0) {
                $response['splitTestCookie'] = $this->getPageSplitTestCookie($html);
            }

        } catch (ClientException $e) {
            $response = $this->parseException($e);

        } catch (RequestException $e) {
            $response = $this->parseException($e);
            if (!$isRetry) {
                $response = $this->downloadPageHtml($pageId, true);
            }

        } catch (ServerException $e) {
            $response = $this->parseException($e);

        } catch (ConnectException $e) {
            $message = 'Can not connect to Leadpages Server:';
            $response = $this->parseException($e, $message);

        }

        return $response;
    }

    /**
     * Get cookies from response and find the splittest cookie
     * return an array containing that cookie
     *
     * @param mixed $response
     *
     * @return array
     */
    public function getPageSplitTestCookie($response)
    {
        $cookieArray = [];
        $cookies = SetCookie::fromString($response->getHeader('Set-Cookie'))->toArray();

        // If multiple cookies, find the cookie we are looking for.
        if (isset($cookies[0])) {
            foreach ($cookies as $cookie) {
                if (strpos($cookie['Name'], 'splitTest')) {
                    $cookieArray = $cookie;
                }
            }

        }

        // Look at base cookies array as it is not multidimensional
        if (strpos($cookies['Name'], 'splitTest') !== false) {
            $cookieArray = $cookies;
        }

        return $cookieArray;
    }

    /**
     * @param string $pageId
     *
     * @return array
     */
    public function isLeadpageSplittested($pageId)
    {
        if (is_null($this->login->apiKey)) {
            $this->login->apiKey = $this->login->getApiKey();
        }

        try {
            $response = $this->client->get($this->PagesUrl . '/' . $pageId, [
                'headers' => ['Authorization' => 'Bearer '. $this->login->apiKey],
                'verify' => $this->certFile,
            ]);

            $body = json_decode($response->getBody(), true);
            $isSplitTested = $body['isSplit'];

            $response = [
                'code' => '200',
                'response' => $isSplitTested,
                'error' => false
            ];

        } catch (ClientException $e) {
            $response = $this->parseException($e);

        } catch (ServerException $e) {
            $response = $this->parseException($e);
        }

        return $response;
    }

    /**
     *
     * @param Exception $e
     * @param string    $message
     *
     * @return array
     */
    public function parseException($e, $message = '')
    {
        return [
            'code' => $e->getCode(),
            'response' => $message . ' ' . $e->getMessage(),
            'error' => true
        ];
    }
}
