
<?php
/**
 * Template Name: Ninja Form with Login Modal
 */


get_header(); ?>

<div class="wrapper">
    <main class="main" style="margin-top: 100px;">
        <div class="grid">
            <div class="col-12">
                <img src="<?php the_field('top_image'); ?>" style="margin-bottom: 5px;">
            </div>
            <div class="col-12" style="background: #E7BA21; padding: 20px; margin-bottom: 40px;">
                <p style="margin: 0; padding: 0; font-weight: 500;"><?php the_field('yellow_box_blurb') ;?></p>
            </div>
            <div class="col-6_sm-12">
               <?php echo do_shortcode( ''.get_field('form_shortcode').'' ); ?>
            </div>
            <div class="col-6_sm-12">
			    <?php the_content(); ?>
                <?php if(!is_user_logged_in()) :?>
                <div class="blocker">
                <div id="ex1" class="modal" style="display: inline-block;">
	                <?php echo do_shortcode('[custom-login-form]'); ?>
                </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php get_footer();
