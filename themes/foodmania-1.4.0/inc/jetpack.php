<?php
/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package Foodmania
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function foodmania_jetpack_setup() {
	add_theme_support(
		'infinite-scroll',
		array(
			'container' => 'main',
			'render'    => 'foodmania_infinite_scroll_render',
			'footer'    => 'page',
		)
	);
} // end function foodmania_jetpack_setup
add_action( 'after_setup_theme', 'foodmania_jetpack_setup' );

/**
 * Rendering infinite scrool for content.
 */
function foodmania_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'templates/content', get_post_format() );
	}
} // end function foodmania_infinite_scroll_render
