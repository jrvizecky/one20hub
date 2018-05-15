<?php


namespace LeadpagesWP\Admin\SettingsPages;

use TheLoop\Contracts\SettingsPage;

class LeadpagesLogoutPage implements SettingsPage
{
    public static function getName()
    {
        return get_called_class();
    }

    public function definePage()
    {
        global $leadpagesConfig;
        add_submenu_page('edit.php?post_type=leadpages_post', 'Leadpages Logout', 'Logout', 'manage_options',
          'leadpages-logout', array($this, 'displayCallback'));
    }


    public function displayCallback()
    {
        ?>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700' rel='stylesheet'
              type='text/css'>
        <div class="logout-wrapper">
            <div class="logout_form">
                <div class="form_header">
                    <h1>Are You Sure?</h1>
                </div>
                <h3 style="text-align: center">
                    Need to logout of your Leadpages Account?<br/>
                    Please agree to the items below and click logout.
                </h3>

                <h3>
                    Do you need support?<br/>
                    <a href="https://support.leadpages.net">Please contact our support team before you go.</a>
                </h3>

                <form method="POST" action="admin-post.php">
                    <p><input type="checkbox" name="agree_to_pages_deactive" id="agree_to_pages_deactivation" class="logout_checkbox">
                        <label for="agree_to_pages_deactivation">All Leadpages will be inaccessible.</label></p>

                    <p>
                        <input type="checkbox" name="agree_to_leadboxes_deactive" id="agree_to_leadboxes_deactivation" class="logout_checkbox">
                        <label for="agree_to_leadboxes_deactivation">All Leadboxes will no longer function.</label>
                    </p>

                    <p>
                        <input type="checkbox" name="agree_to_not_remove_items" id="agree_to_not_remove_items" class="logout_checkbox">
						<label for="agree_to_not_remove_items">
						Logging out will not remove my Leadpages and Leadboxes I have setup. <br/>
                        If I log back in with the same account all my pages and boxes will continue to work.</label>
                    </p>
                    <input type="hidden" name="action" value="leadpages_login_form_support_logout"/>

                    <div class="button_container">
                        <input type="submit" name="submit_button" value="Log Out" class="logout_button" disabled>
                        <input type="submit" name="cancel_button" value="Cancel" class="cancel_button">
                    </div>
                </form>
                <script>
					var numberChecked = 0,
						isBtnDisabled = true;

                    jQuery('.logout_checkbox').change(function(e) {
                        var isChecked = jQuery(this).attr('checked');
                        if (isChecked == 'checked') {
                            numberChecked++;
                        } else {
                            numberChecked--;
                        }

						isBtnDisabled = numberChecked != 3; 
						jQuery('.logout_button').prop('disabled', isBtnDisabled);
                    });

                </script>
            </div>
        </div>
        <?php
    }

    public function registerPage()
    {
        add_action('admin_menu', array($this, 'definePage'));

        add_action('admin_post_leadpages_login_form_support_logout', array($this, 'leadpages_support_log_user_out'));
    }

    public function leadpages_support_log_user_out()
	{
		$url = admin_url() . 'edit.php?post_type=leadpages_post';
        if (isset($_POST['submit_button'])) {
            setcookie('leadpagesLoginCookieGood', '', time() - 3600);
            delete_option('leadpages_security_token');
            $url = admin_url().'admin.php?page=Leadpages';
        } 

		wp_redirect($url);
    }

}
