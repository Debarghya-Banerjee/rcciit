<?php
/**
 * Slide template.
 *
 * @package Foodmania
 */

?>
<?php // phpcs:ignore Generic.PHP.DisallowAlternativePHPTags.MaybeASPShortOpenTagFound, WordPressVIPMinimum.Security.Underscorejs.OutputNotation,PHPCompatibility.Miscellaneous.RemovedAlternativePHPTags.MaybeASPOpenTagFound ?>
<section class="group" data-slide-number='<%= slide_number %>' >

<header class="header">
<?php // phpcs:ignore Generic.PHP.DisallowAlternativePHPTags.MaybeASPShortOpenTagFound, WordPressVIPMinimum.Security.Underscorejs.OutputNotation,PHPCompatibility.Miscellaneous.RemovedAlternativePHPTags.MaybeASPOpenTagFound ?>
	<h3><%= slide_title %><span class="spinner header-loader"></span></h3>
</header>

<section class="content clearfix" >

<div class="slider-preview">

	<?php // phpcs:ignore Generic.PHP.DisallowAlternativePHPTags.MaybeASPOpenTagFound, Generic.PHP.DisallowAlternativePHPTags.MaybeASPShortOpenTagFound, WordPressVIPMinimum.Security.Underscorejs.OutputNotation,PHPCompatibility.Miscellaneous.RemovedAlternativePHPTags.MaybeASPOpenTagFound ?>
	<% if(image_src){ %>

	<?php // phpcs:ignore Generic.PHP.DisallowAlternativePHPTags.MaybeASPShortOpenTagFound, WordPressVIPMinimum.Security.Underscorejs.OutputNotation,PHPCompatibility.Miscellaneous.RemovedAlternativePHPTags.MaybeASPOpenTagFound ?>
	<img width="" height="" class="slider-image" src="<%= image_src %>" alt="">

	<?php // phpcs:ignore Generic.PHP.DisallowAlternativePHPTags.MaybeASPOpenTagFound, Generic.PHP.DisallowAlternativePHPTags.MaybeASPShortOpenTagFound, WordPressVIPMinimum.Security.Underscorejs.OutputNotation,PHPCompatibility.Miscellaneous.RemovedAlternativePHPTags.MaybeASPOpenTagFound ?>
	<% }  %>

</div>

<div class="slider-fields clearfix">

	<p><?php esc_html_e( 'Choose 1408x800px Image for best results.', 'foodmania' ); ?></p>
	<div class="slide-image row">
		<label for=""><?php esc_html_e( 'Choose Image', 'foodmania' ); ?> : </label>
		<?php // phpcs:ignore Generic.PHP.DisallowAlternativePHPTags.MaybeASPShortOpenTagFound, WordPressVIPMinimum.Security.Underscorejs.OutputNotation,PHPCompatibility.Miscellaneous.RemovedAlternativePHPTags.MaybeASPOpenTagFound ?>
		<input class="rtc-choose-image" type='hidden' name='<%= image_src_name %>' value='<%= image_src %>' />
		<input type="button" class="button-secondary upload_image_button" value="<?php esc_attr_e( 'Choose Slider Image', 'foodmania' ); ?>">
		<input type="button" class="button-secondary remove-slider-image" value="<?php esc_attr_e( 'Remove', 'foodmania' ); ?>">
	</div>
	<div class="slide-title row">
		<label for=""><?php esc_html_e( 'Slide Title', 'foodmania' ); ?> :</label>
		<?php // phpcs:ignore Generic.PHP.DisallowAlternativePHPTags.MaybeASPShortOpenTagFound, WordPressVIPMinimum.Security.Underscorejs.OutputNotation,PHPCompatibility.Miscellaneous.RemovedAlternativePHPTags.MaybeASPOpenTagFound ?>
		<input type="text" class="title" name="<%= slide_title_name %>" placeholder="<?php esc_attr_e( 'Enter Title', 'foodmania' ); ?>" value="<%= slide_title %>" maxlength=32>
	</div>
	<div class="slide-content row">
		<label for=""><?php esc_html_e( 'Slide Content', 'foodmania' ); ?> :</label>
		<?php // phpcs:ignore Generic.PHP.DisallowAlternativePHPTags.MaybeASPShortOpenTagFound, WordPressVIPMinimum.Security.Underscorejs.OutputNotation,PHPCompatibility.Miscellaneous.RemovedAlternativePHPTags.MaybeASPOpenTagFound ?>
		<input type="text" name="<%= slide_content_name %>" class="content" placeholder="<?php esc_attr_e( 'Enter some content', 'foodmania' ); ?>" value="<%= slide_content %>" maxlength=72>
	</div>
	<div class="slide-permalink row">
		<label for=""><?php esc_html_e( 'Slider Link', 'foodmania' ); ?> :</label>
		<?php // phpcs:ignore Generic.PHP.DisallowAlternativePHPTags.MaybeASPShortOpenTagFound, WordPressVIPMinimum.Security.Underscorejs.OutputNotation,PHPCompatibility.Miscellaneous.RemovedAlternativePHPTags.MaybeASPOpenTagFound ?>
		<input type="text" class="permalink" name="<%= slide_permalink_name %>" placeholder="<?php esc_attr_e( 'Enter Slide Link', 'foodmania' ); ?>" value="<%= slide_permalink %>">
	</div>
</div>
<footer class="save-delete">
	<input type="button" class="button-primary delete-slide" value="<?php esc_attr_e( 'Delete', 'foodmania' ); ?>">
	<span class="loader"></span>
</footer>

</section>

</section>
