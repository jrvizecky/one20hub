<?php


namespace TheLoop\Contracts;


interface MetaBox
{

    public function defineMetaBox();

    public function callBack($object, $box);

    public function registerMetaBox();


}