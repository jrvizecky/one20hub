<?php

namespace LeadpagesMetrics;


class LeadpagesErrorEvent extends Events
{

    protected $event = 'Error';


    public function buildUrl()
    {
        return $this->eventUrl . 'errors/';
    }

    public function storeEvent($args = array())
    {
        $this->buildClient();
        $url     = $this->buildUrl();
        $headers = [
          'Content-Type' => 'application/json'
        ];
        $errors = ['error' => $args];
        $response = $this->sendEvent($url, $headers, $errors);

        update_option('lp-response', $response);
    }

    public function sendEmail($to, $subject, $message)
    {
        //set content type to html for this message
        add_filter( 'wp_mail_content_type', function( $content_type ) {
            return 'text/html';
        });
        wp_mail($to, $subject, $message);
        //unset content type as to not disrupt other emails from other plugins
        add_filter( 'wp_mail_content_type', function( $content_type ) {
            return 'text/pain';
        });
    }

    public function reportError($apiResponse= array(), $data = array())
    {
        global $leadpagesApp;

        $adminEmail = get_bloginfo('admin_email');
        $url = get_site_url();
        $lpUserEmail = (get_option('lp-event-email') ? get_option('lp-event-email') : '');
        if(isset($_SERVER['HTTPS'])) {
            $protocol = 'https://';
        }else{
            $protocol = 'http://';
        }
        $currentUrl = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        //send error to tracking api
        if(!isset($_GET['leadpages-debug'])) {
            $leadpagesApp['errorEventsHandler']->storeEvent([
              'code'            => $apiResponse['code'],
              'pageId'          => $data['pageId'],
              'url_with_error'  => $currentUrl,
              'message'         => $apiResponse['response'],
              'leadpages_email' => $lpUserEmail
            ]);

            //send email to blog owner
            //$message = $this->constructMessage($url, $currentUrl, $apiResponse);
            //$this->sendEmail($adminEmail, 'Leadpage Error on WordPress Site', $message);
        }
    }

    protected function constructMessage($url, $currentUrl, $apiResponse)
    {
        return "Your blog at {$url} has encountered an error with on of your Leadpages at {$currentUrl} <br />
                Error Code: {$apiResponse['code']} <br />
                Error Message: {$apiResponse['response']} <br /> <br />
                Please contact <a href='https://support.leadpages.net/hc/en-us' target='_blank'> Leadpages Support </a> if you continue to have issues.
                ";
    }



}