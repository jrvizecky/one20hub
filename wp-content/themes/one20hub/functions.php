<?php
/**
 * ONE20Hub functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ONE20Hub
 */

if ( ! function_exists( 'one20hub_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function one20hub_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on ONE20Hub, use a find and replace
		 * to change 'one20hub' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'one20hub', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'partner-archive', 480, 280, true ); // 300 pixels wide (and unlimited height)


		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Top Menu', 'one20hub' ),
		) );

		register_nav_menus( array(
			'menu-2' => esc_html__( 'Footer Menu One', 'one20hub' ),
		) );

		register_nav_menus( array(
			'menu-3' => esc_html__( 'Footer Menu Two', 'one20hub' ),
		) );

		register_nav_menus( array(
			'menu-4' => esc_html__( 'Footer Menu Three', 'one20hub' ),
		) );

		register_nav_menus( array(
			'menu-5' => esc_html__( 'Footer Menu Four', 'one20hub' ),
		) );
		register_nav_menus( array(
			'menu-6' => esc_html__( 'Secondary Partners Menu', 'one20hub' ),
		) );
		register_nav_menus( array(
			'menu-7' => esc_html__( 'Channel Category Menu', 'one20hub' ),
		) );


		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );




	}
endif;
add_action( 'after_setup_theme', 'one20hub_setup' );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function one20hub_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'one20hub' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'one20hub' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'one20hub_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function one20hub_scripts() {
	wp_enqueue_style( 'one20hub-style', get_template_directory_uri() . '/css/style.css' );
	wp_enqueue_style( 'one20hub-firebaseui', get_template_directory_uri() . '/css/firebaseui.2.6.2.min.css', array(), '20151215', false );
//	wp_enqueue_style( 'one20hub-fontawesome', get_template_directory_uri() . '/css/font-awesome.4.7.0.min.css', array(), '20151215', false );

	wp_enqueue_style( 'load-fa', 'https://use.fontawesome.com/releases/v5.0.13/css/all.css' );

	// Replace old jquery 1.12.4 with newer version
	wp_deregister_script( 'jquery-core' );
	wp_enqueue_script( 'jquery-core', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', array(), '20151215', false );
	wp_deregister_script( 'jquery-migrate' );
	wp_register_script( 'jquery-migrate', "https://code.jquery.com/jquery-migrate-3.0.0.min.js", array(), '3.0.0' );

	wp_enqueue_script( 'one20hub-navigation', get_template_directory_uri() . '/js/navigation-min.js', array(), '20151215', true );
//	wp_enqueue_script( 'font-awesome', 'https://use.fontawesome.com/releases/v5.0.6/js/all.js', array(), '20151215', true );


	wp_enqueue_script( 'one20hub-jquerymodal', get_template_directory_uri() . '/js/jquery.modal-min.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'one20hub_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/*
 *
 * One 20 Custom Functions Start Here
 *
 */



///
/// Add ACF Options Page
///
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Global Settings',
		'menu_title'	=> 'Global Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'App Settings',
		'menu_title'	=> 'App Settings',
		'parent_slug'	=> 'theme-general-settings',
	));
}



///
/// Carbon Fields for Menu Items
///
use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('nav_menu', 'Menu Settings')
         ->add_fields(array(
	         Field::make('text', 'category'),
	         Field::make('text', 'action'),
	         Field::make('text', 'location'),
         ));

// Adding Carbon Fields to Menus using Filter
add_filter('nav_menu_link_attributes', 'crb_nav_menu_link_attributes', 10, 4);
function crb_nav_menu_link_attributes($atts, $item, $args, $depth) {
	$category = carbon_get_post_meta($item->ID, 'category');
	$action = carbon_get_post_meta($item->ID, 'action');
	$location = carbon_get_post_meta($item->ID, 'location');
	$atts['data-event-category'] = ! empty( $category ) ? '' . $category . '' : '';
	$atts['data-event-action'] = ! empty( $action ) ? '' . $action . '' : '';
	$atts['data-location'] = ! empty( $location ) ? '' . $location . '' : '';

	return $atts;
}



///
/// A Simple User Logged in Shortcode - http://acroweb.co.uk/user-logged-in-shortcode/
///
function check_user ($params, $content = null){
	//check tha the user is logged in
	if ( is_user_logged_in() ){
		//user is logged in so show the content
		return $content;
	}
	else{
		//user is not logged in so hide the content
		return;
	}
}
//add a shortcode which calls the above function
add_shortcode('loggedin', 'check_user' );



///
/// Shortcode for base url
///
function url_shortcode() {
	return get_bloginfo('url');
}
add_shortcode('url','url_shortcode');
// add_filter('the_content', url_shortcode());



