<?php
/**
 * Plugin Name: RCCIIT Campus Connect
 * Plugin URI: https://github.com/debanjalidas/rcciit_campus_connect.git
 * Description: Collects student feedback for RCCIIT X WordPress Campus Connect program.
 * Version: 1.0.0
 * Author: Debanjali Das
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

/* --------------------------------------------------
   Post Type
-------------------------------------------------- */
function register_cpt() {

    register_post_type('rcciit_campus_connect', array(
        'labels' => array(
            'name' => 'Campus Connect Feedback',
            'singular_name' => 'Campus Feedback'
        ),
        'public' => false,
        'show_ui' => true,
        'supports' => array('title', 'editor'),
        'menu_icon' => 'dashicons-feedback'
    ));
}
add_action('init', 'register_cpt');

/* --------------------------------------------------
   Feedback Form Shortcode
-------------------------------------------------- */
function shortcode() {

    ob_start();
    ?>

    <form method="post" style="max-width:500px;padding:20px;background:#000080;color:#fff;border-radius:8px;">
        <label><b>Name</b></label><br>
        <input type="text" name="rcciit_name" required style="width:100%;height:30px;font-size:24px;"><br><br>

        <label><b>College Roll Number</b></label><br>
        <input type="text" name="rcciit_roll" required style="width:100%;height:30px;font-size:24px;"><br><br>

        <label><b>College Email</b></label><br>
        <input type="email" name="rcciit_email" required style="width:100%;height:30px;font-size:24px;"><br><br>

        <label><b>Experience Feedback</b></label><br>
        <textarea name="rcciit_feedback" rows="10" required style="width:100%;"></textarea><br><br>

        <?php wp_nonce_field('rcciit_nonce_action', 'rcciit_nonce'); ?>

        <input type="submit" name="rcciit_submit" value="Submit" style="padding:10px 20px;background:#90ee90;color:#000;border:none;border-radius:4px;cursor:pointer;">
    </form>

    <?php
    return ob_get_clean();
}
add_shortcode('rcciitcampus_connect_form', 'shortcode');

/* --------------------------------------------------
   Form Submission
-------------------------------------------------- */
function form_submission() {

    if (!isset($_POST['rcciit_submit'])) {
        return;
    }

    if (!isset($_POST['rcciit_nonce']) ||
        !wp_verify_nonce($_POST['rcciit_nonce'], 'rcciit_nonce_action')) {
        return;
    }

    $y_name     = sanitize_text_field($_POST['rcciit_name']);
    $clg_roll     = sanitize_text_field($_POST['rcciit_roll']);
    $clg_email    = sanitize_email($_POST['rcciit_email']);
    $y_feedback = sanitize_textarea_field($_POST['rcciit_feedback']);

    $post_id = wp_insert_post(array(
        'post_type'    => 'rcciit_campus_connect',
        'post_title'   => $y_name,
        'post_content' => $y_feedback,
        'post_status'  => 'publish'
    ));

    if ($post_id) {

        add_post_meta($post_id, 'roll_number', $clg_roll);
        add_post_meta($post_id, 'email', $clg_email);

        wp_mail(
            $clg_email,
            'Thank you for your feedback!',
            'Thank you for sharing your experience at RCCIIT X WordPress Campus Connect.'
        );

        wp_redirect(add_query_arg('success', '1', wp_get_referer()));
        exit;
    }
}
add_action('init', 'form_submission');

/* --------------------------------------------------
    Message
-------------------------------------------------- */
function submit_message() {
    if (isset($_GET['success'])) {
        echo '<p style="color:green;">Thank you! Your feedback has been submitted successfully.</p>';
    }
}
add_action('wp_footer', 'submit_message');
