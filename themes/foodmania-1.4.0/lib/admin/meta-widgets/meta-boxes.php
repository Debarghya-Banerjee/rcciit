<?php
/**
 * Creates metaboxes for the theme.
 *
 * @package Foodmania
 */

if ( ! class_exists( 'Foodmania_Metaboxes' ) ) :
	/**
	 * Class for Metaboxes.
	 */
	class Foodmania_Metaboxes {

		/**
		 * Constructor of the class.
		 */
		public function __construct() {

			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
		}

		/**
		 * Invoke method of the class.
		 *
		 * @return void
		 */
		public function load_scripts() {
			wp_register_script( 'foodmania-meta-js', RTP_ADMIN_URI . 'meta-widgets/js/meta-widgets.js', array( 'jquery' ), FOODMANIA_VERSION, true );
			wp_enqueue_script( 'foodmania-meta-js' );
		}

		/**
		 * Adds a box to the main column on the Post and Page edit screens.
		 */
		public function add_meta_box() {

			$screens = array( 'post', 'page' );

			global $post;

			if ( get_page_template_slug( $post->ID ) === 'page-templates/template-home.php' ) {
				return;
			}

			foreach ( $screens as $screen ) {

				add_meta_box(
					'foodmania_banner_description',
					__( 'Add Page Description', 'foodmania' ),
					array( $this, 'description_meta_box_callback' ),
					$screen,
					'normal',
					'high'
				);

				add_meta_box(
					'foodmania_banner_image',
					__( 'Banner Image', 'foodmania' ),
					array( $this, 'banner_meta_box_callback' ),
					$screen,
					'side',
					'low'
				);
			}
		}

		/**
		 * Prints the box content.
		 *
		 * @param WP_Post $post The object for the current post/page.
		 */
		public function description_meta_box_callback( $post ) {

			// Add a nonce field so we can check for it later.
			wp_nonce_field( 'foodmania_title_description_metabox', 'foodmania_title_description_metabox_nonce' );

			/**
			 * Use get_post_meta() to retrieve an existing value
			 * from the database and use the value for the form.
			 */
			$value = get_post_meta( $post->ID, 'foodmania_title_description', true );

			require_once RTP_ADMIN_PATH . '/meta-widgets/meta-templates/rtp-foodmania-banner-description.php';
		}

		/**
		 * Prints the box content.
		 *
		 * @param WP_Post $post The object for the current post/page.
		 */
		public function banner_meta_box_callback( $post ) {

			// Add a nonce field so we can check for it later.
			wp_nonce_field( 'foodmania_banner_image_metabox', 'foodmania_banner_image_metabox_nonce' );

			$markup = '';

			/**
			 * Use get_post_meta() to retrieve an existing value
			 * from the database and use the value for the form.
			 */
			$value = get_post_meta( $post->ID, 'foodmania_banner_image', true );
			$url   = esc_url( $value );

			require RTP_ADMIN_PATH . '/meta-widgets/meta-templates/rtp-foodmania-banner-image.php';

		}


		/**
		 * When the post is saved, saves our custom data.
		 *
		 * @param int $post_id The ID of the post being saved.
		 */
		public function save_meta_box_data( $post_id ) {

			/**
			 * We need to verify this came from our screen and with proper authorization,
			 * because the save_post action can be triggered at other times.
			 */

			// Check if our nonce is set.
			if ( ! isset( $_POST['foodmania_title_description_metabox_nonce'] )
			|| ! isset( $_POST['foodmania_banner_image_metabox_nonce'] ) ) {
				return;
			}

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( sanitize_text_field( $_POST['foodmania_title_description_metabox_nonce'] ), 'foodmania_title_description_metabox' )
			|| ! wp_verify_nonce( sanitize_text_field( $_POST['foodmania_banner_image_metabox_nonce'] ), 'foodmania_banner_image_metabox' ) ) {
				return;
			}

			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// Check the user's permissions.
			if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {

				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {

				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}

			/* OK, it's safe for us to save the data now. */

			// Make sure that it is set.
			if ( isset( $_POST['foodmania_title_description_field'] ) ) {
				// Sanitize user input.
				$my_data = sanitize_text_field( $_POST['foodmania_title_description_field'] );

				// Update the meta field in the database.
				update_post_meta( $post_id, 'foodmania_title_description', $my_data );
			}

			// Make sure that it is set.
			if ( isset( $_POST['foodmania_banner_image_field'] ) ) {
				// Sanitize user input.
				$banner_image_url = esc_url_raw( $_POST['foodmania_banner_image_field'] );

				// No need to save empty meta data, instead delete it.

				if ( ! empty( $banner_image_url ) ) {

					// Update the meta field in the database.
					update_post_meta( $post_id, 'foodmania_banner_image', esc_url( $banner_image_url ) );

					$attachment_id = attachment_url_to_postid( esc_url( $banner_image_url ) ); // phpcs:ignore WordPressVIPMinimum

					update_post_meta( $post_id, 'foodmania_banner_attachment_id', $attachment_id );

				} else {

					// Delete the metadata if found empty.
					delete_post_meta( $post_id, 'foodmania_banner_image' );
					delete_post_meta( $post_id, 'foodmania_banner_attachment_id' );

				}
			}

		}
	}

endif;

new Foodmania_Metaboxes();