///
///Logout without confirmation
///
add_action('check_admin_referer', 'logout_without_confirm', 10, 2);
function logout_without_confirm($action, $result)
{
	/**
	 * Allow logout without confirmation
	 */
	if ($action == "log-out" && !isset($_GET['_wpnonce'])) {
		$redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : home_url();
		$location = str_replace('&amp;', '&', wp_logout_url($redirect_to));
		header("Location: $location");
		die;
	}
}



///
///  Custom Mobile Partners Endpoint | wp-json/one20/v1/mobile_partners
///
function mobile_partners_get( $request_data ) {

	// setup query argument
	$args = array(
		'post_type' => 'partners',
		'posts_per_page' => '100',
	);

	// get posts
	$posts = get_posts($args);

	// add custom field data to posts array

	$data = array();
	foreach ($posts as $post) {

		$acf = get_fields($post->ID);

		if ($acf["mobile_show_in_menu"] == true) {
			//error_log( print_r( $acf["mobile_show_in_menu"],true ) );
			$item = new stdClass(); // needed to get rid of error
			$item->acf = new stdClass(); // needed to get rid of error
			//	$item->acf->mobile_show_in_menu = $acf["mobile_show_in_menu"];
			if ( ! empty($acf["mobile_active_for"])) {
				$item->acf->mobile_active_for = $acf["mobile_active_for"];
			} else {
				$item->acf->mobile_active_for = [];
			}
			if ( ! empty($acf["mobile_image_ios_pdf"])) {
				$item->acf->mobile_image_ios_pdf = $acf["mobile_image_ios_pdf"];
			} else {
				$item->acf->mobile_image_ios_pdf = '';
			}
			if ( ! empty($acf["mobile_image_android_png"])) {
				$item->acf->mobile_image_android_png = $acf["mobile_image_android_png"];
			} else {
				$item->acf->mobile_image_android_png = '';
			}
			// $item->acf->mobile_image_adroid_svg = ($acf["mobile_image_adroid_svg"] !== false) ? $acf["mobile_image_adroid_svg"] : '';
			$item->acf->mobile_url_link = $acf["mobile_url_link"];
			$item->acf->mobile_section = $acf["mobile_section"];
			$item->acf->mobile_label = $acf["mobile_label"];
			$item->acf->mobile_event_name = $acf["mobile_event_name"];
			$item->acf->mobile_sequence_number = $acf["mobile_sequence_number"];
			$data[] = $item;
		}

	}
	return new WP_REST_Response( $data, 200 );
}

// Register the endpoint
add_action( 'rest_api_init', function () {
//	error_log( print_r('called register routes' ),true );
	register_rest_route( 'one20/v1', '/mobile_partners', array(
			'methods' => 'GET',
			'callback' => 'mobile_partners_get',
		)
	);
});



///
/// Only allow Admins & Editors can access /wp-admin
///
add_action( 'admin_init', 'redirect_non_admin_users' );
function redirect_non_admin_users() {
	if ( ! current_user_can( 'delete_others_posts' ) ) {
		wp_redirect( home_url('/member-home') );
	}
}


///
/// Redirect Non Admins & Editors from home to member-home
///
function redirect_to_memberhome() {
	if ( is_user_logged_in() && ! current_user_can( 'delete_others_posts' ) && is_page('19') ) {
		wp_redirect(home_url('/member-home'));
		exit();
	}
}
add_action('template_redirect', 'redirect_to_memberhome');



///
/// Show Admin Bar for Admins & Editors
///
if (!current_user_can('edit_posts')) {
	add_filter( 'show_admin_bar', '__return_false', PHP_INT_MAX );
}



///
/// Specific login redirect based on page
///
function login_redirect( $redirect_to, $request, $user ) {
	if ( stripos( $_SERVER['HTTP_REFERER'], 'channel' ) !== false ) {
		return 'channel';
	} else {
		return 'member-home';
	}
}
add_filter( 'login_redirect', 'login_redirect', 10, 3 );




/*//
/// Makes RSS Aggregate Posts permalink the original post link
///
add_filter( 'wprss_ftp_link_post_title', '__return_true' );
*/



///
/// Redirect user to page commenting on (not to single.php)
///
add_filter( 'comment_post_redirect', 'redirect_comments', 10,2 );
function redirect_comments( $location, $commentdata ) {
	if(!isset($commentdata) || empty($commentdata->comment_post_ID) ){
		return $location;
	}
	$post_id = $commentdata->comment_post_ID;
	if('post' == get_post_type($post_id)){
		return wp_get_referer()."#comment-".$commentdata->comment_ID;
	}
	return $location;
}





