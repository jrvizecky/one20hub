<?php

namespace TheLoop\ServiceContainer;

use Pimple\Container;

class ServiceContainer
{
    public $ioc;

    public function __construct()
    {
        $this->ioc = new Container();

    }

    public function getContainer()
    {
        return $this->ioc;
    }
}

