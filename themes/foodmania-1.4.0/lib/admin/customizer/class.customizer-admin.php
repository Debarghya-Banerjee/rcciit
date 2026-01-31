<?php
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @package Foodmania
 * @link  http://codex.wordpress.org/Theme_Customization_API
 * @since Foodmania 1.0
 */

/**
 * Class RTP_Customizer_Admin
 */
class RTP_Customizer_Admin extends RTP_Customizer {

	/**
	 * Section priority.
	 *
	 * @var array $section_priority
	 */
	public static $section_priority = array(
		2 => '2nd',
		3 => '3rd',
		4 => '4th',
		5 => '5th',
	);

	/**
	 * Default special font.
	 *
	 * @var string $default_special_font
	 */
	public static $default_special_font = 'candlescript_demo_versionRg';

	/**
	 * Default body font.
	 *
	 * @var string $default_body_font
	 */
	public static $default_body_font = 'Source Sans Pro';

	/**
	 * Default theme color.
	 *
	 * @var string $theme_default_color
	 */
	public static $theme_default_color = '#deb25e';

	/**
	 * RTP_Customizer_Admin constructor.
	 */
	public function __construct() {
		// Setup the Theme Customizer settings and controls...
		add_action( 'customize_register', array( $this, 'register' ) );

		// Enqueue live preview javascript in Theme Customizer admin screen.
		add_action( 'customize_preview_init', array( $this, 'live_preview' ) );

		add_action( 'customize_controls_enqueue_scripts', array( $this, 'load_customizer_controls_scripts' ) );

		add_action( 'wp_ajax_rtp_load_variants_subsets', array( $this, 'load_variants_subsets' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'load_tinymce_js' ) );
	}

	/**
	 * This hooks into 'customize_register' (available as of WP 3.4) and allows
	 * you to add new sections and controls to the Theme Customize screen.
	 *
	 * Note: To enable instant preview, we have to actually write a bit of custom
	 * javascript. See live_preview() for more.
	 *
	 * @see   add_action('customize_register',$func)
	 *
	 * @param \WP_Customize_Manager $wp_customize WP Customizer.
	 *
	 * @since Foodmania 1.0
	 */
	public static function register( $wp_customize ) {

		/**
		 * Home Options.
		 */
		$wp_customize->add_panel(
			'rtp_home_section_panel',
			array(
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Home Template', 'foodmania' ),
			)
		);

		/**
		 * Section Options.
		 */
		$wp_customize->add_section(
			'rtp_section_1',
			array(
				'title'       => __( 'Section 1 (Slider)', 'foodmania' ),
				'priority'    => 35,
				'capability'  => 'edit_theme_options',
				// translators: %s: Site Url.
				'description' => sprintf( __( 'Click <a target="_blank" href="%s">here</a> to change slider settings.', 'foodmania' ), site_url() . '/wp-admin/admin.php?page=foodmania-slider-settings' ),
				'panel'       => 'rtp_home_section_panel',
			)
		);

		/**
		 * Display Section 1.
		 */
		$wp_customize->add_setting(
			'home_section_1_visibility',
			array(
				'default'           => 1,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'home_section_1_visibility',
				array(
					'label'    => __( 'Display Section', 'foodmania' ),
					'section'  => 'rtp_section_1',
					'settings' => 'home_section_1_visibility',
					'type'     => 'checkbox',
				)
			)
		);

		/**
		 * Display Section 2.
		 */
		$wp_customize->add_section(
			'rtp_section_2',
			array(
				'title'       => __( 'Section 2 (About Us)', 'foodmania' ),
				'priority'    => 35,
				'capability'  => 'edit_theme_options',
				'description' => '', // Descriptive tooltip.
				'panel'       => 'rtp_home_section_panel',
			)
		);

		// Display Section.
		$wp_customize->add_setting(
			'home_section_2_visibility',
			array(
				'default'           => 1,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'home_section_2_visibility',
				array(
					'label'    => __( 'Display Section', 'foodmania' ),
					'section'  => 'rtp_section_2',
					'settings' => 'home_section_2_visibility',
					'type'     => 'checkbox',
				)
			)
		);

		// Section Position.
		$wp_customize->add_setting(
			'home_section_2_position',
			array(
				'default'           => 2,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'home_section_2_position',
			array(
				'label'       => __( 'Section Position', 'foodmania' ),
				'section'     => 'rtp_section_2',
				'settings'    => 'home_section_2_position',
				'type'        => 'radio',
				'choices'     => self::$section_priority,
				'description' => __( 'Choose a unique position', 'foodmania' ),
			)
		);

		// Select Page.
		$wp_customize->add_setting(
			'home_section_2_page',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'home_section_2_page',
			array(
				'label'    => __( 'Select Page', 'foodmania' ),
				'section'  => 'rtp_section_2',
				'settings' => 'home_section_2_page',
				'type'     => 'select',
				'choices'  => self::pages_array(),
			)
		);

		// Description.
		$wp_customize->add_setting(
			'home_section_2_description',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'home_section_2_description',
			array(
				'label'    => __( 'Description', 'foodmania' ),
				'section'  => 'rtp_section_2',
				'settings' => 'home_section_2_description',
				'type'     => 'text',
			)
		);

		// Image 1.
		$wp_customize->add_setting(
			'home_section_2_thumb_1',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'home_section_2_thumb_1',
				array(
					'label'       => __( 'Thumbnail 1', 'foodmania' ),
					'section'     => 'rtp_section_2',
					'settings'    => 'home_section_2_thumb_1',
					'description' => __( 'Size should be 280 x 350px', 'foodmania' ),
				)
			)
		);

		// Image 2.
		$wp_customize->add_setting(
			'home_section_2_thumb_2',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'home_section_2_thumb_2',
				array(
					'label'       => __( 'Thumbnail 2', 'foodmania' ),
					'section'     => 'rtp_section_2',
					'settings'    => 'home_section_2_thumb_2',
					'description' => __( 'Size should be 280 x 350px', 'foodmania' ),
				)
			)
		);

