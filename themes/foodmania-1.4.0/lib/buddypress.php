<?php
/**
 * Contains functions for Buddypress
 *
 * @package Foodmania
 */

/*
 * Change BuddyPress avatar widths
 */
if ( ! defined( 'BP_AVATAR_THUMB_WIDTH' ) ) {
	define( 'BP_AVATAR_THUMB_WIDTH', 140 );
}

if ( ! defined( 'BP_AVATAR_THUMB_HEIGHT' ) ) {
	define( 'BP_AVATAR_THUMB_HEIGHT', 140 );
}

if ( ! defined( 'BP_AVATAR_FULL_WIDTH' ) ) {
	define( 'BP_AVATAR_FULL_WIDTH', 240 );
}

if ( ! defined( 'BP_AVATAR_FULL_HEIGHT' ) ) {
	define( 'BP_AVATAR_FULL_HEIGHT', 240 );
}

if ( ! function_exists( 'rtp_activity_image_size' ) ) {

	/**
	 * Change activity image size.
	 *
	 * @return string
	 */
	function rtp_activity_image_size() {
		return 'rt_media_single_image';
	}

	add_filter( 'rtmedia_activity_image_size', 'rtp_activity_image_size' );
}

/**
 * Taken from framework/home.php where clause filter for media query.
 */
if ( ! function_exists( 'rtp_media_where_query' ) ) {

	/**
	 * Where query.
	 *
	 * @param string $where      DB Query.
	 * @param string $table_name DB table name.
	 *
	 * @return string
	 */
	function rtp_media_where_query( $where, $table_name ) {

		global $wpdb, $photo_utime;

		$join_table = $wpdb->posts;
		$end_time   = 'tomorrow';
		$all_flag   = false;

		switch ( $photo_utime ) {
			case 'today':
				$start_time = 'yesterday';
				break;
			case 'this_week':
				$start_time = 'sunday last week';
				break;
			case 'this_month':
				$start_time = 'last day of last month';
				break;
			default:
				$all_flag = true;
				break;
		}

		if ( ! $all_flag ) {
			$last_month = strtotime( 'last month' );

			$start_date  = gmdate( 'Y-m-d', strtotime( $start_time ) );
			$start_date .= ' 23:59:59';

			$end_date  = gmdate( 'Y-m-d', strtotime( $end_time ) );
			$end_date .= ' 00:00:00';
			$where    .= " AND ( {$table_name}.upload_date > '$start_date' and {$table_name}.upload_date < '$end_date' ) ";

			return $where;
		}

		return $where;
	}
}

if ( ! function_exists( 'rtp_media_query_where_filter' ) ) {

	/**
	 * Filter the rtmedia_query to exclude the group media.
	 *
	 * @param string $where      DB Where Query.
	 * @param string $table_name DB Table name.
	 * @param string $join       DB JOIN Query.
	 *
	 * @return string
	 */
	function rtp_media_query_where_filter( $where, $table_name, $join ) {
		$where .= ' AND ( ' . $table_name . '.privacy = "0" OR ' . $table_name . '.privacy is NULL )';

		return $where;
	}
}

if ( ! function_exists( 'rtp_media_select_query_view_count_column' ) ) {

	/**
	 * Getting meta_value and meta_key for view count.
	 *
	 * @param string $select     DB Query.
	 * @param string $table_name DB Table name.
	 *
	 * @return string
	 */
	function rtp_media_select_query_view_count_column( $select, $table_name ) {
		$rtmedia_meta = new RTMediaMeta();

		// Meta table name.
		$select_table = $rtmedia_meta->model->table_name;

		return $select . ', ' . $select_table . '.meta_key, ' . $select_table . '.meta_value ';
	}
}

if ( ! function_exists( 'rtp_media_select_query_view_count_order' ) ) {

	/**
	 * Setting order for views.
	 *
	 * @param string $orderby    DB Query.
	 * @param string $table_name DB Table name.
	 *
	 * @return string
	 */
	function rtp_media_select_query_view_count_order( $orderby, $table_name ) {
		$rtmedia_meta = new RTMediaMeta();
		$select_table = $rtmedia_meta->model->table_name;
		$orderby      = 'ORDER BY ' . $select_table . '.meta_value DESC';

		return $orderby;
	}
}

