<?php


namespace LeadpagesWP\Lib;


/**
 * Class Update
 * @package Leadpages\Admin\Providers
 * Complete copy from old plugin. May need updated at some point
 */
class Update
{

    public function register_auto_update()
    {
        // plugin update information
        add_filter('plugins_api', array(&$this, '_update_information'), 9, 3);
        // exclude from official updates
        add_filter('http_request_args', array(&$this, '_updates_exclude'), 5, 2);
        // check for update twice a day (same schedule as normal WP plugins)
        add_action('lp_check_event', array(&$this, '_check_for_update'));
        add_filter('transient_update_plugins', array(&$this, 'pro_check_update'));
        add_filter('site_transient_update_plugins', array(&$this, 'pro_check_update'));
        // check and schedule next update
        if (!wp_next_scheduled('lp_check_event')) {
            wp_schedule_event(current_time('timestamp'), 'twicedaily', 'lp_check_event');
        }
        // remove cron task upon deactivation
        register_deactivation_hook(__FILE__, array(&$this, '_check_deactivation'));
    }

    /**
     * Remove cron on deactivation
     */
    public function _check_deactivation()
    {
        wp_clear_scheduled_hook('lp_check_event');
    }

    public function _plugin_get($i)
    {
        if (!function_exists('get_plugins')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        $plugin_folder = get_plugins('/leadpages');

        return $plugin_folder['leadpages.php'][$i];
    }

    /**
     * Exclude from WP updates
     **/
    public static function _updates_exclude($r, $url)
    {
        if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check')) {
            return $r;
        } // Not a plugin update request. Bail immediately.
        $plugins = unserialize($r['body']['plugins']);
        if (isset($plugins->plugins['leadpages'])) {
            unset($plugins->plugins['leadpages']);
            unset($plugins->active[array_search('leadpages', $plugins->active)]);
        }
        $r['body']['plugins'] = serialize($plugins);

        return $r;
    }

    public function silent_update_check()
    {
        $result = self::_check_for_update(true); // full response if possible is returned

        $response = $result[0];
        if (false === $response) {
            self::show_message(false,
              'Error while checking for update. Can\'t reach update server. Message: ' . $result[1]);

            return;
        }
        if (isset($response->result) && $response->result == 'ko') {
            self::show_message(false, $response->message);

            return;
        }
        $nv              = $response->version;
        $url             = $response->url;
        $current_version = self::_plugin_get('Version');
        if ($current_version == $nv || version_compare($current_version, $nv, '>')) {
            return;
        }
        $plugin_file = 'leadpages/leadpages.php';
        $upgrade_url = admin_url('update.php?action=upgrade-plugin&amp;plugin=' . urlencode($plugin_file),
          'upgrade-plugin_' . $plugin_file);
        $message     = 'There is a new version of LeadPages plugin available! ( ' . $nv . ' )<br>You can <a href="' . $upgrade_url . '">update</a> to the latest version automatically or <a href="' . $url . '">download</a> the update and install it manually.';
        self::show_message(true, $message);
    }

    public function pro_check_update($option, $cache = true)
    {
        $response = get_site_transient('leadpages_latest_version');
        if (!$response) {
            $result   = self::lb_api_call('update-check');
            $response = $result[0];
            if ($response === false) {
                return $option;
            }
        }
        $current_version = self::_plugin_get('Version');
        if ($current_version == $response->version) {
            return $option;
        }
        if (version_compare($current_version, $response->version, '>')) {
            return $option; // you have the latest version
        }
        $plugin_path = 'leadpages/leadpages.php';
        if (empty($option->response[$plugin_path])) {
            $option->response[$plugin_path] = new \stdClass();
        }
        $option->response[$plugin_path]->url         = self::_plugin_get('AuthorURI');
        $option->response[$plugin_path]->slug        = 'leadpages';
        $option->response[$plugin_path]->package     = $response->url;
        $option->response[$plugin_path]->new_version = $response->version;
        $option->response[$plugin_path]->id          = "0";

        return $option;
    }

