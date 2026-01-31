<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Foodmania Theme
 */

/**
 * Load theme updater functions.
 */
function foodmania_theme_updater() {
	require_once FOODMANIA_ADMIN . '/lib/edd-license/theme-updater.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
}

add_action( 'after_setup_theme', 'foodmania_theme_updater' );

/**
 * Migrate rtMedia cover images to BuddyPress cover images
 *
 * @param mixed  $object_id   ID of the members or group.
 * @param string $object_type Name of the object member or group.
 *
 * @return bool|mixed Value of Option ID.
 */
function foodmania_migrate_cover_bp( $object_id = null, $object_type = 'members' ) {
	if ( empty( $object_id ) ) {
		if ( 'groups' === $object_type ) {
			$object_id = bp_get_current_group_id();
		} elseif ( 'members' === $object_type ) {
			$object_id = wp_get_current_user();
		} else {
			return false;
		}
	}

	if ( 'members' === $object_type ) {
		$rtm_media_id = get_user_meta( $object_id, 'rtmedia_featured_media' );
		if ( empty( $rtm_media_id ) ) {
			return false;
		}
		$rtm_media_id = $rtm_media_id[0];
	} elseif ( 'groups' === $object_type ) {
		$rtm_media_id = groups_get_groupmeta( $object_id, 'rtmedia_group_featured_media' );
	} else {
		return false;
	}
	if ( ! empty( $rtm_media_id ) ) {
		global $wpdb;
		$query_media_string = printf( 'SELECT media_id FROM ' . esc_html( $wpdb->prefix ) . 'rt_rtm_media WHERE id=%d', esc_html( $rtm_media_id ) );
		$media_id           = wp_cache_get( md5( $query_media_string ) );
		if ( false === $media_id ) {
			$media_id = $wpdb->get_var( $wpdb->prepare( 'SELECT media_id FROM ' . $wpdb->prefix . 'rt_rtm_media WHERE id=%d', $rtm_media_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			wp_cache_set( md5( $query_media_string ), $media_id );
		}
		$source_path = get_attached_file( $media_id );
		$extension   = '.' . pathinfo( $source_path, PATHINFO_EXTENSION );

		$destination_path = bp_attachments_get_attachment(
			'path',
			array(
				'object_dir' => $object_type,
				'item_id'    => $object_id,
			)
		);
		if ( empty( $destination_path ) ) {
			$object_dir_path = bp_attachments_uploads_dir_get( 'basedir' ) . '/' . $object_type . '/' . $object_id;
			$cover_dir_path  = trailingslashit( $object_dir_path ) . 'cover-image';
			wp_mkdir_p( $cover_dir_path );
			$destination_path = trailingslashit( $cover_dir_path ) . uniqid() . 'bp-cover-image' . $extension;
		}
		copy( $source_path, $destination_path );
		// Removing rtMedia featured meta.
		if ( 'members' === $object_type ) {
			delete_user_meta( $object_id, 'rtmedia_featured_media' );
		} elseif ( 'groups' === $object_type ) {
			$rtm_media_id = groups_delete_groupmeta( $object_id, 'rtmedia_group_featured_media' );
		} else {
			return false;
		}
	}
}

/**
 * This function runs when WordPress completes its upgrade process
 * It iterates through each theme updated to see if ours is included
 *
 * @param Array $upgrader_object WP_Upgrader object.
 * @param Array $options         Array of thing hav updated.
 */
function foodmania_upgrade_completed( $upgrader_object, $options ) {
	// If an update has taken place and the updated type is themes and the themes element exists.
	if ( 'update' === $options['action'] && 'theme' === $options['type'] && isset( $options['themes'] ) ) {
		foreach ( $options['themes'] as $theme ) {
			if ( 'foodmania' === $theme ) {

				// Migrate User covers.
				if ( is_plugin_active( 'buddypress/bp-loader.php' ) ) {
					$users = get_users( array( 'fields' => array( 'id' ) ) );
					foreach ( $users as $user ) {
						foodmania_migrate_cover_bp( $user->id );
					}

					// Migrate Group covers.
					if ( class_exists( 'BP_Groups_Group' ) ) {
						$groups = BP_Groups_Group::get(
							array(
								'fields' => 'ids',
							)
						);
						if ( ! empty( $groups['groups'] ) ) {
							foreach ( $groups['groups'] as $group ) {
								foodmania_migrate_cover_bp( $group, 'groups' );
							}
						}
					}
				}
			}
		}
	}
}
add_action( 'upgrader_process_complete', 'foodmania_upgrade_completed', 10, 2 );
