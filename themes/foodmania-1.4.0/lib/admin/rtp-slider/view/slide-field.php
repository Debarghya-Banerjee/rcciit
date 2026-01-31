<?php
	/**
	 * Will be used to get existing slide data in javascript.
	 *
	 * @package Foodmania.
	 */

	$args_recieved       = $args; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
	$slider_options_name = ! empty( $args['slider_options_name'] ) ? $args_recieved['slider_options_name'] : '';
	$image_src           = ! empty( $args['image_src'] ) ? $args_recieved['image_src'] : '';
	$slide_title         = ! empty( $args['title'] ) ? $args_recieved['title'] : '';
	$content             = ! empty( $args['content'] ) ? $args_recieved['content'] : '';
	$permalink           = ! empty( $args['permalink'] ) ? $args_recieved['permalink'] : '';

?>

<div class="slide-fields">
	<p class="image-src-field">
		<label for=""><?php esc_html_e( 'Choose Image', 'foodmania' ); ?></label>
		<input class="image-src-input" type='text' name="<?php echo esc_attr( $slider_options_name ); ?>[slides][slide_number][image_src]" value='<?php echo esc_url( $image_src ); ?>' />
		<button class="button-secondary upload_image_button"><?php esc_html_e( 'Choose Slider Image', 'foodmania' ); ?></button>
		<button class="button-secondary remove-slider-image"><?php esc_html_e( 'Remove', 'foodmania' ); ?></button>
	</p>
	<p class="title-field">
		<label for=""><?php esc_html_e( 'Slide Title', 'foodmania' ); ?></label>
		<input type='text' class="title-input" name="<?php echo esc_attr( $slider_options_name ); ?>[slides][slide_number][title]" value='<?php echo esc_attr( $slide_title ); ?>' />
	</p>
	<p class="content-field">
		<label for=""><?php esc_html_e( 'Slide Content', 'foodmania' ); ?></label>
		<textarea class="content-input" name="<?php echo esc_attr( $slider_options_name ); ?>[slides][slide_number][content]" ><?php echo esc_html( $content ); ?></textarea>
	</p>
	<p class="link-field">
		<label for=""><?php esc_html_e( 'Slide Link', 'foodmania' ); ?></label>
		<input type='text' class="permalink-input" name="<?php echo esc_attr( $slider_options_name ); ?>[slides][slide_number][permalink]" value='<?php echo esc_url( $permalink ); ?>' />
	</p>
</div>
