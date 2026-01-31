<?php
/**
 * Contains all hooks used throughout the theme files.
 *
 * @package Foodmania
 */

add_action( 'foodmania_main_header_end', 'foodmania_main_header_end' );

/**
 * Creating navigation for foodmania.
 */
function foodmania_main_header_end() {
	do_action( 'foodmania_nav_end' ); ?>
	<nav id="site-navigation" class="rtp-main-navigation" role="navigation">
		<a class="menu-toggle" href="#nav"><span class="dashicons dashicons-menu"></span></a>
		<?php
		wp_nav_menu(
			apply_filters(
				'foodmania_primary_menu_args',
				array(
					'theme_location'  => 'primary',
					'container_class' => 'rt-primary-menu',
					'menu_id'         => 'primary-menu',
					'depth'           => 3,
				)
			)
		);
		?>
	</nav><!-- #site-navigation -->
	<?php
	do_action( 'foodmania_nav_start' );
}

add_action( 'foodmania_main_header_begin', 'foodmania_main_header_begin' );

/***
 * Navigation bar heading render.
 */
function foodmania_main_header_begin() {
	?>
	<div class="rtp-site-branding">
		<?php
		rtp_header_image();
		if ( get_bloginfo( 'description' ) ) {
			?>
			<p class="rtp-site-description"><?php bloginfo( 'description' ); ?></p>
			<?php
		}
		?>
	</div><!-- .site-branding -->
	<?php
}

add_action( 'foodmania_site_info_left', 'foodmania_site_info_left' );

/**
 * Site info rendering on left side
 */
function foodmania_site_info_left() {
	?>
	<div class="rtp-left-part small-12 medium-4 large-3 column">
		<?php rtp_header_image( 'footer' ); ?>
	</div>
	<?php
}

// rtp_social.
add_action( 'foodmania_social', 'rtp_social' );


if ( ! function_exists( 'rtp_social' ) ) {
	/**
	 * Adding social information inside headers.
	 */
	function rtp_social() {
		$social_method = get_theme_mod( 'rtp_social_visibility', 1 );

		if ( ! $social_method ) {
			return;
		} else {
			$social_facebook = get_theme_mod( 'rtp_social_facebook' );
			$social_twitter  = get_theme_mod( 'rtp_social_twitter' );

			$social_buttons = array(
				'facebook' => array(
					'link'       => $social_facebook,
					'class'      => 'rtp_facebook',
					'title'      => __( 'Facebook', 'foodmania' ),
					'icon-class' => 'dashicons-facebook-alt',

				),
				'twitter'  => array(
					'link'       => $social_twitter,
					'class'      => 'rtp_twitter',
					'title'      => __( 'Twitter', 'foodmania' ),
					'icon-class' => 'dashicons-twitter',

				),
			);
			$social_buttons = apply_filters( 'rtp_customize_social_show', $social_buttons );

			?>
			<ul class="rtp_social_warrp small-12 medium-4 large-5 column">
				<?php
				foreach ( $social_buttons as $key => $social_button ) {
					if ( ! empty( $key ) ) {
						?>
						<li class="rtp_social <?php echo esc_attr( $social_button['class'] ); ?>">
							<a href="<?php echo esc_url( $social_button['link'] ); ?>" title="<?php echo esc_attr( $social_button['title'] ); ?>" target="_blank" ><span class="dashicons <?php echo esc_attr( $social_button['icon-class'] ); ?>"></span></a>
						</li>
						<?php
					}
				}
				?>
			</ul>
			<?php
		}
	}
}

add_action( 'foodmania_site_info_right', 'foodmania_site_info_right' );
/**
 * Header site info right rendering.
 */
function foodmania_site_info_right() {
	$copyright_text = get_theme_mod( 'rtp_footer_copyright' );
	?>
	<div class="rtp-right-part small-12 medium-4 large-4 column">
		<?php
		if ( $copyright_text ) {
			echo esc_html( $copyright_text );
		} else {
			?>
			<?php /* translators: %s is text  */ ?>
			<a href="<?php echo esc_url( __( 'https://rtmedia.io/products/foodmania/', 'foodmania' ) ); ?>"><?php printf( esc_html__( 'Powered by %s', 'foodmania' ), 'foodmania' ); ?></a>
			<span class="sep"> | </span>
			<?php /* translators: %1$s is attribute %2$s is attribute  */ ?>
			<?php printf( esc_html( '%1$s %2$s ' . gmdate( 'Y' ) . '.' ), 'foodmania', '<a href="https://rtcamp.com/" rel="designer">Copyright &copy;</a>' ); ?>
		<?php } ?>
	</div>
	<?php
}