if ( ! function_exists( 'rtp_join_query_rtp_media_interaction_view_count' ) ) {

	/**
	 * Function for join query with rtmedia_interaction table to get view count.
	 * This is used in 'home-recent-photos.php' to show photos by community.
	 *
	 * @param string $join       DB Query.
	 * @param string $table_name DB table Name.
	 *
	 * @return string
	 */
	function rtp_join_query_rtp_media_interaction_view_count( $join, $table_name ) {
		$rtmedia_meta = new RTMediaMeta();
		$join_table   = $rtmedia_meta->model->table_name;

		$join .= " LEFT JOIN {$join_table} ON ( {$join_table}.media_id = {$table_name}.id AND ( {$join_table}.meta_key = 'view' ) ) ";

		return $join;
	}
}

if ( ! function_exists( 'rtp_current_user_links' ) ) {

	/**
	 * Get current user links in dropdown
	 * Using this function for header user icon
	 *
	 * @global object $bp BuddyPress Object.
	 */
	function rtp_current_user_links() {
		global $bp;

		$settings_slug = isset( $bp->settings->slug ) ? $bp->settings->slug : '';
		$activity_slug = isset( $bp->activity->slug ) ? $bp->activity->slug : '';
		$profile_slug  = isset( $bp->profile->slug ) ? $bp->profile->slug : '';
		$groups_slug   = isset( $bp->groups->slug ) ? $bp->groups->slug : '';
		$logout_slug   = wp_logout_url( home_url() );

		$link_slugs = array(
			$settings_slug => esc_html__( 'Settings', 'foodmania' ),
			$activity_slug => esc_html__( 'Activity', 'foodmania' ),
			$profile_slug  => esc_html__( 'Profile', 'foodmania' ),
			$groups_slug   => esc_html__( 'Groups', 'foodmania' ),
			$logout_slug   => esc_html__( 'Logout', 'foodmania' ),
		);
		?>
		<ul id="rtp-user-links" class="rtp-user-links rtp-header-submenu f-dropdown" data-dropdown-content aria-hidden="true">
			<?php
			foreach ( $link_slugs as $slug => $title ) {
				$class = $slug;

				if ( function_exists( 'bp_loggedin_user_domain' ) ) {
					$link = bp_loggedin_user_domain() . $slug;
				}

				if ( $logout_slug === $slug ) {
					$class = 'logout';
					$link  = $slug;
				}

				if ( ! empty( $slug ) ) {
					?>
					<li class="rtp-profile-custom-links">
						<a class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $link ); ?>" title="<?php echo esc_attr( $title ); ?>"><?php echo esc_html( $title ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
		<?php
	}
}

/**
 * Check whether to use BuddyPress' cover pic feature or not.
 *
 * Cover photo feature has been added in BP 2.4.0.
 * Check https://bpdevel.wordpress.com/2015/09/30/buddypress-2-4-0-will-introduce-cover-images-for-members-groups/
 * for more information.
 *
 * @param bool $context Context.
 *
 * @return bool
 */
function foodmania_use_bp_cover_pic_feature( $context = false ) {

	if ( false === $context ) {
		$context = ( bp_is_group() ) ? 'group' : 'profile';
	}

	if ( 'group' === $context ) {
		$return = function_exists( 'bp_group_use_cover_image_header' ) && bp_group_use_cover_image_header();
	} else {
		$return = function_exists( 'bp_displayed_user_use_cover_image_header' ) && bp_displayed_user_use_cover_image_header();
	}

	return $return;
}

/**
 * Load BuddyPress cover photo markup.
 * Cover photo feature was introduced in BP 2.4.0
 */
function foodmania_load_bp_cover_photo() {
	$link = '';
	$url  = foodmania_get_bp_cover_image_url();
	if ( bp_is_group() ) {
		global $bp;
		$link = trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $bp->groups->current_group->slug . '/' );
	} else {
		$link = bp_get_displayed_user_link();
	}

	// Cover photo is not set.
	if ( empty( $url ) ) {
		?>
		<div class="fm-bp-cover-photo clearfix rtp-cover-img-empty">
			<a href="<?php echo esc_url( $link ); ?>"></a>
		</div>
		<?php
	} else {
		?>
		<div class="fm-bp-cover-photo clearfix rtp-cover-img-set">
			<a href="<?php echo esc_url( $link ); ?>"><img src="<?php echo esc_url( $url ); ?>"></a>
		</div>
		<?php
	}

	if ( foodmania_use_bp_cover_pic_feature() ) {

		if ( bp_is_group() ) {
			if ( ! current_user_can( 'edit_users' ) && ! bp_current_user_can( 'bp_moderate' ) && ! bp_is_item_admin() ) {
				return false;
			}
			$group           = groups_get_group( array( 'group_id' => bp_get_current_group_id() ) );
			$group_permalink = trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/' );
			$link            = trailingslashit( $group_permalink . 'admin/group-cover-image' ) . '#group-settings';
		} else {
			if ( function_exists( 'bp_displayed_user_id' ) && ( get_current_user_id() === bp_displayed_user_id() ) && ! bp_disable_avatar_uploads( false ) ) {
				$link = bp_get_members_component_link( 'profile', 'change-cover-image' ) . '#change-cover-image';
			} else {
				return false;
			}
		}

		?>
	<div class='bb_pc_rtmedia_change_cover_pic'>
		<a href='<?php echo esc_url( $link ); ?>' class='bb_pc_rtmedia_change_cover_pic_label'><i class='rtmicon-pencil'></i><?php esc_html_e( 'Change Cover', 'foodmania' ); ?></a>
	</div>
		<?php
	}
	?>
	<?php
}

