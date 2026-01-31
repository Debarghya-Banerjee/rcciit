<?php
/**
 * Template Name: Foodmania Banner Description Template
 * This is used to show the HTML content for the description input and output
 *
 * @package Foodmania
 */

?>
<label for="foodmania_title_description_field">
	<?php esc_html_e( 'Will show below the page title.', 'foodmania' ); ?>
</label>
<input type="text" id="foodmania_title_description_field" name="foodmania_title_description_field" value="<?php echo esc_attr( $value ); // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable ?>"  />
