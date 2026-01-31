<?php
/**
 * Contains custom functions for foodmania theme which is used throughout the theme files
 *
 * @package Foodmania
 */

if ( ! function_exists( 'foodmania_mod' ) ) {

	/**
	 * Works like get_theme_mod() however used specifically for foodmania since
	 * all values go inside the same array foodmania_mod in theme_mod to avoid
	 * any conflicts.
	 *
	 * @param  string $key     the inner key of the option.
	 * @param  mixed  $default default value , just like you pass in get_theme_mod.
	 *
	 * @return mixed           value of the key
	 */
	function foodmania_mod( $key, $default = false ) {
		$foodmania_mod = get_theme_mod( 'foodmania_mod' );

		return isset( $foodmania_mod[ $key ] ) && $foodmania_mod[ $key ] ? $foodmania_mod[ $key ] : $default;
	}
}

if ( ! function_exists( 'foodmania_banner_title' ) ) {

	/**
	 * Decides the tile of the banner
	 *
	 * @param  int $queried_obj_id object id of the current page.
	 *
	 * @return string  banner title of the page.
	 */
	function foodmania_banner_title( $queried_obj_id ) {

		if ( is_archive() ) {

			$object = get_queried_object();

			if ( is_author() ) {
				echo esc_html__( 'Posts by: ', 'foodmania' ) . get_the_author();
			} elseif ( $object->name ) {
				return $object->name;
			} else {
				return esc_html__( 'Archive', 'foodmania' );
			}
		} elseif ( is_404() ) {
			return esc_html__( '404 Page', 'foodmania' );
		} elseif ( is_search() ) {
			return esc_html__( 'Search Results', 'foodmania' );
		} else {
			return get_the_title( $queried_obj_id );
		}

		return get_the_title();
	}
}

if ( ! function_exists( 'foodmania_home_section_order' ) ) {

	/**
	 * Retrieves the homepage sections order
	 * If two sections have been assinged the same position, left position number will be used and interchanged.
	 *
	 * @return array
	 */
	function foodmania_home_section_order() {
		$layout_array = array();
		$unused_keys  = array( 2, 3, 4, 5 );
		$used_keys    = array();

		for ( $i = 2; $i <= 5; $i ++ ) {
			$key = intval( get_theme_mod( "home_section_{$i}_position", $i ) );

			if ( ! in_array( $key, $used_keys, true ) ) {
				$layout_array[ $key ] = "section-{$key}";
			} else { // If two sections have been assined the same position, we will give it any key which is unused.
				$key                  = $unused_keys[0];
				$layout_array[ $key ] = "section-{$unused_keys[ 0 ]}";
			}

			// Keep track of used keys.
			array_push( $used_keys, $key );

			// Remove the unused key since it has been used.
			$unusedkey = array_search( $key, $unused_keys, true );
			if ( false !== $unusedkey ) {
				unset( $unused_keys[ $unusedkey ] );
				$unused_keys = array_values( $unused_keys ); // Fix the array key values.
			}
		}

		return $layout_array;
	}
}

if ( ! function_exists( 'foodmania_post_meta_values' ) ) {

	/**
	 * Creates an array of post meta items and also check to see if its is being called on post page.
	 *
	 * @return array all post items
	 */
	function foodmania_post_meta_values() {
		$post_meta_values = array(
			'post_author'     => get_theme_mod( 'rtp_show_post_author', 1 ),
			'post_date'       => get_theme_mod( 'rtp_show_post_date', 1 ),
			'post_categories' => get_theme_mod( 'rtp_show_post_categories', 1 ),
			'post_tags'       => get_theme_mod( 'rtp_show_post_tags', 1 ),
			'post_comment'    => get_theme_mod( 'rtp_show_post_comments', 1 ),
		);

		return ( array_filter( $post_meta_values ) && 'post' === get_post_type() ) ? $post_meta_values : false;
	}
}

