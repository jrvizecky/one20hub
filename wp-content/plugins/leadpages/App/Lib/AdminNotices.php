<?php

namespace LeadpagesWP\Lib;

class AdminNotices
{

    public static function getName(){
        return get_called_class();
    }

    /**
     * Notice for if a user is not logged in
     */
    public static function NotLoggedInToLeadpages(){
        $loginUrl = admin_url()."?page=Leadpages";
        $message = <<<BOM
        <p>You are not logged into Leadpages. Your pages will not work until you login</p>
        <a class="notice_login_link" href={$loginUrl}>Login to Leadpages</a>
BOM;


        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e( $message, 'leadpages' ); ?></p>
        </div>
        <?php
    }

    /**
     * Notice to let user know they need to turn on permalinks
     */
    public static function TurnOnPermalinks()
    {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e( 'LeadPages plugin needs <a style="text-decoration: underline"href="options-permalink.php">permalinks</a> enabled!', 'leadpages' ); ?></p>
        </div>
        <?php
    }



}