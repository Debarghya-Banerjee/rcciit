<?php
/**
 * Loads content on single page.
 *
 * @package Foodmania
 */

$post_meta_values   = foodmania_post_meta_values();
$content_class      = ( get_post_type() === 'post' && $post_meta_values ) ? 'large-10 medium-10 column' : false;
$entry_header_class = $post_meta_values ? 'large-2 medium-2 column' : false;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>

	<?php the_post_thumbnail(); ?>

	<div class="row main-article-content">
		<?php
		if ( $post_meta_values ) {
			?>
			<header class="entry-header <?php echo esc_attr( $entry_header_class ); ?>">
				<div class="entry-meta">
					<?php foodmania_post_meta(); ?>
				</div>
			</header>
			<?php
		}
		?>

		<div class="entry-content <?php echo esc_attr( $content_class ); ?>">
			<?php
			the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' );

			do_action( 'foodmania_after_single_title' );

			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'foodmania' ),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->
		<div class="row main-article-content">

</article><!-- #post-## -->
