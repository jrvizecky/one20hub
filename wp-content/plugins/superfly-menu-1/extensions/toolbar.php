<?php
#if(class_exists('SFET_WpPluginAutoload\Core\SFET_Plugin')) {
// check if superfly activated
if( !function_exists('is_plugin_active') ) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
if (is_plugin_active('superfly-extension-toolbars/plugin.php'))
{

    function sf_extension_toolbar_settings()
    {
        add_settings_field('sf_toolbar_section1', "", 'sf_toolbar_section1_str', 'sf', 'sf_appearance',                             array('chapter' => 'Toolbar', 'subsection' => 'toolbar'));
        add_settings_field('sf_toolbar_wp_sign_in', "Toolbar Elements", 'sf_toolbar_wp_sign_in_str', 'sf', 'sf_appearance',         array('column' => 1, 'subsection' => 'toolbar'));
        add_settings_field('sf_toolbar_languages', "", 'sf_toolbar_languages_str', 'sf', 'sf_appearance',                           array('column' => 1, 'subsection' => 'toolbar'));
        add_settings_field('sf_toolbar_shoppingcart', "", 'sf_toolbar_shoppingcart_str', 'sf', 'sf_appearance',                     array('column' => 1, 'subsection' => 'toolbar'));
        add_settings_field('sf_toolbar_customlinksignin', "", 'sf_toolbar_customlinksignin_str', 'sf', 'sf_appearance',             array('column' => 1, 'subsection' => 'toolbar'));
        add_settings_field('sf_toolbar_customlinkurl', "Custom link url", 'sf_toolbar_customlinkurl_str', 'sf', 'sf_appearance',    array('column' => 1, 'subsection' => 'toolbar'));
        add_settings_field('sf_toolbar_customlinktext', "Custom link text", 'sf_toolbar_customlinktext_str', 'sf', 'sf_appearance', array('column' => 1, 'subsection' => 'toolbar'));

        add_settings_field('sf_toolbar_section2', "", 'sf_toolbar_section2_str', 'sf', 'sf_appearance',                             array('column' => 1, 'subsection' => 'toolbar'));
        add_settings_field('sf_toolbar_color', "Toolbar color", 'sf_toolbar_color_str', 'sf', 'sf_appearance',                      array('column' => 1, 'subsection' => 'toolbar'));
        add_settings_field('sf_toolbar_uicolor', "UI elements color", 'sf_toolbar_uicolor_str', 'sf', 'sf_appearance',              array('column' => 1, 'subsection' => 'toolbar'));

        add_settings_field('sf_toolbar_section3', "", 'sf_toolbar_section3_str', 'sf', 'sf_appearance',                             array('column' => 1, 'subsection' => 'toolbar'));
        add_settings_field('sf_toolbar_form_button_color', "Button color", 'sf_toolbar_form_button_color_str', 'sf', 'sf_appearance',                      array('column' => 1, 'subsection' => 'toolbar'));
        add_settings_field('sf_toolbar_form_button_label', "Button label color", 'sf_toolbar_form_button_label_str', 'sf', 'sf_appearance',              array('column' => 1, 'subsection' => 'toolbar'));
    }

    function sf_extension_toolbar_options($options)
    {

        if (empty($options['sf_extension']['toolbar']['wp_sign_in'])) {
            $return['wp_sign_in'] = 0;
        } else {
            $return['wp_sign_in'] = 1;
        }

        if (empty($options['sf_extension']['toolbar']['languages'])) {
            $return['languages'] = 0;
        } else {
            $return['languages'] = 1;
        }

        if (empty($options['sf_extension']['toolbar']['shoppingcart'])) {
            $return['shoppingcart'] = 0;
        } else {
            $return['shoppingcart'] = 1;
        }
        if (empty($options['sf_extension']['toolbar']['customlinksignin'])) {
            $return['customlinksignin'] = 0;
        } else {
            $return['customlinksignin'] = 1;
        }
        if (empty($options['sf_extension']['toolbar']['customlinkurl'])) {
            $return['customlinkurl'] = '';
        } else {
            $return['customlinkurl'] = $options['sf_extension']['toolbar']['customlinkurl'];
        }
        if (empty($options['sf_extension']['toolbar']['customlinktext'])) {
            $return['customlinktext'] = '';
        } else {
            $return['customlinktext'] = $options['sf_extension']['toolbar']['customlinktext'];
        }
        if (empty($options['sf_extension']['toolbar']['color'])) {
            $return['color'] = '#535353';
        } else {
            $return['color'] = $options['sf_extension']['toolbar']['color'];
        }
        if (empty($options['sf_extension']['toolbar']['uicolor'])) {
            $return['uicolor'] = '#FFFFFF';
        } else {
            $return['uicolor'] = $options['sf_extension']['toolbar']['uicolor'];
        }
        if (empty($options['sf_extension']['toolbar']['form_button_color'])) {
            $return['form_button_color'] = '#D84988';
        } else {
            $return['form_button_color'] = $options['sf_extension']['toolbar']['form_button_color'];
        }
        if (empty($options['sf_extension']['toolbar']['form_button_label'])) {
            $return['form_button_label'] = '#FFFFFF';
        } else {
            $return['form_button_label'] = $options['sf_extension']['toolbar']['form_button_label'];
        }

        return $return;
    }
    function sf_toolbar_section1_str()
    {
        echo "
    <span class='section-in-toolbars'>General</span>
	
	";
    }
    function sf_toolbar_section2_str()
    {
        echo "
    <span class='section-in-toolbars'>Toolbar Styling</span>
	
	";
    }
    function sf_toolbar_section3_str()
    {
        echo "
    <span class='section-in-toolbars'>Sign In Form</span>
	
	";
    }
    function sf_toolbar_wp_sign_in_str()
    {
        $options = sf_get_options();
        #echo "<pre>";
        #print_r($options);
        #echo "</pre>";
        $checked = $options['sf_extension']['toolbar']['wp_sign_in'] === 1 ? 'checked="checked"' : '';

        echo "
    <h6>Display the following elements on Superfly top toolbar.</h6>
	<p><label><input id='sf_toolbar_wp_sign_in' name='sf_options[sf_extension][toolbar][wp_sign_in]' class='checkbox' type='checkbox' value='1' {$checked} > Wordpress Sign In/ Sign Out</label></p>
	";
    }

    function sf_toolbar_languages_str()
    {
        $options = sf_get_options();
        $checked = $options['sf_extension']['toolbar']['languages'] === 1 ? 'checked="checked"' : '';
        if(function_exists('icl_get_languages')) {
            echo "<p><label><input id='sf_toolbar_languages' name='sf_options[sf_extension][toolbar][languages]' class='checkbox' type='checkbox' value='1' {$checked} > Language Switcher (WPML)</label></p>";
        }
        else{
            $checked = '';
            echo "<p><label style='color:#ccc;'><input id='sf_toolbar_languages' name='sf_options[sf_extension][toolbar][languages]' class='checkbox' type='checkbox' value='0' {$checked} disabled> Language Switcher (WPML)</label></p>";
        }
    }

    function sf_toolbar_shoppingcart_str()
    {

        $active_plugins = (array) apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
        if (is_multisite()) {
            // get_site_option( 'active_sitewide_plugins', array() ) returns a 'reversed list'
            // like [hello-dolly/hello.php] => 1369572703 so we do array_keys to make the array
            // compatible with $active_plugins
            $active_sitewide_plugins = (array) array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
            // merge arrays and remove doubles
            $active_plugins = (array) array_unique( array_merge( $active_plugins, $active_sitewide_plugins ) );
        }
        $shop_plugins = array (
            'WooCommerce'				=> 'woocommerce/woocommerce.php',
            /*'Jigoshop'					=> 'jigoshop/jigoshop.php',
            'WP e-Commerce'				=> 'wp-e-commerce/wp-shopping-cart.php',
            'eShop'						=> 'eshop/eshop.php',
            'Easy Digital Downloads'	=> 'easy-digital-downloads/easy-digital-downloads.php',*/
        );

        // filter shop plugins & add shop names as keys
        $active_shop_plugins = array_intersect( $shop_plugins, $active_plugins );
        $wpmenucart_shop_check = get_option( 'wpmenucart_shop_check' );



        $options = sf_get_options();
        $checked = $options['sf_extension']['toolbar']['shoppingcart'] === 1 ? 'checked="checked"' : '';
        if ( count($active_shop_plugins) > 0 ) {
            echo "<p><label><input id='sf_toolbar_shoppingcart' name='sf_options[sf_extension][toolbar][shoppingcart]' class='checkbox' type='checkbox' value='1' {$checked} > Shopping Cart (WooCommerce)</label></p>";
        }
        else{
            $checked = '';
            echo "<p><label style='color:#ccc;'><input id='sf_toolbar_shoppingcart' name='sf_options[sf_extension][toolbar][shoppingcart]' class='checkbox' type='checkbox' value='0' {$checked} disabled > Shopping Cart (WooCommerce)</label></p>";
        }
    }

    function sf_toolbar_customlinksignin_str()
    {
        $options = sf_get_options();
        $checked = $options['sf_extension']['toolbar']['customlinksignin'] === 1 ? 'checked="checked"' : '';
        echo "
	<p><label><input id='sf_toolbar_customlinksignin' name='sf_options[sf_extension][toolbar][customlinksignin]' class='checkbox' type='checkbox' value='1' {$checked} > Custom link instead of Wordpress login</label></p>
	";
    }

    function sf_toolbar_customlinkurl_str()
    {
        $options = sf_get_options();
        $val = htmlentities($options['sf_extension']['toolbar']['customlinkurl'], ENT_QUOTES);
        echo "<h6>If you wish to use link to a custom login form or another page</h6>";
        echo "<input placeholder='http://superfly.com/privacy' type='text' size='100' id='sf_toolbar_customlinkurl' value='{$val}' name='sf_options[sf_extension][toolbar][customlinkurl]'>";
    }

    function sf_toolbar_customlinktext_str()
    {
        $options = sf_get_options();
        $val = htmlentities($options['sf_extension']['toolbar']['customlinktext'], ENT_QUOTES);
        echo "<input placeholder='Privacy' type='text' size='100' id='sf_toolbar_customlinktext' value='{$val}' name='sf_options[sf_extension][toolbar][customlinktext]'>";
    }
    function sf_toolbar_color_str() {
        $options = sf_get_options();

        echo "<input id='sf_toolbar_color' data-color-format='hex' name='sf_options[sf_extension][toolbar][color]' type='text' value='{$options['sf_extension']['toolbar']['color']}' style='' />
		<script>
				jQuery(function(){
					jQuery('#sf_toolbar_color').ColorPickerSliders(opts)
				});
	</script>
	";
    }
    function sf_toolbar_uicolor_str() {
        $options = sf_get_options();

        echo "<input id='sf_toolbar_uicolor' data-color-format='hex' name='sf_options[sf_extension][toolbar][uicolor]' type='text' value='{$options['sf_extension']['toolbar']['uicolor']}' style='' />
		<script>
				jQuery(function(){
					jQuery('#sf_toolbar_uicolor').ColorPickerSliders(opts)
				});
	</script>
	";
    }
    function sf_toolbar_form_button_color_str() {
        $options = sf_get_options();

        echo "<input id='sf_toolbar_form_button_color' data-color-format='hex' name='sf_options[sf_extension][toolbar][form_button_color]' type='text' value='{$options['sf_extension']['toolbar']['form_button_color']}' style='' />
		<script>
				jQuery(function(){
					jQuery('#sf_toolbar_form_button_color').ColorPickerSliders(opts)
				});
	</script>
	";
    }
    function sf_toolbar_form_button_label_str() {
        $options = sf_get_options();

        echo "<input id='sf_toolbar_form_button_label' data-color-format='hex' name='sf_options[sf_extension][toolbar][form_button_label]' type='text' value='{$options['sf_extension']['toolbar']['form_button_label']}' style='' />
		<script>
				jQuery(function(){
					jQuery('#sf_toolbar_form_button_label').ColorPickerSliders(opts)
				});
	</script>
	";
    }
}
?>