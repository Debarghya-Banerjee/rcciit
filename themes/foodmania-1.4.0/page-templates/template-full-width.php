<?php
/**
 * Template Name: Full Width Template
 * Used for showing full width page.
 *
 * @package Foodmania
 */

get_header(); ?>

<div id="primary" class="content-area large-12 column">

	<?php do_action( 'foodmania_primary_div_page_start' ); ?>

	<main id="main" class="site-main" role="main">
		<?php
		do_action( 'foodmania_main_div_page_start' );
		while ( have_posts() ) {
			the_post();

			get_template_part( 'templates/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		} // End of the loop.
		?>

		<?php do_action( 'foodmania_main_div_page_end' ); ?>

	</main><!-- #main -->

	<?php do_action( 'foodmania_primary_div_page_end' ); ?>

</div><!-- #primary -->

<?php
get_footer();
