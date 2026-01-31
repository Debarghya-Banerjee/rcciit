<?php
/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php
do_action( 'bp_before_groups_loop' );

if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) {
	?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="group-dir-count-top">

			<?php bp_groups_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="group-dir-pag-top">

			<?php bp_groups_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_groups_list' ); ?>

	<div id="rtp-groups-list" class="rtp-group-list-container" role="main">
		<ul class="large-block-grid-4 medium-block-grid-3 small-block-grid-2 clearfix">
			<?php
			while ( bp_groups() ) {
				bp_the_group();
				?>
				<li <?php bp_group_class(); ?>>
					<div class="rtp-group-content rtp-image-box">
						<div class="item-avatar">
							<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">
								<?php bp_group_avatar(); ?>
							</a>
						</div>

						<div class="rtp-member-meta rtp-bp-meta aligncenter">
							<div>
								<a class="rtp-title" href="<?php bp_group_permalink(); ?>">
									<?php bp_group_name(); ?>
								</a>
							</div>

							<div class="action">					

								<div class="meta">

									<?php bp_group_member_count(); ?>

								</div>

								<?php

								/**
								 * Fires inside the action section of an individual group listing item.
								 *
								 * @since 1.1.0
								 */
								do_action( 'bp_directory_groups_actions' );
								?>

							</div>
						</div>
					</div>
				</li>
				<?php
			}
			?>
		</ul>
	</div>

	<?php do_action( 'bp_after_directory_groups_list' ); ?>

	<div id="pag-bottom" class="pagination">
		<div class="pag-count" id="group-dir-count-bottom">
			<?php bp_groups_pagination_count(); ?>
		</div>
		<div class="pagination-links" id="group-dir-pag-bottom">
			<?php bp_groups_pagination_links(); ?>
		</div>
	</div>

<?php } else { ?>

	<div id="message" class="info">
		<p><?php esc_html_e( 'There were no groups found.', 'foodmania' ); ?></p>
	</div>

<?php }

do_action( 'bp_after_groups_loop' );
?>
