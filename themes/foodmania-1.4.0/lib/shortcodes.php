<?php
/**
 * Creates shortcode for the theme.
 *
 * @package Foodmania
 */

/**
 * Class Foodmania_Shortcodes
 */
class Foodmania_Shortcodes {

	/**
	 * Foodmania_Shortcodes constructor.
	 */
	public function __construct() {
		// Enables shortcode to work in text widget.
		add_filter( 'widget_text', 'do_shortcode' );
		add_shortcode( 'food', array( $this, 'list_icon_shortcode' ) );
	}

	/**
	 * List icon shortcode.
	 *
	 * @param array $atts Array of icons.
	 *
	 * @return mixed|void
	 */
	public function list_icon_shortcode( $atts ) {
		$allowed_args = apply_filters(
			'foodmania_food_args',
			array(             // accepted args.
				'type'       => '',
				'content'    => '',
				'phone'      => '',
				'fax'        => '',
				'email'      => '',
				'address'    => '',
				'home'       => '',
				'youtube'    => '',
				'facebook'   => '',
				'twitter'    => '',
				'googleplus' => '',
				'rss'        => '',
				'wordpress'  => '',
				'email'      => '',
				'youtube'    => '',
			)
		);

		$args = shortcode_atts( $allowed_args, $atts, 'food' );

		return $this->list_icon_markup( $args );
	}

	/**
	 * Function to return markup for requested social.
	 *
	 * @param string $type social type.
	 * @param string $content url/content.
	 */
	public function get_social_markup( $type, $content ) {

		$markup = '';

		$has_content = ( in_array(
			$type,
			array(
				'facebook',
				'twitter',
				'youtube',
				'googleplus',
				'rss',
				'wordpress',
			),
			true
		)
		) ? ' foodmania-icon' : '';

		switch ( $type ) {
			case 'phone':
				$markup = sprintf( "<p class='food-shortcode%s' ><span class='dashicons dashicons-phone'></span>%s</p>", $has_content, esc_attr( $content ) );
				break;
			case 'fax':
				$markup = sprintf( "<p class='food-shortcode%s' ><span class='dashicons dashicons-slides'></span>%s</p>", $has_content, esc_attr( $content ) );
				break;
			case 'email':
				$markup = sprintf(
					"<p class='food-shortcode%s' >
					<span class='dashicons dashicons-email'></span>
					<a title='%s' href='mailto:%s?Subject=Subject&amp;body=%s'>%s</a>
					</p>",
					$has_content,
					__( 'Email Us', 'foodmania' ),
					esc_attr( $content ),
					site_url(),
					esc_attr( $content )
				);
				break;
			case 'address':
				$markup = sprintf( "<p class='food-shortcode%s' ><span class='dashicons dashicons-location'></span>%s</p>", $has_content, esc_attr( $content ) );
				break;
			case 'home':
				$markup = sprintf( "<p class='food-shortcode%s' ><span class='dashicons dashicons-admin-home'></span>%s</p>", $has_content, esc_attr( $content ) );
				break;
			case 'facebook':
				$markup = sprintf( "<p class='food-shortcode%s' ><a href='%s' target='_blank'><span class='dashicons dashicons-facebook'></span></a></p>", $has_content, esc_url( $content ) );
				break;
			case 'twitter':
				$markup = sprintf( "<p class='food-shortcode%s' ><a href='%s' target='_blank'><span class='dashicons dashicons-twitter'></span></a></p>", $has_content, esc_url( $content ) );
				break;
			case 'youtube':
				$markup = sprintf( "<p class='food-shortcode%s' ><a href='%s' target='_blank'><span class='dashicons dashicons-video-alt3'></span></a></p>", $has_content, esc_url( $content ) );
				break;
			case 'googleplus':
				$markup = sprintf( "<p class='food-shortcode%s' ><a href='%s' target='_blank'><span class='dashicons dashicons-googleplus'></span></a></p>", $has_content, esc_url( $content ) );
				break;
			case 'rss':
				$markup = sprintf( "<p class='food-shortcode%s' ><a href='%s' target='_blank'><span class='dashicons dashicons-rss'></span></a></p>", $has_content, esc_url( $content ) );
				break;
			case 'wordpress': // phpcs:ignore spelling ok.
				$markup = sprintf( "<p class='food-shortcode%s' ><a href='%s' target='_blank'><span class='dashicons dashicons-wordpress'></span></a></p>", $has_content, esc_url( $content ) );
				break;
			default:
				break;
		}

		return $markup;
	}

	/**
	 * List icon markup.
	 *
	 * @param array $args Icon Markup.
	 *
	 * @return mixed|void
	 */
	public function list_icon_markup( $args ) {

		// Generally works for a single icon.
		$type    = ! empty( $args['type'] ) ? esc_html( $args['type'] ) : '';
		$content = ! empty( $args['content'] ) ? esc_attr( $args['content'] ) : '';

		$markup = '';

		if ( ! empty( $type ) ) {
			$markup .= $this->get_social_markup( $type, $content );
		}

		foreach ( $args as $key => $value ) {

			if ( ! empty( $value ) && 'type' !== $key ) {
				$markup .= $this->get_social_markup( $key, $value );
			}
		}

		// merging both horizontal and vertical markups while returning.
		return apply_filters( 'foodmania_food_markup', $markup, $args );
	}

}

new Foodmania_Shortcodes();
