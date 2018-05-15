<?php


namespace LeadpagesWP\ServiceProviders;

use GuzzleHttp\Client;
use Leadpages\Leadboxes\Leadboxes;
use Leadpages\Auth\LeadpagesLogin;
use LeadpagesWP\Helpers\LeadboxDisplay;

class LeadboxesApi extends Leadboxes
{
    use LeadboxDisplay;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    /**
     * @var \Leadpages\Auth\LeadpagesLogin
     */
    private $login;

    public function __construct(Client $client, LeadpagesLogin $login)
    {
        parent::__construct($client, $login);
        $this->client = $client;
        $this->login = $login;
        add_action( 'wp_ajax_nopriv_allLeadboxesAjax', array($this, 'allLeadboxesAjax') );
        add_action( 'wp_ajax_allLeadboxesAjax', array($this, 'allLeadboxesAjax') );
    }

    /**
     * Function for ajax to call to generate dropdowns
     */
    public function allLeadboxesAjax(){
        $apiResponse = $this->getAllLeadboxes();
        $allLeadBoxes = json_decode($apiResponse['response'], true);

        $timedBoxes = $this->timedDropDown($allLeadBoxes);
        $exitLeadboxes = $this->exitDropDown($allLeadBoxes);
        $data = array(
          'timedLeadboxes' => $timedBoxes,
          'exitLeadboxes' => $exitLeadboxes
        );

        die(json_encode($data));
    }

}