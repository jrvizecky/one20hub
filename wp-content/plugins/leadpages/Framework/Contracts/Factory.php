<?php


namespace TheLoop\Contracts;

/**
 * Interface Factory
 * @package TheLoop\Contracts
 *
 * @description interface for defining Factories.
 * Use of IOC container would be preferable
 */

interface Factory
{

    public static function create($object);

}