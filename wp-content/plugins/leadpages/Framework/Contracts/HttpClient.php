<?php

namespace TheLoop\Contracts;


interface HttpClient
{
    public function get($url);
    public function post($url);
    public function patch($url);
    public function delete($url);

}

