<?php


namespace LeadpagesWP\Front\ShortCodes;

class LeadboxShortCodes
{
    /**
     * Displays the Leadboxes from shortcodes on front-end.
     */
    function dispalyLeadboxes( $atts ) {

        global $leadpagesApp;

        $atts     = shortcode_atts( array(
          'leadbox_id' => '',
        ), $atts );
        $leadboxId = $atts['leadbox_id'];

        $leadbox = $leadpagesApp['leadboxesApi']->getSingleLeadboxEmbedCode($leadboxId, '');

        $leadbox = json_decode($leadbox['response']);
        $embedCode = $leadbox->embed_code;
        return $embedCode;
    }


    public function addLeadboxesShortCode()
    {
        add_shortcode( 'leadpages_leadbox', array( $this, 'dispalyLeadboxes' ) );
    }

}