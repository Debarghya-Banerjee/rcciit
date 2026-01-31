<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Foodmania
 */

get_header();
?>

	<div id="primary" class="content-area large-8 medium-12 column">
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
			} // end of the loop.

			do_action( 'foodmania_main_div_page_end' );
			?>
		</main><!-- #main -->
		<?php do_action( 'foodmania_primary_div_page_end' ); ?>
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
