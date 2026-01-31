<?php
/**
 * Contains Class with functions for the customizer
 *
 * @package Foodmania
 * @since Foodmania 1.0
 */

/**
 * Contains Custom functions for Customizer.
 */
class RTP_Customizer {

	/**
	 * Get the Pages that are published
	 *
	 * @return array $pages_array The array with post title.
	 */
	public static function pages_array() {

		$args = array(
			'posts_per_page' => 100,
			'offset'         => 0,
			'post_type'      => 'page',
			'post_status'    => 'publish',
		);

		$query = new WP_Query( apply_filters( 'rtp_customizer_list_pages', $args ) );

		$posts = $query->posts;

		$pages_array = array();

		if ( is_array( $posts ) ) {
			foreach ( $posts as $post ) {
				$pages_array[ $post->ID ] = $post->post_title;
			}
		}

		return $pages_array;

	}

	/**
	 * Get the categories.
	 *
	 * @return array $cat_array The array with category name.
	 */
	public static function category_array() {

		$args = array(
			'posts_per_page' => 100,
			'child_of'       => 0,
			'orderby'        => 'name',
			'order'          => 'ASC',
			'hide_empty'     => 1,
			'hierarchical'   => 1,
			'taxonomy'       => 'category',
			'pad_counts'     => false,
		);

		$categories = get_categories( apply_filters( 'rtp_customizer_list_categories', $args ) );

		$cat_array = array();

		if ( is_array( $categories ) ) {
			foreach ( $categories as $category ) {
				$cat_array[ $category->term_id ] = $category->cat_name;
			}
		}

		return $cat_array;

	}

	/**
	 * This will generate a line of CSS for use in header output. If the setting
	 * ($mod_name) has no defined value, the CSS will not be output.
	 *
	 * @uses get_theme_mod()
	 * @param string       $selector CSS selector.
	 * @param string/array $style The name of the CSS *property* to modify.
	 * @param string/array $mod_name The name of the 'theme_mod' option to fetch.
	 * @param string/array $prefix Optional. Anything that needs to be output before the CSS property.
	 * @param string/array $postfix Optional. Anything that needs to be output after the CSS property.
	 * @param bool         $default Optional. The Theme Modification Value.
	 * @param bool         $echo Optional. Whether to return the value or echo the value.
	 *
	 * @return string $return Returns a single line of CSS with selectors and a property.
	 *
	 * @since foodmania 1.0
	 */
	public static function generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $default = false, $echo = true ) {

		$return = '';

		$selector = is_array( $selector ) ? join( ',', $selector ) : $selector;

		if ( is_array( $style ) && is_array( $mod_name ) ) {
			$return .= $selector . '{';
			foreach ( $style as $key => $property ) {
				$mod          = is_array( $default ) ? get_theme_mod( $mod_name[ $key ], $default[ $key ] ) : get_theme_mod( $mod_name[ $key ], $default );
				$this_prefix  = is_array( $prefix ) ? $prefix[ $key ] : $prefix;
				$this_postfix = is_array( $postfix ) ? $postfix[ $key ] : $postfix;

				if ( 'color' === $selector ) {
					$final_value = ( strpos( $this_prefix . $mod . $this_postfix, '#' ) === false ) ? '#' . $this_prefix . $mod . $this_postfix : $this_prefix . $mod . $this_postfix;
				} else {
					$final_value = $this_prefix . $mod . $this_postfix;
				}

				$return .= ( isset( $mod ) && ! empty( $mod ) ) ?
						sprintf( '%s:%s;', $property, $final_value ) :
						false;
			}
			$return .= '}';
		} else {
			$mod = get_theme_mod( $mod_name, $default );

			if ( 'color' === $selector ) {
				$final_value = ( strpos( $prefix . $mod . $postfix, '#' ) === false ) ? '#' . $prefix . $mod . $postfix : $prefix . $mod . $postfix;
			} else {
				$final_value = $prefix . $mod . $postfix;
			}

			$return = ( isset( $mod ) && ! empty( $mod ) ) ?
				sprintf( '%s { %s:%s; }', $selector, $style, $final_value ) :
				false;
		}

		if ( $echo ) {
			echo wp_kses_post( $return );
		} else {
			return $return;
		}
	}
}

