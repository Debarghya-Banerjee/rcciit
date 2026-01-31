<?php
/**
 * Used for showing section 2 on home page
 *
 * @package Foodmania
 */

$post_ids           = get_theme_mod( 'home_section_2_page' );
$thumb1             = get_theme_mod( 'home_section_2_thumb_1' );
$thumb2             = get_theme_mod( 'home_section_2_thumb_2' );
$post_title         = get_the_title( $post_ids );
$thumb_exists_class = ( $thumb2 || $thumb1 ) ? 'large-6 medium-6 small-12 column' : false;
$about_class        = $thumb_exists_class ? 'large-6 medium-6 small-12 column' : false;
$thumb_exists_class = ( $post_ids ) ? $thumb_exists_class : false;

if ( $post_ids ) {

	$args = array(
		'post__in'    => array( $post_ids ),
		'post_status' => 'publish',
		'post_type'   => 'page',
	);

	$the_query    = new WP_Query( apply_filters( 'foodmania_section2_post_args', $args ) );
	$section_desc = get_theme_mod( 'home_section_2_description' );

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			?>
			<?php the_title( '<h2 class="rtp-special-title aligncenter">', '</h2>' ); ?>

			<div class="rtp-about-site-content <?php echo esc_attr( $about_class ); ?>">
				<header class="rtp-section-2-title">
					<?php
					if ( $section_desc ) {
						?>
						<p class="rtp-heading-description"><?php printf( '%s', esc_html( $section_desc ) ); ?></p>
						<?php
					}
					?>
				</header>
				<div class="rtp-content">
					<?php
					if ( ! empty( trim( get_the_content() ) ) ) {
						?>
						<p><?php echo esc_html( trim( wp_html_excerpt( get_the_content(), 450, '&hellip;' ) ) ); ?></p>
						<?php
					}
					?>
					<a class="rtp-readmore" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php esc_html_e( 'Read More', 'foodmania' ); ?></a>
				</div>
			</div>
			<?php
		}
	}

	wp_reset_postdata();
}

if ( $thumb2 || $thumb1 ) {
	?>
	<div class="rtp-about-site-images <?php echo esc_attr( $thumb_exists_class ); ?>">
		<?php if ( $thumb1 ) { ?>
			<img class="medium-6" src="<?php echo esc_url( $thumb1 ); ?>" alt="<?php echo esc_attr( $post_title ); ?>">
		<?php } ?>
		<?php if ( $thumb2 ) { ?>
			<img class="medium-6" src="<?php echo esc_url( $thumb2 ); ?>" alt="<?php echo esc_attr( $post_title ); ?>">
		<?php } ?>
	</div>
	<?php
}
