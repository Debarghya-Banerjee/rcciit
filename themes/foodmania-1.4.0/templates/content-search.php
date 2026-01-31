<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Foodmania
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'rtp-list-post clearfix' ); ?>>
	<?php
	if ( has_post_thumbnail() ) {
		?>
		<a href="<?php the_permalink(); ?>" class="rtp-featured-image">
			<?php the_post_thumbnail( 'full' ); ?>
		</a>
		<?php
	}

	if ( 'post' === get_post_type() ) {
		?>
		<header class="entry-header large-2 medium-2 column">
			<div class="entry-meta">
				<?php foodmania_post_meta(); ?>
			</div>
		</header>
		<?php
	}
	?>

	<div class="entry-content <?php echo get_post_type() === 'post' ? 'large-10 medium-10 column' : false; ?>">

		<?php
		the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' );

		the_excerpt(
			sprintf(
			/* translators: %s: Name of current post */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'foodmania' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'foodmania' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
