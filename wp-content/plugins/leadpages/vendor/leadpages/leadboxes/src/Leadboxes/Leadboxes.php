<?php

namespace Leadpages\Leadboxes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Leadpages\Auth\LeadpagesLogin;

class Leadboxes
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

    /**
     * @property string leadboxesUrl
     */
    public $leadboxesUrl;
    public $certFile;

    public function __construct(Client $client, LeadpagesLogin $login)
    {

        $this->client = $client;
        $this->login = $login;
        $this->login->getApiKey();
        $this->leadboxesUrl = "https://my.leadpages.net/leadbox/v1/leadboxes";
        $this->certFile = ABSPATH . WPINC . '/certificates/ca-bundle.crt';

    }


    public function getAllLeadboxes()
    {
        try {
            $response = $this->client->get($this->leadboxesUrl,
                [
                    'headers' => ['Authorization' => 'Bearer ' . $this->login->apiKey],
                    'verify' => $this->certFile,
                ]);
            $response = [
                'code' => '200',
                'response' => $response->getBody()->getContents()
            ];
        } catch (ClientException $e) {
            $response = $this->parseException($e);
        } catch (ConnectException $e) {
            $message = 'Can not connect to Leadpages Server:';
            $response = $this->parseException($e, $message);
        }

        return $response;
    }


    public function getSingleLeadboxEmbedCode($id, $type)
    {
        try {
            $url = $this->buildSingleLeadboxUrl($id, $type);
            $response = $this->client->get($url,
                [
                    'headers' => ['Authorization' => 'Bearer '. $this->login->apiKey],
                    'verify' => $this->certFile,
                ]);

            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            $response = [
                'code' => '200',
                'response' => json_encode(['embed_code' => $body['_items']['publish_settings']['embed_code']])
            ];
        } catch (ClientException $e) {
            $response = $this->parseException($e);
        } catch (ServerException $e) {
            $response = $this->parseException($e);
        } catch (ConnectException $e) {
            $message = 'Can not connect to Leadpages Server:';
            $response = $this->parseException($e, $message);
        }

        return $response;
    }

    public function buildSingleLeadboxUrl($id, $type)
    {
        $queryParams = http_build_query(['popup_type' => $type]);
        $url = $this->leadboxesUrl . '/' . $id . '?' . $queryParams;
        return $url;
    }


    /**
     * @param $e
     *
     * @param string $message
     *
     * @return array
     */
    public function parseException($e, $message = '')
    {
        $response = [
            'code' => $e->getCode(),
            'response' => $message . ' ' . $e->getMessage(),
            'error' => (bool)true
        ];
        return $response;
    }

}
