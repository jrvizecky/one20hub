<?php


/**
 * Message for WordPress version not being suitable
 * 
 * @since 1.0
 */
function wprss_ftr_min_wp_msg() {
	wp_die(
		sprintf(
			__("The WP RSS Aggregator - Full Text RSS add-on requires WordPress version %s or higher", WPRSS_TEXT_DOMAIN),
			WPRSS_FTR_WP_MIN_VERSION
		)
	) ;
}


/**
 * Message for WP RSS Aggregator core not present, or version too low.
 * 
 * @since 1.0
 */
function wprss_ftr_min_core_msg() {
	$msg = sprintf(
			__("The <strong>WP RSS Aggregator - Full Text RSS</strong> add-on requires the WP RSS Aggregator plugin to be installed and activated, at version <strong>%s</strong> or higher.", WPRSS_TEXT_DOMAIN),
			WPRSS_FTR_CORE_MIN_VERSION
		);

	echo "<div class='error'><p>" . $msg . "</p></div>";
}


/**
 * Message for WP RSS Aggregator Feed to Post not present, or version too low.
 * 
 * @since 1.0
 */
function wprss_ftr_min_ftp_msg() {
	$msg = sprintf(
			__("The <strong>WP RSS Aggregator - Full Text RSS</strong> add-on requires the WP RSS Aggregator - Feed to Post plugin to be installed and activated, at version <strong>%s</strong> or higher.", WPRSS_TEXT_DOMAIN),
			WPRSS_FTR_FTP_MIN_VERSION
		);

	echo "<div class='error'><p>" . $msg . "</p></div>";
}

/**
 * Message for license key not active when Full Text RSS add-on is selected as the Full Text RSS service to be used.
 * 
 * @since 1.0
 */
function wprss_ftr_license_msg() {
	$msg = __("The license key for the <strong>WP RSS Aggregator - Full Text RSS</strong> add-on is not activated. Without an activated license, the add-on will not be able to import full content for your RSS feeds. ", WPRSS_TEXT_DOMAIN);
	$link = "<a href='" . esc_attr(admin_url( 'edit.php?post_type=wprss_feed&page=wprss-aggregator-settings&tab=licenses_settings' )) . "'>" . 
		__("Activate your license key", WPRSS_TEXT_DOMAIN) .
		"</a>";

	echo "<div class='error'><p>" . $msg . $link . "</p></div>";
}