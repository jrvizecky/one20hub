<?php

	add_action( 'wprss_admin_init', 'wprss_kf_add_settings' );
	/**
	 * Adds some more settings fields pertaining to keyword filtering
	 * @since 1.0
	 */    
	function wprss_kf_add_settings() {

		add_settings_section(   
			'wprss_settings_kf_section',                    // ID used to identify this section and with which to register options      
			__( 'Keyword Filtering Settings', WPRSS_TEXT_DOMAIN ),    // Title to be displayed on the administration page  
			'wprss_settings_kf_callback',                   // Callback used to render the description of the section
			'wprss_settings_kf'                             // Page on which to add this section of options  
		);

		add_settings_section(   
			'wprss_settings_kf_tags_section',               // ID used to identify this section and with which to register options      
			__( 'Tag Filtering Settings', WPRSS_TEXT_DOMAIN ),    	// Title to be displayed on the administration page  
			'wprss_settings_kf_tags_callback',              // Callback used to render the description of the section
			'wprss_settings_kf'                             // Page on which to add this section of options  
		);

		register_setting( 
			'wprss_settings_kf',                            // A settings group name.
			'wprss_settings_kf',                            // The name of an option to sanitize and save.
			'wprss_settings_kf_validate'                    // A callback function that sanitizes the option's value.
		);

		add_settings_field(
			'wprss-settings-kf-keywords',
			__( 'Contains <b>all</b> these words/phrases', WPRSS_TEXT_DOMAIN ),
			'wprss_setting_kf_keywords_callback',           // The name of the function responsible for rendering the option interface
			'wprss_settings_kf',                            // The page on which this option will be displayed  
			'wprss_settings_kf_section'                     // The name of the section to which this field belongs  
		);
		add_settings_field(
			'wprss-settings-kf-keywords-any',
			__( 'Contains <b>any</b> these words/phrases', WPRSS_TEXT_DOMAIN ),
			'wprss_setting_kf_keywords_any_callback',       // The name of the function responsible for rendering the option interface
			'wprss_settings_kf',                            // The page on which this option will be displayed  
			'wprss_settings_kf_section'                     // The name of the section to which this field belongs  
		);
		add_settings_field(
			'wprss-settings-kf-keywords-not',
			__( 'Contains <b>none</b> these words/phrases', WPRSS_TEXT_DOMAIN ),
			'wprss_setting_kf_keywords_not_callback',       // The name of the function responsible for rendering the option interface
			'wprss_settings_kf',                            // The page on which this option will be displayed  
			'wprss_settings_kf_section'                     // The name of the section to which this field belongs  
		);
		add_settings_field(
			'wprss-settings-kf-keywords-tags',
			__( 'Contains <b>any</b> these tags', WPRSS_TEXT_DOMAIN ),
			'wprss_setting_kf_tags_callback',       		// The name of the function responsible for rendering the option interface
			'wprss_settings_kf',                            // The page on which this option will be displayed  
			'wprss_settings_kf_tags_section'                // The name of the section to which this field belongs  
		);
		add_settings_field(
			'wprss-settings-kf-keywords-not-tags',
			__( 'Contains <b>none</b> these tags', WPRSS_TEXT_DOMAIN ),
			'wprss_setting_kf_not_tags_callback',       	// The name of the function responsible for rendering the option interface
			'wprss_settings_kf',                            // The page on which this option will be displayed  
			'wprss_settings_kf_tags_section'                // The name of the section to which this field belongs  
		);


		if ( version_compare(WPRSS_VERSION, '4.5', '<') ) {
			add_settings_section(   
				'wprss_settings_kf_licenses_section',                          
				__( 'Keyword Filtering License', WPRSS_TEXT_DOMAIN ),    
				'wprss_kf_settings_license_callback',               
				'wprss_settings_license_keys'                         
			);

			add_settings_field( 
				'wprss-settings-license', 
				__( 'License Key', WPRSS_TEXT_DOMAIN ), 
				'wprss_kf_setting_license_callback', 
				'wprss_settings_license_keys', 
				'wprss_settings_kf_licenses_section' 
			);          

			add_settings_field( 
				'wprss-settings-license-activation', 
				__( 'Activate License', WPRSS_TEXT_DOMAIN ), 
				'wprss_kf_setting_license_activation_callback', 
				'wprss_settings_license_keys', 
				'wprss_settings_kf_licenses_section' 
			);
		}
	}


	add_action( 'wprss_add_settings_fields_sections', 'wprss_kf_add_settings_fields_sections', 10, 1 );
	/** 
	 * Add settings fields and sections for Keyword Filtering
	 *
	 * @since 1.0
	 */
	function wprss_kf_add_settings_fields_sections( $active_tab ) {
			
		if ( $active_tab == 'kf_settings' ) {         
			settings_fields( 'wprss_settings_kf' );
			do_settings_sections( 'wprss_settings_kf' ); 
		}
	}


	/** 
	 * Draw the licenses settings section header
	 * @since 1.0
	 */
	function wprss_kf_settings_license_callback() {
		//  echo '<p>' . ( 'License details' ) . '</p>';
	}     


	/** 
	 * Set license
	 * @since 1.0
	 */
	function wprss_kf_setting_license_callback( $args ) {
		$license_keys = get_option( 'wprss_settings_license_keys' ); 
		$kf_license_key = ( isset( $license_keys['kf_license_key'] ) ) ? $license_keys['kf_license_key'] : FALSE;      
		echo "<input id='wprss-kf-license-key' name='wprss_settings_license_keys[kf_license_key]' type='text' value='" . esc_attr( $kf_license_key ) ."' />";
		echo "<label class='description' for='wprss-kf-license-key'>" . __( 'Enter your license key', WPRSS_TEXT_DOMAIN ) . '</label>';                   
	}    


	/** 
	 * License activation button and indicator
	 * @since 1.0
	 */
	function wprss_kf_setting_license_activation_callback( $args ) {
		$license_keys = get_option( 'wprss_settings_license_keys' ); 
		$license_statuses = get_option( 'wprss_settings_license_statuses' ); 
		$kf_license_key = ( isset( $license_keys['kf_license_key'] ) ) ? $license_keys['kf_license_key'] : FALSE;
		$kf_license_status = ( isset( $license_statuses['kf_license_status'] ) ) ? $license_statuses['kf_license_status'] : FALSE;
	
	   // if( '' === $kf_license_key ) { ?>

			<?php if( $kf_license_status != FALSE && $kf_license_status == 'valid' ) { ?>
					<span style="color:green;"><?php _e( 'active', WPRSS_TEXT_DOMAIN ); ?></span>
					<?php wp_nonce_field( 'wprss_kf_license_nonce', 'wprss_kf_license_nonce' ); ?>
					<input type="submit" class="button-secondary" name="wprss_kf_license_deactivate" value="<?php _e( 'Deactivate License', WPRSS_TEXT_DOMAIN ); ?>"/>
			<?php } else {
					wp_nonce_field( 'wprss_kf_license_nonce', 'wprss_kf_license_nonce' ); ?>
					<input type="submit" class="button-secondary" name="wprss_kf_license_activate" value="<?php _e( 'Activate License', WPRSS_TEXT_DOMAIN ); ?>"/>
			<?php //} ?>

		<?php }
	}


	/**
	 * Callback function that validates the wprss_settings_kf page's options
	 *
	 * @todo settings The settings to be validated and sanitized before insertion into DB
	 * @since 1.0
	 */
	function wprss_settings_kf_validate( $settings ) {
		$settings['keywords'] = preg_replace( '/,(\s*)/', ', ', $settings['keywords'] );
		return $settings;
	}


	/**
	 * The callback that displays the description of the section
	 *
	 * @since 1.0
	 */
	function wprss_settings_kf_callback() {
		echo '<p>' . __( 'Global keyword filtering settings', WPRSS_TEXT_DOMAIN ) . '</p>';
	}


	/**
	 * The callback that displays the description of the section
	 *
	 * @since 1.0
	 */
	function wprss_settings_kf_tags_callback() {
		echo '<p>' . __( 'Global tag filtering settings', WPRSS_TEXT_DOMAIN ) . '</p>';
	}



	/**
	 * Callback that prints the keywords option field
	 *
	 * @since 1.0
	 */
	function wprss_setting_kf_keywords_callback() {
		$options = get_option( 'wprss_settings_kf', array() );
		$keywords = ( isset( $options['keywords'] ) )? $options['keywords'] : '';
		echo "<textarea id='keywords' name='wprss_settings_kf[keywords]' cols='50' rows='5' type='text' value='$keywords' class='small-text'>$keywords</textarea>";
	}
	/**
	 * Callback that prints the any keywords option field
	 *
	 * @since 1.0
	 */
	function wprss_setting_kf_keywords_any_callback() {
		$options = get_option( 'wprss_settings_kf', array() );
		$keywords_any = ( isset( $options['keywords_any'] ) )? $options['keywords_any'] : '';
		echo "<textarea id='keywords_any' name='wprss_settings_kf[keywords_any]' cols='50' rows='5' type='text' value='$keywords_any' class='small-text'>$keywords_any</textarea>";
	}
	/**
	 * Callback that prints the no keywords option field
	 *
	 * @since 1.0
	 */
	function wprss_setting_kf_keywords_not_callback() {
		$options = get_option( 'wprss_settings_kf', array() );
		$keywords_not = ( isset( $options['keywords_not'] ) )? $options['keywords_not'] : '';
		echo "<textarea id='keywords_not' name='wprss_settings_kf[keywords_not]' cols='50' rows='5' type='text' value='$keywords_not' class='small-text'>$keywords_not</textarea>";
	}
	/**
	 * Callback that prints the tags option field
	 *
	 * @since 1.0
	 */
	function wprss_setting_kf_tags_callback() {
		$options = get_option( 'wprss_settings_kf', array() );
		$keywords_tags = ( isset( $options['keywords_tags'] ) )? $options['keywords_tags'] : '';
		echo "<textarea id='keywords_tags' name='wprss_settings_kf[keywords_tags]' cols='50' rows='5' type='text' value='$keywords_tags' class='small-text'>$keywords_tags</textarea>";
	}
	/**
	 * Callback that prints the not tags option field
	 *
	 * @since 1.5
	 */
	function wprss_setting_kf_not_tags_callback() {
		$options = get_option( 'wprss_settings_kf', array() );
		$keywords_not_tags = ( isset( $options['keywords_not_tags'] ) )? $options['keywords_not_tags'] : '';
		echo "<textarea id='keywords_not_tags' name='wprss_settings_kf[keywords_not_tags]' cols='50' rows='5' type='text' value='$keywords_not_tags' class='small-text'>$keywords_not_tags</textarea>";
	}



	add_action( 'wprss_options_tabs', 'wprss_kf_add_settings_tabs' );
	/** 
	 * Add KF-related settings on the RSS Aggregator > Settings page
	 * @since 1.0
	 */
	function wprss_kf_add_settings_tabs( $args ) {
		$args['keyword_filtering'] = array(
			'label' => __( 'Keyword Filtering', WPRSS_TEXT_DOMAIN ),
			'slug'  => 'kf_settings'
		);
		return $args;
	}


	/**
	 * The default addon options
	 *
	 * @since 1.0
	 */
	function wprss_kf_default_options() {
		return array(
			'filter_title'		=>	'true',
			'filter_content'	=>	'true',
			'keywords'			=>	'',
			'keywords_any'		=>	'',
			'keywords_not'		=>	'',
			'keywords_tags'		=>	'',
			'keywords_not_tags'	=>	'',
		);
	}
