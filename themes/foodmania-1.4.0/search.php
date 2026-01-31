<?php
/**
 * The template for displaying search results pages.
 *
 * @package Foodmania
 */

get_header();
?>

<section id="primary" class="content-area large-8 medium-12 column">
	<?php do_action( 'foodmania_primary_div_search_start' ); ?>
	<main id="main" class="site-main" role="main">
		<?php
		do_action( 'foodmania_main_div_search_start' );
		?>
		<?php
		if ( have_posts() ) {
			?>
			<header class="page-header">
				<h1 class="page-title">
					<?php
					/* translators: %s is search term */
					printf( esc_html__( 'Search Results for: %s', 'foodmania' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) {
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'templates/content', 'search' );

			}

			the_posts_navigation();

		} else {

			get_template_part( 'templates/content', 'none' );

		}
		do_action( 'foodmania_main_div_search_end' );
		?>
	</main><!-- #main -->

	<?php do_action( 'foodmania_primary_div_search_end' ); // Pagination hooked here. ?>

</section><!-- #primary -->

<?php
get_sidebar();
get_footer();
