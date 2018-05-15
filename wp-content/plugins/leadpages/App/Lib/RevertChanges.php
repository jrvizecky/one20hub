<?php

/**
 * We changed some functionality in 2.0 and 2.01 from the 1.x versions of the plugin.
 * This function will change all of it back and hopeful mitigate any issues
 */
function fixAllTheThings()
{
    /**
     * fix post slugs back to just the title
     */

    global $wpdb;
    $posts = $wpdb->get_results("Select * from {$wpdb->prefix}posts where post_type = 'leadpages_post'");

    //remove all the slashes
    foreach ($posts as $post) {
        $title = $post->post_title;

        $title = ltrim($title, '/');
        $title = rtrim($title, '/');
        update_post_meta($post->ID, 'leadpages_slug', $title);

        $post_update_name = array(
          'post_name' => $title,
        );
        if ($title != '/') {
            $wpdb->update("{$wpdb->prefix}posts", $post_update_name, ['ID' => $post->ID]);
        }
    }

    /**
     * revert leadbox settings back
     */

    $lp_settings = get_option('lp_settings');
    if ($lp_settings['leadboxes_timed_display_radio'] == 'post') {
        $lp_settings['leadboxes_timed_display_radio'] = 'posts';
    }
    if ($lp_settings['leadboxes_exit_display_radio'] == 'post') {
        $lp_settings['leadboxes_exit_display_radio'] = 'posts';
    }

    update_option('lp_settings', $lp_settings);


    /**
     * remove page specific leadboxes if setup
     */
    $pagesWithLeadboxes = $posts = $wpdb->get_results("Select post_id from {$wpdb->prefix}postmeta where meta_key = 'pageTimedLeadbox'");
    foreach ($pagesWithLeadboxes as $post) {
        delete_post_meta($post->post_id, 'pageTimedLeadbox');
    }

    $pagesWithLeadboxes = $posts = $wpdb->get_results("Select post_id from {$wpdb->prefix}postmeta where meta_key = 'pageExitLeadbox'");
    foreach ($pagesWithLeadboxes as $post) {
        delete_post_meta($post->post_id, 'pageExitLeadbox');
    }

    update_option('LeadpagesDBFixApplied', true);
}

if(version_compare( $leadpages_connector_plugin_version, '2.1.0', '<=' )){
    $fixAlreadyApplied = get_option('LeadpagesDbFixApplied');
    if(!$fixAlreadyApplied) {
        fixAllTheThings();
    }
}

