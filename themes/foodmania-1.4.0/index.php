<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Foodmania
 */

get_header();
?>

<div id="primary" class="content-area large-8 medium-12 column">
	<?php do_action( 'foodmania_primary_div_index_start' ); ?>
	<main id="main" class="site-main" role="main">

		<?php
		do_action( 'foodmania_main_div_index_start' );

		if ( have_posts() ) {
			/* Start the Loop */
			while ( have_posts() ) {
				the_post();

				/**
				 * Include the Post-Format-specific template for the content.
				* If you want to override this in a child theme, then include a file
				* called content-___.php (where ___ is the Post Format name) and that will be used instead.
				*/
				do_action( 'foodmania_inside_index_loop' );
				get_template_part( 'templates/content', get_post_format() );
			}
		} else {
			get_template_part( 'templates/content', 'none' );
		}
		do_action( 'foodmania_main_div_index_end' );
		?>
	</main><!-- #main -->
	<?php do_action( 'foodmania_primary_div_index_end' ); // Pagination hooked here. ?>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