		/**
		 * Section 3.
		 */
		$wp_customize->add_section(
			'rtp_section_3',
			array(
				'title'       => __( 'Section 3 (Latest Recipies)', 'foodmania' ),
				'priority'    => 35,
				'capability'  => 'edit_theme_options',
				'description' => '', // Descriptive tooltip.
				'panel'       => 'rtp_home_section_panel',
			)
		);

		// Display Section.
		$wp_customize->add_setting(
			'home_section_3_visibility',
			array(
				'default'           => 1,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'home_section_3_visibility',
				array(
					'label'    => __( 'Display Section', 'foodmania' ),
					'section'  => 'rtp_section_3',
					'settings' => 'home_section_3_visibility',
					'type'     => 'checkbox',
				)
			)
		);

		// Section Position.
		$wp_customize->add_setting(
			'home_section_3_position',
			array(
				'default'           => 3,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'home_section_3_position',
			array(
				'label'       => __( 'Section Position', 'foodmania' ),
				'section'     => 'rtp_section_3',
				'settings'    => 'home_section_3_position',
				'type'        => 'radio',
				'choices'     => self::$section_priority,
				'description' => __( 'Choose a unique position', 'foodmania' ),
			)
		);

		// Select Category.
		$wp_customize->add_setting(
			'home_section_3_category',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'home_section_3_category',
			array(
				'label'    => __( 'Select Category', 'foodmania' ),
				'section'  => 'rtp_section_3',
				'settings' => 'home_section_3_category',
				'type'     => 'select',
				'choices'  => self::category_array(),
			)
		);

		// Title.
		$wp_customize->add_setting(
			'home_section_3_title',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'home_section_3_title',
			array(
				'label'    => __( 'Title', 'foodmania' ),
				'section'  => 'rtp_section_3',
				'settings' => 'home_section_3_title',
				'type'     => 'text',
			)
		);

		// Description.
		$wp_customize->add_setting(
			'home_section_3_description',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'home_section_3_description',
			array(
				'label'    => __( 'Description', 'foodmania' ),
				'section'  => 'rtp_section_3',
				'settings' => 'home_section_3_description',
				'type'     => 'text',
			)
		);

		// Link Text.
		$wp_customize->add_setting(
			'home_section_3_linktext',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'home_section_3_linktext',
			array(
				'label'       => __( 'Link Text', 'foodmania' ),
				'section'     => 'rtp_section_3',
				'settings'    => 'home_section_3_linktext',
				'type'        => 'text',
				'description' => __( 'Will be visible when there are more than 3 posts available in selected cat.', 'foodmania' ),
			)
		);

		// Choose Background.
		$wp_customize->add_setting(
			'home_section_3_background',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'home_section_3_background',
				array(
					'label'       => __( 'Background Image', 'foodmania' ),
					'section'     => 'rtp_section_3',
					'settings'    => 'home_section_3_background',
					'description' => __( 'Size should be 1408x800px', 'foodmania' ),
				)
			)
		);

