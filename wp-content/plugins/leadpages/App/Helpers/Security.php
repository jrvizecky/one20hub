<?php


namespace LeadpagesWP\Helpers;


Class Security
{

    public function userPrivilege($role){
        if ( ! \current_user_can( $role ) )
        {
            return;
        }
    }

    public function checkAdminReferer( $nonce ){
        check_admin_referer( $nonce );
    }

    public static function checkAdminRefererStatic( $nonce ){
        if(!check_admin_referer( $nonce )){
            //    return;
        }
    }
}