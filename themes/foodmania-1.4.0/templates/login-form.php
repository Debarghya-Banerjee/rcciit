<?php
/**
 *  Used in the login form popup.
 *
 * @package Foodmania
 */

if ( ! is_user_logged_in() ) {
	?>
	<h3 class="rtp-login-form-title"><?php esc_html_e( 'Login', 'foodmania' ); ?></h3>

	<?php do_action( 'foodmania_hook_before_login_form' ); ?>

	<div class="rtp-login-form-container clearfix">

		<?php do_action( 'foodmania_hook_begin_login_form' ); ?>

		<form name="rtp-login-form" class="rtp-login-form clearfix" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
			<input type="text" name="log" class="input" value="" placeholder="<?php esc_attr_e( 'Username', 'foodmania' ); ?>" required />
			<input type="password" name="pwd" class="input" value="" placeholder="<?php esc_attr_e( 'Password', 'foodmania' ); ?>" required />
			<div class="clearfix rtp-forgetmenot-content">
				<label class="forgetmenot"><input name="rememberme" type="checkbox" value="forever" />
					<?php esc_html_e( 'Remember Me', 'foodmania' ); ?>
				</label>
				<a class="rtp-lost-pass" href="<?php echo esc_url( wp_lostpassword_url() ); ?>" title="Lost Password"><?php esc_html_e( 'Lost Password?', 'foodmania' ); ?></a>
			</div>
			<input type="hidden" name="action" value="rtp_login" />
			<input type="submit" name="wp-submit" class="rtp-button submit-button rtp-wp-submit full" value="<?php esc_attr_e( 'Log In', 'foodmania' ); ?>" />
		</form>

		<?php do_action( 'foodmania_hook_end_login_form' ); ?>

		<?php
		if ( function_exists( 'bp_get_signup_allowed' ) && bp_get_signup_allowed() ) :
			?>
			<footer class="rtp-register">
				<?php esc_html_e( "Don't have an account? ", 'foodmania' ); ?>
				<span class="rtp-login-widget-register-link">
					<?php
					/* translators: %s is sign-up page url */
					printf( '<a href="%s" title="' . esc_attr__( 'Register for a new account', 'foodmania' ) . '">' . esc_html__( 'Register', 'foodmania' ) . '</a>', esc_url( bp_get_signup_page() ) );
					?>
				</span>
			</footer>
			<?php
		endif;
		?>
	</div>
	<?php
	do_action( 'foodmania_hook_after_login_form' );
}
