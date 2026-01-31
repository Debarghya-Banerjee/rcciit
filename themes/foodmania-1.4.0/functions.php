<?php
/**
 * Foodmania functions and definitions.
 *
 * @package Foodmania
 */

global $foodmania_version;

if ( ! defined( 'FOODMANIA_TEMP_URI' ) ) {
	define( 'FOODMANIA_TEMP_URI', get_template_directory_uri() );
}
if ( ! defined( 'FOODMANIA_TEMP_DIR' ) ) {
	define( 'FOODMANIA_TEMP_DIR', get_template_directory() );
}
if ( ! defined( 'FOODMANIA_CSS_URI' ) ) {
	define( 'FOODMANIA_CSS_URI', FOODMANIA_TEMP_URI . '/css' );
}
if ( ! defined( 'FOODMANIA_JS_URI' ) ) {
	define( 'FOODMANIA_JS_URI', FOODMANIA_TEMP_URI . '/js' );
}
if ( ! defined( 'FOODMANIA_IMG_URI' ) ) {
	define( 'FOODMANIA_IMG_URI', FOODMANIA_TEMP_URI . '/img' );
}
if ( ! defined( 'FOODMANIA_ADMIN' ) ) {
	define( 'FOODMANIA_ADMIN', FOODMANIA_TEMP_DIR . '/admin' );
}

/* Includes Theme admin init */
require_once FOODMANIA_ADMIN . '/rtp-admin-init.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
/**
 * This is the URL our updater / license checker pings.
 * This should be the URL of the site with EDD installed
 */
if ( ! defined( 'FOODMANIA_EDD_SL_STORE_URL' ) ) {
	define( 'FOODMANIA_EDD_SL_STORE_URL', 'https://rtmedia.io/' );
}

/**
 * Returns Theme version along with WordPress version.
 *
 * @return array
 */
function rtp_export_version() {
	global $wp_version;
	require_once ABSPATH . '/wp-admin/includes/update.php';

	if ( function_exists( 'wp_get_theme' ) ) {
		$theme_info = wp_get_theme();

		if ( is_child_theme() ) {
			$theme_info = wp_get_theme( 'foodmania' );
		}
	}

	$theme_version = array(
		'wp'        => $wp_version,
		'Foodmania' => $theme_info['Version'],
	);

	return $theme_version;
}

$foodmania_version = rtp_export_version();

if ( ! get_option( 'foodmania_version' ) || ( get_option( 'foodmania_version' ) !== $foodmania_version ) ) {
	update_option( 'foodmania_version', $foodmania_version );
}

if ( ! defined( 'FOODMANIA_VERSION' ) ) {
	define( 'FOODMANIA_VERSION', $foodmania_version['Foodmania'] );
}




/**
 * The name of your product.
 * This should match the download name in EDD exactly
 */
if ( ! defined( 'FOOMANIA_EDD_SL_THEME_NAME' ) ) {
	define( 'FOOMANIA_EDD_SL_THEME_NAME', 'Foodmania' );
}

/**
 * Buddypress avatar sizes.
 */
define( 'BP_AVATAR_THUMB_WIDTH', 81 );
define( 'BP_AVATAR_THUMB_HEIGHT', 81 );

/**
 * File Includes.
 */
foreach (
	array_merge(
		glob( FOODMANIA_TEMP_DIR . '/modules/cover-photo/*.php' ),
		glob( FOODMANIA_TEMP_DIR . '/inc/*.php' ),
		glob( FOODMANIA_TEMP_DIR . '/lib/*.php' ),
		glob( FOODMANIA_TEMP_DIR . '/lib/admin/*.php' ),
		glob( FOODMANIA_TEMP_DIR . '/lib/admin/rtp-extensions/*.php' )
	) as $filename
) {
	require_once $filename; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
}

if ( ! function_exists( 'foodmania_setup' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function foodmania_setup() {

		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on foodmania, use a find and replace
		 * to change 'foodmania' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'foodmania', FOODMANIA_TEMP_DIR . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		add_theme_support( 'customize-selective-refresh-widgets' );

		add_theme_support( 'custom-logo' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		// add_theme_support( 'post-thumbnails' );
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'foodmania' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/**
		 * Add support for featured image
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'foodmania_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add Woocommerce Support.
		add_theme_support( 'woocommerce' );

		// Add image size for home page section 2.
		add_image_size( 'foodmania_section_3_thumb', 380, 286, true );
		add_image_size( 'foodmania_slider_image', 1408, 700, true );

		// Thumbnail.
		set_post_thumbnail_size( 780, 520, true );

		/*
		 * This theme styles the visual editor to resemble the theme style,
		 * specifically font, colors, icons, and column width.
		 */
		$body_font_url = rtp_library_get_google_font_uri( 'rtp_body_font', 'Source Sans Pro', 'rtp_body_font_variant', 'rtp_body_font_subset' );
		add_editor_style( array( 'editor-style.css', $body_font_url ) );
	}

endif; // Foodmania_setup.

add_action( 'after_setup_theme', 'foodmania_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function foodmania_content_width() {
	global $content_width;
	$content_width = apply_filters( 'foodmania_content_width', 640 );
}

add_action( 'after_setup_theme', 'foodmania_content_width', 0 );

if ( ! function_exists( 'foodmania_widgets_init' ) ) {

	/**
	 * Register widget area.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
	 */
	function foodmania_widgets_init() {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Sidebar', 'foodmania' ),
				'id'            => 'sidebar-1',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer', 'foodmania' ),
				'id'            => 'sidebar-2',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s large-3 medium-6 column">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'foodmania_widgets_init' );


if ( ! function_exists( 'foodmania_scripts' ) ) {

	/**
	 * Enqueues scripts and styles for the theme.
	 */
	function foodmania_scripts() {
		/**
		 * Styles.
		 */
		wp_enqueue_style( 'foodmania-style', get_stylesheet_uri(), array( 'dashicons' ), FOODMANIA_VERSION );
		wp_enqueue_style( 'bb_pc_rtmedia_addon', trailingslashit( get_template_directory_uri() ) . 'css/style.css', array(), FOODMANIA_VERSION );


		/**
		 * Scripts.
		 */
		wp_register_script( 'foodmania-main', FOODMANIA_JS_URI . '/rtp-package-min.js', array( 'jquery' ), FOODMANIA_VERSION, true );
		wp_enqueue_script( 'foodmania-main' );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		wp_localize_script( 'foodmania-main', 'foodmaniaVars', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
}

add_action( 'wp_enqueue_scripts', 'foodmania_scripts' );



/**
 * Ajax Login.
 */
function rtp_ajax_login() {

	$response = array(
		'error'     => false,
		'error_msg' => '',
		'data'      => '',
	);

	$login = wp_signon();

	if ( is_wp_error( $login ) ) {
		$response['error']     = true;
		$response['error_msg'] = $login->get_error_messages();
	} else {
		$response['data'] = apply_filters( 'foodmania_login_redirect', home_url() );
	}

	echo wp_json_encode( $response );

	die;
}

add_action( 'wp_ajax_nopriv_rtp_login', 'rtp_ajax_login' );
