<?php
/**
 * Used for showing Section 4(Buddypress Members) on home page template
 *
 * @package Foodmania
 */

if ( function_exists( 'bp_is_active' ) ) {

	$special_title = get_theme_mod( 'home_section_4_title' );
	$description   = get_theme_mod( 'home_section_4_description' );
	?>

	<header class="rtp-section-4-title">
		<?php
		if ( $special_title ) {
			?>
			<h2 class="rtp-special-title"><?php printf( esc_html( $special_title ) ); ?></h2>
			<?php
		}

		if ( $description ) {
			?>
			<p class="rtp-heading-description">
				<?php printf( esc_html( $description ) ); ?>
			</p>
			<?php
		}
		?>
	</header>

	<div class="rtp-section-4-thumbs clearfix">
		<ul class="large-block-grid-6 medium-block-grid-6 small-block-grid-3">
			<?php
			$types        = get_theme_mod( 'home_section_4_member_type', 'newest' );
			$member_count = 0;

			$members_args = array(
				'user_id'         => 0,
				'type'            => $types,
				'per_page'        => 6,
				'max'             => 6,
				'populate_extras' => true,
				'search_terms'    => false,
			);

			if ( function_exists( 'bp_has_members' ) && bp_has_members( $members_args ) ) {
				global $members_template;

				while ( bp_members() ) {
					bp_the_member();
					?>
					<li>
						<a href="<?php bp_member_permalink(); ?>" class="rtp-member-avatar"><?php bp_member_avatar( 'height=150&width=150' ); ?></a>
						<a href="<?php bp_member_permalink(); ?>" class="rtp-profile-name"><?php bp_member_name(); ?></a>
					</li>
					<?php
					$member_count ++;
				}
			} else {
				?>
				<div class="info">
					<p><?php esc_html_e( 'There were no members found.', 'foodmania' ); ?></p>
				</div>
				<?php
			}
			?>
		</ul>
	</div><!-- End of .rtp-section-4-thumbs -->

	<?php
	if ( $member_count > 6 ) {
		?>
		<footer>
			<a class="rtp-readmore" href="<?php echo esc_url( home_url( '/' ) . bp_get_members_slug() ); ?>">
				<?php echo esc_html( apply_filters( 'rtp_section3_link_text', esc_html__( 'View All Members', 'foodmania' ) ) ); ?>
			</a>
		</footer>
		<?php
	}
}