/**
 * Get BuddyPress cover photo url.
 * Cover photo feature was introduced in BP 2.4.0
 *
 * @return bool|string
 */
function foodmania_get_bp_cover_image_url() {
	$bp = buddypress();

	// Find the component of the current item.
	if ( bp_is_group() ) {
		$cover_image_object = array(
			'component' => 'groups',
			'object'    => $bp->groups->current_group,
		);
	} else {
		$cover_image_object = array(
			'component' => 'xprofile',
			'object'    => $bp->displayed_user,
		);
	}

	$object_dir = $cover_image_object['component'];

	if ( 'xprofile' === $object_dir ) {
		$object_dir = 'members';
	}

	$cover_image = bp_attachments_get_attachment(
		'url',
		array(
			'object_dir' => $object_dir,
			'item_id'    => $cover_image_object['object']->id,
		)
	);

	return $cover_image;
}

/**
 * BuddyPress attachments cover image dimensions.
 *
 * @param array  $wh        Width and height of cover image.
 * @param array  $settings  Cover image settings.
 * @param string $component BuddyPress components.
 *
 * @return mixed
 */
function foodmania_bp_attachments_cover_image_dimensions( $wh, $settings, $component ) {

	// Height / Width for FoodMania cover photo only for profile and group.
	if ( 'groups' === $component || 'xprofile' === $component ) {
		$wh['width']  = '1600';
		$wh['height'] = '600';
	}

	return $wh;
}

// Filter to set BuddyPress cover photo height width.
add_filter( 'bp_attachments_get_cover_image_dimensions', 'foodmania_bp_attachments_cover_image_dimensions', 10, 3 );

/**
 * Load the list of group admin.
 *
 * @return void
 */
function rtp_load_group_admin_list() {

	?>
	<h3><?php esc_html_e( 'Group Admins', 'foodmania' ); ?></h3>
	<?php
	bp_group_list_admins();
	do_action( 'bp_after_group_menu_admins' );

}

add_action( 'rtp_group_admin_list_load', 'rtp_load_group_admin_list' );

/**
 * Load the group mod list
 *
 * @return void
 */
function rtp_load_group_mod_list() {
	if ( bp_group_has_moderators() ) {
		do_action( 'bp_before_group_menu_mods' );
		?>
		<h3><?php esc_html_e( 'Group Mods', 'foodmania' ); ?></h3>
		<?php
		bp_group_list_mods();
		do_action( 'bp_after_group_menu_mods' );
	}
}

add_action( 'rtp_group_mod_list_load', 'rtp_load_group_mod_list' );

/**
 * Load the group staff.
 *
 * @return void
 */
function rtp_load_group_staff() {

	if ( bp_is_group_home() ) {
		?>
		<div id="foodmania-group-item-actions" class="clearfix">
			<?php

			if ( bp_group_is_visible() ) {
				do_action( 'rtp_group_admin_list_load' );
				do_action( 'rtp_group_mod_list_load' );

			}
			?>
		</div><!-- #rtp-item-actions -->
		<?php
	}

}

