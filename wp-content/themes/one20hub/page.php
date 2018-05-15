<?php
/**
 * The template for displaying all pages
 */


get_header(); ?>


    <main id="main" class="site-full-width">


	    <?php include( 'acf/flexible-content-fields.php' ); ?>

        <?php /*
        <div class="wrapper">
            <div class="row">
                <div class="col-12">
					<?php
					while ( have_posts() ) : the_post();

						get_template_part( 'template-parts/content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>




                </div>
            </div>
        </div>
         */?>
    </main><!-- #main -->

<?php
//get_sidebar();
get_footer();
