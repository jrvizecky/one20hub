<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ONE20Hub
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>

    <script>
        dataLayer = window.dataLayer = window.dataLayer || [];
        <?php if(is_user_logged_in()):?>
            dataLayer.push({
            'user.id': localStorage.getItem('uid'),
            'user.email': localStorage.getItem('email')
        });
        <?php elseif(!is_user_logged_in()):?>
            if (localStorage.getItem('uid') !== null) {
                localStorage.removeItem('uid');
                localStorage.removeItem('email');
            }
        <?php endif; ?>
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-W5JRLM7');</script>
    <!-- End Google Tag Manager -->


</head>


<body <?php body_class(); ?>>

<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=933196776805336&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W5JRLM7"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<div id="page" class="site">
    
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'one20hub' ); ?></a>

    <header id="masthead" class="site-header">

        <div class="site-branding">
	        <?php if(!is_user_logged_in()):?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <img src="/wp-content/themes/one20hub/image/logo.svg" class="header-logo" data-event-category="internal_link" data-event-action="home-logo-clicked">
                </a>
	        <?php elseif(is_user_logged_in()):?>
                <a href="<?php echo esc_url( home_url( '/member-home' ) ); ?>" rel="home">
                    <img src="/wp-content/themes/one20hub/image/logo.svg" class="header-logo" data-event-category="internal_link" data-event-action="home-logo-clicked">
                </a>
            <?php endif; ?>
            <nav class="top-menu">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'top-menu',
				) );
				?>
            </nav>
            <div class="header-buttons">
                <?php if(!is_user_logged_in()):?>

                    <a href="<?php echo esc_url( home_url() ); ?>/member-login/" class="login button-transparent" data-event-category="internal_link" data-event-action="go_to_login">Log In</a>
                    <a href="<?php echo esc_url( home_url() ); ?>/member-login/" class="signup button-yellow" data-event-category="internal_link" data-event-action="go_to_signup">Sign Up</a>

                <?php elseif(is_user_logged_in()):?>
                    <a href="<?php echo esc_url( home_url() ); ?>/my-profile" class="login button-transparent" data-event-category="internal_link" data-event-action="go_to_my_profile">My Profile</a>
                    <a href="<?php echo wp_logout_url( home_url() ); ?>" class="signup button-yellow" data-event-category="internal_link" data-event-action="logout_clicked">Log Out</a>
                <?php endif; ?>
            </div>
            <div class="toggle">
                <a href="#" id="custom_toggle"><i class="fas fa-bars"></i></a>
            </div>
            <div class="clear"></div>
        </div><!-- .site-branding -->

        <?php if ( is_singular( 'partners' ) or is_page_template('page-whats_included.php')) { ?>
        <div class="secondary-nav">
            <div class="pn-ProductNav_Wrapper">
                <nav id="pnProductNav" class="pn-ProductNav">
                    <div id="pnProductNavContents" class="pn-ProductNav_Contents">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'menu-6',
                            'menu_id'        => 'secondary-partners-menu',
                        ) );
                        ?>
                    </div>
                </nav>
                <button id="pnAdvancerLeft" class="pn-Advancer pn-Advancer_Left" type="button">
                    <svg class="pn-Advancer_Icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 551 1024"><path d="M445.44 38.183L-2.53 512l447.97 473.817 85.857-81.173-409.6-433.23v81.172l409.6-433.23L445.44 38.18z"/></svg>
                </button>
                <button id="pnAdvancerRight" class="pn-Advancer pn-Advancer_Right" type="button">
                    <svg class="pn-Advancer_Icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 551 1024"><path d="M105.56 985.817L553.53 512 105.56 38.183l-85.857 81.173 409.6 433.23v-81.172l-409.6 433.23 85.856 81.174z"/></svg>
                </button>
            </div>
        </div>

	        <?php
	        $hero = get_field('hero');
	        if (!$hero['image']) { ?>
                <div style="position: relative; height: 57px;"></div>
            <?php } ?>
            <script src="/wp-content/themes/one20hub/js/horizontal-scroll.js" type="text/javascript"></script>
        <?php } ?>


<style>


</style>



		<?php
		$hero = get_field('hero');
		if( $hero['image'] ): ?>
            <div class="hero-image">
                <div class="hero-text">
                    <h1><?php echo $hero['title']; ?></h1>
                    <p><?php echo $hero['tagline']; ?></p>
                    <?php if ($hero['button_link']) : ?>
                        <a href="<?php echo $hero['button_link'] ?>" class="button-yellow"  id="<?php echo $hero['id']; ?>" data-event-category="<?php echo $hero['category']; ?>" data-event-action="<?php echo $hero['action']; ?> "<?php if (($hero['open_link_in_new_tab']) == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $hero['button_text']; ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <style type="text/css">
                .hero-image {
                    background-image: url(<?php echo $hero['image']; ?>);
                }
                .hero-text {
                    color: <?php echo $hero['font_color']; ?>;
                }
            </style>


		<?php elseif (!$hero['image'] && !$hero['title']): ?>



		<?php elseif (!$hero['image']) :?>

        <div class="hero-image" style="background: none; height: 251px;">
            <div class="hero-text" style="top: 75%;">
                <h1 style="text-shadow: none;"><?php echo $hero['title']; ?></h1>
                <p style="text-shadow: none;"><?php echo $hero['tagline']; ?></p>
	            <?php if ($hero['button_link']) : ?>
                    <a href="<?php echo $hero['button_link'] ?>" class="button-yellow"  id="<?php echo $hero['id']; ?>" data-event-category="<?php echo $hero['category']; ?>" data-event-action="<?php echo $hero['action']; ?> "<?php if (($hero['open_link_in_new_tab']) == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $hero['button_text']; ?></a>
	            <?php endif; ?>
            </div>
        </div>



		<?php endif; ?>


    </header><!-- #masthead -->

    <div id="content" class="site-content">
