<?php
/**
 * Template Name: Landing Page Template
 */





get_header(); ?>

<style>
    body {
        background: url(<?php the_field('desktop_bg_image'); ?>);
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
    }
    @media (max-width: 48em) {
        body {
            background: url(<?php the_field('mobile_bg_image'); ?>);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
    }
</style>

		<main id="main" class="site-full-width">
            <div class="wrapper" style="padding-top: 60px;">
                <div class="grid-middle sm-hidden" style="min-height: 700px; height: 91vh;">
                    <div class="col-8_md-6">
                        <?php the_content();?>
                    </div>
                    <div class="col-4_md-6">
                        <div style="background: #fff; padding: 15px; border-radius: 5px;">
                            <h2><?php the_field('secondary_title'); ?></h2>
	                        <?php echo do_shortcode('[custom-login-form]'); ?>
                            <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
                <div class="grid hideme" style="height: 90vh;">
                    <div class="col-12" style="padding-top: 40px;">
                        <?php the_content(); ?>
                        <div class="bottom-signup">
                            <h2><a href="/member-login"><?php the_field('secondary_title'); ?></a></h2>
                        </div>
                    </div>
                </div>
            </div>
		</main><!-- #main -->

<?php
//get_sidebar();
get_footer();

