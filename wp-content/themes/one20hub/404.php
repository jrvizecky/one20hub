<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package ONE20Hub
 */

get_header(); ?>

	<div id="page" style="background-image:url(https://one20.com/wp-content/themes/one20hub/image/404-bg.jpg);background-repeat: no-repeat;
    background-size: cover;">
		<main id="main" class="footer-page">
			<div class="wrapper">
				<div class="grid">
					<section class="error-404 not-found">
						<header class="page-header footer-header">
							<h1 class="page-title"><?php esc_html_e( 'WELL, THIS IS TRUCKED-UP.', 'one20hub' ); ?></h1>
						</header><!-- .page-header -->

						<div class="page-content">
							<p><?php esc_html_e( 'Seems we’ve got a bit of work to do to find our 20. Why don’t you give us a shout and we’ll help get you back on the road. (Bonus points if you tell us how you arrived at this detour!)', 'one20hub' ); ?></p>
							<p class="button-link">
							<a class="button-yellow" href="/contact">Connect With Us</a>
							</p>
						</div><!-- .page-content -->
					</section><!-- .error-404 -->
				</div><!-- .grid -->
			</div><!-- .wrapper -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
