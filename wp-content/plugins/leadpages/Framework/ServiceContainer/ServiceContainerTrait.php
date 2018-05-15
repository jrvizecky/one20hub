<?php


namespace TheLoop\ServiceContainer;

/**
 *
 * hack of a way to not have to include global $ioc in every class the IOC
 * container is needed
 *
 * Class ServiceContainerTrait
 * @package TheLoop\ServiceContainer
 */
trait ServiceContainerTrait
{

    public function getContainer()
    {
        global $app;
        return $app;
    }

}