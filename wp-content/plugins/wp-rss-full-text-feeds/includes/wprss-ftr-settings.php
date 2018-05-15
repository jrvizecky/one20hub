<?php

/**
 * The Settings class.
 * 
 * @since 1.0
 * @package WP RSS Aggregator
 * @subpackage Full Text RSS
 */
class WPRSS_FTR_Settings {
	
	/**
	 * The Singleton Instance
	 * 
	 * @type WPRSS_FTR_Settings
	 */
	protected static $instance;
	
	
	/**
	 * Constructor.
	 * 
	 * @since 1.0
	 */
	public function __construct() {
		// Check the singleton instance
		if ( self::$instance !== NULL ) {
			wp_die( __('WPRSS_FTR_Settings class is singleton class, and cannot be instantiated more than once!', WPRSS_TEXT_DOMAIN) );
		}
		
		// Add full text rss option
		add_filter( 'wprss_ftp_full_text_rss_service_options', array( $this, 'add_dropdown_option' ) );
		add_filter( 'wprss_ftp_full_text_rss_selectable_services', array( $this, 'check_dropdown_option_selectable' ) );

		if ( version_compare( WPRSS_VERSION, '4.5', '<' ) ) {
			// Register the license settings
			add_action( 'wprss_admin_init', array( $this, 'license_settings' ) );
		}
	}
	
	
	/**
	 * Returns the singleton instance
	 * 
	 * @since 1.0
	 * @return WPRSS_FTR_Settings The singleton instance
	 */
	public static function instance() {
		if ( self::$instance === NULL ) {
			self::$instance = new WPRSS_FTR_Settings();
		}
		return self::$instance;
	}
	
	
	/**
	 * Adds the Full Text RSS service to the option
	 * 
	 * @since 1.0
	 * @param array $services The assoc array of full text rss services. Keys represent the
	 * 		value saved in the DB, while values represent the text shown in the option.
	 * @return array An assoc array containing the services, along with the newly added service.
	 */
	public function add_dropdown_option( $services ) {
		$services['ftpr'] = __('Premium Full Text Service', WPRSS_TEXT_DOMAIN);
		return $services;
	}
	

	/**
	 * If the license is not active, the dropdown option is disabled.
	 *
	 * @since 1.2.3
	 */
	public function check_dropdown_option_selectable( $selectables ) {
		$selectables['ftpr'] = wprss_licensing_get_manager()->getLicense( 'ftr' )->getStatus() == 'valid';
		return $selectables;
	}

	
	/**
	 * Registers the license settings.
	 * 
	 * @since 1.0
	 */
	public function license_settings() {
		if ( version_compare(WPRSS_VERSION, '4.5', '>') ) return;
		add_settings_section(
			'wprss_settings_ftr_licenses_section',
			__( 'Full Text RSS License', WPRSS_TEXT_DOMAIN ),
			'__return_empty_string',
			'wprss_settings_license_keys'
		);

		add_settings_field(
			'wprss_settings_ftr_license',
			__( 'License Key', WPRSS_TEXT_DOMAIN ),
			array( $this, 'license_callback' ),
			'wprss_settings_license_keys',
			'wprss_settings_ftr_licenses_section'
		);

		add_settings_field(
			'wprss_settings_ftr_activate_license',
			__( 'Activate License', WPRSS_TEXT_DOMAIN ),
			array( $this, 'activate_license_callback' ),
			'wprss_settings_license_keys',
			'wprss_settings_ftr_licenses_section'
		);
	}
	
	
	/**
	 * Renders the license field.
	 * 
	 * @since 1.0
	 */
	public function license_callback() {
		$ftr_license = WPRSS_FTR::instance()->get_license_key(); ?>

		<input id="wprss-ftr-license-key" name="wprss_settings_license_keys[ftr_license_key]"
			   type="text" value="<?php echo esc_attr( $ftr_license ); ?>" style="width: 300px;" />

		<label class='description' for='wprss-ftr-license-key'>
			<?php _e( 'Enter your license key', WPRSS_TEXT_DOMAIN ); ?>
		</label>
		
		<?php
	}
	
	
	/**
	 * Renders the activate/deactivate license button.
	 * 
	 * @since 1.0
	 */
	public function activate_license_callback() {
		$status = WPRSS_FTR::instance()->get_license_status();
		if ( $status === 'site_inactive' ) $status = 'inactive';
		
		$valid = $status == 'valid';
		$btn_text = $valid ? __('Deactivate License', WPRSS_TEXT_DOMAIN) : __('Activate License', WPRSS_TEXT_DOMAIN);
		$btn_name = 'wprss_ftr_license_' . ( $valid? 'deactivate' : 'activate' );
		wp_nonce_field( 'wprss_ftr_license_nonce', 'wprss_ftr_license_nonce' ); ?>

		<input type="submit" class="button-secondary" name="<?php echo $btn_name; ?>" value="<?php echo $btn_text; ?>" />
		<span id="wprss-ftr-license-status-text">
			<strong><?php _e("Status:", WPRSS_TEXT_DOMAIN); ?>
			<span class="wprss-ftr-license-<?php echo $status; ?>">
					<?php echo ucfirst($status); ?>
					<?php if ( $status === 'valid' ) : ?>
						<i class="fa fa-check"></i>
					<?php elseif( $status === 'invalid' ): ?>
						<i class="fa fa-times"></i>
					<?php elseif( $status === 'inactive' ): ?>
						<i class="fa fa-warning"></i>
					<?php endif; ?>
				</strong>
			</span>
		</span>

		<style type="text/css">
			.wprss-ftr-license-valid {
				color: green;
			}
			.wprss-ftr-license-invalid {
				color: #b71919;
			}
			.wprss-ftr-license-inactive {
				color: #d19e5b;
			}
			#wprss-ftr-license-status-text {
				margin-left: 8px;
				line-height: 27px;
				vertical-align: middle;
			}
		</style>

		
		<?php
	}
	
}