add_action( 'bp_after_group_header', 'rtp_load_group_staff' );

/**
 * Load the cover
 *
 * @return void
 */
function rtp_load_cover() {
	?>
	<figure class="rtp-profile-feature-img rtp-overlay">
		<?php foodmania_load_bp_cover_photo(); ?>
	</figure>
	<?php
}

add_action( 'rtp_cover_load', 'rtp_load_cover' );

/**
 * Load the group photo button on  change
 *
 * @return bool Return false if not permitted or function does not exist.
 */
function rtp_load_change_group_photo_button() {
	if ( bp_is_group() ) {
		if ( ! current_user_can( 'edit_users' ) && ! bp_current_user_can( 'bp_moderate' ) && ! bp_is_item_admin() ) {
			return false;
		}
		$link = bp_get_groups_action_link( 'admin/group-avatar' );
	} else {
		if ( function_exists( 'bp_displayed_user_id' ) && ( get_current_user_id() === bp_displayed_user_id() ) && ! bp_disable_avatar_uploads( false ) ) {
			$link = bp_get_members_component_link( 'profile', 'change-avatar' );
		} else {
			return false;
		}
	}
	?>
		<div class='bb_pc_rtmedia_change_profile_pic' >
				<a href="<?php echo esc_url( $link ); ?>#bp-avatar-upload" class="bb_pc_rtmedia_change_profile_pic_label"><i class='rtmicon-pencil'></i><?php esc_html_e( 'Edit profile picture', 'foodmania' ); ?></a>
		</div>
	<?php
}

add_action( 'rtp_change_profile_button_load', 'rtp_load_change_group_photo_button' );

/**
 * Load the group profile
 *
 * @return void
 */
function rtp_load_group_profile() {
	global $bp;
	?>
	<div id="item-header-avatar" class="rtp-cover-header-avatar">
			<?php do_action( 'rtp_change_profile_button_load' ); ?>
			<?php
			echo wp_kses_post(
				bp_get_group_avatar(
					array(
						'type' => 'full',
						'id'   => $bp->groups->current_group->id,
					),
					$bp->groups->current_group->id
				)
			);
			?>
		</a>
	</div><!-- #item-header-avatar -->

	<?php
}

// Action to call the rtp_load_group_profile function.
add_action( 'rtp_group_profile_load', 'rtp_load_group_profile' );

/**
 * Load the Member Profile
 *
 * @return void
 */
function rtp_load_member_profile() {

	?>
	<div id="item-header-avatar" class="rtp-cover-header-avatar">
		<a class="rtp-cover-header-avatar-wrapper" href="<?php bp_displayed_user_link(); ?>">
			<?php bp_displayed_user_avatar( 'type=full' ); ?>
		</a>
		<?php do_action( 'rtp_change_profile_button_load' ); ?>
	</div><!-- #item-header-avatar -->
	<?php

}
// Action to call the rtp_load_member_profile function.
add_action( 'rtp_member_profile_load', 'rtp_load_member_profile' );

/**
 * Load the group description
 *
 * @return void
 */
function rtp_load_group_description() {
	?>
	<div class="rtp-item-header-right">
		<?php
		global $bp;
		$group_id = $bp->groups->current_group->id;
		if ( bp_has_groups( array( 'group_id' => $group_id ) ) ) :
			while ( bp_groups() ) :
				bp_the_group();
				?>
				<div id="item-header-content" class="rtp-item-header-content">
					<h2>
						<a href="<?php echo esc_url( bp_group_permalink() ); ?>" title="<?php bp_group_name(); ?>"><?php bp_group_name(); ?></a>
					</h2>
					<span class="highlight"><?php bp_group_type(); ?></span>
					<span class="activity">
									<?php
									/* translators: %s Last active time */
									printf( esc_html__( 'active %s', 'foodmania' ), esc_html( bp_get_group_last_active() ) );
									?>
								</span>

					<?php do_action( 'bp_before_group_header_meta' ); ?>

					<div id="rtp-group-item-meta">

						<?php bp_group_description(); ?>

						<div id="item-buttons" class="rtp-button-group">

							<?php do_action( 'bp_group_header_actions' ); ?>

						</div><!-- #item-buttons -->

						<?php do_action( 'bp_group_header_meta' ); ?>

					</div>
				</div><!-- #item-header-content -->
				<?php
			endwhile;
		endif;
		?>
	</div>
	<?php
}
// Action to call the rtp_load_group_description function.
add_action( 'rtp_group_description_load', 'rtp_load_group_description' );

