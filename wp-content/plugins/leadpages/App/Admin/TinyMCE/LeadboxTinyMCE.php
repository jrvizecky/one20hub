<?php

namespace LeadpagesWP\Admin\TinyMCE;

use LeadpagesWP\ServiceProviders\LeadboxesApi;


class LeadboxTinyMCE
{

    public function init()
    {
        //tinymce setup
        add_action('init', array($this, 'leadboxes_buttons'));
        foreach (array('post.php', 'post-new.php') as $hook) {
            add_action("admin_head-$hook", array($this, 'tinymceVars'));
        }
    }

    /**
     * register tiny mce button
     *
     * @param $buttons
     *
     * @return mixed
     */

    public function leadboxes_buttons()
    {
        add_filter("mce_external_plugins", array($this, "leadboxes_add_buttons"));
        add_filter('mce_buttons', array($this, 'leadboxes_register_buttons'));
    }

    public function leadboxes_add_buttons($plugin_array)
    {
        global $leadpagesConfig;
        $plugin_array['leadpages_leadboxes'] = $leadpagesConfig['admin_js'] . 'leadbox_tinymce.js';
        return $plugin_array;
    }

    public function leadboxes_register_buttons($buttons)
    {
        array_push($buttons, 'add_leadbox');
        return $buttons;
    }

    public function tinymceVars()
    {

        global $leadpagesApp;

        $leadboxApi = $leadpagesApp['leadboxesApi'];
        $leadboxes = $leadboxApi->getAllLeadboxes();
        $allLeadBoxes = json_decode($leadboxes['response'], true);
        $allLeadBoxes = $allLeadBoxes['_items'];
        $leadboxesJavascript = [];
        $i = 0;
        if(!empty($allLeadBoxes)){
        foreach($allLeadBoxes as $leadBox) {
            //echo '<pre>'; print_r($leadBox);
            if (isset($leadBox['publish_settings']['embed']) && strpos($leadBox['publish_settings']['embed'],
                'href') > 0
            ) {

                $leadboxesJavascript[$i]['name']   = $leadBox['name'];
                $leadboxesJavascript[$i]['xor_id'] = $leadBox['xor_hex_id'];
                $i++;
            }
        }
      }else{
        $leadboxesJavascript[$i]['name']   = 'No Standard Leadboxes Configured';
        $leadboxesJavascript[$i]['xor_id'] = '000';
      }
        ?>
        <!-- TinyMCE Shortcode Plugin -->
		<script type='text/javascript'>
			window.leadboxes = <?php echo json_encode($leadboxesJavascript); ?>
		</script>
		<!-- TinyMCE Shortcode Plugin -->
        <?php
    }

}