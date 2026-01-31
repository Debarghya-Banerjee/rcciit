<?php
/**
 * Rtp admin loads from here.
 *
 * @package Foodmania
 */

// Enter the path where you have put the admin folder.
define( 'RTP_ADMIN_FOLDER_PATH', '/lib/admin/' );

define( 'RTP_ADMIN_PATH', get_template_directory() . RTP_ADMIN_FOLDER_PATH );
define( 'RTP_ADMIN_URI', get_template_directory_uri() . RTP_ADMIN_FOLDER_PATH );
define( 'RTP_CUSTOMIZER_PATH', RTP_ADMIN_PATH . 'customizer/' );
define( 'RTP_ADMIN_SLIDER_PATH', RTP_ADMIN_PATH . 'rtp-slider/' );

// define version.
define( 'RTP_ADMIN_VERSION', '1.0.0' );

// Loading Files.
require_once RTP_CUSTOMIZER_PATH . 'customizer.php';
require_once RTP_ADMIN_SLIDER_PATH . 'rtp-slider.php';
require_once RTP_ADMIN_PATH . '/meta-widgets/meta-boxes.php';
require_once RTP_ADMIN_PATH . '/meta-widgets/rtp-widgets.php';

/**
 * Creates Admin Menu
 */
if ( ! class_exists( 'RTP_Admin' ) ) {

	/**
	 * Class file for adding the Menu Titles.
	 */
	class RTP_Admin {

		/**
		 * Static string to use later.
		 *
		 * @var string
		 */
		public static $slug_prefix = 'foodmania';

		/**
		 * Constructor for the class in which the actions are added.
		 */
		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_pages' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_ajax_rtp_submit_request', array( $this, 'rtp_submit_support_request' ), 1 );
		}

		/**
		 * Function to register the pages.
		 *
		 * @return void
		 */
		public function register_pages() {
			global $rtcamp_slider;

			foreach ( $this->get_admin_subpages() as $admin_page ) {
				add_submenu_page( $rtcamp_slider->slider_options_name, $admin_page['menu_title'], $admin_page['menu_title'], 'manage_options', $admin_page['menu_slug'], array( $this, $admin_page['callback'] ) );
			}
		}

		/**
		 * Function to get the admin subpages.
		 */
		public function get_admin_subpages() {

			return apply_filters(
				'foodmania_admin_subpages',
				array(
					'plugins'    => array(
						'menu_title' => __( 'Plugins', 'foodmania' ),
						'menu_slug'  => self::$slug_prefix . '-plugins',
						'callback'   => 'plugins_page_callback',
					),
					'support'    => array(
						'menu_title' => __( 'Support', 'foodmania' ),
						'menu_slug'  => self::$slug_prefix . '-support',
						'callback'   => 'support_page_callback',
					),
					'debug_info' => array(
						'menu_title' => __( 'Debug Info', 'foodmania' ),
						'menu_slug'  => self::$slug_prefix . '-debug-info',
						'callback'   => 'debug_info_page_callback',
					),
				)
			);
		}

		/**
		 * The callback function for plugins.
		 *
		 * @return void
		 */
		public function plugins_page_callback() {
			?>
			<div class="wrap rtp-admin-page">
				<?php Rtcamp_Slider_Admin::header_tabs(); ?>
				<h2><?php esc_html_e( 'Suggested Plugins for Foodmania', 'foodmania' ); ?></h2>
				<?php rtp_plugins_submenu_page_callback(); ?>
			</div>
			<?php
		}

		/**
		 * The callback function for support.
		 *
		 * @return void
		 */
		public function support_page_callback() {
			?>
			<div class="wrap rtp-admin-page">
				<?php Rtcamp_Slider_Admin::header_tabs(); ?>
				<h2><span class="dashicons dashicons-sos"></span><?php esc_html_e( 'Foodmania Support', 'foodmania' ); ?></h2>
				<h3><?php esc_html_e( 'Create a ticket', 'foodmania' ); ?></h3>
				<?php rtp_support_form(); ?>
			</div>
			<?php
		}

		/**
		 * The callback function for license.
		 *
		 * @return void
		 */
		public function license_page_callback() {
			?>
			<div class="wrap rtp-admin-page">
				<?php Rtcamp_Slider_Admin::header_tabs(); ?>
				<h2><span class="dashicons dashicons-admin-network"></span><?php esc_html_e( 'Foodmania Theme Licence Key', 'foodmania' ); ?></h2>
				<h4><?php esc_html_e( 'Enter Licence Key, save and activate it.', 'foodmania' ); ?></h4>
				<div id="poststuff" class="wrap rtp-license-settings postbox">
					<?php do_action( 'foodmania_license_key_api' ); ?>
				</div>
			</div>
			<?php
		}

		/**
		 * The callback function for debug info.
		 *
		 * @return void
		 */
		public function debug_info_page_callback() {
			?>
			<div class="wrap rtp-admin-page">
				<?php Rtcamp_Slider_Admin::header_tabs(); ?>
				<h2><span class="dashicons dashicons-chart-bar"></span><?php esc_html_e( 'Debug Info', 'foodmania' ); ?></h2>
				<p><?php esc_html_e( 'We may ask you to provide this information for debugging purposes.', 'foodmania' ); ?></p>
				<?php rtp_debug_info(); ?>
			</div>
			<?php
		}

		/**
		 * Enqueue the scripts.
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			$get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

			if ( isset( $get_page ) && in_array( $get_page, array( self::$slug_prefix . '-plugins', self::$slug_prefix . '-support', self::$slug_prefix . '-license', self::$slug_prefix . '-debug-info' ), true ) ) {

				wp_enqueue_script( 'rtp-admin-scripts', RTP_ADMIN_URI . 'rtp-extensions/js/rtp-admin-min.js', array(), RTP_ADMIN_VERSION, false );
				wp_enqueue_style( 'rtp-admin-styles', RTP_ADMIN_URI . 'rtp-extensions/css/rtp-admin.css', array(), RTP_ADMIN_VERSION, false );

			}
		}

		/**
		 * Function to submit the request of support.
		 *
		 * @return void
		 */
		public function rtp_submit_support_request() {
			if ( class_exists( 'rtpSupport' ) ) {
				$ib_support = new rtpSupport( false );
				$ib_support->submit_request();
			}
		}
	}
}

new RTP_Admin();