/**
 * Load the member description
 *
 * @return void
 */
function rtp_load_member_description() {

	?>
	<div id="item-header-content" class="rtp-item-header-content">
		<?php
		if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) {
			?>
			<h2 class="user-name"><?php bp_displayed_user_username(); ?></h2>
			<?php
		}
		?>
		<span class="activity"><?php bp_last_activity( bp_displayed_user_id() ); ?></span>
		<?php do_action( 'bp_before_member_header_meta' ); ?>
		<div id="item-meta">
			<?php
			/**
			 * If you'd like to show specific profile fields here use:
			 * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
			 */
			do_action( 'bp_profile_header_meta' );
			?>
		</div><!-- #item-meta -->
	</div><!-- #item-header-content -->
	<?php

}
// Action to call the rtp_load_member_description function.
add_action( 'rtp_member_description_load', 'rtp_load_member_description' );

/**
 * Load the member navigation
 *
 * @return void
 */
function rtp_load_member_nav() {

	?>
	<nav id="item-nav" class="rtp-cover-header-nav-wrapper">
		<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
			<ul class="rtp-cover-header-nav clearfix">
				<?php
				bp_get_displayed_user_nav();
				do_action( 'bp_member_options_nav' );
				?>
			</ul>
		</div>
	</nav><!-- #item-nav -->
	<nav class="rtp-cover-header-mobile-nav"></nav>
	<?php
}
// Action to call the rtp_load_member_nav function.
add_action( 'rtp_member_nav_load', 'rtp_load_member_nav' );

/**
 * Load the group header template
 *
 * @return void
 */
function rtp_load_group_header_template() {

	if ( ! is_front_page() && function_exists( 'bp_is_group' ) && bp_is_group() ) {

		?>
		<div id="item-header" class="rtp-feature-img-cover">
			<?php do_action( 'rtp_cover_load' ); ?>
			<div class="rtp-group-profile-wrapper row">
				<div class="large-12 columns">
					<div class="clearfix rtp-group-profile-header">
						<?php do_action( 'rtp_group_profile_load' ); ?>
						<?php do_action( 'rtp_group_description_load' ); ?>
					</div>
				</div>
			</div> <!-- rtp-group-profile-wrapper -->
		</div> <!-- item-header -->
		<?php

	}
}
// Action to call the rtp_load_group_header_template function.
add_action( 'foodmania_hook_after_main_header', 'rtp_load_group_header_template' );

/**
 * Load the member's header template
 *
 * @return void
 */
function rtp_load_member_header_template() {

	if ( ! is_front_page() && function_exists( 'bp_is_user' ) && bp_is_user() ) {
		?>
		<div id="item-header" class="rtp-feature-img-cover">
			<?php do_action( 'rtp_cover_load' ); ?>
			<div class="rtp-member-profile-wrapper row clearfix">
				<div class="large-12 column">
					<?php do_action( 'bp_before_member_header' ); ?>
					<div class="clearfix rtp-member-profile-header">
						<?php do_action( 'rtp_member_profile_load' ); ?>
						<div class="rtp-item-header-right">
							<?php do_action( 'rtp_member_description_load' ); ?>
							<?php do_action( 'rtp_member_nav_load' ); ?>
						</div>
						<div id="item-buttons" class="rtp-item-buttons">
							<?php
							if ( bp_get_theme_package_id() === 'nouveau' ) :
								bp_nouveau_member_header_buttons(
									array(
										'button_element' => 'a',
										'container_classes' => array( 'member-header-actions' ),
									)
								);
							else :
								do_action( 'bp_member_header_actions' );
							endif;
							?>
						</div><!-- #item-buttons -->
					</div>
				</div>
			</div> <!-- rtp-member-profile-wrapper -->
		</div> <!-- item-header -->
		<?php

	}
}
// Action to call the rtp_load_member_header_template function.
add_action( 'foodmania_hook_after_main_header', 'rtp_load_member_header_template' );

