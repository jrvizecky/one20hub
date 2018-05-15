<?php

namespace Leadpages\Auth\Contracts;


/**
 * Abstract class to contract the names of functions to store and retrieve the Leadpages Token form the data store
 * Class LeadpagesToken
 * @package Leadpages\Auth\Interfaces
 */

interface LeadpagesToken
{

    /**
     * method to implement to store token in database
     *
     * @return mixed
     */
    public function storeToken();

    /**
     * method to implement to get token from datastore
     * should return token not set property of $this->token
     * @return mixed
     */
    public function getToken();

    /**
     * method to implement to remove token from database
     * @return mixed
     */
    public function deleteToken();

    /**
     * method to check if token is empty
     *
     * @return mixed
     */
    public function checkIfTokenIsEmpty();
}
