<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ONE20Hub
 */

get_header(); ?>

    <main id="main" class="site-full-width top-margin">
        <div class="wrapper">
            <div class="grid">
                <div class="col-9_sm-12 primary-content">
					<?php
                        while ( have_posts() ) : the_post();

                            get_template_part( 'template-parts/content', get_post_type() );
                            ?>

                            <div class="recent-posts">
                                <h2>Recent Posts</h2>
                                <ul>
                                    <?php
                                    $args = array( 'numberposts' => '5' );
                                    $recent_posts = wp_get_recent_posts($args);
                                    foreach( $recent_posts as $recent ){
                                        echo '<li><a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a> </li> ';
                                    }
                                    wp_reset_query();
                                    ?>
                                </ul>
                            </div>

                            <?php
                            //the_post_navigation();

                            // If comments are open or we have at least one comment, load up the comment template.
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;

                        endwhile; // End of the loop.
                    ?>

                </div>
                <div class="col-3 sm-hidden posts-ad-container">
                    <img src="https://dummyimage.com/200x500/d8d8d8/000000" />
                    <img src="https://dummyimage.com/200x500/d8d8d8/000000" />
                </div>
            </div>
        </div>
    </main><!-- #main -->

<?php
//get_sidebar();
get_footer();