<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Foodmania
 */

get_header();
?>

<div id="primary" class="content-area large-8 medium-12 column">
	<?php do_action( 'foodmania_primary_div_archive_start' ); ?>
	<main id="main" class="site-main" role="main">
		<?php
		do_action( 'foodmania_main_div_archive_start' );
		if ( have_posts() ) {
			?>
			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) {
				the_post();
				/**
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'templates/content', get_post_format() );
			}
			the_posts_navigation();

		} else {
			get_template_part( 'templates/content', 'none' );
		}

		do_action( 'foodmania_main_div_archive_end' );
		?>
	</main><!-- #main -->
	<?php do_action( 'foodmania_primary_div_archive_end' ); // Pagination hooked here. ?>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
