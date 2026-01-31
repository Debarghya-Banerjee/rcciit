<?php
/**
 * Maintaining the supported Plugins and displaying Plugin List.
 *
 * @package Foodmania
 */

/**
 * Supported Plugin List
 */
function rtp_install_plugin_manage_hook() {
	if ( current_user_can( 'install_plugins' ) ) {
		add_action( 'wp_ajax_rtp_plugin_manage', 'rtp_manage_plugin' );
	}
}

add_action( 'admin_init', 'rtp_install_plugin_manage_hook' );

/**
 * Plugins Page Content
 */
global $rtp_support_plugins;

$rtp_support_plugins = array(
	'bbpress'                          => array(
		'title'         => 'bbPress',
		'plugin_path'   => 'bbpress/bbpress.php',
		'plugin_link'   => '//wordpress.org/plugins/bbpress/',
		'is_rt_product' => false,
		'product_link'  => false,
	),
	'buddypress'                       => array(
		'title'         => 'BuddyPress',
		'plugin_path'   => 'buddypress/bp-loader.php',
		'plugin_link'   => '//wordpress.org/plugins/buddypress/',
		'is_rt_product' => false,
		'product_link'  => false,
	),
	'buddypress-docs'                  => array(
		'title'         => 'BuddyPress Docs',
		'plugin_path'   => 'buddypress-docs/loader.php',
		'plugin_link'   => '//wordpress.org/plugins/buddypress-docs/',
		'is_rt_product' => false,
		'product_link'  => false,
	),
	'jetpack'                          => array(
		'title'         => 'Jetpack by WordPress.com',
		'plugin_path'   => 'jetpack/jetpack.php',
		'plugin_link'   => '//wordpress.org/plugins/jetpack/',
		'is_rt_product' => false,
		'product_link'  => false,
	),
	'ninja-forms'                      => array(
		'title'         => 'Ninja Forms',
		'plugin_path'   => 'ninja-forms/ninja-forms.php',
		'plugin_link'   => '//wordpress.org/plugins/ninja-forms/',
		'is_rt_product' => false,
		'product_link'  => false,
	),
	'regenerate-thumbnails'            => array(
		'title'         => 'Regenerate Thumbnails',
		'plugin_path'   => 'regenerate-thumbnails/regenerate-thumbnails.php',
		'plugin_link'   => '//wordpress.org/plugins/regenerate-thumbnails/',
		'is_rt_product' => false,
		'product_link'  => false,
	),
	'rtsocial'                         => array(
		'title'         => 'rtSocial',
		'plugin_path'   => 'rtsocial/source.php',
		'plugin_link'   => '//wordpress.org/plugins/rtsocial/',
		'is_rt_product' => true,
		'product_link'  => false,
	),
	'rtwidgets'                        => array(
		'title'         => 'rtWidgets',
		'plugin_path'   => 'rtwidgets/rtwidgets-main.php',
		'plugin_link'   => '//wordpress.org/plugins/rtwidgets/',
		'is_rt_product' => true,
		'product_link'  => false,
	),
	'buddypress-media'                 => array(
		'title'         => 'rtMedia for WordPress, BuddyPress and bbPress',
		'plugin_path'   => 'buddypress-media/index.php',
		'plugin_link'   => '//wordpress.org/plugins/buddypress-media/',
		'is_rt_product' => true,
		'product_link'  => false,
	),
	'woocommerce'                      => array(
		'title'         => 'WooCommerce - excelling eCommerce',
		'plugin_path'   => 'woocommerce/woocommerce.php',
		'plugin_link'   => '//wordpress.org/plugins/woocommerce/',
		'is_rt_product' => false,
		'product_link'  => false,
	),
	'wordpress-seo'                    => array(
		'title'         => 'WordPress SEO by Yoast',
		'plugin_path'   => 'wordpress-seo/wp-seo.php',
		'plugin_link'   => '//wordpress.org/plugins/wordpress-seo/',
		'is_rt_product' => false,
		'product_link'  => false,
	),
	'yet-another-related-posts-plugin' => array(
		'title'         => 'YARPP – Yet Another Related Posts Plugin',
		'plugin_path'   => 'yet-another-related-posts-plugin/yarpp.php',
		'plugin_link'   => '//wordpress.org/plugins/yet-another-related-posts-plugin/',
		'is_rt_product' => false,
		'product_link'  => false,
	),
);

