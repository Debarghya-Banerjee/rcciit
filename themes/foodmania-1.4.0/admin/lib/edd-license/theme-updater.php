<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Foodmania Theme
 */

// Includes the files needed for the theme updater.
if ( ! class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include_once dirname( __FILE__ ) . '/theme-updater-admin.php';
}

$test_license = trim( get_option( 'edd_foodmania_license_key' ) );

global $updater;
// Loads the updater classes.
$updater = new EDD_Theme_Updater_Admin(
	// Config settings.
	array(
		'remote_api_url' => FOODMANIA_EDD_SL_STORE_URL, // Site where EDD is hosted.
		'item_name'      => FOOMANIA_EDD_SL_THEME_NAME, // Name of theme.
		'theme_slug'     => 'edd_foodmania', // Theme slug.
		'license'        => $test_license, // The license key (used get_option above to retrieve from DB).
		'version'        => FOODMANIA_VERSION, // The current version of this theme.
		'author'         => 'rtCamp', // The author of this theme.
		'download_id'    => '', // Optional, used for generating a license renewal link.
		'renew_url'      => '', // Optional, allows for a custom license renewal link.
		'beta'           => false, // Optional, set to true to opt into beta versions.
	),
	// Strings.
	array(
		'theme-license'             => __( 'Theme License', 'foodmania' ),
		'enter-key'                 => __( 'Enter your theme license key.', 'foodmania' ),
		'license-key'               => __( 'License Key', 'foodmania' ),
		'license-action'            => __( 'License Action', 'foodmania' ),
		'deactivate-license'        => __( 'Deactivate License', 'foodmania' ),
		'activate-license'          => __( 'Activate License', 'foodmania' ),
		'status-unknown'            => __( 'License status is unknown.', 'foodmania' ),
		'renew'                     => __( 'Renew?', 'foodmania' ),
		'unlimited'                 => __( 'unlimited', 'foodmania' ),
		'license-key-is-active'     => __( 'License key is active.', 'foodmania' ),
		/* translators: %s is date */
		'expires%s'                 => __( 'Expires %s.', 'foodmania' ),
		'expires-never'             => __( 'Lifetime License.', 'foodmania' ),
		/* translators: %s is count */
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'foodmania' ),
		/* translators: %s is key */
		'license-key-expired-%s'    => __( 'License key expired %s.', 'foodmania' ),
		'license-key-expired'       => __( 'License key has expired.', 'foodmania' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'foodmania' ),
		'license-is-inactive'       => __( 'License is inactive.', 'foodmania' ),
		'license-key-is-disabled'   => __( 'License key is disabled.', 'foodmania' ),
		'site-is-inactive'          => __( 'Site is inactive.', 'foodmania' ),
		'license-status-unknown'    => __( 'License status is unknown.', 'foodmania' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'foodmania' ),
		/* translators: %1$s is value %2$s is value %3$s is link %4$s is title %5$s is link %6$s is link*/
		'update-available'          => __( '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4$s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'foodmania' ),
	)
);
