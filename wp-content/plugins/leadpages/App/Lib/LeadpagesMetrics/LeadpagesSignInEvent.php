<?php

namespace LeadpagesMetrics;


class LeadpagesSignInEvent extends Events
{

    protected $event = 'Sign-In';


    public function buildUrl()
    {
        return $this->eventUrl . 'activation/';
    }

    public function storeEvent($args = array())
    {
        $this->buildClient();
        $url     = $this->buildUrl();
        $headers = [
          'Content-Type' => 'application/json'
        ];

        WordPressEventEmail::storeEventEmail($args['email_address']);

        $response = $this->sendEvent($url, $headers, $args);

        update_option('lp-response', $response);
    }


}