if ( ! function_exists( 'foodmania_post_meta' ) ) {

	/**
	 * Displays Post Meta.
	 */
	function foodmania_post_meta() {
		$post_meta_values = ! empty( foodmania_post_meta_values() ) ? foodmania_post_meta_values() : array();
		$post_date        = ! empty( $post_meta_values['post_date'] ) ? $post_meta_values['post_date'] : '';
		$post_author      = ! empty( $post_meta_values['post_author'] ) ? $post_meta_values['post_author'] : '';
		$post_categories  = ! empty( $post_meta_values['post_categories'] ) ? $post_meta_values['post_categories'] : '';
		$post_tags        = ! empty( $post_meta_values['post_tags'] ) ? $post_meta_values['post_tags'] : '';
		$post_comment     = ! empty( $post_meta_values['post_comment'] ) ? $post_meta_values['post_comment'] : '';

		if ( empty( $post_meta_values ) ) {
			return;
		}
		?>

		<div class="post-meta">
			<?php
			do_action( 'foodmania_post_meta_begin' );

			/* Post Date */
			if ( $post_date ) {
				echo "<div class='rtp-meta-row rtp-meta-date-row'>";
				/* Adding permalink to the date so that posts without titles can also be accessed */
				echo "<a class='date-permalink' href='" . esc_url( get_permalink() ) . "'>";
				/* Translators: %1$s for datetime %2$s for the time %3$s for the time */
				printf( wp_kses_post( __( '<time class="published date updated" datetime="%1$s"><span class="date-day">%2$s</span><span class="date-month" >%3$s</span></time> ', 'foodmania' ) ), esc_attr( get_the_date( 'c' ) ), esc_html( get_the_time( 'j' ) ), esc_html( get_the_time( 'M' ) ) );
				echo '</a>';
				echo "<div class='rtp-white-divider' ></div>";
				echo '</div>';
			}

			/* Post Author */
			if ( $post_author ) {
				echo "<div class='rtp-meta-row rtp-meta-author-row'>";
				/* Translators: %1$s for author posts url %2$s for the title in the attribute %3$s for the author of the post */
				printf( wp_kses_post( __( '<span class="rtp-post-author-prefix rtp-meta-title">Author</span> <span class="vcard author"><a class="fn" href="%1$s" title="%2$s">%3$s</a></span> ', 'foodmania' ) ), esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ), esc_attr( sprintf( __( 'Posts by %s', 'foodmania' ), get_the_author() ) ), esc_html( get_the_author() ) );
				echo '</div>';
			}

			/* Post Comments */
			if ( $post_comment && ( get_comments_number() || comments_open() ) ) {
				?>
				<div class='rtp-meta-row rtp-meta-comments-row'>
					<span class="rtp-meta-title"><?php esc_html_e( 'Comments', 'foodmania' ); ?></span>
					<span class="rtp-post-comment-count">
						<?php comments_popup_link( _x( '0 Comment', 'comments number', 'foodmania' ), _x( '<span>1</span> Comment', 'comments number', 'foodmania' ), _x( '<span>%</span> Comments', 'comments number', 'foodmania' ), 'rtp-post-comment rtp-common-link' ); ?>
					</span>
				</div>
				<?php
			}

			/* Post Categories */
			if ( $post_categories && get_the_category_list() ) {
				?>
				<div class='rtp-meta-row rtp-meta-categories-row'>
					<span class="rtp-meta-title"><?php esc_html_e( 'Categories', 'foodmania' ); ?></span>
					<span class="rtp-cat-list">
						<?php echo wp_kses_post( get_the_category_list( ', ' ) ); ?>
					</span>
				</div>
				<?php
			}

			/* Post Tags */
			if ( ( $post_tags && get_the_tag_list() ) ) {
				?>
				<div class='rtp-meta-row rtp-meta-tags-row'>
					<span class="rtp-meta-title"><?php esc_html_e( 'Tags', 'foodmania' ); ?></span>
					<span class="rtp-tags-list"><?php echo wp_kses_post( get_the_tag_list( '<span>', ', ', '</span>' ) ); ?></span>
				</div>
				<?php
			}

			do_action( 'foodmania_post_meta_end' );
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foodmania_excerpt_length' ) ) {

	/**
	 * Hooked into excerpt length for changing the excerpt length of the post
	 *
	 * @param int $length Length.
	 *
	 * @return string
	 */
	function foodmania_excerpt_length( $length ) {
		return intval( get_theme_mod( 'rtp_excerpt_length', 90 ) ) ? get_theme_mod( 'rtp_excerpt_length', 90 ) : $length;
	}
}

add_filter( 'excerpt_length', 'foodmania_excerpt_length' );