/**
 * Include class-wp-upgrader.php
 */
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

if ( ! class_exists( 'RTP_Plugin_Upgrader_Skin' ) ) {

	/**
	 * Class to extend Upgrader Skin for all plugins.
	 */
	class RTP_Plugin_Upgrader_Skin extends WP_Upgrader_Skin {

		/**
		 * Sets up the generic skin for the WordPress Upgrader classes.
		 *
		 * @param array $args pass arguments to override default options.
		 */
		public function __construct( $args = array() ) {
			$defaults = array(
				'type'   => 'web',
				'url'    => '',
				'plugin' => '',
				'nonce'  => '',
				'title'  => '',
			);
			$args     = wp_parse_args( $args, $defaults );

			$this->type = $args['type'];
			$this->api  = isset( $args['api'] ) ? $args['api'] : array();

			parent::__construct( $args );
		}

		/**
		 * Displays a form to the user to request for their FTP/SSH details in order to connect to the filesystem
		 *
		 * @param bool $error wheather current request has failed to connect.
		 * @param bool $context full path to directory that is tested for being writable.
		 * @param bool $allow_relaxed_file_ownership weather to allow world writable.
		 */
		public function request_filesystem_credentials( $error = false, $context = false, $allow_relaxed_file_ownership = false ) {
			return true;
		}

		/**
		 * Throw a error
		 *
		 * @param WP_Error $errors Errors passed.
		 */
		public function error( $errors ) {
			die( '-1' );
		}

		/**
		 * Header function
		 */
		public function header() {

		}

		/**
		 * Footer function.
		 */
		public function footer() {

		}
		
		/**
		 * Feedback function
		 *
		 * @param string $string feedback string to be passed.
		 * @param array  ...$args splat-operated arguments.
		 */
		public function feedback( $string, ...$args ) {

		}
	}

}

/**
 * Get Supported Plugin List
 *
 * @global array $rtp_support_plugins
 * @return type
 */
function rtp_get_supported_plugin() {
	global $rtp_support_plugins;
	return apply_filters( 'rtp_supported_plugin_list', $rtp_support_plugins );
}

/**
 * Manage Plugins
 */
