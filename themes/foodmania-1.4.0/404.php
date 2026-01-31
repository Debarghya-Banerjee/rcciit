<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Foodmania
 */

get_header();
?>

<div id="primary" class="content-area large-8 medium-12 column">
	<?php do_action( 'foodmania_primary_div_404_start' ); ?>
	<main id="main" class="site-main" role="main">

		<?php do_action( 'foodmania_main_div_404_start' ); ?>
		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'foodmania' ); ?></h1>
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'foodmania' ); ?></p>
			</header><!-- .page-header -->

			<div class="page-content">
				<?php get_search_form(); ?>
			</div><!-- .page-content -->
		</section><!-- .error-404 -->
		<?php do_action( 'foodmania_main_div_404_end' ); ?>
	</main><!-- #main -->
	<?php do_action( 'foodmania_primary_div_404_end' ); // Pagination hooked here. ?>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
