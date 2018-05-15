<?php

/**
 * Registers the Feed to Post add-on in the core.
 *
 * @since 1.3
 * @param array $addons The registered add-ons
 * @return array The registered add-ons, now also including an entry for this addon.
 */
add_filter( 'wprss_register_addon', function($addons) {
	$addons['ftr'] = WPRSS_FTR_SL_ITEM_NAME;
	return $addons;
});

// Enqueue admin_init hook after licensing system initialization
add_action( 'wprss_init_licensing', function() {
	add_action( 'admin_init', 'wprss_ftr_init_updater' );
});

/**
 * Creates and initializes the updater for this addon.
 *
 * @uses Aventura\Wprss\Core\Licensing\Manager::initUpdaterInstance() To initialize the updater instance
 */
function wprss_ftr_init_updater() {
	if ( method_exists(wprss_licensing_get_manager(), 'initUpdaterInstance') ) {
		wprss_licensing_get_manager()->initUpdaterInstance('ftr', WPRSS_FTR_SL_ITEM_NAME, WPRSS_FTR_VERSION, WPRSS_FTR_PATH, WPRSS_FTR_SL_STORE_URL);
	}
}
