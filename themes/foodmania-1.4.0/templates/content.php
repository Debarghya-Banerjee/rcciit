<?php
/**
 * Used for listing posts.
 *
 * @package Foodmania
 */

$post_meta_values   = foodmania_post_meta_values();
$content_class      = ( get_post_type() === 'post' && $post_meta_values ) ? 'large-10 medium-10 column' : false;
$entry_header_class = $post_meta_values ? 'large-2 medium-2 column' : false;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'rtp-list-post clearfix' ); ?>>

	<?php do_action( 'foodmania_post_listing_article_begin' ); // Post thumbnail hooked here. ?>

	<div class="row main-article-content">
		<?php
		if ( $post_meta_values ) {
			?>
			<header class="entry-header <?php echo esc_attr( $entry_header_class ); ?>">
				<div class="entry-meta">
					<?php foodmania_post_meta(); ?>
				</div><!-- .entry-meta -->
			</header><!-- .entry-header -->
			<?php
		}
		?>

		<div class="entry-content <?php echo esc_attr( $content_class ); ?>">

			<?php
			the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			do_action( 'foodmania_after_post_list_title' );

			the_excerpt(
				sprintf(
				/* translators: %s: Name of current post */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'foodmania' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				)
			);
			?>

			<?php
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'foodmania' ),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->
		<?php do_action( 'foodmania_post_listing_article_end' ); ?>
	</div>

</article><!-- #post-## -->