if ( ! function_exists( 'foodmania_excerpt_more' ) ) {

	/**
	 * Hooked into except more for overriding the readmore link.
	 *
	 * @param mixed $more More markup.
	 *
	 * @return mixed|void
	 */
	function foodmania_excerpt_more( $more ) {
		$default = __( 'Read More', 'foodmania' );
		$text    = get_theme_mod( 'rtp_readmore_text', $default );

		$more = apply_filters( 'rtp_readmore', ( '&hellip; <br /><a class="rtp-readmore" title="' . $default . '" href="' . get_permalink( get_the_ID() ) . '">' . $text . '</a>' ) );

		return $more;
	}
}

/* Add Filter */
add_filter( 'excerpt_more', 'foodmania_excerpt_more' );

if ( ! function_exists( 'foodmania_single_pagination' ) ) {

	/**
	 * Creates the pagination for single page
	 */
	function foodmania_single_pagination() {

		if ( is_single() && ( get_adjacent_post( '', '', true ) || get_adjacent_post( '', '', false ) ) ) { // phpcs:ignore WordPressVIPMinimum
			?>
			<nav class="rtp-post-link-nav clearfix">
				<?php
				if ( get_adjacent_post( '', '', true ) ) { // phpcs:ignore WordPressVIPMinimum
					?>
					<div class="left button-link-div"><?php previous_post_link( '%link', __( 'Previous Post', 'foodmania' ) ); ?></div>
					<?php
				}

				if ( get_adjacent_post( '', '', false ) ) { // phpcs:ignore WordPressVIPMinimum
					?>
					<div class="right button-link-div"><?php next_post_link( '%link', __( 'Next Post', 'foodmania' ) ); ?></div>
					<?php
				}
				?>
			</nav><!-- .rtp-navigation -->
			<?php
		}
	}
}

if ( ! function_exists( 'rtp_pagination' ) ) {

	/**
	 * Creates pagination for the archive page.
	 */
	function rtp_pagination() {
		if ( is_single() ) {
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'foodmania' ),
					'after'  => '</div>',
				)
			);
		} else {
			echo wp_kses_post( "<nav class='rtp-pagination' >" . paginate_links() . '</nav>' );
		}
	}
}

/**
 * Add WooCommerce support, tested upto WooCommerce version 2.0.20
 *
 * @since foodmania 1.0.0
 */
function rtp_add_woocommerce_support() {
	if ( class_exists( 'Woocommerce' ) ) {

		/**
		 * Unhook WooCommerce Wrappers
		 */
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

		/**
		 * Hook foodmania wrappers
		 */
		add_action( 'woocommerce_before_main_content', 'rtp_woocommerce_wrapper_start', 10 );
		add_action( 'woocommerce_after_main_content', 'rtp_woocommerce_wrapper_end', 10 );

		/**
		 * Declare theme support for WooCommerce
		 */
		add_theme_support( 'woocommerce' );
	}
}

add_action( 'init', 'rtp_add_woocommerce_support' );


if ( ! function_exists( 'rtp_woocommerce_wrapper_start' ) ) {

	/**
	 * WooCommerce wrapper start.
	 */
	function rtp_woocommerce_wrapper_start() {
		$rtp_content_grid_class = apply_filters( 'rtp_set_content_grid_class', 'large-8 columns ' );

		if ( is_shop() || is_archive() || is_category() ) {
			$rtp_content_class = ' class="content-area ' . $rtp_content_grid_class . ' rtp-woocommerce-archive" ';
		} else {
			$rtp_content_class = ' class="content-area ' . $rtp_content_grid_class . ' rtp-singular" ';
		}
		echo wp_kses_post( '<section id="primary" role="main"' . $rtp_content_class . '>' );
	}
}

if ( ! function_exists( 'rtp_woocommerce_wrapper_end' ) ) {

	/**
	 * WooCommerce wrapper end.
	 */
	function rtp_woocommerce_wrapper_end() {
		echo '</section> <!-- End of #content -->';
	}
}

