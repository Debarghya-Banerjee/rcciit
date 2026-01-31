<?php
/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php do_action( 'bp_before_members_loop' ); ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>

	<?php do_action( 'bp_before_directory_members_list' ); ?>

	<ul id="members-list" class="rtp-member-list-container clearfix large-block-grid-4 medium-block-grid-3 small-block-grid-2" role="main">

		<?php
		while ( bp_members() ) :
			bp_the_member();
			?>

			<?php global $members_template; ?>

			<li>
				<div class="rtp-member rtp-image-box">
					<div class="item-avatar">
						<a href="<?php bp_member_permalink(); ?>"><?php echo get_avatar( bp_get_member_user_id(), 240 ); ?></a>
					</div>

					<div>
						<a class="rtp-title" href="<?php bp_member_permalink(); ?>">
							<strong class="name fn"><?php bp_member_name(); ?></strong>
						</a>
					</div>

					<div>
						<span class="count"><?php echo esc_html( $members_template->member->total_friend_count ); ?> <?php esc_html_e( 'Friends', 'foodmania' ); ?></span>
					</div>

					<div>
						<a class="link" href="<?php bp_member_permalink(); ?>"><?php esc_html_e( 'Portfolio', 'foodmania' ); ?></a>
					</div>

					<?php do_action( 'bp_directory_members_item' ); ?>

					<div class="action">

						<?php do_action( 'bp_directory_members_actions' ); ?>

					</div>

				</div>

			</li>

		<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-dir-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else : ?>

	<div id="message" class="info">
		<p><?php esc_html_e( 'Sorry, no members were found.', 'foodmania' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>
