<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Foodmania
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div>
</div>
<div id="page" class="hfeed site">
	<?php do_action( 'foodmania_before_header' ); ?>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'foodmania' ); ?></a>
	<header id="masthead" class="rtp-site-header" role="banner">
		<div class="rtp-main-header <?php rtp_main_header_class(); ?>">
			<div class="rtp-header-inner row">
				<?php do_action( 'foodmania_main_header_begin' ); // Site title, desc, logo goes here. ?>
				<?php do_action( 'foodmania_main_header_end' ); // Navigation goes here. ?>
			</div>
		</div> <!-- rtp-main-header -->
		<?php do_action( 'foodmania_hook_after_main_header' ); // Slider also loads from here. ?>
	</header><!-- #masthead -->
	<?php do_action( 'foodmania_after_header' ); ?>
	<?php $content_class = ! is_page_template( 'page-templates/template-home.php' ) ? 'row' : false; ?>
	<div id="content" class="rtp-site-content <?php echo esc_attr( $content_class ); ?>">
		<?php
		do_action( 'foodmania_content_begin' );
