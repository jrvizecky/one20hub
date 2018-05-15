<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ONE20Hub
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!

    $comments_args = array(
        // change the title of send button
        'label_submit'=>'Post Comment',
        // change the title of the reply section
        'title_reply'=>'Leave a comment',
        // remove "Text or HTML to be displayed after the set of comment fields"
        'comment_notes_after' => '',
        // redefine your own textarea (the comment body)
        'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="5" aria-required="true"></textarea></p>',
        // logged in as
        'logged_in_as' => ''
    );

    comment_form($comments_args);

    echo '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. Not you? <a href="%3$s" title="Log out of this account">Log out.</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>';

	if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php

//			Might need this later
//			$comment_count = get_comments_number();
//			if ( '1' === $comment_count ) {
//				printf(
//					/* translators: 1: title. */
//					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'one20hub' ),
//					'<span>' . get_the_title() . '</span>'
//				);
//			} else {
//				printf( // WPCS: XSS OK.
//					/* translators: 1: comment count number, 2: title. */
//					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'one20hub' ) ),
//					number_format_i18n( $comment_count ),
//					'<span>' . get_the_title() . '</span>'
//				);
//			}
			?>
		</h2><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'none',
					'short_ping' => true,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'one20hub' ); ?></p>
		<?php
		endif;

	endif; // Check for have_comments().

	?>

</div><!-- #comments -->
