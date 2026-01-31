<?php
/**
 * Support Page Content
 *
 * @package Foodmania
 */

/**
 * Function to get support form if RTPSupport class exists.
 */
function rtp_support_form() {
	if ( class_exists( 'RTPSupport' ) ) {
		$rtp_support = new RTPSupport();
		$rtp_support->call_get_form();
	}
}

/**
 * Function to get debug info if RTPSupport class exists.
 */
function rtp_debug_info() {
	if ( class_exists( 'RTPSupport' ) ) {
		$rtp_support = new RTPSupport();
		$rtp_support->debug_info_html();
	}
}

if ( ! class_exists( 'RTPSupport' ) ) {

	/**
	 * Class to handle support tickit operations
	 */
	class RTPSupport {

		/**
		 * Gathering debug info.
		 *
		 * @var array
		 */
		private $debug_info;

		/**
		 * Constructor function, return if user is not admin
		 *
		 * @param bool $init on init.
		 */
		public function __construct( $init = true ) {
			if ( $init ) {
				if ( ! is_admin() ) {
					return;
				}
			}
		}

		/**
		 * Function to create form for support.
		 */
		public function call_get_form() {
			echo "<div id='rtp-support' class='wrap'><div id='rtp_service_contact_container'><form name='rtp_service_contact_detail' method='post'>";
			$this->get_form( 'premium_support' );
			echo '</form></div></div>';
		}

		/**
		 * Function to get plugin info.
		 */
		public function get_plugin_info() {
			$active_plugins = (array) get_option( 'active_plugins', array() );
			$rtp_plugins    = array();
			foreach ( $active_plugins as $plugin ) {
				$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
				if ( ! empty( $plugin_data ) ) {
					$version_string = '';
					if ( ! empty( $plugin_data['Name'] ) ) {
						$rtp_plugins[] = $plugin_data['Name'] . ' ' . __( 'by', 'foodmania' ) . ' ' . $plugin_data['Author'] . ' ' . __( 'version', 'foodmania' ) . ' ' . $plugin_data['Version'] . $version_string;
					}
				}
			}
			if ( count( $rtp_plugins ) === 0 ) {
				return false;
			} else {
				return implode( ', <br/>', $rtp_plugins );
			}
		}

		/**
		 * Get all debug info.
		 */
		public function debug_info() {
			global $wpdb, $wp_version, $bp;
			$debug_info                                  = array();
			$debug_info['Home URL']                      = home_url();
			$debug_info['Site URL']                      = site_url();
			$debug_info['PHP']                           = PHP_VERSION;
			$debug_info['MYSQL']                         = $wpdb->db_version();
			$debug_info['WordPress']                     = $wp_version;
			$debug_info['OS']                            = PHP_OS_FAMILY;
			$debug_info['[php.ini] post_max_size']       = ini_get( 'post_max_size' );
			$debug_info['[php.ini] upload_max_filesize'] = ini_get( 'upload_max_filesize' );
			$debug_info['[php.ini] memory_limit']        = ini_get( 'memory_limit' );
			$debug_info['Installed Plugins']             = $this->get_plugin_info();
			$active_theme                                = wp_get_theme();
			$debug_info['Theme Name']                    = $active_theme->Name; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase 
			$debug_info['Theme Version']                 = $active_theme->Version; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$debug_info['Author URL']                    = $active_theme->{'Author URI'};

			$this->debug_info = $debug_info;
		}

		/**
		 * Function to convert debug info to html.
		 */
		public function debug_info_html() {
			$this->debug_info();
			?>
			<div id="rtp-debug-info" class="wrap">
				<div id="debug-info">

					<table class="form-table">
						<tbody>
							<?php
							if ( $this->debug_info ) {
								foreach ( $this->debug_info as $configuration => $value ) {
									?>
									<tr valign="top">
										<th scope="row"><?php echo esc_html( $configuration ); ?></th>
										<td><?php echo wp_kses_post( $value ); ?></td>
									</tr>
									<?php
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<?php
		}

		/**
		 * Get form type add required fields.
		 *
		 * @global type $current_user
		 *
		 * @param string $form form-type.
		 */
		public function get_form( $form = '' ) {

			// Filtering all requests.
			$filtered_form             = filter_input( INPUT_POST, 'form', FILTER_SANITIZE_STRING );
			$filtered_name             = stripslashes( trim( filter_input( INPUT_POST, 'name', FILTER_SANITIZE_STRING ) ) );
			$filtered_email            = stripslashes( trim( filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING ) ) );
			$filtered_website          = stripslashes( trim( filter_input( INPUT_POST, 'website', FILTER_SANITIZE_STRING ) ) );
			$filtered_phone            = stripslashes( trim( filter_input( INPUT_POST, 'phone', FILTER_SANITIZE_STRING ) ) );
			$filtered_subject          = stripslashes( trim( filter_input( INPUT_POST, 'subject', FILTER_SANITIZE_STRING ) ) );
			$filtered_details          = stripslashes( trim( filter_input( INPUT_POST, 'details', FILTER_SANITIZE_STRING ) ) );
			$filtered_admin_username   = stripslashes( trim( filter_input( INPUT_POST, 'wp_admin_username', FILTER_SANITIZE_STRING ) ) );
			$filtered_admin_pwd        = stripslashes( trim( filter_input( INPUT_POST, 'wp_admin_pwd', FILTER_SANITIZE_STRING ) ) );
			$filtered_ssh_ftp_host     = stripslashes( trim( filter_input( INPUT_POST, 'ssh_ftp_host', FILTER_SANITIZE_STRING ) ) );
			$filtered_ssh_ftp_username = stripslashes( trim( filter_input( INPUT_POST, 'ssh_ftp_username', FILTER_SANITIZE_STRING ) ) );
			$filtered_ssh_ftp_pwd      = stripslashes( trim( filter_input( INPUT_POST, 'ssh_ftp_pwd', FILTER_SANITIZE_STRING ) ) );

			if ( empty( $form ) ) {
				$form = ( isset( $filtered_form ) ) ? $filtered_form : '';
			}

			if ( '' === $form ) {
				$form = 'premium_support';
			}

			global $current_user;

			if ( 'premium_support' === $form ) {
				?>
				<div id="support-form" class="rtp-form">
					<ul class="rtp-support-form">
						<li>
							<label class="bp-media-label" for="name"><?php esc_html_e( 'Name', 'foodmania' ); ?>:</label>
							<input 
								id="name" 
								type="text" 
								name="name" 
								value="<?php echo( ! empty( $filtered_name ) ? esc_attr( $filtered_name ) : esc_attr( $current_user->display_name ) ); ?>" 
								required
							/>
						</li>
						<li>
							<label class="bp-media-label" for="email"><?php esc_html_e( 'Email', 'foodmania' ); ?>:</label>
							<input 
								id="email" 
								type="text" 
								name="email" 
								value="<?php echo( ! empty( $filtered_email ) ? esc_attr( $filtered_email ) : esc_attr( get_option( 'admin_email' ) ) ); ?>" 
								required
							/>
						</li>
						<li>
							<label class="bp-media-label" for="website"><?php esc_html_e( 'Website', 'foodmania' ); ?>:</label>
							<input 
								id="website" 
								type="text" 
								name="website"  
								value="<?php echo( ! empty( $filtered_website ) ? esc_url( $filtered_website ) : esc_url( home_url( '/' ) ) ); ?>" 
								required
							/>
						</li>
						<li>
							<label class="bp-media-label" for="phone"><?php esc_html_e( 'Phone', 'foodmania' ); ?> :</label>
							<input 
								id="phone" 
								type="text" 
								name="phone" 
								value="<?php echo( ! empty( $filtered_phone ) ? esc_url( $filtered_phone ) : '' ); ?>"
							/>
						</li>
						<li>
							<label class="bp-media-label" for="subject"><?php esc_html_e( 'Subject', 'foodmania' ); ?>:</label>
							<input 
								id="subject" 
								type="text" 
								name="subject" 
								value="<?php echo( ! empty( $filtered_subject ) ? esc_url( $filtered_subject ) : '' ); ?>" 
								required
							/>
						</li>
						<li>
							<label class="bp-media-label" for="details"><?php esc_html_e( 'Details', 'foodmania' ); ?>:</label>
							<textarea 
								id="details" 
								type="text" 
								name="details" 
								required
							/>
								<?php echo( ! empty( $filtered_details ) ? esc_url( $filtered_details ) : '' ); ?>
							</textarea>
						</li>

						<input type="hidden" name="request_type" value="<?php echo esc_attr( $form ); ?>"/>
						<input type="hidden" name="request_id" value="<?php echo esc_attr( wp_create_nonce( gmdate( 'YmdHis' ) ) ); ?>"/>
						<input type="hidden" name="server_address" value="<?php echo( ! empty( $_SERVER['SERVER_ADDR'] ) ? esc_attr( sanitize_text_field( $_SERVER['SERVER_ADDR'] ) ) : '' ); ?>"/>
						<input type="hidden" name="ip_address" value="<?php echo( ! empty( $_SERVER['REMOTE_ADDR'] ) ? esc_attr( sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) ) : '' ); // phpcs:ignore ?>"/> 
						<input type="hidden" name="server_type" value="<?php echo( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ? esc_attr( sanitize_text_field( $_SERVER['SERVER_SOFTWARE'] ) ) : '' ); ?>"/>
						<input type="hidden" name="user_agent" value="<?php echo( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ? esc_attr( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) ) : '' ); // phpcs:ignore?>"/>
						<input type="hidden" name="tickit_nonce" value="<?php echo esc_attr( wp_create_nonce( 'user_tickit_nonce' ) ); ?>"/>

					</ul>
				</div>
				<!-- .submit-bug-box -->
				<?php
				if ( 'bug_report' === $form ) {
					?>
					<h3 class="rtp-meta-title"><?php esc_html_e( 'Additional Information', 'foodmania' ); ?></h3>
					<div id="support-form" class="rtp-form">
						<ul class="rtp-support-form">
							<li>
								<label for="wp_admin_username"><?php esc_html_e( 'Your WP Admin Login:', 'foodmania' ); ?></label>
								<input 
									id="wp_admin_username" 
									type="text" 
									name="wp_admin_username" 
									value="<?php echo( ! empty( $filtered_admin_username ) ? esc_attr( $filtered_admin_username ) : esc_attr( $current_user->user_login ) ); ?>"
								/>
							</li>
							<li>
								<label class="bp-media-label" for="wp_admin_pwd"><?php esc_html_e( 'Your WP Admin password:', 'foodmania' ); ?></label>
								<input 
									class="bp-media-input" 
									id="wp_admin_pwd" 
									type="password" 
									name="wp_admin_pwd" 
									value="<?php echo( ! empty( $filtered_admin_pwd ) ? esc_attr( $filtered_admin_pwd ) : '' ); ?>"
								/>
							</li>
							<li>
								<label class="bp-media-label" for="ssh_ftp_host"><?php esc_html_e( 'Your SSH / FTP host:', 'foodmania' ); ?></label>
								<input 
									class="bp-media-input" 
									id="ssh_ftp_host" 
									type="text" 
									name="ssh_ftp_host" 
									value="<?php echo( ! empty( $filtered_ssh_ftp_host ) ? esc_attr( $filtered_ssh_ftp_host ) : '' ); ?>"
								/>
							</li>
							<li>
								<label class="bp-media-label" for="ssh_ftp_username"><?php esc_html_e( 'Your SSH / FTP login:', 'foodmania' ); ?></label>
								<input 
									class="bp-media-input" 
									id="ssh_ftp_username" 
									type="text" 
									name="ssh_ftp_username" 
									value="<?php echo( ! empty( $filtered_ssh_ftp_username ) ? esc_attr( $filtered_ssh_ftp_username ) : '' ); ?>"
								/>
							</li>
							<li>
								<label class="bp-media-label" for="ssh_ftp_pwd"><?php esc_html_e( 'Your SSH / FTP password:', 'foodmania' ); ?></label>
								<input 
									class="bp-media-input" 
									id="ssh_ftp_pwd" 
									type="password" 
									name="ssh_ftp_pwd" 
									value="<?php echo( ! empty( $filtered_ssh_ftp_pwd ) ? esc_attr( $filtered_ssh_ftp_pwd ) : '' ); ?>"
								/>
							</li>
						</ul>
					</div><!-- .submit-bug-box -->
					<?php
				}
				?>
				<div class="rtp-button-cotainer">
					<?php submit_button( __( 'Submit', 'foodmania' ), 'primary', 'rtp-submit-request', false ); ?>
				</div>
				<?php
			}
		}

		/**
		 * Function to submit request.
		 */
		public function submit_request() {
			$this->debug_info();
			$rtp_support_url = '';
			$form_data       = array();

			if ( ! empty( $_POST['form_data']['tickit_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['form_data']['tickit_nonce'] ), 'user_tickit_nonce' ) ) {

				// sanitize array form_data.
				$form_data_sanitized = ! empty( $_POST['form_data'] ) ? filter_input( INPUT_POST, 'form_data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY ) : array();
				$form_data           = wp_parse_args( $form_data_sanitized );

			} else {
				return new WP_Error( 'Nonce mismatch.' );
			}

			foreach ( $form_data as $key => $formdata ) {
				if ( '' === $formdata && 'phone' !== $key ) {
					echo 'false';
					die();
				}
			}
			if ( 'premium_support' === $form_data['request_type'] ) {
				$mail_type = 'Premium Support';
				$title     = __( 'foodmania Premium Support Request from', 'foodmania' );
			} elseif ( 'new_feature' === $form_data['request_type'] ) {
				$mail_type = 'New Feature Request';
				$title     = __( 'foodmania New Feature Request from', 'foodmania' );
			} elseif ( 'bug_report' === $form_data['request_type'] ) {
				$mail_type = 'Bug Report';
				$title     = __( 'foodmania Bug Report from', 'foodmania' );
			} else {
				$mail_type = 'Bug Report';
				$title     = __( 'foodmania Contact from', 'foodmania' );
			}
			$message = '<html>
                            <head>
                                    <title>' . $title . get_bloginfo( 'name' ) . '</title>
                            </head>
                            <body>
								<table>
                                    <tr>
                                        <td>Name</td><td>' . wp_strip_all_tags( $form_data['name'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td><td>' . wp_strip_all_tags( $form_data['email'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>Website</td><td>' . wp_strip_all_tags( $form_data['website'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td><td>' . wp_strip_all_tags( $form_data['phone'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>Subject</td><td>' . wp_strip_all_tags( $form_data['subject'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>Details</td><td>' . wp_strip_all_tags( $form_data['details'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>Request ID</td><td>' . wp_strip_all_tags( $form_data['request_id'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>Server Address</td><td>' . wp_strip_all_tags( $form_data['server_address'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>IP Address</td><td>' . wp_strip_all_tags( $form_data['ip_address'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>Server Type</td><td>' . wp_strip_all_tags( $form_data['server_type'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>User Agent</td><td>' . wp_strip_all_tags( $form_data['user_agent'] ) . '</td>
                                    </tr>';
			if ( 'bug_report' === $form_data['request_type'] ) {
				$message .=
									'<tr>
                                        <td>WordPress Admin Username</td><td>' . wp_strip_all_tags( $form_data['wp_admin_username'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>WordPress Admin Password</td><td>' . wp_strip_all_tags( $form_data['wp_admin_pwd'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>SSH FTP Host</td><td>' . wp_strip_all_tags( $form_data['ssh_ftp_host'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>SSH FTP Username</td><td>' . wp_strip_all_tags( $form_data['ssh_ftp_username'] ) . '</td>
                                    </tr>
                                    <tr>
                                        <td>SSH FTP Password</td><td>' . wp_strip_all_tags( $form_data['ssh_ftp_pwd'] ) . '</td>
                                    </tr>
                                    ';
			}
			$message .= '</table>';

			if ( $this->debug_info ) {
				$message .= '<h3>' . __( 'Debug Info', 'foodmania' ) . '</h3>';
				$message .= '<table>';

				foreach ( $this->debug_info as $configuration => $value ) {
					$message .= '<tr>
                                    <td style="vertical-align:top">' . $configuration . '</td><td>' . $value . '</td>
                                </tr>';
				}
				$message .= '</table>';
			}

			$message .= '</body></html>';

			add_filter(
				'wp_mail_content_type',
				function() {
					return 'text/html';
				}
			);

			$headers       = 'From: ' . $form_data['name'] . ' <' . $form_data['email'] . '>' . "\r\n";
			$support_email = 'support@rtcamp.com';

			// phpcs:ignore 
			if ( wp_mail( $support_email, '[foodmania] ' . $mail_type . ' from ' . str_replace( array( 'http://', 'https://' ), '', $form_data['website'] ), $message, $headers ) ) {

				echo '<div class="rtp-success">';
				if ( 'new_feature' === $form_data['request_type'] ) {
					echo '<p>' . esc_html__( 'Thank you for your Feedback/Suggestion.', 'foodmania' ) . '</p>';
				} else {
					echo '<p>' . esc_html__( 'Thank you for posting your support request.', 'foodmania' ) . '</p>';
					echo '<p>' . esc_html__( 'We will get back to you shortly.', 'foodmania' ) . '</p>';
				}
				echo '</div>';

			} else {

				echo '<div class="rtp-error">';
				echo '<p>' . esc_html__( 'Your server failed to send an email.', 'foodmania' ) . '</p>';
				echo '<p>' . esc_html__( 'Kindly contact your server support to fix this.', 'foodmania' ) . '</p>';

				echo wp_kses(
					/* translators: %s: url to support. */
					'<p>' . sprintf( __( 'You can alternatively create a support request <a href="%s">here</a>', 'foodmania' ), esc_url( $rtp_support_url ) ) . '</p>'
				);
				echo '</div>';

			}
			die();
		}
	}

}
