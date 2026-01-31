<?php
/**
 * Template Name: Foodmania Banner Image Template
 * This is used to show the HTML content for the Image input and output
 *
 * @package Foodmania
 */

?>
<div id='foodmania-banner-image'>
	<?php
	if ( $url ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
		?>
		<img src="<?php echo esc_url( $url ); // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable ?>" alt='banner-image' width='100%'>
		<?php
	}
	?>
</div>
<input type='hidden' id='foodmania_banner_image_field' name='foodmania_banner_image_field' value="<?php echo esc_url( $url ); // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable ?>" />
<p class='hide-if-no-js foodmania-banner-links'>
<?php
if ( ! $value ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
	?>
	<a id="foodmania-upload-banner" href="" ><?php esc_html_e( 'Set banner image', 'foodmania' ); ?></a>
	<?php
} else {
	?>
	<a id="foodmania-remove-banner" href="" ><?php esc_html_e( 'Remove banner image', 'foodmania' ); ?></a>
	<?php
}
?>
</p>
