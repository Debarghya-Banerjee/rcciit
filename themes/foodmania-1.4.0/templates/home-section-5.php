<?php
/**
 * Used for showing section 5 on home page template.
 *
 * @package Foodmania
 */

$section_title = get_theme_mod( 'home_section_5_title' );
$section_desc  = get_theme_mod( 'home_section_5_description' );
$section_link  = get_theme_mod( 'home_section_5_link' );
$section_text  = get_theme_mod( 'home_section_5_linktext' );
?>
<div class="rtp-section-5-inner row">
	<header class="rtp-section-5-title">
		<?php
		if ( $section_title ) {
			?>
			<h2 class="rtp-special-title"><?php printf( esc_html( $section_title ) ); ?></h2>
			<?php
		}
		?>
		<?php
		if ( $section_desc ) {
			?>
			<p class="rtp-heading-description"><?php printf( esc_html( $section_desc ) ); ?></p>
			<?php
		}
		?>
	</header>
	<?php
	if ( $section_text ) {
		?>
		<footer>
			<a class="rtp-readmore" href="<?php echo esc_url( $section_link ); ?>"><?php printf( esc_html( $section_text ) ); ?></a>
		</footer>
		<?php
	}
	?>
</div>
