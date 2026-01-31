<?php
	/**
	 * Renders Rtcamp slider settings page.
	 *
	 * @package Foodmania
	 */

	$args_recieved      = $args; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
	$section_group      = ! empty( $args['section_group'] ) ? $args_recieved['section_group'] : '';
	$settings_menu_slug = ! empty( $args['settings_menu_slug'] ) ? $args_recieved['settings_menu_slug'] : '';

?>

<div class="wrap rtp-admin-page" id="rtc-slider">
	<?php Rtcamp_Slider_Admin::header_tabs(); ?>
	<h2><span class="dashicons dashicons-images-alt2"></span><?php esc_html_e( 'Slider Settings', 'foodmania' ); ?></h2>
	<form action="options.php" method="post" id="rtcamp-slider-form" >
		<?php
				// Output nonce, action, and option_page fields.
				settings_fields( $section_group ); 

				// Print out all settings sections added to the settings page.
				do_settings_sections( $settings_menu_slug ); 
		?>
		<div id="rtcamp-accordion" class="rtcamp"></div>
		<input type="button" class="button-secondary add-new-button" value="<?php esc_attr_e( 'Add New', 'foodmania' ); ?>" >
		<input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes', 'foodmania' ); ?>" class="button button-primary" />
	</form>
</div>

<script type="text/template" id="rtcamp-accordion-template">
	<?php require RTCAMP_SLIDER__PLUGIN_DIR . 'view/slide-template.php'; ?>
</script>
