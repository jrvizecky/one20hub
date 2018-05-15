<?php


namespace LeadpagesMetrics\Traits;

trait WordPressPlatformVersion
{

    public function getPlatformVersion()
    {
        global $wp_version;

        if ( $wp_version  ) {
            return $wp_version;
        }else {
            return 'undefined';
        }

    }

}