if ( ! function_exists( 'rtp_header_image' ) ) {

	/**
	 * Shows the header image or the title.
	 *
	 * @param bool $contex Contex.
	 */
	function rtp_header_image( $contex = false ) {
		$header_image   = '';
		$optional_class = '';

		if ( function_exists( 'the_custom_logo' ) ) {
			$header_image   = get_custom_logo();
			$optional_class = has_custom_logo() ? 'rtp-hide-title' : false;
		}

		$site_name   = get_bloginfo( 'name' );
		$heading_tag = ( ( is_home() || is_front_page() ) && 'footer' !== $contex ) ? 'h1' : 'h2';

		if ( $header_image ) {
			echo wp_kses_post( $header_image );
		}
		?>
		<<?php echo esc_html( $heading_tag ); ?> class="rtp-site-title <?php echo esc_attr( $optional_class ); ?>">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html( $site_name ); ?></a>
		</<?php echo esc_html( $heading_tag ); ?>>
		<?php
	}
}

if ( ! function_exists( 'rtp_main_header_class' ) ) {

	/**
	 * For styling the header when there is no slider on home page.
	 */
	function rtp_main_header_class() {
		$home_slides = get_option( 'foodmania-slider-settings' );
		$visibility  = get_theme_mod( 'home_section_1_visibility', 1 );
		echo ( $home_slides && $visibility ) ? '' : 'rtp-no-slider';
	}
}

if ( ! function_exists( 'rtp_sanitize_choices' ) ) {

	/**
	 * Used for sanitizing radio or select options in customizer
	 *
	 * @param  mixed $input   user input.
	 * @param  mixed $setting choices provied to the user.
	 *
	 * @since Foodmania 1.0.0
	 * @return mixed  output after sanitization
	 */
	function rtp_sanitize_choices( $input, $setting ) {
		global $wp_customize;

		$control = $wp_customize->get_control( $setting->id );

		if ( array_key_exists( $input, $control->choices ) ) {
			return $input;
		} else {
			return $setting->default;
		}
	}
}

if ( ! function_exists( 'foodmania_comment_nav' ) ) {

	/**
	 * Display navigation to next/previous comments when applicable.
	 *
	 * @since Twenty Fifteen 1.0
	 */
	function foodmania_comment_nav() {

		// Are there comments to navigate through?
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
			?>
			<nav class="navigation comment-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'foodmania' ); ?></h2>
				<div class="nav-links">
					<?php
					$prev_link = get_previous_comments_link( __( 'Older Comments', 'foodmania' ) );
					if ( $prev_link ) {
						printf( '<div class="nav-previous">%s</div>', wp_kses_post( $prev_link ) );
					}
					$next_link = get_next_comments_link( __( 'Newer Comments', 'foodmania' ) );
					if ( $next_link ) {
						printf( '<div class="nav-next">%s</div>', wp_kses_post( $next_link ) );
					}
					?>
				</div><!-- .nav-links -->
			</nav><!-- .comment-navigation -->
			<?php
		}
	}
}

if ( ! function_exists( 'rtp_sanitize_choices' ) ) {

	/**
	 * Used for sanitizing radio or select options in customizer
	 *
	 * @param  mixed $input   user input.
	 * @param  mixed $setting choices provied to the user.
	 *
	 * @return mixed  output after sanitization
	 */
	function rtp_sanitize_choices( $input, $setting ) {
		global $wp_customize;

		$control = $wp_customize->get_control( $setting->id );

		if ( array_key_exists( $input, $control->choices ) ) {
			return $input;
		} else {
			return $setting->default;
		}
	}
}

if ( ! function_exists( 'rtp_sanitize_checkboxes' ) ) {

	/**
	 * Sanitizes checkbox for customizer.
	 *
	 * @param int $input Input.
	 *
	 * @return int|string
	 */
	function rtp_sanitize_checkboxes( $input ) {
		if ( true === $input ) {
			return 1;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'rtp_slider_extra_params' ) ) {

	/**
	 * Slider parameters.
	 *
	 * @param array $slides Slider array.
	 *
	 * @return mixed|void
	 */
	function rtp_slider_extra_params( $slides ) {
		$extra_params     = false;
		$images_available = false;

		if ( is_array( $slides ) ) {
			foreach ( $slides as $key => $slide_array ) {
				if ( isset( $slide_array['image_src'] ) && $slide_array['image_src'] ) {
					$images_available = true;
					break;
				}
			}
		}

		$extra_params .= ' data-cycle-log=false data-cycle-fx=fade ';

		$extra_params .= $images_available ? ' data-cycle-loader=false data-cycle-auto-height=container ' : false;

		return apply_filters( 'foodmania_slider_extra_params', $extra_params, $slides, $images_available );
	}
}
