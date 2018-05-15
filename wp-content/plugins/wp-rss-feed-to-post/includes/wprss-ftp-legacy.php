<?php

/**
 * Contains all functions relating to the use of the legacy wprss_feed_item CPT.
 *
 * @since 2.9.5
 */


/**
 * Returns TRUE if using the legacy imported feed items, FALSE otherwise.
 * If a source ID is given, it returns TRUE if the source is using the wprss_feed_item
 * post type, FALSE for any other.
 *
 * @since 2.9.5
 * @param int|string $source_id The ID of the feed source
 */
function wprss_ftp_using_feed_items( $source_id = NULL ) {
	if ( $source_id === NULL ) {
		return WPRSS_FTP_Utils::multiboolean( WPRSS_FTP_Settings::get_instance()->get('legacy_enabled') );
	} else {
		return WPRSS_FTP_Meta::get_instance()->get( $source_id, 'post_type' ) == 'wprss_feed_item';
	}
}

add_filter('wprss_ftp_section_docs_link_urls', 'wprss_ftp_legacy_register_setting_section_docs_urls');
function wprss_ftp_legacy_register_setting_section_docs_urls($urls)
{
    $urls['wprss_settings_legacy_section'] = 'http://docs.wprssaggregator.com/general-settings/#feed-to-post-compatibility';
    return $urls;
}


add_action( 'wprss_ftp_after_settings_register', 'wprss_ftp_legacy_settings_section' );
/**
 * Registers the settings for the legacy compatability section.
 *
 * @since 2.9.5
 * @param WPRSS_FTP_Settings $settings The instance of the class that registered the settings
 */
function wprss_ftp_legacy_settings_section($settings) {
	add_settings_section(
		'wprss_settings_legacy_section',
		__('Feed to Post Compatibility', WPRSS_TEXT_DOMAIN)
            . $settings->get_section_docs_link_html('wprss_settings_legacy_section'),
		'wprss_settings_legacy_callback',
		'wprss_settings_ftp'
	);

	add_settings_field(
		'wprss-settings-enable-legacy-cpt',
		__('Legacy Feed Items', WPRSS_TEXT_DOMAIN),
		'wprss_ftp_legacy_enable_option',
		'wprss_settings_ftp',
		'wprss_settings_legacy_section'
	);
}


/**
 * Prints the description for the legacy callback section.
 *
 * @since 2.9.5
 */
function wprss_settings_legacy_callback() {
	echo '<p>' . __('Change how Feed to Post works with WP RSS Aggregator', WPRSS_TEXT_DOMAIN) . '</p>';
}


/**
 * Prints the checkbox settings for enabling the legacy feed item.
 *
 * @since 2.9.5
 */
function wprss_ftp_legacy_enable_option() {
	$legacy_enabled = WPRSS_FTP_Settings::get_instance()->get('legacy_enabled');
	echo WPRSS_FTP_Utils::boolean_to_checkbox(
		WPRSS_FTP_Utils::multiboolean( $legacy_enabled ),
		array(
			'id'	=>	'wprss-ftp-legacy-enabled',
			'name'	=>	WPRSS_FTP_Settings::OPTIONS_NAME . '[legacy_enabled]',
			'value'	=>	'true',
		)
	);
	echo WPRSS_Help::get_instance()->do_tooltip( WPRSS_FTP_HELP_PREFIX.'legacy_enabled' );
}