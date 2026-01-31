<?php
/**
 * The template for displaying all single posts.
 *
 * @package Foodmania
 */

get_header();
?>

<div id="primary" class="content-area large-8 medium-12 column">
	<?php do_action( 'foodmania_primary_div_single_start' ); ?>
	<main id="main" class="site-main" role="main">
		<?php
		do_action( 'foodmania_main_div_single_start' );

		while ( have_posts() ) {
			the_post();
			get_template_part( 'templates/content', 'single' );
			do_action( 'foodmania_inside_page_loop' ); // Comments and pagination goes here.
		} // end of the loop.

		do_action( 'foodmania_main_div_single_end' );
		?>
	</main><!-- #main -->
	<?php do_action( 'foodmania_primary_div_single_end' ); ?>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
