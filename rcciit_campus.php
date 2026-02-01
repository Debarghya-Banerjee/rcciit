<?php
/**
 * Plugin Name: RCCIIT Campus Connect
 * Plugin URI: https://github.com/shibashisdas76/rcciit
 * Description: Collects student feedback for RCCIIT X WordPress Campus Connect program.
 * Version: 1.0.0
 * Author: Shibashis Das
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

/* --------------------------------------------------
   Post Type
-------------------------------------------------- */
function register() {

    register_post_type('rcciit_campus_connect', array(
        'labels' => array(
            'name' => 'Program Feedback',
            'singular_name' => 'Program Feedback'
        ),
        'public' => false,
        'show_ui' => true,
        'supports' => array('title', 'editor'),
        'menu_icon' => 'dashicons-feedback'
    ));
}
add_action('init', 'register');

/* --------------------------------------------------
   Feedback Form Shortcode
-------------------------------------------------- */
function shortcode() {

    ob_start();
    ?>


    <form method="post" style="max-width:500px;padding:20px;background:	rgb(76, 203, 232);color:#000075;border-radius:8px;">
        <label><b>Name</b></label><br>
        <input type="text" name="your_name" required style="width:100%;height:30px;"><br><br>

        <label><b>College Roll Number</b></label><br>
        <input type="text" name="your_college_roll" required style="width:100%;height:30px;"><br><br>

        <label><b>College Email</b></label><br>
        <input type="email" name="your_college_email" required style="width:100%;height:30px;"><br><br>

        <label><b>Program Feedback</b></label><br>
        <textarea name="Program_feedback" rows="10" required style="width:100%;"></textarea><br><br>

        <?php wp_nonce_field('rcciit_action', 'rcciit_nonce'); ?>

       <center><input type="submit" name="submit" value="Submit" style="padding:30px 40px;background:#90ee90;color:#000;border:none;border-radius:4px;cursor:pointer;font-size:25px;"></center>
    </form>

    <?php
    return ob_get_clean();
}
add_shortcode('campus_connect_form', 'shortcode');

/* --------------------------------------------------
   Form Submission
-------------------------------------------------- */
function form_submission() {

    if (!isset($_POST['submit'])) {
        return;
    }

    if (!isset($_POST['rcciit_nonce']) ||
        !wp_verify_nonce($_POST['rcciit_nonce'], 'rcciit_nonce_action')) {
        return;
    }

    $your_name     = sanitize_text_field($_POST['your_name']);
    $your_college_roll     = sanitize_text_field($_POST['your_college_roll']);
    $your_college_email    = sanitize_email($_POST['your_college_email']);
    $Program_feedback = sanitize_textarea_field($_POST['Program_feedback']);

    $post_id = wp_insert_post(array(
        'post_type'    => 'rcciit_campus_connect',
        'post_title'   => $your_name,
        'post_content' => $Program_feedback,
        'post_status'  => 'publish'
    ));

    if ($post_id) {

        add_post_meta($post_id, 'roll_number', $your_college_roll);
        add_post_meta($post_id, 'email', $your_college_email);

        wp_mail(
            $your_college_email,
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
add_action('footer', 'submit_message');