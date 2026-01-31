<?php
/**
 * Admin controls page for slider
 *
 * @package Foodmania
 */

/**
 * Creates admin page for the slider
 */
class Rtcamp_Slider_Admin extends Rtcamp_Slider {

	/**
	 * Set section group.
	 *
	 * @var string
	 */
	protected $section_group = 'rtc_slider_group';

	/**
	 * Set default slide count.
	 *
	 * @var int
	 */
	protected $default_slide_count = 1;

	/**
	 * Constructor function.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_theme_admin_page' ) );
		add_action( 'admin_init', array( $this, 'slider_admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
	}

	/**
	 * Add theme page menu and submenu
	 */
	public function add_theme_admin_page() {
		add_menu_page( 'Foodmania', 'Foodmania', 'manage_options', $this->slider_options_name, false, 'dashicons-images-alt2', 59 );
		add_submenu_page( $this->slider_options_name, __( 'Slider', 'foodmania' ), __( 'Slider', 'foodmania' ), 'manage_options', $this->slider_options_name, array( $this, 'create_page' ) );
	}

	/**
	 * Create page
	 */
	public function create_page() {

		echo $this->render(  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'/view/admin-page',
			array(
				'section_group'      => $this->section_group, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'settings_menu_slug' => $this->slider_options_name, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			)
		);

	}

	/**
	 * Slider init function.
	 */
	public function slider_admin_init() {
		// Create Setting.
		register_setting(
			$this->section_group, // settings group name.
			$this->slider_options_name, // The name of an option to sanitize and save.
			array( $this, 'validate_inputs' )
		);

		$settings_section = 'rtc_add_slides_section';

		// Create section of Page.
		add_settings_section(
			$settings_section, // Id.
			__( 'Add Slides', 'foodmania' ), // Title of the section.
			false, // Callback for output.
			$this->slider_options_name  // menu_slug.
		);

		$this->get_slides();
		$this->add_settings_field( $settings_section, $this->slider_settings );
	}


	/**
	 * Add settings field
	 *
	 * @param string $settings_section section name.
	 * @param mixed  $slider_settings array of settings.
	 */
	public function add_settings_field( $settings_section, $slider_settings ) {
		$slides      = ( isset( $slider_settings['slides'] ) ) ? $slider_settings['slides'] : '';
		$slide_count = ( is_array( $slides ) && count( $slides ) > $this->default_slide_count ) ? count( $slides ) : $this->default_slide_count;

		// Add fields to that section.
		for ( $i = 0; $i < $slide_count; $i ++ ) {
			$slide_key    = 'slide-' . $i;
			$slide_number = $i + 1;

			$image_src     = ( isset( $slides[ $i ]['image_src'] ) ) ? esc_url( $slides[ $i ]['image_src'] ) : '';
			$attachment_id = ( isset( $slides[ $i ]['attachment_id'] ) ) ? esc_url( $slides[ $i ]['attachment_id'] ) : '';
			$title         = ( isset( $slides[ $i ]['title'] ) ) ? esc_attr( $slides[ $i ]['title'] ) : '';
			$content       = ( isset( $slides[ $i ]['content'] ) ) ? esc_attr( $slides[ $i ]['content'] ) : '';
			$permalink     = ( isset( $slides[ $i ]['permalink'] ) ) ? esc_url( $slides[ $i ]['permalink'] ) : '';

			add_settings_field(
				'rtc_slide_' . $i, // id.
				__( 'Slide ', 'foodmania' ) . $slide_number, // title.
				array( $this, 'single_slider_field' ), // callback.
				$this->slider_options_name, // page.
				$settings_section, // Section.
				array(
					'image_src'           => $image_src,
					'attachment_id'       => $attachment_id,
					'title'               => $title,
					'content'             => $content,
					'permalink'           => $permalink,
					'count'               => $i,
					'slider_options_name' => $this->slider_options_name,
				)
			);
		}
	}

	/**
	 * Single slider field render
	 *
	 * @param [type] $arg array of params.
	 */
	public function single_slider_field( $arg ) {

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $this->render( '/view/slide-field', $arg );
	}

