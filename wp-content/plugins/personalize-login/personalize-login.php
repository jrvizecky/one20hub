<?php
/**
 * Plugin Name:       Personalize Login
 * Description:       A plugin that replaces the WordPress login flow with a custom page.
 * Version:           1.0.0
 * Author:            Jarkko Laine
 * License:           GPL-2.0+
 * Text Domain:       personalize-login
 */

class Personalize_Login_Plugin {

	/**
	 * Initializes the plugin.
	 *
	 * To keep the initialization fast, only add filter and action
	 * hooks in the constructor.
	 */

	public $_redirectUrl;

	public function __construct() {

		// Redirects
		add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
		add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
		add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
		add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );

		add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );

		// Handlers for form posting actions
		add_action( 'login_form_register', array( $this, 'do_register_user' ) );

		// Setup
		// add_filter( 'admin_init' , array( $this, 'register_settings_fields' ) );

		// Shortcodes
		add_shortcode( 'custom-login-form', array( $this, 'render_login_form' ) );
		add_shortcode( 'custom-register-form', array( $this, 'render_register_form' ) );

		add_action('wp_head', array($this, 'firebaseui_head'));

		add_action( 'init', array($this, 'pluginScripts' ));
	}

	public function setUrl ($url) {
	    $this->_redirectUrl = $url;
    }

    public function getUrl () {
        return $this->_redirectUrl;
    }

	public function pluginScripts() {
		// wp_enqueue_style( "bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), null, 'all' );
		// wp_enqueue_style( "awesome-build", plugin_dir_url( __FILE__ ) . 'css/build.css', array(), null, 'all' );
		// wp_enqueue_style( "awesome", plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), null ,'all' );
	}


	/**
	 * Plugin activation hook.
	 *
	 * Creates all WordPress pages needed by the plugin.
	 */
	public static function plugin_activated() {
		// Information needed for creating the plugin's pages
		$page_definitions = array(
			'member-login' => array(
				'title' => __( '', 'personalize-login' ),
				'content' => '[custom-login-form]'
			),
			'member-account' => array(
				'title' => __( 'Your Account', 'personalize-login' ),
				'content' => '[account-info]'
			)
		);

		foreach ( $page_definitions as $slug => $page ) {
			// Check that the page doesn't exist already
			$query = new WP_Query( 'pagename=' . $slug );
			if ( ! $query->have_posts() ) {
				// Add the page using the data from the array above
				wp_insert_post(
					array(
						'post_content'   => $page['content'],
						'post_name'      => $slug,
						'post_title'     => $page['title'],
						'post_status'    => 'publish',
						'post_type'      => 'page',
						'ping_status'    => 'closed',
						'comment_status' => 'closed',
					)
				);
			}
		}
	}

	//
	// REDIRECT FUNCTIONS
	//

	public function firebaseui_head()
	{
		echo '<script src="https://www.gstatic.com/firebasejs/4.8.2/firebase-app.js"></script>';
		echo '<script src="https://www.gstatic.com/firebasejs/4.8.2/firebase-auth.js"></script>';
		echo '<script src="https://www.gstatic.com/firebasejs/4.8.2/firebase-database.js"></script>';
		// echo '<script src="https://www.gstatic.com/firebasejs/4.8.2/firebase-firestore.js"></script>';
		// echo '<script src="https://www.gstatic.com/firebasejs/4.8.2/firebase-messaging.js"></script>';
		// echo '<link type="text/css" rel="stylesheet" href="https://cdn.firebase.com/libs/firebaseui/2.6.2/firebaseui.css" />';
		// echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';
	}

	/**
	 * Redirect the user to the custom login page instead of wp-login.php.
	 */
	public function redirect_to_custom_login() {

		error_log( print_r( "redirect_to_custom_login",true ) );

		error_log( print_r( "redirect_to_custom_login REQUEST",true ) );
		error_log( print_r( $_REQUEST, true ) );

        if ( empty($this->_redirectUrl) && isset( $_REQUEST['redirect_to'] )) {
            $this->setUrl($_REQUEST['redirect_to']);
            error_log( print_r( ' redirect_to_custom_login var test: ' . $this->getUrl(),true ) );
        }

		if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			if ( is_user_logged_in() ) {
                error_log( print_r( ' user is logged in ' . $this->_redirectUrl,true ) );
				$this->redirect_logged_in_user( $this->_redirectUrl );
				exit;
			}

			if (empty( $_REQUEST['admin'] ) ) {
				// The rest are redirected to the login page
				$login_url = home_url( 'member-login' );
				if ( ! empty( $_REQUEST['redirect_to'] ) ) {
					$login_url = add_query_arg( 'redirect_to', $_REQUEST['redirect_to'], $login_url );
				}

				if ( ! empty( $_REQUEST['checkemail'] ) ) {
					$login_url = add_query_arg( 'checkemail', $_REQUEST['checkemail'], $login_url );
				}

				wp_redirect( $login_url );
				exit;
			}
			else
			{
				return;
			}
		} else {
			error_log( print_r( "redirect_to_custom_login POST",true ) );
			error_log( print_r( $_POST, true ) );
		}
	}

	/**
	 * Redirect the user after authentication if there were any errors.
	 *
	 * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
	 * @param string            $username   The user name used to log in.
	 * @param string            $password   The password used to log in.
	 *
	 * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
	 */
	public function maybe_redirect_at_authenticate( $user, $username, $password ) {
		error_log( print_r( "maybe_redirect_at_authenticate",true ) );
		error_log( print_r( "maybe_redirect_at_authenticate POST",true ) );
		error_log( print_r( $_POST, true ) );
		error_log( print_r( "maybe_redirect_at_authenticate REQUEST",true ) );
		error_log( print_r( $_REQUEST, true ) );

        $this->_redirectUrl = $_REQUEST['redirectUrl'];

        error_log( print_r( 'maybe_redirect_at_authenticate redirectUrl: ' . $this->_redirectUrl, true ) );

		// Check if the earlier authenticate filter (most likely,
		// the default WordPress authentication) functions have found errors
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

			if ( is_wp_error( $user ) ) {
				$error_codes = join( ',', $user->get_error_codes() );

				// Check whether authenticated user already existent
				if(!username_exists($username)){
					$userdata=array(
						'user_login' => $username,
						'display_name' => $username,
						'user_pass' => $password,
						'nickname' => $username,
						'user_email' => $username
					);
					$user_id = wp_insert_user( $userdata );
					update_user_meta($user_id ,"id",$user_id);

					// user login
					$user = wp_signon(array("user_login"=>$username,"user_password"=>$password),false);

					$this->redirect_logged_in_user($this->_redirectUrl);

				} else {
					$login_url = home_url( 'member-login' );
					$login_url = add_query_arg( 'login', $error_codes, $login_url );

					wp_redirect( $login_url );
					exit;
				}
			}
			else {
				// redirect existent user to manage page or normal home page.
				$this->redirect_logged_in_user($this->_redirectUrl);
			}
		}

		return $user;
	}

	/**
	 * Returns the URL to which the user should be redirected after the (successful) login.
	 *
	 * @param string           $redirect_to           The redirect destination URL.
	 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
	 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
	 *
	 * @return string Redirect URL
	 */
	public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
