<?php
/**
 * Template Name: Whats Included
 */


get_header(); ?>

		<main id="main" class="site-full-width whats-included">
            <div class="wrapper">
                <div class="grid">
                    <div class="col-12">
                        <?php /*
                        while ( have_posts() ) : the_post();

                            get_template_part( 'template-parts/content', 'page' );

                            // If comments are open or we have at least one comment, load up the comment template.
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;

                        endwhile; // End of the loop.
                        */ ?>
	                    <?php
	                    $args = array(
		                    'post_type'   => 'partners',
		                    'post_status' => 'publish',
	                    );

	                    $partners = new WP_Query( $args );
	                    if( $partners->have_posts() ) :
		                    ?>
                          <div class="grid">
			                    <?php
			                    while( $partners->have_posts() ) :
				                    $partners->the_post();
				                    ?>
                                    <div class="col-6_sm-12">
                                        <a href="<?php the_permalink();?>">
                                            <?php the_post_thumbnail('partner-archive', array('class' => 'partner-archives')); ?>
                                        </a>
                                        <h3><a href="<?php the_permalink();?>"><?php the_title(); ?> </a></h3>
                                        <?php the_excerpt() ;?>
                                        <p class="learn-more">
                                            <a href="<?php the_permalink();?>" class="button-yellow" data-event-category="internal_link" data-event-action="to_<?php the_title();?>">
                                                Learn More
                                            </a>
                                        </p>
                                    </div>
			                    <?php
			                    endwhile;
			                    wp_reset_postdata();
			                    ?>
                          </div>
	                    <?php
	                    else :
		                    esc_html_e( 'No partners', 'text-domain' );
	                    endif;
	                    ?>


                    </div>
                </div>
            </div>
		</main><!-- #main -->

<?php
//get_sidebar();
get_footer();
