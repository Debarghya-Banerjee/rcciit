<?php
/**
 * BuddyPress - Groups Directory
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_directory_groups_page' ); ?>

<div id="buddypress">

	<?php
	do_action( 'bp_before_directory_groups' );
	do_action( 'bp_before_directory_groups_content' );
	do_action( 'bp_before_directory_groups_tabs' );
	?>

	<form action="" method="post" id="groups-directory-form" class="dir-form">

		<h3><?php esc_html_e( 'Groups Directory', 'foodmania' ); ?>
					<?php
					if ( is_user_logged_in() && bp_user_can_create_groups() ) :
						?>
			&nbsp;<a class="button" href="<?php echo esc_url( trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create' ) ); ?>"><?php esc_html_e( 'Create a Group', 'foodmania' ); ?></a><?php endif; ?></h3>

		<?php do_action( 'template_notices' ); ?>

		<div class="item-list-tabs rtp-group-tabs" role="navigation">
			<ul class="clearfix">
				<li class="selected" id="groups-all">

				<a href="<?php echo esc_url( trailingslashit( bp_get_root_domain() . ' / ' . bp_get_groups_root_slug() ) ); ?>">
					<?php /* translators: %s: group count */ ?>
					<?php printf( wp_kses_post( __( 'All Groups <span>%s</span> ', 'foodmania' ) ), esc_html( bp_get_total_group_count() ) ); ?>
				</a>
				</li>

				<?php
				if ( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) {
					?>
					<li id="groups-personal">
						<a href="<?php echo esc_url( trailingslashit( bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups' ) ); ?>">
							<?php /* translators: %s: group count */ ?>
							<?php printf( wp_kses_post( __( 'My Groups <span>%s</span> ', 'foodmania' ) ), esc_html( bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) ); ?>
						</a>
					</li>
					<?php
				}

				?>

				<li class="rtp-group-search">
					<div id="group-dir-search" class="dir-search" role="search">
						<?php bp_directory_groups_search_form(); ?>
					</div><!-- #group-dir-search -->
				</li>

				<?php do_action( 'bp_groups_directory_group_types' ); ?>

				<li id="groups-order-select" class="last filter rtp-select-wrapper">
					<select id="groups-order-by" class="rtp-select">
						<option value="active"><?php esc_html_e( 'Last Active ', 'foodmania' ); ?></option>
						<option value="popular"><?php esc_html_e( 'Most Members ', 'foodmania' ); ?></option>
						<option value="newest"><?php esc_html_e( 'Newly Created ', 'foodmania' ); ?></option>

						<?php
						if ( bp_is_active( 'xprofile' ) ) {
							?>
							<option value="alphabetical"><?php esc_html_e( 'Alphabetical ', 'foodmania' ); ?></option>
							<?php
						}

						do_action( 'bp_groups_directory_order_options' );
						?>
					</select>
				</li>

				<?php do_action( 'bp_groups_directory_group_sub_types' ); ?>
			</ul>
		</div>

		<div id="groups-dir-list" class="groups dir-list">

			<?php bp_get_template_part( 'groups/groups-loop' ); ?>

		</div><!-- #groups-dir-list -->

		<?php
		do_action( 'bp_directory_groups_content' );
		wp_nonce_field( 'directory_groups ', '_wpnonce-groups-filter' );
		do_action( 'bp_after_directory_groups_content' );
		?>

	</form><!-- #groups-directory-form -->

	<?php do_action( 'bp_after_directory_groups' ); ?>

</div><!-- #buddypress -->

<?php do_action( 'bp_after_directory_groups_page' ); ?>
