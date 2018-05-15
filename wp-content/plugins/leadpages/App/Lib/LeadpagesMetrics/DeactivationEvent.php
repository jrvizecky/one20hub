<?php


namespace LeadpagesMetrics;

use GuzzleHttp\Client;
use LeadpagesMetrics\Traits\ActiveInstallUpdate;

class DeactivationEvent extends Events
{
    
    protected $event = 'deactivated';

    public function buildUrl()
    {
        return $this->eventUrl . 'activation/';
    }

    public function storeEvent($body = array())
    {
        $this->buildClient();
        $url     = $this->buildUrl();
        $headers = [
          'Content-Type' => 'application/json'
        ];

        if(!$email = WordPressEventEmail::getEventEmail()){
            $email = '';
        }
        $body = [
          "email_address" => $email
        ];
        $body = $this->buildBodyJson($body);

        try {
            $response = $this->client->post($url, [
              'headers' => $headers,
              'body'    => $body
            ]);

            ActiveInstallUpdate::decreaseActiveInstalls($this->client, $this->eventUrl);

        } catch (\Exception $e) {
            $response = $e->getMessage();
        }

        update_option('lp-response', $response);
    }


}