function rtp_manage_plugin() {

	// Get all supported plugins.
	$rtp_support_plugins = rtp_get_supported_plugin();

	if ( empty( $_POST['nonce'] ) || empty( $_POST['plugin'] ) || empty( $_POST['plugin_action'] ) ) {
		wp_send_json_error(
			array(
				'success' => false,
				'data'    => esc_html__( 'ERROR: Data not sufficient.', 'foodmania' ),
			)
		);
	}

	// Verifying nonces.
	if ( ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), sanitize_key( $_POST['plugin'] ) ) ) {
		wp_send_json_error(
			array(
				'success' => false,
				'data'    => esc_html__( 'ERROR: Nonce mismatch.', 'foodmania' ),
			)
		);
	}

	if ( empty( $rtp_support_plugins[ $_POST['plugin'] ] ) ) {
		wp_send_json_error(
			array(
				'success' => false,
				'data'    => esc_html__( 'ERROR: Plugin not in support plugins list.', 'foodmania' ),
			)
		);
	}

	// get plugin name.
	$plugin = sanitize_key( $_POST['plugin'] );

	switch ( $_POST['plugin_action'] ) {

		case 'activate':
			$return = activate_plugins( (array) $rtp_support_plugins[ $plugin ]['plugin_path'] );
			if ( is_wp_error( $return ) ) {
				wp_send_json_error(
					array(
						'success' => false,
						'data'    => esc_html( $return->get_error_message() ),
					)
				);
			} else {
				wp_send_json_success();
			}
			break;

		case 'deactivate':
			$return = deactivate_plugins( (array) $rtp_support_plugins[ $plugin ]['plugin_path'] );
			if ( is_wp_error( $return ) ) {
				wp_send_json_error(
					array(
						'success' => false,
						'data'    => esc_html( $return->get_error_message() ),
					)
				);
			} else {
				wp_send_json_success();
			}
			break;

		case 'delete':
			$return = delete_plugins( (array) $rtp_support_plugins[ $plugin ]['plugin_path'] );
			if ( is_wp_error( $return ) ) {
				wp_send_json_error(
					array(
						'success' => false,
						'data'    => esc_html( $return->get_error_message() ),
					)
				);
			} else {
				wp_send_json_success();
			}
			break;

		case 'install':
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

			$api = plugins_api(
				'plugin_information',
				array(
					'slug'   => $plugin,
					'fields' => array( 'sections' => false ),
				)
			);

			if ( is_wp_error( $api ) ) {
				wp_send_json_error(
					array(
						'success' => false,
						'data'    => sprintf(
							/* translators: %s: plugin name. */
							esc_html__(
								'ERROR: Failed to install plugin %s',
								'foodmania'
							),
							esc_html( $plugin() )
						),
					)
				);
			}

			$upgrader = new Plugin_Upgrader(
				new RTP_Plugin_Upgrader_Skin(
					array(
						'nonce'  => 'install-plugin_' . $plugin,
						'plugin' => $plugin,
						'api'    => $api,
					)
				)
			);

			$install_result = $upgrader->install( $api->download_link );

			if ( ! $install_result || is_wp_error( $install_result ) ) {
				/* $install_result can be false if the file system isn't writable. */
				$error_message = __( 'Please ensure the file system is writable', 'foodmania' );

				if ( is_wp_error( $install_result ) ) {
					$error_message = $install_result->get_error_message();
				}

				wp_send_json_error(
					array(
						'success' => false,
						'data'    => sprintf(
							/* translators: %s: error message. */
							esc_html__(
								'ERROR: Failed to install plugin: %s',
								'foodmania'
							),
							esc_html( $error_message )
						),
					)
				);
			} else {
				wp_send_json_success();
			}
			break;

	}
	wp_send_json_error();
}

/**
 * Function to install plugin
 */
function rtp_install_plugin() {

	// verify if slug was passed to ajax callback.
	if ( empty( $_POST['plugin_slug'] ) ) {
		die( esc_html__( 'ERROR: No slug was passed to the AJAX callback.', 'foodmania' ) );
	}

	$plugin_slug = sanitize_key( $_POST['plugin_slug'] );

	check_ajax_referer( sanitize_key( $plugin_slug ) . '-install' );

	if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
		die( esc_html__( 'ERROR: You lack permissions to install and/or activate plugins.', 'foodmania' ) );
	}

	include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

	$api = plugins_api(
		'plugin_information',
		array(
			'slug'   => $plugin_slug,
			'fields' => array( 'sections' => false ),
		)
	);

	if ( is_wp_error( $api ) ) {
		die(
			sprintf(
			/* translators: %s: error message. */
				esc_html__( 'ERROR: Error fetching plugin information: %s', 'foodmania' ),
				esc_html( $api->get_error_message() )
			)
		);
	}

	$upgrader = new Plugin_Upgrader(
		new RTP_Plugin_Upgrader_Skin(
			array(
				'nonce'  => 'install-plugin_' . $plugin_slug,
				'plugin' => $plugin_slug,
				'api'    => $api,
			)
		)
	);

	$install_result = $upgrader->install( $api->download_link );

	if ( ! $install_result || is_wp_error( $install_result ) ) {
		/* $install_result can be false if the file system isn't writable. */
		$error_message = __( 'Please ensure the file system is writable', 'foodmania' );

		if ( is_wp_error( $install_result ) ) {
			$error_message = $install_result->get_error_message();
		}

		die(
			sprintf(
			/* translators: %s: error message. */
				esc_html__( 'ERROR: Failed to install plugin: %s', 'foodmania' ),
				esc_html( $error_message )
			)
		);
	}

	echo 'true';
	die();
}

