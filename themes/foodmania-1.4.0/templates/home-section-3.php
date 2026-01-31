<?php
/**
 * Used for showing section 3 on home page template
 *
 * @package Foodmania
 */

$cat_ids = intval( get_theme_mod( 'home_section_3_category' ) );

$args = array(
	'posts_per_page' => 3,
	'cat'            => $cat_ids,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
);

$cat_query         = $cat_ids ? new WP_Query( apply_filters( 'foodmania_section3_posts_args', $args ) ) : false;
$section_title     = get_theme_mod( 'home_section_3_title' );
$title_description = get_theme_mod( 'home_section_3_description' );
$link_text         = get_theme_mod( 'home_section_3_linktext' );
$total_posts_found = $cat_query ? $cat_query->found_posts : 0;
?>


<div class="rtp-section-3-inner row">
	<header class="rtp-section-3-title">
		<?php if ( $section_title ) { ?>
			<h2 class="rtp-special-title"><?php printf( esc_html( $section_title ) ); ?></h2>
		<?php } ?>
		<?php if ( $title_description ) { ?>
			<p class="rtp-heading-description"><?php printf( esc_html( $title_description ) ); ?></p>
		<?php } ?>
	</header>

	<div class="rtp-section-3-thumbs large-12 clearfix">
		<?php
		if ( $cat_query && $cat_query->have_posts() ) :
			while ( $cat_query->have_posts() ) :
				$cat_query->the_post();
				?>
				<div class="rtp-thumb-box large-4 medium-4 column">
					<?php if ( has_post_thumbnail() ) { ?>
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'foodmania_section_3_thumb' ); ?>
						</a>
					<?php } ?>
					<?php the_title( sprintf( '<h3 class="rtp-thumb-title"><a href="%s" rel="bookmark">', get_permalink() ), '</a></h3>' ); ?>
				</div>
				<?php
			endwhile;
		endif;
		?>
		<?php wp_reset_postdata(); ?>
	</div>
	<?php if ( $link_text && $total_posts_found > 3 ) { ?>
		<footer>
			<a class="rtp-readmore" href="<?php echo esc_url( get_category_link( $cat_ids ) ); ?>">
				<?php
				/* translators: %s is Link text */
				printf( esc_html( get_theme_mod( 'home_section_3_linktext' ) ) );
				?>
			</a>
		</footer>
	<?php } ?>
</div>
