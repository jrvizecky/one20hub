<?php

namespace LeadpagesWP\Front\Controllers;

use Leadpages\Pages\LeadpagesPages;
use LeadpagesWP\Helpers\LeadpageType;
use LeadpagesWP\models\LeadPagesPostTypeModel;

class NotFoundController
{

    protected $nfPageId;
    protected $nfPageUrl;
    /**
     * @var
     */
    private $postTypeModel;
    /**
     * @var \Leadpages\Pages\LeadpagesPages
     */
    private $pagesApi;

    public function __construct(LeadPagesPostTypeModel $postTypeModel, LeadpagesPages $pagesApi)
    {
        $this->postTypeModel = $postTypeModel;
        $this->pagesApi = $pagesApi;
    }

    protected function nfPageExists()
    {
        $this->nfPageId = LeadpageType::get_404_lead_page();
        $postExists = LeadpageController::checkLeadpagePostExists($this->nfPageId);
        //if the post does not exist remove the option from the db
        if(!$postExists){
            LeadpageController::deleteOrphanPost('leadpages_404_page_id');
            return false;
        }
        if (!$this->nfPageId) {
            return false;
        }

        return true;
    }

    protected function nfPageUrl(){
        $this->nfPageUrl = get_post_meta($this->nfPageId, 'leadpages_slug', true);
    }

    public function displaynfPage()
    {
        if($this->nfPageExists() && is_404()){
            $pageId = $this->postTypeModel->getLeadpagePageId($this->nfPageId);

            //check for cache
            $getCache = get_post_meta($this->nfPageId, 'cache_page', true);
            if($getCache == "true"){
                $html = $this->postTypeModel->getCacheForPage($pageId);
                if(empty($html)){
                    $apiResponse = $this->pagesApi->downloadPageHtml($pageId);
                    $html = $apiResponse['response'];
                    $this->postTypeModel->setCacheForPage($pageId);
                }
            }else {
                //no cache download html
                $apiResponse = $this->pagesApi->downloadPageHtml($pageId);
                $html = $apiResponse['response'];
            }

            $html = LeadpageType::modifyMetaServedBy($html, 'wordpress');
            LeadpageType::renderHtml($html, 404);
        }
    }

}
