<?php


namespace LeadpagesMetrics\Traits;

use GuzzleHttp\Client;

trait ActiveInstallUpdate
{

    public static function incrementActiveInstalls(Client $client, $eventsUrl)
    {

        $url = $eventsUrl."active_installs/increase/";

        $headers = [
          'Content-Type' => 'application/json'
        ];

        $body = json_encode([
            "plugin_name" => "leadpages_connector",
            "domain" => $_SERVER['HTTP_HOST']
        ]);

        try {
            $response = $client->post($url, [
              'headers' => $headers,
              'body' => $body
            ]);

        } catch (\Exception $e) {
            $response = $e->getMessage();
        }

        update_option('lp-active-install', $response);

    }

    public static function decreaseActiveInstalls(Client $client, $eventsUrl)
    {

        $url = $eventsUrl."active_installs/decrease/";

        $headers = [
          'Content-Type' => 'application/json'
        ];

        $body = json_encode([
          "plugin_name" => "leadpages_connector",
          "domain" => $_SERVER['HTTP_HOST']
        ]);

        try {
            $response = $client->post($url, [
              'headers' => $headers,
              'body' => $body
            ]);

        } catch (\Exception $e) {
            $response = $e->getMessage();
        }

        update_option('lp-active-install', $response);

    }

}