    public function _check_for_update($full = false)
    {
        if (defined('WP_INSTALLING')) {
            return false;
        }
        $result = self::lb_api_call('update-check');
        //echo '<pre>'; print_r($result);die();
        $response = $result[0];
        if ($full === true) {
            return $result; // giving the full response ...
        }
        if ($response === false) { // we have a problem
            return array(false, $result[1]);
        }
        $current_version = self::_plugin_get('Version');
        if ($current_version == $response->version) {
            return false;
        }
        if (version_compare($current_version, $response->version, '>')) {
            return array(true, 'You have the latest version!');
        }

        return array($response->version, 'There is a newer version!');
    }

    public function _update_information($false, $action, $args)
    {
        // Check if this plugins API is about this plugin
        if (!isset($args->slug)) {
            return false;
        }

        if ($args->slug != 'leadpages') {
            return $false;
        }
        $result   = self::lb_api_call('info');
        $response = $result[0];
        if ($response === false) {
            return $false;
        }
        $response->slug        = 'leadpages';
        $response->plugin_name = 'leadpages';

        return $response;
    }

    public function lb_api_call($service)
    {
        global $leadpagesConfig;
        $licence_key = 'upUbSkfvYbd74rYnAl5hWczFlGbnYLCp';
        $url         = $leadpagesConfig['update_url'] . '/service/leadpages/' . $service . '/';
        $current_ver = self::_plugin_get('Version');
        $response    = wp_remote_post(
          $url,
          array(
            'method'      => 'POST',
            'timeout'     => 70,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(),
            'body'        => array(
              'version'     => $current_ver,
              'licence_key' => $licence_key,
              'php_version' => PHP_VERSION
            ),
            'cookies'     => array()
          )
        );
        if (is_wp_error($response)) {
            return array(false, $response->get_error_message());
        }
        if (isset($response['response']['code'])) {
            $code_char = substr($response['response']['code'], 0, 1);
        } else {
            $code_char = '5';
        }
        if ($code_char == '5' || $code_char == '4') {
            return array(false, $response['response']['message']);
        }
        $res = json_decode($response['body'], true);
        if (!is_array($res)) {
            return array(false, 'Unexpected response. Failed to decode JSON.');
        }
        if (isset($res['result']) && $res['result'] == 'ko') {
            return array(false, $res['message']);
        }
        $r = new \stdClass;
        foreach ($res as $key => $val) {
            $r->$key = $val;
        }
        if ($service == 'update-check') {
            set_site_transient('leadpages_latest_version', $r, 60 * 60 * 12);
        }

        return array($r, 'Everything is good!');
    }

    private static $message = false;

    function show_message($not_error, $message)
    {
        self::$message = $message;
        if ($not_error) {
            add_action('admin_notices', array(&$this, 'showMessage'));
        } else {
            add_action('admin_notices', array(&$this, 'showErrorMessage'));
        }
    }

    function showMessage()
    {
        echo '<div id="message" class="updated">';
        echo '<p><strong>' . self::$message . '</strong></p></div>';
    }

    function showErrorMessage()
    {
        echo '<div id="message" class="error">';
        echo '<p><strong>' . self::$message . '</strong></p></div>';
    }


    public function scheduleCacheUpdates()
    {
        add_action('lp_cache_updates', array($this, 'UpdateNonSplittTestedPagesCache'));

        if (!wp_next_scheduled('lp_cache_updates')) {
            wp_schedule_event(current_time('timestamp'), 'twicedaily', 'lp_cache_updates');
        }
    }

    /**
     * If a page is from the old plugin and has a leadpages_split_test post meta option and its false
     * Update the cache post meta data to true
     */
    public function UpdateNonSplittTestedPagesCache()
    {
        global $wpdb;
        //get all Leadpages Post Types
        $posts = $wpdb->get_results("Select ID from {$wpdb->prefix}posts where post_type = 'leadpages_post'");

        $query   = <<<BOQ
        SELECT
	        pm.post_id,
	        pm.meta_key,
	        pm.meta_value
        FROM
	        {$wpdb->prefix}postmeta as pm
        WHERE
	        pm.meta_key = 'leadpages_split_test'
BOQ;
        $results = $wpdb->get_results($query);
        foreach ($results as $row) {
            if ($row->meta_value == false) {
                update_post_meta($row->post_id, 'cache_page', 'true');
            }
        }
    }

}