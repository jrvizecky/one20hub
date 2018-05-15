<?php

use GuzzleHttp\Client;
use ICanBoogie\Inflector;
use Leadpages\Pages\LeadpagesPages;
use LeadpagesMetrics\LeadpagesErrorEvent;
use LeadpagesWP\Front\ShortCodes\LeadboxShortCodes;
use LeadpagesWP\models\LeadboxesModel;
use LeadpagesWP\Lib\ApiResponseHandler;
use LeadpagesWP\Bootstrap\AdminBootstrap;
use LeadpagesWP\Bootstrap\FrontBootstrap;
use LeadpagesWP\Helpers\PasswordProtected;
use LeadpagesWP\Admin\TinyMCE\LeadboxTinyMCE;
use LeadpagesWP\ServiceProviders\LeadboxesApi;
use LeadpagesWP\ServiceProviders\SplitTestApi;
use TheLoop\ServiceContainer\ServiceContainer;
use LeadpagesWP\models\LeadPagesPostTypeModel;
use LeadpagesWP\Front\Controllers\LeadboxController;
use LeadpagesWP\Front\Controllers\LeadpageController;
use LeadpagesWP\Front\Controllers\NotFoundController;
use LeadpagesWP\Front\Controllers\WelcomeGateController;
use LeadpagesWP\Admin\CustomPostTypes\LeadpagesPostType;
use LeadpagesWP\ServiceProviders\WordPressLeadpagesAuth;

/*
|--------------------------------------------------------------------------
| Instantiate Service Container
|--------------------------------------------------------------------------
|
|
*/

$leadpagesContainer = new ServiceContainer();
$leadpagesApp       = $leadpagesContainer->getContainer();

/**
 * register config into container
 */
$leadpagesApp['config'] = $leadpagesConfig;

/*
|--------------------------------------------------------------------------
| Base Providers
|--------------------------------------------------------------------------
|
| Leadpages Base Service providers
|
*/

/**
 * HttpClient
 *
 * @param $leadpagesApp
 *
 * @return \TheLoop\Providers\WordPressHttpClient
 */

$leadpagesApp['httpClient'] = function ($leadpagesApp) {
    return new Client();
};

$leadpagesApp['adminBootstrap'] = function ($leadpagesApp) {
    return new AdminBootstrap($leadpagesApp['leadpagesLogin'], $leadpagesApp['lpPostTypeModel'],
      $leadpagesApp['leadboxesApi'], $leadpagesApp['leadboxesModel'], $leadpagesApp['leadboxTinyMce']);
};

$leadpagesApp['frontBootstrap'] = function ($leadpagesApp) {
    return new FrontBootstrap($leadpagesApp['leadpagesLogin'], $leadpagesApp['leadpageController'],
      $leadpagesApp['pagesApi'], $leadpagesApp['leadboxController'], $leadpagesApp['leadboxShortCode']);
};


$leadpagesApp['lpPostType'] = function ($leadpagesApp) {
    return new LeadpagesPostType();
};


$leadpagesApp['lpPostTypeModel'] = function ($leadpagesApp) {
    return new LeadPagesPostTypeModel($leadpagesApp['pagesApi'], $leadpagesApp['lpPostType']);
};

$leadpagesApp['leadboxesModel'] = function ($leadpagesApp) {
    return new LeadboxesModel();
};


$leadpagesApp['passwordProtected'] = function ($leadpagesApp) {
    global $wpdb;
    return new PasswordProtected($wpdb);
};


$leadpagesApp['leadpageController'] = function ($leadpagesApp) {
    return new LeadpageController($leadpagesApp['notfound'], $leadpagesApp['WelcomeGateController'],
      $leadpagesApp['lpPostTypeModel'], $leadpagesApp['pagesApi'], $leadpagesApp['passwordProtected']);
};
$leadpagesApp['notfound']           = function ($leadpagesApp) {
    return new NotFoundController($leadpagesApp['lpPostTypeModel'], $leadpagesApp['pagesApi']);
};

$leadpagesApp['WelcomeGateController'] = function ($leadpagesApp) {
    return new WelcomeGateController();
};

$leadpagesApp['leadboxController'] = function ($leadpagesApp) {
    return new LeadboxController($leadpagesApp['leadboxesApi'], $leadpagesApp['leadboxesModel']);
};

$leadpagesApp['leadboxTinyMce'] = function($leadpagesApp) {
  return new LeadboxTinyMCE();
};

$leadpagesApp['inflector'] = Inflector::get('en');

$leadpagesApp['leadboxShortCode'] = function($leadpagesApp){
  return new LeadboxShortCodes();
};

$leadpagesApp['errorEventsHandler'] = function(){
    return new LeadpagesErrorEvent();
};
/*
|--------------------------------------------------------------------------
| API Providers
|--------------------------------------------------------------------------
|
| Leadpages API Service providers
|
*/


/**
 * response object for handling leadpages api calls
 *
 * @param $leadpagesApp
 *
 * @return \LeadpagesWP\Lib\ApiResponseHandler
 */
$leadpagesApp['apiResponseHandler'] = function ($leadpagesApp) {
    return new ApiResponseHandler();
};

/**
 * Leadpages login api object
 *
 * @param $leadpagesApp
 *
 * @return \LeadpagesWP\ServiceProviders\LeadpagesLogin
 */
$leadpagesApp['leadpagesLogin'] = function ($leadpagesApp) {
    return new WordPressLeadpagesAuth($leadpagesApp['httpClient']);
};

/**
 * Leadpages pages api object
 *
 * @param $leadpagesApp
 *
 * @return Leadpages\Pages\
 */
$leadpagesApp['pagesApi'] = function ($leadpagesApp) {
    return new LeadpagesPages($leadpagesApp['httpClient'], $leadpagesApp['leadpagesLogin']);
};
/**
 * Leadpages login api object
 *
 * @param $leadpagesApp
 *
 * @return \LeadpagesWP\ServiceProviders\LeadboxesApi
 */
$leadpagesApp['leadboxesApi'] = function ($leadpagesApp) {
    return new LeadboxesApi($leadpagesApp['httpClient'], $leadpagesApp['leadpagesLogin']);
};

$leadpagesApp['splitTestApi'] = function ($leadpagesApp){
    return new SplitTestApi($leadpagesApp['leadpagesLogin']);
};



