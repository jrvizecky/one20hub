<?php


namespace TheLoop\Contracts;

/**
 * Interface RegisterDependencies
 * @package TheLoop\Contracts
 *
 * @description interface for defining Factory Dependencies.
 * Use of IOC container would be preferable
 */

interface RegisterDependencies
{

    public function register($Dependencies = array());

}