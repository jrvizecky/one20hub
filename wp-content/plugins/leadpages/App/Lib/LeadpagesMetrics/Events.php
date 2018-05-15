<?php


namespace LeadpagesMetrics;

use GuzzleHttp\Client;
use LeadpagesMetrics\Traits\EventsTrackingId;
use LeadpagesMetrics\Traits\WordPressPlatformVersion;


abstract class Events
{

    use EventsTrackingId;
    use WordPressPlatformVersion;

    protected $client;
    protected $baseUrl  = "https://leadbrite.appspot.com/";
    protected $eventUrl = "https://leadbrite.appspot.com/events/";
    protected $pluginName = 'leadpages_connector';
    protected $platform = "WordPress";
    protected $phpVersion = PHP_VERSION;
    protected $event;

    public abstract function buildUrl();

    public function storeEvent($body = array())
    {

    }

    protected function buildClient()
    {
        $this->client = new Client();
    }

    protected function getDomain()
    {
        return $_SERVER['HTTP_HOST'];
    }

    protected function baseEvent()
    {
        global $leadpages_connector_plugin_version;
        return [
          'event'              => $this->event, //comes form class being extended
          'events_tracking_id' => $this->getEventsTrackingId(),
          'plugin_name'        => $this->pluginName,
          'php_version'        => $this->phpVersion,
          'platform_version'   => $this->getPlatformVersion(),
          'domain'             => $this->getDomain(),
          'platform'           => 'WordPress',
          'event_date'         => $this->getDate(),
          'event_time'         => $this->getTime(),
          'plugin_version'     => $leadpages_connector_plugin_version
          ];
    }

    protected function getDate()
    {
        $date = new \DateTime(null, new \DateTimeZone('America/Chicago'));
        return $date->format('m/d/Y');
    }

    protected function getTime()
    {
        $date = new \DateTime(null, new \DateTimeZone('America/Chicago'));
        return $date->format('h:i A') . " CST";
    }

    protected function buildBodyJson($bodyArray = [])
    {
        $baseArray = $this->baseEvent();
        if(!empty($bodyArray)) {
            $body = array_merge($baseArray, $bodyArray);
        }else{
            $body = $baseArray;
        }
        return json_encode($body);
    }

    protected function sendEvent($url, $headers, $body)
    {
        $body = $this->buildBodyJson($body);

        try {
            $response = $this->client->post($url, [
              'headers' => $headers,
              'body'    => $body
            ]);

        } catch (\Exception $e) {
            $response = $e->getMessage();
        }

        return $response;
    }


}