//        global $user;

        error_log( print_r( "redirect_after_login redirect_to: " . $redirect_to,true ) );
        error_log( print_r( "redirect_after_login requested_redirect_to: " . $requested_redirect_to,true ) );
        error_log( print_r( "redirect_after_login this->_test: " . $this->_redirectUrl,true ) );

        if ( isset( $user->roles ) && is_array( $user->roles ) ) {
            if ( in_array( 'administrator', $user->roles ) ) {
                $this->_redirectUrl = admin_url();
                return $this->_redirectUrl;
            }
	        if ( in_array( 'editor', $user->roles ) ) {
		        $this->_redirectUrl = admin_url();
		        return $this->_redirectUrl;
	        }
            if ( in_array( 'subscriber', $user->roles ) ) {
                    if ( empty($this->_redirectUrl) ) {
                        error_log( print_r( "Going to member home..." . $this->_redirectUrl,true ) );
                        $this->_redirectUrl = 'member-home';
                        return $this->_redirectUrl;
                    } else {
                        $redirect_to = $this->_redirectUrl;
                        if ( strpos( $redirect_to, home_url() ) === false ) {
                            $redirect_to = home_url('member-home');
                        } else {
                            $redirect_to = $this->_redirectUrl;
                        }
                        error_log( print_r( "Going to redirect..." . $redirect_to,true ) );
                        return $redirect_to;
                    }
            }
        }
        return;
	}

	/**
	 * Redirect to custom login page after the user has been logged out.
	 */
	public function redirect_after_logout() {
		error_log( print_r( "redirect_after_logout",true ) );
		$redirect_url = home_url( 'member-login?logged_out=true' );
		wp_redirect( $redirect_url );
		exit;
	}

	/**
	 * Redirects the user to the custom registration page instead
	 * of wp-login.php?action=register.
	 */
	public function redirect_to_custom_register() {
		error_log( print_r( "redirect_to_custom_register",true ) );
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			if ( is_user_logged_in() ) {
				$this->redirect_logged_in_user();
			} else {
				wp_redirect( home_url( 'member-register' ) );
			}
			exit;
		}
	}


	//
	// FORM RENDERING SHORTCODES
	//

	/**
	 * A shortcode for rendering the login form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_login_form( $attributes, $content = null ) {
		error_log( print_r( "render_login_form",true ) );
		// Parse shortcode attributes
		$default_attributes = array( 'show_title' => false );
		$attributes = shortcode_atts( $default_attributes, $attributes );

		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'personalize-login' );
		}

		// Pass the redirect parameter to the WordPress login functionality: by default,
		// don't specify a redirect, but if a valid redirect URL has been passed as
		// request parameter, use it.
		$attributes['redirect'] = '';
		if ( isset( $_REQUEST['redirect_to'] ) ) {
			$attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
		}

		// Error messages
		$errors = array();
		if ( isset( $_REQUEST['login'] ) ) {
			$error_codes = explode( ',', $_REQUEST['login'] );

			foreach ( $error_codes as $code ) {
				$errors []= $this->get_error_message( $code );
			}
		}
		$attributes['errors'] = $errors;

		// Check if user just logged out
		$attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

		// Check if the user just registered
		$attributes['registered'] = isset( $_REQUEST['registered'] );

		// Check if the user just requested a new password
		$attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

		// Check if user just updated password
		$attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';

		// Render the login form using an external template
		return $this->get_template_html( 'login_form', $attributes );
	}

	/**
	 * A shortcode for rendering the new user registration form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_register_form( $attributes, $content = null ) {
		error_log( print_r( "render_register_form",true ) );
		// Parse shortcode attributes
		$default_attributes = array( 'show_title' => false );
		$attributes = shortcode_atts( $default_attributes, $attributes );

		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'personalize-login' );
		} elseif ( ! get_option( 'users_can_register' ) ) {
			return __( 'Registering new users is currently not allowed.', 'personalize-login' );
		} else {
			// Retrieve possible errors from request parameters
			$attributes['errors'] = array();
			if ( isset( $_REQUEST['register-errors'] ) ) {
				$error_codes = explode( ',', $_REQUEST['register-errors'] );

				foreach ( $error_codes as $error_code ) {
					$attributes['errors'] []= $this->get_error_message( $error_code );
				}
			}

			return $this->get_template_html( 'register_form', $attributes );
		}
	}

	/**
	 * Renders the contents of the given template to a string and returns it.
	 *
	 * @param string $template_name The name of the template to render (without .php)
	 * @param array  $attributes    The PHP variables for the template
	 *
	 * @return string               The contents of the template.
	 */
	private function get_template_html( $template_name, $attributes = null ) {
		error_log( print_r( "get_template_html",true ) );
		if ( ! $attributes ) {
			$attributes = array();
		}

		ob_start();

		do_action( 'personalize_login_before_' . $template_name );

		require( 'templates/' . $template_name . '.php');

		do_action( 'personalize_login_after_' . $template_name );

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	//
	// ACTION HANDLERS FOR FORMS IN FLOW
	//

	/**
	 * Handles the registration of a new user.
	 *
	 * Used through the action hook "login_form_register" activated on wp-login.php
	 * when accessed through the registration action.
	 */
	public function do_register_user() {
		error_log( print_r( "do_register_user",true ) );

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$redirect_url = home_url( 'member-register' );

			if ( ! get_option( 'users_can_register' ) ) {
				// Registration closed, display error
				$redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
			} else {
				$email = $_POST['email'];
				$user_id = $_POST['user_id'];
				$first_name = sanitize_text_field( $_POST['first_name'] );
				$last_name = sanitize_text_field( $_POST['last_name'] );
				error_log( print_r( "do_register_user email " + email ,true ) );
				error_log( print_r( "do_register_user user_id " + user_id,true ) );

				$result = $this->register_user( $email, $user_id, $first_name, $last_name );

				if ( is_wp_error( $result ) ) {
					// Parse errors into a string and append as parameter to redirect
					$errors = join( ',', $result->get_error_codes() );
					$redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
				} else {
					// Success, redirect to login page.
					$redirect_url = home_url( 'member-login' );
					$redirect_url = add_query_arg( 'registered', $email, $redirect_url );
				}
			}

			wp_redirect( $redirect_url );
			exit;
		}
	}

	//
	// HELPER FUNCTIONS
	//

	/**
	 * Validates and then completes the new user signup process if all went well.
	 *
	 * @param string $email         The new user's email address
	 * @param string $first_name    The new user's first name
	 * @param string $last_name     The new user's last name
	 *
	 * @return int|WP_Error         The id of the user that was created, or error if failed.
	 */
	private function register_user( $email, $user_id, $first_name, $last_name ) {
		error_log( print_r( "register_user",true ) );
		$errors = new WP_Error();

		// Email address is used as both username and email. It is also the only
		// parameter we need to validate
		if ( ! is_email( $email ) ) {
			$errors->add( 'email', $this->get_error_message( 'email' ) );
			return $errors;
		}

		if ( username_exists( $email ) || email_exists( $email ) ) {
			$errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
			return $errors;
		}

		// Generate the password so that the subscriber will have to check email...
		$password = wp_generate_password( 12, false );

		$user_data = array(
			'user_login'    => $email,
			'user_email'    => $email,
			'user_pass'     => $password,
			'first_name'    => $first_name,
			'last_name'     => $last_name,
			'nickname'      => $first_name,
		);

		$user_id = wp_insert_user( $user_data );
		wp_new_user_notification( $user_id, $password );

		return $user_id;
	}

	/**
	 * Redirects the user to the correct page depending on whether he / she
	 * is an admin or not.
	 *
	 * @param string $redirect_to   An optional redirect_to URL for admin users
	 */
	private function redirect_logged_in_user( $redirect_to = null ) {
	    // tuohy
        error_log( print_r( 'redirect_logged_in_user var redirect_to: ' . $this->_redirectUrl,true ) );

		error_log( print_r( "redirect_logged_in_user",true ) );
		$user = wp_get_current_user();
		if ( user_can( $user, 'delete_other_posts' ) ) {
			if ( $redirect_to ) {
				wp_safe_redirect( $redirect_to );
			} else {
                error_log( print_r( ' Going to admin url: ' . $redirect_to,true ) );
				wp_redirect( admin_url() );

			}
		} else {

            error_log( print_r( ' Going to member-home url: ' . $redirect_to,true ) );
            if ( $redirect_to == '' ) {
                $redirect_to = 'member-home';
            }
            error_log( print_r( ' Going to member-home url: ' . $redirect_to,true ) );
            wp_redirect(  $redirect_to );
		}
	}

	/**
	 * Finds and returns a matching error message for the given error code.
	 *
	 * @param string $error_code    The error code to look up.
	 *
	 * @return string               An error message.
	 */
	private function get_error_message( $error_code ) {
		switch ( $error_code ) {
			// Login errors

			case 'empty_username':
				return __( 'You do have an email address, right?', 'personalize-login' );

			case 'empty_password':
				return __( 'You need to enter a password to login.', 'personalize-login' );

			case 'invalid_username':
				return __(
					"We don't have any users with that email address. Maybe you used a different one when signing up?",
					'personalize-login'
				);

			case 'incorrect_password':
				$err = __(
					"The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
					'personalize-login'
				);
				return sprintf( $err, wp_lostpassword_url() );

			// Registration errors

			case 'email':
				return __( 'The email address you entered is not valid.', 'personalize-login' );

			case 'email_exists':
				return __( 'An account exists with this email address.', 'personalize-login' );

			case 'closed':
				return __( 'Registering new users is currently not allowed.', 'personalize-login' );

			// Lost password

			case 'empty_username':
				return __( 'You need to enter your email address to continue.', 'personalize-login' );

			case 'invalid_email':
			case 'invalidcombo':
				return __( 'There are no users registered with this email address.', 'personalize-login' );

			// Reset password

			case 'expiredkey':
			case 'invalidkey':
				return __( 'The password reset link you used is not valid anymore.', 'personalize-login' );

			case 'password_reset_mismatch':
				return __( "The two passwords you entered don't match.", 'personalize-login' );

			case 'password_reset_empty':
				return __( "Sorry, we don't accept empty passwords.", 'personalize-login' );

			default:
				break;
		}

		return __( 'An unknown error occurred. Please try again later.', 'personalize-login' );
	}
}

// Initialize the plugin
$personalize_login_pages_plugin = new Personalize_Login_Plugin();

// Create the custom pages at plugin activation
register_activation_hook( __FILE__, array( 'Personalize_Login_Plugin', 'plugin_activated' ) );
