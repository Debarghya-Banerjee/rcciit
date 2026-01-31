<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Foodmania
 */

/**
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

/**
 * Callback function for showing custom comment template.
 * li should not be closed because WordPress will provide the closing the tag..
 *
 * @param array $comment Comment.
 * @param array $args    Args.
 * @param int   $depth   Depth.
 */
function rtp_comment_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
	?>

	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<div id="comment-<?php comment_ID(); ?>" class="rtp-comment-body clearfix">
		<?php do_action( 'foodmania_comment_body_begin' ); ?>
		<div class="rtp-comment-user-profile clearfix">
			<?php echo get_avatar( $comment, BP_AVATAR_THUMB_WIDTH ); ?>
			<div class="rtp-comment-meta">
					<span class="rtp-comment-author">
						<?php comment_author_link(); ?>
					</span>
				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
						<?php
						/* translators: 1. is comment date, 2. is comment time */
						printf( esc_html__( '%1$s at %2$s', 'foodmania' ), esc_html( get_comment_date() ), esc_html( get_comment_time() ) );
						?>
					</a>
				</div>
			</div>
		</div> <!-- rtp-comment-user-profile -->
		<div class="rtp-main-comment">
			<?php
			if ( '0' === $comment->comment_approved ) {
				?>
				<div class="rtp-awaiting-moderation">
					<em>"<?php esc_html_e( 'Your comment is awaiting moderation.', 'foodmania' ); ?>"</em>
				</div>
				<?php
			}
			?>
			<div class="comment_text"><?php comment_text(); ?></div>
			<div class="rtp-comment-footer">
					<span class="replyback">
						<?php
						comment_reply_link(
							apply_filters(
								'foodmania_comment_reply_link_args',
								array(
									'before'     => '',
									'after'      => '',
									'reply_text' => sprintf( '%s <span>&darr;</span>', __( 'Reply', 'foodmania' ) ),
									'depth'      => $depth,
									'max_depth'  => $args['max_depth'],
								)
							)
						);
						?>
					</span>
				<span class="rtp-edit-comment">
						<?php edit_comment_link( __( 'Edit', 'foodmania' ) ); ?>
					</span>
			</div>
		</div>
		<?php do_action( 'foodmania_comment_body_end' ); ?>
	</div>  <!-- rtp_comment_body -->

	<?php
}
?>

<div id="comments" class="comments-area">
	<?php
	do_action( 'foodmania_comment_area_start' );

	if ( have_comments() ) {
		?>

		<h2 class="comments-title">
			<?php
			if ( ! is_rtl() ) {
				/* translators: %s is comments number */
				printf( esc_html__( 'Comments (%s)', 'foodmania' ), esc_html( get_comments_number() ) );
			} else {
				/* translators: %s is comments number */
				printf( esc_html__( 'Comments %s', 'foodmania' ), esc_html( get_comments_number() ) );
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			$args = apply_filters(
				'foodmania_list_comments_args',
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 56,
					'callback'    => 'rtp_comment_callback',
				)
			);
			wp_list_comments( $args );
			?>
		</ol><!-- .comment-list -->


		<?php
	} // Have Comments.

	foodmania_comment_nav();

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'foodmania' ); ?></p>
		<?php
	}

	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );

	$fields = array(
		'author' => sprintf( '<p class="comment-form-author"><label for="author">%s</label><input id="author" name="author" placeholder="%s%s" type="text" value="%s" size="30" %s /></p>', esc_html__( 'Name', 'foodmania' ), esc_attr__( 'Name', 'foodmania' ), ( $req ? ' *' : '' ), esc_attr( $commenter['comment_author'] ), $aria_req ),

		'email'  => sprintf( '<p class="comment-form-email"><label for="email">%s</label><input id="email" name="email" placeholder="%s%s" type="text" value="%s" size="30" %s /></p>', esc_html__( 'Email', 'foodmania' ), esc_attr__( 'Email', 'foodmania' ), ( $req ? ' *' : '' ), esc_attr( $commenter['comment_author_email'] ), $aria_req ),

		'url'    => sprintf( '<p class="comment-form-url"><label for="url">%s</label><input id="url" name="url" placeholder="%s" type="text" value="%s" size="30" /></p>', esc_html__( 'Website', 'foodmania' ), esc_attr__( 'Website', 'foodmania' ), esc_attr( $commenter['comment_author_url'] ) ),
	);

	?>

	<?php
	comment_form(
		apply_filters(
			'foodmania_comment_form_args',
			array(
				'fields'      => $fields,
				'title_reply' => __( 'Leave a comment', 'foodmania' ),
			),
			$fields
		)
	);

	do_action( 'foodmania_comment_area_end' );
	?>
</div><!-- .comments-area -->
