<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ONE20Hub
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php
				one20hub_posted_on();
				one20hub_posted_by();
			?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
	</header><!-- .entry-header -->

	<?php one20hub_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'one20hub' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'one20hub' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
        <div class="grid">
            <div class="col-6">
                <?php one20hub_entry_footer(); ?>
            </div>
            <div class="col-6 social-share">
                <div class="fb-like" data-href="<?php the_permalink();?>" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="false"></div>
                <!-- Twitter -->
                <a onclick="window.open('http://twitter.com/share?url=<?php the_permalink();?>&amp;text=<?php the_title();?>', 'newwindow', 'width=600,height=500'); return false;" class="share-btn twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <!-- Facebook -->
                <a onclick="window.open( 'http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink();?>&amp;title=<?php the_title(); ?>', 'newwindow', 'width=600,height=500' ); return false;" class="share-btn facebook">
                    <i class="fab fa-facebook"></i>
                </a>
                <!-- LinkedIn -->
                <a onclick="window.open( 'http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title();?>&amp;summary=', 'newwindow', 'width=600,height=500' ); return false;" href="http://www.linkedin.com/shareArticle?url=<?php the_permalink();?>&amp;title=<?php the_title();?>&amp;summary=&amp;source=<?php the_permalink();?>" class="share-btn linkedin">
                    <i class="fab fa-linkedin"></i>
                </a>
                <!-- Email -->
                <a href="mailto:?subject=<?php the_title();?>&amp;body=I enjoyed reading this article from <?php the_author(); ?>: <?php the_permalink();?>" target="_self" class="share-btn email">
                    <i class="fas fa-envelope"></i>
                </a>
            </div>
        </div>

        <!--- Comments in Modal / Need to get working with Ajax / https://rudrastyh.com/wordpress/ajax-comments.html ---->
<!--        <div id="ex1" class="modal">-->
<!--			--><?php //comments_template(); ?>
<!--        </div>-->
<!--        <p><a href="#ex1" rel="modal:open">Open Modal</a></p>-->

	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