/**
 * Submenu page for plugin
 */
function rtp_plugins_submenu_page_callback() {
	$plugins         = get_plugins();
	$support_plugins = rtp_get_supported_plugin();
	?>
	<div id="poststuff" class="wrap rtp-manage-plugin postbox">

		<div class="inside">

			<table class="form-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Plugin', 'foodmania' ); ?></th>
						<th><?php esc_html_e( 'Status', 'foodmania' ); ?></th>
						<th><?php esc_html_e( 'Action', 'foodmania' ); ?></th>
						<th><?php esc_html_e( 'Edit', 'foodmania' ); ?></th>
					</tr>
				</thead>

				<?php
				foreach ( $support_plugins as $plugin_key => $plugin_info ) {
					?>
					<tr>
						<?php
						if ( is_plugin_active( $plugin_info['plugin_path'] ) ) {
							$status  = __( 'Active', 'foodmania' );
							$st_flag = 'active';
						} elseif ( array_key_exists( $plugin_info['plugin_path'], $plugins ) ) {
							$status  = __( 'Inactive', 'foodmania' );
							$st_flag = 'inactive';
						} else {
							$status  = __( 'Not Installed', 'foodmania' );
							$st_flag = 'not-installed';
						}
						?>
						<td>
							<a target="_blank" href="<?php echo esc_url( $plugin_info['plugin_link'] ); ?>"><?php echo esc_html( $plugin_info['title'] ); ?></a>
						</td>

						<td class="<?php echo esc_attr( str_replace( ' ', '-', strtolower( $status ) ) ); ?>">
							<?php echo esc_html( $status ); ?>
						</td>

						<td>
						<?php
							$action_label = array();
							$action       = array();
							$links        = array();
						switch ( $st_flag ) {
							case 'active':
								$action_label[] = __( 'Deactivate', 'foodmania' );
								$action []      = 'deactivate';
								$links []       = '#';
								break;
							case 'inactive':
								$action_label[] = __( 'Activate', 'foodmania' );
								$action []      = 'activate';
								$links []       = '#';

								$action_label[] = __( 'Delete', 'foodmania' );
								$action []      = 'delete';
								$links []       = '#';
								break;
							default:
								if ( false === $plugin_info['is_rt_product'] || false === $plugin_info['product_link'] ) {
									$action_label[] = __( 'Install', 'foodmania' );
									$action []      = 'install';
									$links []       = '#';
								} else {
									$action_label[] = __( 'Buy Now', 'foodmania' );
									$action []      = 'purchase';
									$links []       = $plugin_info['product_link'];
								}
						}

						$sep = '';
						foreach ( $action_label as $key => $val ) {
							echo esc_html( $sep );
							?>
							<a class='rtp-manage-plugin-action <?php echo esc_attr( $action[ $key ] ); ?>' 
								data-plugin='<?php echo esc_attr( $plugin_key ); ?>' 
								href='<?php echo esc_url( $links[ $key ] ); ?>' 
								data-action='<?php echo esc_attr( $action[ $key ] ); ?>' 
								data-site-url='<?php echo esc_url( get_template_directory_uri() ); ?>' 
								data-plugin-title ='<?php echo esc_attr( $plugin_info['title'] ); ?>' 
								data-nonce='<?php echo esc_attr( wp_create_nonce( $plugin_key ) ); ?>'
							>
								<?php echo esc_html( $action_label[ $key ] ); ?>
							</a>
							<?php
							$sep = '/ ';
						}
						?>
						</td>

						<td>
						<?php
						if ( 'not-installed' !== $st_flag ) {
							?>

							<a href="<?php echo esc_url( admin_url( 'plugin-editor.php?file=' . $plugin_info['plugin_path'] ) ); ?>">
								<?php esc_html_e( 'Edit', 'foodmania' ); ?>
							</a>

							<?php
						} else {
							?>
							-----
							<?php
						}
						?>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
		</div>
	</div>
	<?php
}