/** ====================================================================================

 * Force read more link to all excerpts whether or not it meets the word length criteria
 * and whether or not it is a custom excerpt

==================================================================================== **/
function excerpt_more_link_all_the_time() {

	// Remove More Link from get_the_excerpt()
	function more_link() {
		return '';
	}
	add_filter('excerpt_more', 'more_link');

	//Force read more link on all excerpts
	function get_read_more_link() {
		$excerpt = get_the_excerpt();
		global $post;
		$link = WPRSS_FTP_Meta::get_instance()->get_meta( $post->ID, 'wprss_item_permalink', false );
		if (is_user_logged_in()){
			if ( $link ) {
				return '<p>' . $excerpt . '..&nbsp;<a target="_blank" class="readmore" href="' . $link . '"> View More</a></p>';
			} else {
				return '<p>' . $excerpt . '..&nbsp;<a class="readmore" href="' . get_permalink( $post->ID ) . '"> View More</a></p>';
			}
		} else {
			if ( $link ) {
				return '<p>' . $excerpt . '..&nbsp;<a href="#loginModal" rel="modal:open" class="readmore"> View More</a></p>';
			} else {
				return '<p>' . $excerpt . '..&nbsp;<a href="#loginModal" rel="modal:open" class="readmore"> View More</a></p>';
			}
		}
	}
	add_filter( 'the_excerpt', 'get_read_more_link' );

}
add_action( 'after_setup_theme', 'excerpt_more_link_all_the_time' );


/*///
/// Ajax Commenting - https://rudrastyh.com/wordpress/ajax-comments.html#comment-3201
///
add_action( 'wp_enqueue_scripts', 'misha_ajax_comments_scripts' );

function misha_ajax_comments_scripts() {

	// I think jQuery is already included in your theme, check it yourself
	wp_enqueue_script('jquery');

	// just register for now, we will enqueue it below
	wp_register_script( 'ajax_comment', get_stylesheet_directory_uri() . '/js/ajax-comment.js', array('jquery') );

	// let's pass ajaxurl here, you can do it directly in JavaScript but sometimes it can cause problems, so better is PHP
	wp_localize_script( 'ajax_comment', 'misha_ajax_comment_params', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php'
	) );

	wp_enqueue_script( 'ajax_comment' );
}

add_action( 'wp_ajax_ajaxcomments', 'misha_submit_ajax_comment' ); // wp_ajax_{action} for registered user
add_action( 'wp_ajax_nopriv_ajaxcomments', 'misha_submit_ajax_comment' ); // wp_ajax_nopriv_{action} for not registered users

function misha_submit_ajax_comment(){
	$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
	if ( is_wp_error( $comment ) ) {
		$error_data = intval( $comment->get_error_data() );
		if ( ! empty( $error_data ) ) {
			wp_die( '<p>' . $comment->get_error_message() . '</p>', __( 'Comment Submission Failure' ), array( 'response' => $error_data, 'back_link' => true ) );
		} else {
			wp_die( 'Unknown error' );
		}
	}

	//Set Cookies
	$user = wp_get_current_user();
	do_action('set_comment_cookies', $comment, $user);

	$comment_depth = 1;
	$comment_parent = $comment->comment_parent;
	while( $comment_parent ){
		$comment_depth++;
		$parent_comment = get_comment( $comment_parent );
		$comment_parent = $parent_comment->comment_parent;
	}

	$GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $comment_depth;

	//
	//Here is the comment template, you can configure it for your website

	$comment_html = '<li ' . comment_class('', null, null, false ) . ' id="comment-' . get_comment_ID() . '">
		<article class="comment-body" id="div-comment-' . get_comment_ID() . '">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					' . get_avatar( $comment, 100 ) . '
					<b class="fn">' . get_comment_author_link() . '</b> <span class="says">says:</span>
				</div>
				<div class="comment-metadata">
					<a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '">' . sprintf('%1$s at %2$s', get_comment_date(),  get_comment_time() ) . '</a>';

	if( $edit_link = get_edit_comment_link() )
		$comment_html .= '<span class="edit-link"><a class="comment-edit-link" href="' . $edit_link . '">Edit</a></span>';

	$comment_html .= '</div>';
	if ( $comment->comment_approved == '0' )
		$comment_html .= '<p class="comment-awaiting-moderation">Your comment is awaiting moderation.</p>';

	$comment_html .= '</footer>
			<div class="comment-content">' . apply_filters( 'comment_text', get_comment_text( $comment ), $comment ) . '</div>
		</article>
	</li>';
	echo $comment_html;

	die();

}*/