/**
 * Load the home's slider template.
 *
 * @return void
 */
function rtp_load_home_slider_template() {

	?>
	<?php
	if ( is_front_page() ) {

		$home_slides = get_option( 'foodmania-slider-settings' );
		$visibility  = get_theme_mod( 'home_section_1_visibility', 1 );

		if ( isset( $home_slides['slides'] ) && is_array( $home_slides['slides'] ) && $visibility ) {

			?>

			<div id="main-slider" class="rtp-main-slider clearfix">
				<div class="cycle-slideshow" data-cycle-slides=".rtp-slide" data-cycle-prev=".rtp-prev"
					data-cycle-next=".rtp-next" <?php echo esc_attr( rtp_slider_extra_params( $home_slides['slides'] ) ); ?>>
					<?php
					foreach ( $home_slides['slides'] as $slide ) {
						?>
						<div class="rtp-slide">
							<div class="rtp-slide-inner rtp-overlay">
								<?php
								$slide_image = ( isset( $slide['attachment_id'] ) && $slide['attachment_id'] ) ? wp_get_attachment_image( $slide['attachment_id'], 'foodmania_slider_image' ) : "<img src='{$slide[ 'image_src' ]}' alt='' />";

								echo wp_kses_post( $slide_image );

								if ( $slide['title'] || $slide['content'] ) {

									?>
									<div
										class="rtp-slide-content <?php echo $slide_image ? false : 'rtp-noslide-image'; ?>">
										<div class="rtp-slide-content-inner row">
											<?php if ( $slide['permalink'] ) { ?>
												<a class="rtp-link-mask"
												href="<?php echo esc_url( $slide['permalink'] ); ?>"></a>
											<?php } ?>
											<?php if ( $slide['title'] ) { ?>
												<h2 class="rtp-special-title"><?php echo esc_html( $slide['title'] ); ?></h2>
											<?php } ?>
											<?php if ( $slide['content'] ) { ?>
												<p class="rtp-heading-description"><?php echo esc_html( $slide['content'] ); ?></p>
											<?php } ?>
										</div>
									</div> <!-- rtp-slide-content -->
									<?php
								}
								?>
							</div>
						</div> <!-- rtp-slide -->
						<?php
					}

					if ( count( $home_slides['slides'] ) > 1 ) {
						?>
						<div class="rtp-prev"></div>
						<div class="rtp-next"></div>
						<?php
					}
					?>
				</div> <!-- cycle-slideshow -->
			</div> <!-- main-slider -->
			<?php
		}
	}
}

// Action to call the rtp_load_home_slider_template function.
add_action( 'foodmania_hook_after_main_header', 'rtp_load_home_slider_template' );

/**
 * Load the banner's template
 *
 * @return void
 */
function rtp_load_banner_template() {

	if ( ! is_front_page() && function_exists( 'bp_is_user' ) && ! bp_is_user() && function_exists( 'bp_is_group' ) && ! bp_is_group() ) {
		$queried_ob_id    = get_queried_object_id();
		$attachment_id    = get_post_meta( $queried_ob_id, 'foodmania_banner_attachment_id', true );
		$featured_image   = wp_get_attachment_image( $attachment_id, 'full' );
		$absolute_class   = $featured_image ? 'rtp-has-featured-image' : false;
		$page_description = get_post_meta( $queried_ob_id, 'foodmania_title_description', true );
		$desc_class       = $page_description ? false : 'rtp-no-description';
		?>

		<div id="rtp-site-banner" class="rtp-site-banner rtp-overlay">
			<?php echo wp_get_attachment_image( $attachment_id, 'full' ); ?>
			<div class="rtp-banner-content <?php echo esc_attr( $absolute_class ); ?> <?php echo esc_attr( $desc_class ); ?> ">
				<div class="rtp-banner-content-inner row">
					<h2 class="rtp-special-title"><?php echo esc_html( foodmania_banner_title( $queried_ob_id ) ); ?></h2>
					<?php
					if ( $page_description ) {
						?>
						<p class="rtp-heading-description">
							<?php
								echo esc_html( $page_description );
							?>
						</p>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php

	}
}
// Action to call the rtp_load_banner_template function.
add_action( 'foodmania_hook_after_main_header', 'rtp_load_banner_template' );
