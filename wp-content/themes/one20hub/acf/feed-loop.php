<div class="hero-image">
	<div class="hero-text">
		<h1>Channel</h1>
	</div>
</div>

<script>
    $(document).ready(function(){
        $(".nav-button").click(function () {
            $(".nav-button,.categories ul").toggleClass("open");
        });
    });
</script>

<div class="category-nav">
	<div class="category-title">Categories</div>
    <nav id="category-navigation" class="categories">
        <div class="nav-button"></div>
        <?php
	    wp_nav_menu( array(
		    'theme_location' => 'menu-7',
		    'menu_id'        => 'channel-category-menu',
	    ) );
	    ?>


    </nav><!-- #site-navigation -->

</div>


<main id="main" class="feed-page">
	<div class="wrapper">



        <div class="channel-selection">
			<?php if (is_author()) : ?>
                <span class="posts-by">Posts By:</span>
                <span class="channel-selected">
                  <?php the_author(); ?>
                    <a href="/channel">
                      <i class="fas fa-times-circle"></i>
                  </a>
              </span>
			<?php endif; ?>
			<?php if (is_category()) : ?>
                <span class="posts-by">Posts In:</span>
                <span class="channel-selected">
                  <?php single_cat_title(); ?>
                    <a href="/channel">
                      <i class="fas fa-times-circle"></i>
                  </a>
              </span>
			<?php endif; ?>
        </div>





        <div class="grid articles-container">

			<div id="loginModal" class="modal">
				<?php echo do_shortcode( '[custom-login-form]' ); ?>
			</div>
            <div class="col-9_sm-12">
                <div class="grid">
			<?php
			//$args = array();
			//$the_query = new WP_Query( $args );
			?>

			<?php if ( have_posts()  ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>


					<div id="post-<?php the_ID(); ?>" class="col-4_xs-12_sm-4_md-4 post-id"
                        <?php if (get_field('featured_post')): ?>style="border: 4px solid #e7ba21;"
                        <?php else :?> <?php endif; ?>
                    >

						<article>
							<div class="entry-content">
								<div class="post-info">
									<div class="author-image">
										<?php
										$author_id = get_the_author_meta('ID');
										$author_image = get_field('author_image', 'user_'. $author_id );
										$size = 'thumbnail'; // (thumbnail, medium, large, full or custom size)

										if( $author_image ) {

											echo wp_get_attachment_image( $author_image, $size );

										}

										?>

                                    </div>
									<p class="author">
										<?php
										$author_url = get_the_author_meta( 'url' );
										?>
										<a class="author-link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
											<?php the_author(); ?></a>
									</p>
									<p class="date"> <?php echo get_the_date(); ?></p>
								</div><!-- .post-info -->

								<div class="post-body">
									<?php
									// Original Article Permalink from RSS Aggregator
									$link = WPRSS_FTP_Meta::get_instance()->get_meta( $post->ID, 'wprss_item_permalink', false );
									?>
									<?php if ( is_user_logged_in() ) { ?>
									<?php if ( $link ) { ?>
									<a href="<?php echo $link; ?>" target="_blank">
										<?php } else { ?>
										<a href="<?php the_permalink(); ?>">
											<?php } ?>
											<?php the_post_thumbnail( 'feed-featured', array( 'class' => 'partner-archives' ) ); ?>
										</a>
										<?php } else { ?>
											<a href="#loginModal" rel="modal:open">
												<?php the_post_thumbnail( 'partner-archive', array( 'class' => 'partner-archives' ) ); ?>
											</a>
										<?php } ?>

										<h2 class="post-title">
											<?php if ( is_user_logged_in() ) { ?>
											<?php if ( $link ) { ?>
											<a href="<?php echo $link; ?>" target="_blank">
												<?php } else { ?>
												<a href="<?php the_permalink(); ?>">
													<?php } ?>
													<?php the_title(); ?>
												</a>
												<?php } else { ?>
													<a href="#loginModal" rel="modal:open">
														<?php the_title(); ?>
													</a>
												<?php } ?>
										</h2>

										<?php the_excerpt(); ?>

										<div class="category-channels">
                                            <?php echo get_the_category_list(); ?>
                                        </div>


								</div>
                                <div class="social-wrap">
                                    <div class="left">
                                        <?php
	                                    //get_favorites_button();
	                                    the_favorites_button();
	                                    ?>
                                    </div>
                                    <div class="right">
                                        <a href="#socialModal-<?php the_ID(); ?>" rel="modal:open">
                                            <span class="icon"> <i class="fas fa-share"></i></span> Share
                                        </a>
                                        <div id="socialModal-<?php the_ID();?>" class="modal socialModal" style="width: 290px;">
                                            <h3><?php the_title(); ?></h3>
                                            <div class="social-share">
                                                <a onclick="window.open('http://twitter.com/share?url=<?php the_permalink(); ?>&amp;text=<?php the_title(); ?>', 'newwindow', 'width=600,height=500'); return false;"
                                                   class="share-btn twitter">
                                                    <img src="http://dev.one20.com/wp-content/uploads/2018/05/Twitter-1.png" alt="">
                                                </a>
                                                <a onclick="window.open( 'http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>', 'newwindow', 'width=600,height=500' ); return false;"
                                                   class="share-btn facebook">
                                                    <img src="http://dev.one20.com/wp-content/uploads/2018/05/Facebook-2.png" alt="">
                                                </a>
                                                <?php /*<a onclick="window.open( 'http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>&amp;summary=', 'newwindow', 'width=600,height=500' ); return false;"
                                                   href="http://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>&amp;summary=&amp;source=<?php the_permalink(); ?>"
                                                   class="share-btn linkedin">
                                                    <img src="http://dev.one20.com/wp-content/uploads/2018/05/linkedin.png" alt="">
                                                </a>*/?>
                                                <a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                                                    <img src="http://dev.one20.com/wp-content/uploads/2018/05/Google-1.png" alt="">
                                                </a>
                                                <a href="mailto:?subject=<?php the_title(); ?>&amp;body=I enjoyed reading this article from <?php the_author(); ?>: <?php the_permalink(); ?>"
                                                   target="_self" class="share-btn email">
                                                    <img src="http://dev.one20.com/wp-content/uploads/2018/05/Email-1.png" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="center">
                                        <div id="commentModal-<?php the_ID(); ?>" class="modal">
		                                    <?php
		                                    global $withcomments;
		                                    $withcomments = true;
		                                    comments_template( '', true );
		                                    ?>
                                        </div>

	                                    <?php if ( is_user_logged_in() ) { ?>
                                            <a href="#commentModal-<?php the_ID(); ?>" rel="modal:open">
				                                    <?php //comments_number( 'No Comments', '1 Comment', '% Comments' ); ?>
                                                    <span class="icon"><i class="fas fa-comments"></i> </span> Comment
                                                </a>
	                                    <?php } else { ?>
                                            <a href="#loginModal" rel="modal:open">
				                                    <?php //comments_number( 'No Comments', '1 Comment', '% Comments' ); ?>
                                                <span class="icon"><i class="fas fa-comments"></i></span> Comment
                                                </a>
	                                    <?php } ?>
                                    </div>
                                </div>

							</div><!-- .entry-content -->

						</article>

					</div><!-- .post$ID -->


                    <?php endwhile; ?>


                    <?php wp_reset_postdata(); ?>


                    <?php else : ?>

                        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>

                    <?php endif; ?>

                </div>
                <div class="pagination">
		            <?php
		            global $wp_query;

		            $big = 999999999; // need an unlikely integer

		            echo paginate_links( array(
			            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			            'format' => '?paged=%#%',
			            'current' => max( 1, get_query_var('paged') ),
			            'total' => $wp_query->max_num_pages
		            ) );
		            ?>
		    </div>
        </div><!--- .col-8 --->
        <div class="col-3_sm-12">
            <?php get_sidebar(); ?>
        </div>

        </div><!-- .grid -->

	</div><!-- .wrapper -->

</main><!-- #main -->

<script>
    $("#map").toggle();
</script>