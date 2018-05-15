<?php


namespace LeadpagesWP\Lib;


class ApiResponseHandler
{

    protected $acceptedResponseCodes = array(
      200 => 'OK',
      201 => 'Created',
      202 => 'Accepted'
    );

    public function checkResponse($response)
    {
        $code = $this->getResponseCode($response);
        if (array_key_exists($code, $this->acceptedResponseCodes)) {
            return $this->handleSuccess();
        }
        //return handle error if not success
        $body = $this->getBody($response);
        return $this->handleError($code, $body);
    }

//    public function handleError($code, $body)
//    {
//        return $code;
//        //the below should happen but not appears to work for now return code
//        /*
//        $body = $body['_status']['errors'][0]['message'];
//        add_action('admin_notices', function () use ($body, $code) {
//            echo '<div class="notice notice-error is-dismissible"><p>', esc_html($body), ' Error Code: '. esc_html($code) .'</p></div>';
//        });
//        */
//    }

    public function adminErrorNotice($code, $body)
    {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><strong>Error:</strong> <?php $body ?></p>
        </div>
        <?php
        return 'error';
    }

    protected function handleSuccess()
    {
        return 'success';
    }

    public function getResponseCode($response)
    {
        if (isset($response) && is_array($response)) {
            $code = null;
            array_walk_recursive($response, function ($value, $key) use (&$code) {
                if ($key == 'code') {
                    $code = strval($value);
                }
            });

        }
        return $code;
    }

    public function getBody($response)
    {

        if (isset($response) && is_array($response)) {

            $body = null;
            array_walk_recursive($response, function ($value, $key) use (&$body) {
                if ($key == 'body') {
                    if (!is_array($value)) {
                        $body = json_decode($value, true); //return as an array
                    }
                }
            });
        }
        return $body;
    }


}