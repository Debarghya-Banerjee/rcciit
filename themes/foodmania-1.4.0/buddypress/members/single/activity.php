<?php
/**
 * BuddyPress - Users Activity
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
	<ul>

		<?php bp_get_options_nav(); ?>

		<?php
		if ( 'nouveau' === get_option( '_bp_theme_package_id' ) ) {
			bp_get_template_part( 'common/search-and-filters-bar' );
		} else {
			?>
		<li id="activity-filter-select" class="last rtp-select-wrapper">

			<select id="activity-filter-by" class="rtp-select">
				<option value="-1"><?php esc_html_e( '&mdash; Everything &mdash;', 'foodmania' ); ?></option>

				<?php bp_activity_show_filters(); ?>

				<?php do_action( 'bp_activity_filter_options' ); ?>

			</select>
		</li>
		<?php } ?>

	</ul>
</div><!-- .item-list-tabs -->

<?php do_action( 'bp_before_member_activity_post_form' ); ?>

<?php

// Check if selected template is Nouveau template then use Nouveau member activity functions.
if ( 'nouveau' === get_option( '_bp_theme_package_id' ) ) { 
	if ( is_user_logged_in() && bp_is_my_profile() && ( ! bp_current_action() || bp_is_current_action( 'just-me' ) ) ) {
		bp_nouveau_activity_member_post_form();
	}

	bp_nouveau_member_hook( 'before', 'activity_content' );
	?>

	<div id="activity-stream" class="activity single-user" data-bp-list="activity">

		<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'member-activity-loading' ); ?></div>

		<ul class="<?php bp_nouveau_loop_classes(); ?>"></ul>

	</div><!-- .activity -->

	<?php
	bp_nouveau_member_hook( 'after', 'activity_content' );

} else {
	if ( is_user_logged_in() && bp_is_my_profile() && ( ! bp_current_action() || bp_is_current_action( 'just-me' ) ) ) {
		bp_get_template_part( 'activity/post-form' );
	}
	
	do_action( 'bp_after_member_activity_post_form' );
	do_action( 'bp_before_member_activity_content' );
	?>

	<div class="activity" role="main">

		<?php bp_get_template_part( 'activity/activity-loop' ); ?>

	</div><!-- .activity -->

	<?php do_action( 'bp_after_member_activity_content' ); ?>
<?php } ?>
