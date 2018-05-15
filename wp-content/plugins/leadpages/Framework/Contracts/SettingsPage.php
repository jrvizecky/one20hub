<?php


namespace TheLoop\Contracts;


/**
 * Interface SettingsPage
 * @package TheLoop\Contracts
 */
interface SettingsPage
{

    /**
     * define page using add_menu_page
     * @return mixed
     */
    public function definePage();

    /**
     * Define HTML for Settings Page
     * Need to implement a view??
     * @return mixed
     */
    public function displayCallback();

    /**
     * add action admin_menu calling definePage ass callback
     * @return mixed
     */
    public function registerPage();

}