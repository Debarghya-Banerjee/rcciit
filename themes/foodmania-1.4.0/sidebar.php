<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Foodmania
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<div id="secondary" class="rtp-sidebar-section large-4 medium-12 column" role="complementary">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div><!-- #secondary -->
