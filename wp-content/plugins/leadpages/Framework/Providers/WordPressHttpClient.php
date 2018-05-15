<?php


namespace TheLoop\Providers;

use TheLoop\Contracts\HttpClient;

class WordPressHttpClient implements HttpClient
{

    public $url;
    public $args =array();

    public function get($url)
    {
        $response = wp_remote_get($url, $this->args);
        return $response;
    }

    public function post($url)
    {
        $response = wp_remote_post($url, $this->args);
        return $response;
    }

    public function patch($url)
    {
        // TODO: Implement patch() method.
    }

    public function delete($url)
    {
        // TODO: Implement delete() method.
    }


    /**
     * @param array $args
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }

    public function getArgs(){
        return $this->args;
    }



}