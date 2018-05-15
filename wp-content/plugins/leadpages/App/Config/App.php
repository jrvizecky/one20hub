<?php

$leadpagesConfig = array();

/*
|--------------------------------------------------------------------------
| Base Path
|--------------------------------------------------------------------------
|
| Base path setup for a wordpress plugin
|
*/
$leadpagesConfig['basePath']  = plugin_dir_path(dirname(dirname(__FILE__)));
$leadpagesConfig['pluginUrl'] = plugin_dir_url((dirname(__FILE__)));

/*
|--------------------------------------------------------------------------
| API URLS
|--------------------------------------------------------------------------
|
| URL's for Leadpages API
|
*/

$leadpagesConfig['api']['sessions']['new']     = 'https://api.center.io/account/v1/sessions/';
$leadpagesConfig['api']['sessions']['current'] = 'https://api.center.io/account/v1/sessions/current';
$leadpagesConfig['api']['pages']               = 'https://my.leadpages.net/page/v1/pages';
$leadpagesConfig['api']['leadboxes']           = 'https://my.leadpages.net/leadbox/v1/leadboxes';

/*
|--------------------------------------------------------------------------
| Application Config
|--------------------------------------------------------------------------
|
| Config values specific for application
|
*/
$leadpagesConfig['update_url']   = "http://leadbrite.appspot.com";
$leadpagesConfig['admin_assets'] = $leadpagesConfig['pluginUrl'] . 'assets';
$leadpagesConfig['admin_images'] = $leadpagesConfig['admin_assets'] . '/images';
$leadpagesConfig['admin_css']    = $leadpagesConfig['admin_assets'] . '/css/';
$leadpagesConfig['admin_js']     = $leadpagesConfig['admin_assets'] . '/js/';

//Required version of php for plugin
$leadpagesConfig['php_version'] = 5.4;

