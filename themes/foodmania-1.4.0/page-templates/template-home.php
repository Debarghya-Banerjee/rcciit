<?php
/**
 * Template Name: Home Template
 * Used for showing the home page template
 *
 * @package Foodmania
 */

get_header(); ?>

	<main id="main" class="rtp-main-content clearfix" role="main">

		<?php
		$section_order = foodmania_home_section_order();

		if ( is_array( $section_order ) ) {
			foreach ( $section_order as $key => $value ) {
				$extra_class = ( in_array( $value, array( 'section-3', 'section-5' ), true ) ) ? 'rtp-overlay' : 'row';

				if ( get_theme_mod( "home_section_{$key}_visibility", 1 ) ) {
					if ( 4 === $key && ! function_exists( 'bp_is_active' ) ) {
						continue;
					}
					?>
					<section id="rtp-<?php echo esc_attr( $value ); ?>"
							class="rtp-home-block-section rtp-<?php echo esc_attr( $value ); ?>
							<?php echo esc_attr( $extra_class ); ?>">
						<?php get_template_part( 'templates/home', $value ); ?>
					</section>
					<?php
				}
			}
		}
		?>

	</main><!-- .site-main -->

<?php
get_footer();
