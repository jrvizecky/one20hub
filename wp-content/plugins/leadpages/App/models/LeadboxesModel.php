<?php


namespace LeadpagesWP\models;


use LeadpagesWP\Helpers\Security;

class LeadboxesModel
{

    public $currentLeadboxes = '';

    public static function init()
    {
        add_action('admin_post_save_leadbox_options', array(get_called_class(), 'saveGlobalLeadboxes'));

    }

    public static function saveGlobalLeadboxes()
    {
        Security::checkAdminRefererStatic('save_leadbox_options');
        global $leadpagesApp;

        $b3TimedLeadbox = self::checkIfB3GlobalTimedLeadbox();
        $b3ExitLeadbox = self::checkIfB3GlobalExitLeadbox();
        if(!empty($b3TimedLeadbox)){
            $jsTimed = new \stdClass();
            $jsTimed->embed_code = str_replace(PHP_EOL, '', $b3TimedLeadbox);
        }else {
            $response = $leadpagesApp['leadboxesApi']->getSingleLeadboxEmbedCode($_POST['lp_select_field_0'], 'timed');
            $jsTimed  = json_decode($response['response']);
        }
        if(!empty($b3ExitLeadbox)){
            $jsExit = new \stdClass();
            $jsExit->embed_code = $b3ExitLeadbox;
        }else {
            $response = $leadpagesApp['leadboxesApi']->getSingleLeadboxEmbedCode($_POST['lp_select_field_2'], 'exit');
            $jsExit   = json_decode($response['response']);
        }

        $globalLeadboxes = array(
          'lp_select_field_0'             => sanitize_text_field($_POST['lp_select_field_0']),
          'leadboxes_timed_display_radio' => sanitize_text_field((!empty($_POST['leadboxes_timed_display_radio']) ? $_POST['leadboxes_timed_display_radio'] : '')),
          'leadboxes_timed_js'            => $jsTimed,
          'lp_select_field_2'             => sanitize_text_field($_POST['lp_select_field_2']),
          'leadboxes_exit_display_radio'  => sanitize_text_field((!empty($_POST['leadboxes_exit_display_radio']) ? $_POST['leadboxes_exit_display_radio'] : '')),
          'leadboxes_exit_js'             => $jsExit,
        );

        static::updateLeadboxOption($globalLeadboxes);
        wp_redirect(admin_url() . '?page=Leadboxes');
    }

    public static function checkIfB3GlobalTimedLeadbox()
    {
        if(!empty($_POST['leadbox_timed_script']) && $_POST['lp_select_field_0'] == 'ddbox'){
            return stripslashes($_POST['leadbox_timed_script']);
        }
    }
    public static function checkIfB3GlobalExitLeadbox()
    {
        if(!empty($_POST['leadbox_exit_script']) && $_POST['lp_select_field_2'] == 'ddbox'){
            return stripslashes($_POST['leadbox_exit_script']);
        }
    }

    protected static function updateLeadboxOption($data)
    {
        update_option('lp_settings', $data);
    }

    public static function getLpSettings()
    {
        $data = get_option('lp_settings');
        if (!is_wp_error($data)) {
            return $data;
        }
    }
    
    public static function getB3Script($type){
        $leadboxes = self::getLpSettings();
        if($type == 'timed'){
            if($leadboxes['lp_select_field_0'] == 'ddbox'){
                if($leadboxes['leadboxes_timed_js']) {
                    return $leadboxes['leadboxes_timed_js']->embed_code;
                }
            }
        }

        if($type == 'exit'){
            if($leadboxes['lp_select_field_2'] == 'ddbox'){
                if($leadboxes['leadboxes_exit_js']) {
                    return $leadboxes['leadboxes_exit_js']->embed_code;
                }
            }
        }


    }

    public static function getCurrentTimedLeadbox($leadboxes)
    {

        if (isset($leadboxes['lp_select_field_0']) && $leadboxes['lp_select_field_0'] != 'none') {
            @$currentTimedLeadbox = array($leadboxes['lp_select_field_0'], $leadboxes['leadboxes_timed_display_radio'], $leadboxes['leadboxes_timed_js']->embed_code);
        } else {
            $currentTimedLeadbox = array('none', 'none');
        }
        return $currentTimedLeadbox;
    }

    public static function getCurrentExitLeadbox($leadboxes)
    {
        if (isset($leadboxes['lp_select_field_2']) && $leadboxes['lp_select_field_2'] != 'none') {
            @$currentExitLeadbox = array($leadboxes['lp_select_field_2'], $leadboxes['leadboxes_exit_display_radio'], $leadboxes['leadboxes_exit_js']->embed_code);
        } else {
            $currentExitLeadbox = array('none', 'none');
        }
        return $currentExitLeadbox;
    }

    public static function savePageSpecificLeadboxes($post_id, $post)
    {

        self::savePageSpecificTimedLeadbox($post_id);

        self::savePageSpecificExitLeadbox($post_id);
    }


    public static function saveLeadboxMeta()
    {
        add_action('edit_post', array(get_called_class(), 'savePageSpecificLeadboxes'), 999, 2);
    }

    /**
     * @param $post_id
     * @param $timedLeadbox
     */
    public static function savePageSpecificTimedLeadbox($post_id)
    {
        if (isset($_POST['pageTimedLeadbox'])) {
            $timedLeadbox = sanitize_text_field($_POST['pageTimedLeadbox']);

            if ($timedLeadbox != 'select') {
                update_post_meta($post_id, 'pageTimedLeadbox', $timedLeadbox);
            } else {
                //if switched back to select delete the post meta so global leadboxs will display again
                delete_post_meta($post_id, 'pageTimedLeadbox');
            }
        }
    }

    /**
     * @param $post_id
     * @param $exitLeadbox
     */
    public static function savePageSpecificExitLeadbox($post_id)
    {
        if (isset($_POST['pageExitLeadbox'])) {
            $exitLeadbox = sanitize_text_field($_POST['pageExitLeadbox']);

            if ($exitLeadbox != 'select') {
                update_post_meta($post_id, 'pageExitLeadbox', $exitLeadbox);
            } else {
                //if switched back to select delete the post meta so global leadboxs will display again
                delete_post_meta($post_id, 'pageExitLeadbox');
            }
        }
    }
}