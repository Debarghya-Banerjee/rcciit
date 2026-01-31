<?php
/**
 * Handles frontend
 *
 * @package Foodmania
 * @since Foodmania 1.0
 */

/**
 * Class RTP_Customizer_Front
 */
class RTP_Customizer_Front extends RTP_Customizer_Admin {

	/**
	 * RTP_Customizer_Front constructor.
	 */
	public function __construct() {
		// Output custom CSS to live site.
		add_action( 'wp_head', array( $this, 'header_output' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_font' ) );

	}

	/**
	 * This will output the custom WordPress settings to the live theme's WP head.
	 */
	public function header_output() {
		do_action( 'foodmania_customizer_header_output' );
		$custom_css = get_theme_mod( 'rtp_custom_css' );
		?>

		<!--Customizer CSS-->
		<style type="text/css"><?php echo wp_kses_post( self::custom_css() ); ?></style>
		<!--/Customizer CSS-->

		<!--Foodmania Custom CSS-->
		<style type="text/css"><?php echo wp_kses_post( $custom_css ); ?></style>
		<!--/Foodmania Custom CSS-->

		<?php
	}

	/**
	 * Enqueue Fonts.
	 */
	public function enqueue_font() {
		$special_font_url = rtp_library_get_google_font_uri( 'rtp_special_font', 'Arizonia', 'rtp_special_font_variant', 'rtp_special_font_subset' );
		$body_font_url    = rtp_library_get_google_font_uri( 'rtp_body_font', 'Source Sans Pro', 'rtp_body_font_variant', 'rtp_body_font_subset' );

		if ( $special_font_url ) {
			wp_enqueue_style( 'rtp_special_font', $special_font_url, array(), FOODMANIA_VERSION );
		}
		if ( $body_font_url ) {
			wp_enqueue_style( 'rtp_body_font', $body_font_url, array(), FOODMANIA_VERSION );
		}
	}

	/**
	 * Generates all css
	 *
	 * @return void
	 */
	public static function custom_css() {
		self::generate_css(
			'body',
			array(
				'background-color',
				'background-repeat',
				'background-attachment',
				'background-image',
				'font-family',
			),
			array(
				'background_color',
				'background_repeat',
				'background_attachment',
				'background_image',
				'rtp_body_font',
			),
			array( '#', '', '', '', '"' ),
			array( '', '', '', '', '"' ),
			array(
				false,
				false,
				false,
				false,
				self::$default_body_font,
			)
		);

		self::generate_css(
			'.rtp-special-title',
			array( 'font-family', 'color' ),
			array(
				'rtp_special_font',
				'rtp_special_font_color',
			),
			array( '"', '' ),
			array( '"', false ),
			array( self::$default_special_font, '#deb25e' )
		);
		self::generate_css( '.rtp-section-3', 'background-image', 'home_section_3_background', 'url(', ')' );
		self::generate_css( '.rtp-section-5', 'background-image', 'home_section_5_background', 'url(', ')' );

		self::create_skin();

		do_action( 'foodmania_customizer_custom_css' );
	}

	/**
	 * Creates skin for the theme using the color selected by the user.
	 *
	 * @return void
	 */
	public static function create_skin() {
		/**
		 * Contains all selectors for changing colors
		 *
		 * @var array
		 */
		$color_selectors = apply_filters(
			'foodmania_customizer_color_selectors',
			array(
				'a',
				'.star-rating',
				'.rtp-main-navigation .current_page_item > a',
				'.rtp-main-navigation .current-menu-item > a',
				'.rtp-main-navigation .current_page_ancestor > a',
				'.rtp-main-navigation a:hover',
				'.rtp-thumb-box .rtp-thumb-title a:hover',
				'.post-meta .rtp-meta-row .rtp-cat-list',
				'.rtp-comment-body .rtp-comment-author a',
				'.rtp-site-description',
				'.rtp-item-header-content .user-name',
				'.rtp-cover-header-nav li a:hover',
				'.rtp-cover-header-nav li.current a',
				'.rtp-site-footer a:hover',
				'.rtp-site-footer li.current_page_item a',
				'#buddypress div.item-list-tabs ul li a:hover',
				'#buddypress div.item-list-tabs ul li.selected a',
				'#buddypress div.item-list-tabs ul li:hover a',
				'#buddypress div.activity-meta a',
				'#buddypress .acomment-options a',
				'#buddypress .feed a:hover:before',
				'#buddypress .rtmedia-actions-before-comments .rtmedia-like',
				'.site #buddypress div.item-list-tabs#subnav ul li.current a',
				'.site #buddypress div.item-list-tabs ul li a:hover',
				'.site #buddypress div.activity-meta a',
				'.site #buddypress .acomment-options a',
				'#buddypress #reply-title small a span',
				'#buddypress #reply-title small a:hover span',
				'#buddypress a.bp-primary-action span',
				'#buddypress a.bp-primary-action:hover span',
				'.site #buddypress div.item-list-tabs ul li.selected a',
				'.site #buddypress div.item-list-tabs ul li:hover a',
				'.site #buddypress div.item-list-tabs ul li.current a',
				'.woocommerce .woocommerce-breadcrumb a',
				'#buddypress .rtmedia-media-edit .rtmedia-image-editor-cotnainer .imgedit-settings .imgedit-group .imgedit-group-top .imgedit-help-toggle',
				'.site #buddypress div.activity-meta .acomment-reply:hover:before',
				'.site #buddypress div.activity-meta .delete-activity:hover:before',
				'.site #buddypress div.activity-meta .react.bp-primary-action:hover:before',
				'.site #buddypress div.activity-meta .view.bp-secondary-action:hover:before',
			)
		);

		/**
		 * Contains all selectors for changing background colors
		 *
		 * @var array
		 */
		$background_selectors = apply_filters(
			'foodmania_customizer_background_selectors',
			array(
				'button',
				'input[type="button"]',
				'input[type="reset"]',
				'input[type="submit"]',
				'.button-link-div a',
				'.rtp-pagination .page-numbers',
				'.widget_bp_core_members_widget.buddypress div.item-options a.selected',
				'.widget_bp_core_members_widget.buddypress div.item-options a:hover',
				'.widget.widget_bp_groups_widget.buddypress div.item-options a.selected',
				'.widget.widget_bp_groups_widget.buddypress div.item-options a:hover',
				'.bbp_widget_login .button.logout-link',
				'.widget_bp_core_login_widget .logout',
				'.RTMediaGalleryWidget .rtm-tabs-container .rtm-tabs li.active',
				'.RTMediaGalleryWidget .rtm-tabs-container .rtm-tabs li:hover',
				'#buddypress #whats-new-submit #aw-whats-new-submit',
				'#buddypress div.item-list-tabs#subnav ul li.current a',
				'#buddypress div.item-list-tabs ul li.current a',
				'#buddypress #groups-directory-form .button',
				'#buddypress #pag-bottom.pagination div.pagination-links .page-numbers',
				'.rtp-login-form-title',
				'.comment-reply-link:hover',
				'a.button:focus',
				'a.button:hover',
				'button:hover',
				'.rtp-member-profile-header .generic-button a',
				'input[type=button]:hover',
				'input[type=reset]:hover',
				'input[type=submit]:hover',
				'ul.button-nav li a:hover',
				'#buddypress button',
				'#buddypress input[type=submit]',
				'#buddypress input[type=submit]:hover',
				'#buddypress input[type=button]',
				'#buddypress input[type=reset]',
				'#buddypress #activity-stream li.load-more',
				'#buddypress #activity-stream li.load-newest',
				'.woocommerce .rtp-main-wrapper button',
				'.woocommerce .rtp-main-wrapper .button',
				'.woocommerce .rtp-main-wrapper input[type="submit"]',
				'.woocommerce .rtp-main-wrapper #payment #place_order',
				'.woocommerce .rtp-main-wrapper #content-wrapper #content .button',
				'.woocommerce-page .rtp-main-wrapper button',
				'.woocommerce-page .rtp-main-wrapper .button',
				'.woocommerce-page .rtp-main-wrapper input[type="submit"]',
				'.woocommerce-page .rtp-main-wrapper #payment #place_order',
				'.woocommerce-page .rtp-main-wrapper #content-wrapper #content .button',
				'.woocommerce #respond input#submit',
				'.woocommerce a.button',
				'.woocommerce button.button',
				'.woocommerce input.button',
				'.woocommerce #respond input#submit.alt',
				'.woocommerce a.button.alt',
				'.woocommerce button.button.alt',
				'.woocommerce input.button.alt',
				'.woocommerce .rtp-main-wrapper button:hover',
				'.woocommerce .rtp-main-wrapper .button:hover',
				'.woocommerce .rtp-main-wrapper input[type="submit"]:hover',
				'.woocommerce .rtp-main-wrapper #payment #place_order:hover',
				'.woocommerce .rtp-main-wrapper #content-wrapper #content .button:hover',
				'.woocommerce-page .rtp-main-wrapper button:hover',
				'.woocommerce-page .rtp-main-wrapper .button:hover',
				'.woocommerce-page .rtp-main-wrapper input[type="submit"]:hover',
				'.woocommerce-page .rtp-main-wrapper #payment #place_order:hover',
				'.woocommerce-page .rtp-main-wrapper #content-wrapper #content .button:hover',
				'.woocommerce #respond input#submit:hover',
				'.woocommerce a.button:hover',
				'.woocommerce button.button:hover',
				'.woocommerce input.button:hover',
				'.woocommerce #respond input#submit.alt:hover',
				'.woocommerce a.button.alt:hover',
				'.woocommerce button.button.alt:hover',
				'.woocommerce input.button.alt:hover',
				'.rtp-cover-header-nav li a span',
				'.rtp-cover-header-nav li a span:hover',
				'.site #buddypress button',
				'.site #buddypress button:hover',
				'.site #buddypress input[type=submit]',
				'.site #buddypress input[type=submit]:hover',
				'.site #buddypress input[type=button]',
				'.site #buddypress input[type=button]:hover',
				'.site #buddypress input[type=reset]',
				'.site #buddypress input[type=reset]:hover',
				'.site #buddypress div.activity-comments form div.ac-reply-content a.ac-reply-cancel',
				'.site #buddypress div.activity-comments form div.ac-reply-content a.ac-reply-cancel:hover',
				'.site #buddypress div.item-list-tabs ul li a span',
				'.site #buddypress #whats-new-submit #aw-whats-new-submit',
				'.site #buddypress #activity-stream li.load-more',
				'.site #buddypress #activity-stream li.load-newest',
				'.site #buddypress a.button',
				'.site #buddypress a.button:hover',
				'#buddypress .generic-button a',
				'.site #buddypress div.generic-button a:hover',
				'.site #buddypress div.generic-button a:focus',
				'.site #buddypress #groups-directory-form .button',
				'.rtp-minicart-wrapper .rtp-count',
				'.site .rtmedia-container .rtm-load-more a',
				'.woocommerce nav.woocommerce-pagination ul li a',
				'.woocommerce nav.woocommerce-pagination ul li a:hover',
				'.site #buddypress #pag-bottom.pagination div.pagination-links .page-numbers',
				'.site #buddypress #pag-top.pagination div.pagination-links .page-numbers',
				'.rt-primary-menu .rtp-site-title',
				'.slicknav_nav li a span',
				'.woocommerce .widget_price_filter .ui-slider .ui-slider-range',
				'.woocommerce .widget_price_filter .ui-slider .ui-slider-handle',
				'.widget.widget_bp_core_friends_widget div.item-options a.selected',
				'.widget.widget_bp_core_friends_widget div.item-options a:hover',
			)
		);

		/**
		 * Contains all selectors for changing box shadow color
		 *
		 * @var array
		 */
		$box_showdow_selectors = apply_filters(
			'foodmania_customizer_box_shadow_selectors',
			array(
				'.rtp-main-navigation .current_page_item > a',
				'.rtp-main-navigation .current-menu-item > a',
				'.rtp-main-navigation .current_page_ancestor > a',
				'.rtp-main-navigation a:hover',
			)
		);

		/**
		 * Contains all selectors for changing the border color
		 *
		 * @var array
		 */
		$border_color_selectors = apply_filters(
			'foodmania_customizer_border_color_selectors',
			array(
				'.rtp-readmore',
				'.rtp-cover-header-nav li a:hover',
				'#buddypress div.item-list-tabs ul li a:hover',
				'#buddypress div.item-list-tabs ul li.selected a',
				'#buddypress div.item-list-tabs ul li:hover a',
				'#buddypress div.item-list-tabs#subnav ul li.current a',
				'#buddypress div.item-list-tabs ul li.current a',
				'.woocommerce ul.products li.product',
				'.woocommerce-page ul.products li.product',
				'.hentry',
				'.single-product div.product',
				'.widget',
				'.widget_calendar th',
				'.widget_calendar td',
				'.widget .rtas-tabs .login-register-tabs',
				'.comment-body',
				'.rtp-navigation',
				'#main-wrapper #content-wrapper .tabs',
				'#item-body>#bbpress-forums',
				'#bbpress-forums ul.bbp-lead-topic',
				'#bbpress-forums ul.bbp-topics',
				'#bbpress-forums ul.bbp-forums',
				'#bbpress-forums ul.bbp-replies',
				'#bbpress-forums ul.bbp-search-results',
				'#bbpress-forums div.bbp-topic-content code',
				'#bbpress-forums div.bbp-reply-content code',
				'#bbpress-forums div.bbp-topic-content pre',
				'#bbpress-forums div.bbp-reply-content pre',
				'.bbp-pagination-links span.current',
				'.bbp-topic-pagination a',
				'#bbpress-forums fieldset.bbp-form',
				'body.topic-edit .bbp-topic-form div.avatar img',
				'body.reply-edit .bbp-reply-form div.avatar img',
				'body.single-forum .bbp-topic-form div.avatar img',
				'body.single-reply .bbp-reply-form div.avatar img',
				'#bbpress-forums div.bbp-the-content-wrapper textarea.bbp-the-content',
				'#bbpress-forums div.bbp-the-content-wrapper div.quicktags-toolbar',
				'#bbpress-forums #bbp-your-profile fieldset input',
				'#bbpress-forums #bbp-your-profile fieldset textarea',
				'#whats-new-options',
				'#buddypress div.ac-reply-avatar img',
				'.bp-docs div.docs-info-header',
				'.docs div.docs-info-header',
				'.bp-docs div.doc-content',
				'.bp-docs .doctable',
				'.docs div.doc-content',
				'.docs .doctable',
				'.bp-docs #buddypress #subnav.item-list-tabs',
				'.docs #buddypress #subnav.item-list-tabs',
				'.bp-docs #buddypress table#post-revisions',
				'.docs #buddypress table#post-revisions',
				'#buddypress .rtp-tab-wrapper',
				'#buddypress .rtp-more-menus>ul',
				'#subnav.item-list-tabs',
				'ul.item-list>li',
				'.rtp-group-header-container',
				'.rendez-vous .wp-editor-area',
				'#subnav+.rtmedia-container',
				'body.woocommerce #content div.product .woocommerce-tabs ul.tabs',
				'body.woocommerce div.product .woocommerce-tabs ul.tabs',
				'body.woocommerce-page #content div.product .woocommerce-tabs ul.tabs',
				'body.woocommerce-page div.product .woocommerce-tabs ul.tabs',
				'body.woocommerce ul.products li.product',
				'body.woocommerce-page ul.products li.product',
				'.rtp-box-style',
				'#pag-top',
				'#pag-bottom',
				'.buddypress.settings #item-body #subnav ~ form',
				'.buddypress.single-item #item-body #ass-email-subscriptions-options-page',
				'.buddypress.mycred-history #item-body #myCRED-wrap',
				'.buddypress.group-events #item-body',
				'#eab-events-fpe',
				'#send-invite-form',
				'#rendez-vous-edit-form',
				'#rendez-vous-single-form',
				'.my-events #eab-bp-my_events-wrapper',
				'#invite-anyone-by-email',
				'.buddypress.messages #item-body #send_message_form',
				'.rtp-not-found',
				'.rtp-comments-pagination',
				'.f-dropdown',
				'.rtp-cover-header-nav li.current a',
				'.site #buddypress div.item-list-tabs#subnav ul li.current a',
				'.site #buddypress div.item-list-tabs ul li a:hover',
				'.site #buddypress div.item-list-tabs ul li.selected a',
				'.site #buddypress div.item-list-tabs ul li:hover a',
				'.site #buddypress div.item-list-tabs ul li.current a',
				'.f-dropdown:after',
			)
		);

		if ( class_exists( 'WooCommerce' ) ) {

			$woo_button_selectors = apply_filters(
				'foodmania_customizer_woo_background_selectors',
				array(
					'.woocommerce .rtp-main-wrapper button',
					'.woocommerce .rtp-main-wrapper .button',
					'.woocommerce .rtp-main-wrapper input[type="submit"]',
					'.woocommerce .rtp-main-wrapper #payment #place_order',
					'.woocommerce .rtp-main-wrapper #content-wrapper #content .button',
					'.woocommerce-page .rtp-main-wrapper button',
					'.woocommerce-page .rtp-main-wrapper .button',
					'.woocommerce-page .rtp-main-wrapper input[type="submit"]',
					'.woocommerce-page .rtp-main-wrapper #payment #place_order',
					'.woocommerce-page .rtp-main-wrapper #content-wrapper #content .button',
					'.woocommerce #respond input#submit',
					'.woocommerce a.button',
					'.woocommerce button.button',
					'.woocommerce input.button',
					'.woocommerce #respond input#submit.alt',
					'.woocommerce a.button.alt',
					'.woocommerce button.button.alt',
					'.woocommerce input.button.alt',
					'.woocommerce .rtp-main-wrapper button:hover',
					'.woocommerce .rtp-main-wrapper .button:hover',
					'.woocommerce .rtp-main-wrapper input[type="submit"]:hover',
					'.woocommerce .rtp-main-wrapper #payment #place_order:hover',
					'.woocommerce .rtp-main-wrapper #content-wrapper #content .button:hover',
					'.woocommerce-page .rtp-main-wrapper button:hover',
					'.woocommerce-page .rtp-main-wrapper .button:hover',
					'.woocommerce-page .rtp-main-wrapper input[type="submit"]:hover',
					'.woocommerce-page .rtp-main-wrapper #payment #place_order:hover',
					'.woocommerce-page .rtp-main-wrapper #content-wrapper #content .button:hover',
					'.woocommerce #respond input#submit:hover',
					'.woocommerce a.button:hover',
					'.woocommerce button.button:hover',
					'.woocommerce input.button:hover',
					'.woocommerce #respond input#submit.alt:hover',
					'.woocommerce a.button.alt:hover',
					'.woocommerce button.button.alt:hover',
					'.woocommerce input.button.alt:hover',
					'.rt-primary-menu .rtp-site-title',
					'.rtp-minicart-wrapper .rtp-count',
				)
			);

			array_push( $background_selectors, $woo_button_selectors );
		}

		$theme_color = get_theme_mod( 'rtp_theme_color' );

		// We would not output the css if the color is same as the default color.
		if ( ( ! empty( $theme_color ) ) && ( $theme_color !== self::$theme_default_color ) ) {
			self::generate_css( $color_selectors, 'color', 'rtp_theme_color' );
			self::generate_css( $background_selectors, 'background-color', 'rtp_theme_color' );
			self::generate_css( $border_color_selectors, 'border-color', 'rtp_theme_color' );
			self::generate_css( $box_showdow_selectors, '-webkit-box-shadow', 'rtp_theme_color', 'inset 0 -3px 0 0 ' );
			self::generate_css( $box_showdow_selectors, 'box-shadow', 'rtp_theme_color', 'inset 0 -3px 0 0 ' );
		}
	}
}

new RTP_Customizer_Front();