add_action( 'foodmania_inside_page_loop', 'foodmania_inside_page_loop', 10 );
/**
 * Rendering the page comments.
 */
function foodmania_inside_page_loop() {
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

add_action( 'foodmania_post_listing_article_begin', 'foodmania_post_listing_article_begin' );

/**
 * Rendering post data after articles are loaded.
 */
function foodmania_post_listing_article_begin() {
	if ( has_post_thumbnail() ) {
		?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="rtp-featured-image">
			<?php the_post_thumbnail(); ?>
		</a>
		<?php
	}
}

// Pagination hooks.
add_action( 'foodmania_primary_div_search_end', 'rtp_pagination' );
add_action( 'foodmania_inside_page_loop', 'foodmania_single_pagination', 9 );
add_action( 'foodmania_primary_end', 'rtp_pagination' );
add_action( 'foodmania_primary_div_archive_end', 'rtp_pagination' );
add_action( 'foodmania_primary_div_index_end', 'rtp_pagination' );


/*
 * Login Box.
 */

add_action( 'wp_footer', 'rtp_login_popup' );
/**
 * Login dashboard rendering.
 */
function rtp_login_popup() {
	$login_method = get_theme_mod( 'rtp_login_method', 'popup' );
	if ( 'popup' !== $login_method ) {
		return;
	}
	?>
	<div id="rtp-login-popup" class="reveal-modal rtp-login-popup-model tiny" data-reveal>
		<?php get_template_part( 'templates/login-form' ); ?>
		<a class="close-reveal-modal rtp-close-reveal">&#215;</a>
	</div>
	<?php
}

/* Current User Links */

add_action( 'foodmania_nav_end', 'rtp_logged_in_user_section' );

/**
 * Check if user is logged in and render data to logged in user.
 */
function rtp_logged_in_user_section() {
	$login_method = get_theme_mod( 'rtp_login_method', 'popup' );
	$show_tab     = get_theme_mod( 'rtp_login_menu_visibility', 1 );
	$reveal_id    = ( 'popup' === $login_method ) ? 'data-reveal-id="rtp-login-popup"' : false;

	if ( ! $show_tab ) {
		return;
	}
	?>
	<div class="rtp-current-user-details clearfix">
		<?php
		if ( is_user_logged_in() ) {
			global $current_user;
			$current_user_id   = get_current_user_id();
			$user_profile_link = ( function_exists( 'bp_loggedin_user_domain' ) ) ? bp_loggedin_user_domain() : '#';
			?>

			<a aria-controls="rtp-user-links" aria-expanded="false" data-dropdown="rtp-user-links" class="rtp-current-user-avatar dropdown" href="<?php echo esc_url( $user_profile_link ); ?>" title="<?php echo esc_attr( $current_user->user_nicename ); ?>">
				<span class="rtp-user-name"><?php echo esc_html( $current_user->display_name ); ?></span>
				<?php echo get_avatar( $current_user_id, 36 ); ?>
			</a>
			<?php
			if ( function_exists( 'rtp_current_user_links' ) ) {
				rtp_current_user_links();
			}
		} else {
			?>
			<a class="rtp-login button tiny" href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" <?php echo esc_html( $reveal_id ); ?> ><?php esc_html_e( 'Login', 'foodmania' ); ?></a>
			<?php
		}
		?>
	</div>
	<?php
}


if ( ! function_exists( 'foodmania_wordpress_social_login' ) ) {
	/**
	 * Rendering social login option if not already present.
	 */
	function foodmania_wordpress_social_login() {
		if ( has_action( 'wordpress_social_login' ) ) {
			?>
			<div class="rtp-feature-separator"><span><?php esc_html_e( 'OR', 'foodmania' ); ?></span></div>
			<h6 class="rtp-signin-title"><?php esc_html_e( 'Sign in with', 'foodmania' ); ?></h6>
			<?php
			do_action( 'wordpress_social_login' );
		}
	}

	add_action( 'foodmania_hook_end_login_form', 'foodmania_wordpress_social_login' );
}