	/**
	 * Validating inputs
	 *
	 * @param mixed $input array of slide info.
	 */
	public function validate_inputs( $input ) {
		$output = array();

		$slides = $input['slides'];

		if ( is_array( $slides ) ) {
			foreach ( $slides as $key => $slide ) {
				// Image url.
				$output['slides'][ $key ]['image_src'] = esc_url( $slides[ $key ]['image_src'] );

				// Create attachment id.
				if ( $output['slides'][ $key ]['image_src'] && trim( $output['slides'][ $key ]['image_src'] ) ) {

					// Ignoring due to VIP function recommended wpcom_vip_attachment_url_to_postid().
					//phpcs:ignore
					$output['slides'][ $key ]['attachment_id'] = attachment_url_to_postid( $output['slides'][ $key ]['image_src'] ); }

				// Title.
				$output['slides'][ $key ]['title'] = wp_filter_nohtml_kses( $slides[ $key ]['title'] );

				// Content.
				$output['slides'][ $key ]['content'] = wp_filter_post_kses( $slides[ $key ]['content'] );

				// Permalink.
				$output['slides'][ $key ]['permalink'] = esc_url( $slides[ $key ]['permalink'] );
			}
		}

		return apply_filters( 'rtcamp_slider_input_validation', $output, $input );
	}

	/**
	 * Load admin styles.
	 */
	public function load_admin_styles() {
		wp_register_style( 'rtc-jquery-ui', RTCAMP_SLIDER__PLUGIN_URL . 'css/vendor/jquery-ui.css', array(), RTCAMP_SLIDER_VERSION, 'all' );
		wp_register_style( 'rtc-admin-css', RTCAMP_SLIDER__PLUGIN_URL . 'css/admin.css', array( 'thickbox', 'rtc-jquery-ui' ), RTCAMP_SLIDER_VERSION, 'all' );

		$get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		if ( isset( $get_page ) && $get_page === $this->slider_options_name ) {
			wp_enqueue_style( 'rtc-admin-css' );
		}
	}

	/**
	 * Load admin scripts.
	 */
	public function load_admin_scripts() {
		wp_register_script(
			'rtc-admin-script',
			RTCAMP_SLIDER__PLUGIN_URL . 'js/admin.js',
			array(
				'backbone',
				'jquery-ui-sortable',
				'jquery-ui-accordion',
				'media-upload',
				'thickbox',
			),
			RTCAMP_SLIDER_VERSION,
			true
		);

		$get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		if ( isset( $get_page ) && $get_page === $this->slider_options_name ) {
			wp_enqueue_media();
			wp_enqueue_script( 'rtc-admin-script' );

			wp_localize_script(
				'rtc-admin-script',
				'rtc_slide_data',
				array(
					'choose_slider_image_text' => __( 'Choose Slider Image', 'foodmania' ),
					'delete_confirm_text'      => __( 'Are you sure you want to delete the slide?', 'foodmania' ),
					'no_image_error_text'      => __( 'One of your slide does not have image.', 'foodmania' ),
					'no_title_error_text'      => __( 'One of your slide does not have title', 'foodmania' ),
					'slider_options_name'      => $this->slider_options_name,
				)
			);
		}
	}

	/**
	 * Creates header tabs for each slider setting page
	 * Move it anywhere else as per project requirment.
	 */
	public static function header_tabs() {
		settings_errors();

		$get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		if ( isset( $get_page ) ) {
			$active_tab = $get_page;
		}
		?>

		<h2 class="nav-tab-wrapper">
			<a href="?page=foodmania-slider-settings" class="nav-tab <?php echo 'foodmania-slider-settings' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Slider Settings', 'foodmania' ); ?></a>
			<a href="?page=foodmania-plugins" class="nav-tab <?php echo 'foodmania-plugins' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Plugins', 'foodmania' ); ?></a>
			<a href="?page=foodmania-support" class="nav-tab <?php echo 'foodmania-support' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Support', 'foodmania' ); ?></a>
			<a href="?page=foodmania-debug-info" class="nav-tab <?php echo 'foodmania-debug-info' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Debug Info', 'foodmania' ); ?></a>
			<a href="?page=edd_foodmania-license" class="nav-tab <?php echo 'edd_foodmania-license' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Theme Licence', 'foodmania' ); ?></a>
		</h2>
		<?php
	}
}

$rtcamp_slider_admin = new Rtcamp_Slider_Admin();
