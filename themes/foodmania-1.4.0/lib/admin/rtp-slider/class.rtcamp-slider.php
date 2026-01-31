<?php
/**
 * Rtcamp Slider creation
 *
 * @package Foodmania
 */

/**
 * Slider class to create slider
 */
class Rtcamp_Slider {

	/**
	 * Used for the option's name and admin page slug
	 *
	 * @var [string]
	 */
	public $slider_options_name = 'foodmania-slider-settings';

	/**
	 * Will contain all the slider saved or default slider settings
	 *
	 * @var [array]
	 */
	public $slider_settings;

	/**
	 * Contains the slides key from $slider_settings
	 *
	 * @var [array]
	 */
	public $slides;

	/**
	 * Contains the default slides
	 *
	 * @var [array]
	 */
	public $default_slides;

	/**
	 * Used to avoid mixing of php and html. Outputs the mark contained in the file.
	 *
	 * @param  [string] $file_path the path of the file from the plugin directory without php extention.
	 * @param  [array]  $args arguments that should be available in the file.
	 * @return [html] markup
	 */
	public function render( $file_path, $args = null ) {

		// Ignoring removal of extract as recommended creating a issue for same
		// @todo remove usage of extract.
		// phpcs:ignore
		( $args ) ? extract( $args ) : null;  

		ob_start();

		include RTCAMP_SLIDER__PLUGIN_DIR . $file_path . '.php';
		$template = ob_get_contents();

		ob_end_clean();

		return $template;
	}

	/**
	 * Get slides
	 */
	public function get_slides() {

		$this->default_slides  = array(
			'slides' => array(
				array(
					'image_src'     => '',
					'attachment_id' => '',
					'title'         => '',
					'content'       => '',
					'permalink'     => '',
				),
			),
		);
		$this->slider_settings = (array) get_option( $this->slider_options_name, apply_filters( 'rtcamp_slider_default_slides', $this->default_slides ) );
		$this->slides          = ( isset( $this->slider_settings['slides'] ) ) ? $this->slider_settings['slides'] : false;

		return $this->slides;
	}
}

$rtcamp_slider = new Rtcamp_Slider();