		/**
		 * Section 4.
		 */
		$wp_customize->add_section(
			'rtp_section_4',
			array(
				'title'       => __( 'Section 4 (Featured Memebers)', 'foodmania' ),
				'priority'    => 35,
				'capability'  => 'edit_theme_options',
				'description' => __( 'This is Buddypress specific section. If you dont have Buddypress plugin installed you should hide this section.', 'foodmania' ),
				'panel'       => 'rtp_home_section_panel',
			)
		);

		// Display Section.
		$wp_customize->add_setting(
			'home_section_4_visibility',
			array(
				'default'           => 4,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'home_section_4_visibility',
				array(
					'label'    => __( 'Display Section', 'foodmania' ),
					'section'  => 'rtp_section_4',
					'settings' => 'home_section_4_visibility',
					'type'     => 'checkbox',
				)
			)
		);

		// Section Position.
		$wp_customize->add_setting(
			'home_section_4_position',
			array(
				'default'           => 4,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'home_section_4_position',
			array(
				'label'       => __( 'Section Position', 'foodmania' ),
				'section'     => 'rtp_section_4',
				'settings'    => 'home_section_4_position',
				'type'        => 'radio',
				'choices'     => self::$section_priority,
				'description' => __( 'Choose a unique position', 'foodmania' ),
			)
		);

		// Title.
		$wp_customize->add_setting(
			'home_section_4_title',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'home_section_4_title',
			array(
				'label'    => __( 'Title', 'foodmania' ),
				'section'  => 'rtp_section_4',
				'settings' => 'home_section_4_title',
				'type'     => 'text',
			)
		);

		// Description.
		$wp_customize->add_setting(
			'home_section_4_description',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'home_section_4_description',
			array(
				'label'    => __( 'Description', 'foodmania' ),
				'section'  => 'rtp_section_4',
				'settings' => 'home_section_4_description',
				'type'     => 'text',
			)
		);

		// Member Type.
		$wp_customize->add_setting(
			'home_section_4_member_type',
			array(
				'default'           => 'newest',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'home_section_4_member_type',
			array(
				'label'    => __( 'Member Type', 'foodmania' ),
				'section'  => 'rtp_section_4',
				'settings' => 'home_section_4_member_type',
				'type'     => 'select',
				'choices'  => array(
					'newest'  => 'Newest',
					'active'  => 'Active',
					'popular' => 'Popular',
				),
			)
		);

		/**
		 * Section 5.
		 */
		$wp_customize->add_section(
			'rtp_section_5',
			array(
				'title'       => __( 'Section 5 (Like What You See)', 'foodmania' ),
				'priority'    => 35,
				'capability'  => 'edit_theme_options',
				'description' => '', // Descriptive tooltip.
				'panel'       => 'rtp_home_section_panel',
			)
		);

		// Display Section.
		$wp_customize->add_setting(
			'home_section_5_visibility',
			array(
				'default'           => 1,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'home_section_5_visibility',
				array(
					'label'    => __( 'Display Section', 'foodmania' ),
					'section'  => 'rtp_section_5',
					'settings' => 'home_section_5_visibility',
					'type'     => 'checkbox',
				)
			)
		);

		// Section Position.
		$wp_customize->add_setting(
			'home_section_5_position',
			array(
				'default'           => 5,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'home_section_5_position',
			array(
				'label'       => __( 'Section Position', 'foodmania' ),
				'section'     => 'rtp_section_5',
				'settings'    => 'home_section_5_position',
				'type'        => 'radio',
				'choices'     => self::$section_priority,
				'description' => __( 'Choose a unique position', 'foodmania' ),
			)
		);

		// Title.
		$wp_customize->add_setting(
			'home_section_5_title',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'home_section_5_title',
			array(
				'label'    => __( 'Title', 'foodmania' ),
				'section'  => 'rtp_section_5',
				'settings' => 'home_section_5_title',
				'type'     => 'text',
			)
		);

		// Description.
		$wp_customize->add_setting(
			'home_section_5_description',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'home_section_5_description',
			array(
				'label'    => __( 'Description', 'foodmania' ),
				'section'  => 'rtp_section_5',
				'settings' => 'home_section_5_description',
				'type'     => 'text',
			)
		);

		// Link.
		$wp_customize->add_setting(
			'home_section_5_link',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'home_section_5_link',
			array(
				'label'    => __( 'Link', 'foodmania' ),
				'section'  => 'rtp_section_5',
				'settings' => 'home_section_5_link',
				'type'     => 'text',
			)
		);

		// Link Text.
		$wp_customize->add_setting(
			'home_section_5_linktext',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'home_section_5_linktext',
			array(
				'label'    => __( 'Link Text', 'foodmania' ),
				'section'  => 'rtp_section_5',
				'settings' => 'home_section_5_linktext',
				'type'     => 'text',
			)
		);

		// Choose Background.
		$wp_customize->add_setting(
			'home_section_5_background',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'home_section_5_background',
				array(
					'label'       => __( 'Background Image', 'foodmania' ),
					'section'     => 'rtp_section_5',
					'settings'    => 'home_section_5_background',
					'description' => __( 'Size should be 1408 x 800px', 'foodmania' ),
				)
			)
		);

		/**
		 * Blog.
		 */

		// Add "Content Options" section.
		$wp_customize->add_section(
			'rtp_blog_section',
			array(
				'title'       => __( 'Blog', 'foodmania' ),
				'priority'    => 11,
				'capability'  => 'edit_theme_options',
				'description' => __( 'Will show on blog page.', 'foodmania' ),
			)
		);

		// Excerpt Length.
		$wp_customize->add_setting(
			'rtp_excerpt_length',
			array(
				'default'           => 90,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rtp_excerpt_length',
			array(
				'label'    => __( 'Excerpt Length', 'foodmania' ),
				'section'  => 'rtp_blog_section',
				'settings' => 'rtp_excerpt_length',
				'type'     => 'text',
			)
		);

		// Read More Text.
		$wp_customize->add_setting(
			'rtp_readmore_text',
			array(
				'default'           => __( 'Read More', 'foodmania' ),
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rtp_readmore_text',
			array(
				'label'    => __( 'Read More Text', 'foodmania' ),
				'section'  => 'rtp_blog_section',
				'settings' => 'rtp_readmore_text',
				'type'     => 'text',
			)
		);

		// Show Post Author.
		$wp_customize->add_setting(
			'rtp_show_post_author',
			array(
				'default'           => 1,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			'rtp_show_post_author',
			array(
				'label'    => __( 'Show post author', 'foodmania' ),
				'section'  => 'rtp_blog_section',
				'settings' => 'rtp_show_post_author',
				'type'     => 'checkbox',
			)
		);

		// Show Post Date.
		$wp_customize->add_setting(
			'rtp_show_post_date',
			array(
				'default'           => 1,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			'rtp_show_post_date',
			array(
				'label'    => __( 'Show post date', 'foodmania' ),
				'section'  => 'rtp_blog_section',
				'settings' => 'rtp_show_post_date',
				'type'     => 'checkbox',
			)
		);

		// Show Post Category.
		$wp_customize->add_setting(
			'rtp_show_post_categories',
			array(
				'default'           => 1,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			'rtp_show_post_categories',
			array(
				'label'    => __( 'Show post categories', 'foodmania' ),
				'section'  => 'rtp_blog_section',
				'settings' => 'rtp_show_post_categories',
				'type'     => 'checkbox',
			)
		);

		// Show Post Tags.
		$wp_customize->add_setting(
			'rtp_show_post_tags',
			array(
				'default'           => 1,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			'rtp_show_post_tags',
			array(
				'label'    => __( 'Show post tags', 'foodmania' ),
				'section'  => 'rtp_blog_section',
				'settings' => 'rtp_show_post_tags',
				'type'     => 'checkbox',
			)
		);

		// Show Post Comments.
		$wp_customize->add_setting(
			'rtp_show_post_comments',
			array(
				'default'           => 1,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			'rtp_show_post_comments',
			array(
				'label'    => __( 'Show comment in meta', 'foodmania' ),
				'section'  => 'rtp_blog_section',
				'settings' => 'rtp_show_post_comments',
				'type'     => 'checkbox',
			)
		);

		/**
		 * Theme Color Options.
		 */

		// Choose Background.
		$wp_customize->add_setting(
			'rtp_theme_color',
			array(
				'default'           => self::$theme_default_color,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control( // Instantiate the color control class.
				$wp_customize, // Pass the $wp_customize object (required).
				'rtp_theme_color', // Set a unique ID for the control.
				'rtp_theme_color', // Set a unique ID for the control.
				array(
					'label'    => __( 'Link Color', 'foodmania' ),
					// Admin-visible name of the control.
					'section'  => 'colors',
					// ID of the section this control should render in (can be one of yours, or a WordPress default section).
					'settings' => 'rtp_theme_color',
				// Which setting to load and manipulate (serialized is okay).
				)
			)
		);

		// SPECIAL FONT Color.
		$wp_customize->add_setting(
			'rtp_special_font_color',
			array(
				'default'           => self::$theme_default_color,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'rtp_special_font_color',
				array(
					'label'    => __( 'Special Font Color', 'foodmania' ),
					'section'  => 'colors',
					'settings' => 'rtp_special_font_color',
				)
			)
		);

		/**
		 * Font section.
		 */
		$wp_customize->add_section(
			'rtp_fonts_section',
			array(
				'priority'    => 20,
				'title'       => __( 'Font', 'foodmania' ),
				'capability'  => 'edit_theme_options',
				'description' => __( 'Will show on whole site', 'foodmania' ), // Descriptive tooltip.
			)
		);

		// Special Fonts.
		$wp_customize->add_setting(
			'rtp_special_font',
			array(
				'default'           => self::$default_special_font,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'rtp_special_font',
			array(
				'label'    => __( 'Special Font', 'foodmania' ),
				'section'  => 'rtp_fonts_section',
				'settings' => 'rtp_special_font',
				'type'     => 'select',
				'choices'  => rtp_library_get_font_choices(),
			)
		);

		$wp_customize->add_setting(
			'rtp_special_font_variant',
			array(
				'default'           => 'regular',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rtp_special_font_variant',
			array(
				'label'    => __( 'Special Font Variant', 'foodmania' ),
				'section'  => 'rtp_fonts_section',
				'settings' => 'rtp_special_font_variant',
				'type'     => 'select',
				'choices'  => rtp_get_google_fonts_variants( 'rtp_special_font', self::$default_special_font ),
			)
		);

		$wp_customize->add_setting(
			'rtp_special_font_subset',
			array(
				'default'           => 'latin',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rtp_special_font_subset',
			array(
				'label'    => __( 'Special Font Subset', 'foodmania' ),
				'section'  => 'rtp_fonts_section',
				'settings' => 'rtp_special_font_subset',
				'type'     => 'select',
				'choices'  => rtp_get_google_fonts_subsets( 'rtp_special_font', self::$default_special_font ),
			)
		);

		// Body Fonts.
		$wp_customize->add_setting(
			'rtp_body_font',
			array(
				'default'           => self::$default_body_font,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'rtp_body_font',
			array(
				'label'    => __( 'Body Font', 'foodmania' ),
				'section'  => 'rtp_fonts_section',
				'settings' => 'rtp_body_font',
				'type'     => 'select',
				'choices'  => rtp_library_get_font_choices(),
			)
		);

		$wp_customize->add_setting(
			'rtp_body_font_variant',
			array(
				'default'           => 'regular',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rtp_body_font_variant',
			array(
				'label'    => __( 'Body Font Variant', 'foodmania' ),
				'section'  => 'rtp_fonts_section',
				'settings' => 'rtp_body_font_variant',
				'type'     => 'select',
				'choices'  => rtp_get_google_fonts_variants( 'rtp_body_font', self::$default_body_font ),
			)
		);

		$wp_customize->add_setting(
			'rtp_body_font_subset',
			array(
				'default'           => 'latin',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rtp_body_font_subset',
			array(
				'label'    => __( 'Body Font Subset', 'foodmania' ),
				'section'  => 'rtp_fonts_section',
				'settings' => 'rtp_body_font_subset',
				'type'     => 'select',
				'choices'  => rtp_get_google_fonts_subsets( 'rtp_body_font', self::$default_body_font ),
			)
		);

		/**
		 * Copyright.
		 */
		$wp_customize->add_section(
			'rtp_copyright_section',
			array(
				'priority'    => 120,
				'title'       => __( 'Copyright', 'foodmania' ),
				'capability'  => 'edit_theme_options',
				'description' => __( 'Will override the footer copyright text', 'foodmania' ), // Descriptive tooltip.
			)
		);

		// Special fonts.
		$wp_customize->add_setting(
			'rtp_footer_copyright',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rtp_footer_copyright',
			array(
				'label'    => __( 'Copyright Text', 'foodmania' ),
				'section'  => 'rtp_copyright_section',
				'settings' => 'rtp_footer_copyright',
				'type'     => 'text',
			)
		);

		/**
		 * Advanced.
		 */
		$wp_customize->add_panel(
			'rtp_advanced_panel',
			array(
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Advanced Settings', 'foodmania' ),
			)
		);

		// Custom CSS.
		$wp_customize->add_section(
			'rtp_custom_css_section',
			array(
				'title'       => __( 'Custom CSS', 'foodmania' ),
				'priority'    => 10,
				'capability'  => 'edit_theme_options',
				'description' => __( 'Do not use style tag', 'foodmania' ),
				'panel'       => 'rtp_advanced_panel',
			)
		);

		// Choose Background.
		$wp_customize->add_setting(
			'rtp_custom_css',
			array(
				'default'           => 'body{}',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rtp_custom_css',
			array(
				'label'    => __( 'Write Custom CSS', 'foodmania' ),
				'section'  => 'rtp_custom_css_section',
				'settings' => 'rtp_custom_css',
				'type'     => 'textarea',
			)
		);

		// Social.
		$wp_customize->add_section(
			'rtp_social_section',
			array(
				'title'       => __( 'Social', 'foodmania' ),
				'priority'    => 10,
				'capability'  => 'edit_theme_options',
				'description' => __( 'Show social icon in footer', 'foodmania' ),
				'panel'       => 'rtp_advanced_panel',
			)
		);

		// Display Social option  Yes / No.
		$wp_customize->add_setting(
			'rtp_social_visibility',
			array(
				'default'           => 1,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			'rtp_social_visibility',
			array(
				'label'    => __( 'Display Social ', 'foodmania' ),
				'section'  => 'rtp_social_section',
				'settings' => 'rtp_social_visibility',
				'type'     => 'checkbox',
			)
		);

		// Social Facebook.
		$wp_customize->add_setting(
			'rtp_social_facebook',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rtp_social_facebook',
			array(
				'label'    => __( 'Facebook', 'foodmania' ),
				'section'  => 'rtp_social_section',
				'settings' => 'rtp_social_facebook',
				'type'     => 'text',
			)
		);

		// Social Twitter.
		$wp_customize->add_setting(
			'rtp_social_twitter',
			array(
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rtp_social_twitter',
			array(
				'label'    => __( 'Twitter', 'foodmania' ),
				'section'  => 'rtp_social_section',
				'settings' => 'rtp_social_twitter',
				'type'     => 'text',
			)
		);

		// Login.
		$wp_customize->add_section(
			'rtp_login_section',
			array(
				'title'       => __( 'Login', 'foodmania' ),
				'priority'    => 10,
				'capability'  => 'edit_theme_options',
				'description' => __( 'Show or hide login in menu', 'foodmania' ),
				'panel'       => 'rtp_advanced_panel',
			)
		);

		// Login Visibility.
		$wp_customize->add_setting(
			'rtp_login_menu_visibility',
			array(
				'default'           => 1,
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_checkboxes',
			)
		);

		$wp_customize->add_control(
			'rtp_login_menu_visibility',
			array(
				'label'    => __( 'Display Login Tab', 'foodmania' ),
				'section'  => 'rtp_login_section',
				'settings' => 'rtp_login_menu_visibility',
				'type'     => 'checkbox',
			)
		);

		// Login Method.
		$wp_customize->add_setting(
			'rtp_login_method',
			array(
				'default'           => 'popup',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rtp_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'rtp_login_method',
			array(
				'label'    => __( 'Login Method', 'foodmania' ),
				'section'  => 'rtp_login_section',
				'settings' => 'rtp_login_method',
				'type'     => 'radio',
				'choices'  => array(
					'popup'     => __( 'Popup', 'foodmania' ),
					'wordpress' => __( 'WordPress Login Page', 'foodmania' ),
				),
			)
		);

		$wp_customize = apply_filters( 'rtp_customize_social', $wp_customize );

		// 4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
		$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
		$wp_customize->remove_control( 'header_image' );
	}

	/**
	 * This outputs the javascript needed to automate the live settings preview.
	 * Also keep in mind that this function isn't necessary unless your settings
	 * are using 'transport'=>'postMessage' instead of the default 'transport'
	 * => 'refresh'
	 *
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see   add_action('customize_preview_init',$func)
	 * @since Foodmania 1.0
	 */
	public static function live_preview() {
		// To avoid caching during development.
		wp_enqueue_script(
			'rtp-themecustomizer', // Give the script a unique ID.
			get_template_directory_uri() . RTP_ADMIN_FOLDER_PATH . 'customizer/js/customizer-live-preview.js',
			array( 'jquery', 'customize-preview' ), // Define dependencies.
			'1.0', // Define a version (optional).
			true // Specify whether to put in footer (leave this true).
		);
	}

	/**
	 * Customizer controls scripts.
	 */
	public function load_customizer_controls_scripts() {
		wp_enqueue_script(
			'rtp-customizer-control-scripts', // Give the script a unique ID.
			get_template_directory_uri() . RTP_ADMIN_FOLDER_PATH . 'customizer/js/customizer-control.js',
			array( 'jquery' ), // Define dependencies.
			'1.0', // Define a version (optional).
			true // Specify whether to put in footer (leave this true).
		);
		wp_localize_script(
			'rtp-customizer-control-scripts',
			'rtp_ajax_data',
			array(
				'rtp_ajax_nonce' => wp_create_nonce( 'rtp_nonce' ),
			)
		);
	}

	/**
	 * Load variants subsets.
	 */
	public function load_variants_subsets() {
		if ( empty( $_REQUEST['rtp_security_nonce'] ) || ! check_ajax_referer( 'rtp_nonce', 'rtp_security_nonce' ) ) {

			return wp_send_json_error( esc_html__( 'Number not only once is invalid', 'foodmania' ), 404 );
		
		} 
		$font_value = isset( $_REQUEST['font_value'] ) ? sanitize_text_field( $_REQUEST['font_value'] ) : false;

		$variants = rtp_get_google_fonts_variants( false, false, $font_value );
		$subsets  = rtp_get_google_fonts_subsets( false, false, $font_value );

		echo wp_json_encode(
			array(
				'variants' => $variants,
				'subsets'  => $subsets,
			)
		);

		die();
	}

	/**
	 * Load TinyMCE JS
	 */
	public function load_tinymce_js() {
		wp_register_script( 'rtc-tinymce-script', RTP_ADMIN_URI . 'customizer/js/tinymce-custom.js', array(), '1.0', true );
		wp_enqueue_script( 'rtc-tinymce-script' );

		$custom_css = self::generate_css( 'html body', 'font-family', 'rtp_body_font', '"', '"', self::$default_body_font, false );

		wp_localize_script( 'rtc-tinymce-script', 'rtp_tinymce_css', array( $custom_css ) );
	}

}

new RTP_Customizer_Admin();
