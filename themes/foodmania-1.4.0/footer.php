<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Foodmania
 */

do_action( 'foodmania_content_end' );
?>
</div><!-- #content -->

<?php do_action( 'foodmania_before_footer' ); ?>
<footer id="colophon" class="rtp-site-footer" role="contentinfo">
	<?php
	do_action( 'foodmania_footer_start' );

	if ( is_active_sidebar( 'sidebar-2' ) ) {
		?>
		<div class="rtp-footer-widgets row">
			<?php dynamic_sidebar( 'sidebar-2' ); ?>
		</div>
		<?php
	}
	?>
	<div class="rtp-site-info">
		<div class="rtp-site-info-inner row">
			<?php
			do_action( 'foodmania_site_info_left' ); // Left site info hooked here.
			rtp_social(); // Social links.
			do_action( 'foodmania_site_info_right' ); // Right site info hooked here.
			?>
		</div>
	</div><!-- .site-info -->
	<?php do_action( 'foodmania_footer_end' ); ?>
</footer><!-- #colophon -->
<?php do_action( 'foodmania_after_footer' ); ?>
</div><!-- #page -->

<div class="rt-overlay"></div>

<?php wp_footer(); ?>

</body>
</html>
