<?php
/*
Plugin Name: RCCIIT Campus Connect
Description: Collect student feedback 
Version: 1.0
Author:Jayeeta
*/
add_action('init', 'rcciit_hook');
function rcciit_hook() {
    //  Runs when WP loads
}
add_shortcode('rcciit_campus_connect_form', 'rcciit_render_form');
function rcciit_render_form() {
    ob_start(); ?>

    <?php if (isset($_GET['rcciit_success'])) : ?>
        <p style="color: green; font-weight: bold;">Thank you for your feedback!</p>
    <?php endif; ?>

    <form method="post">
        <p>
            <label>Name</label><br>
            <input type="text" name="rcciit_name" required style="width: 100%; padding: 8px;">
        </p>

        <p>
            <label>Roll Number</label><br>
            <input type="text" name="rcciit_roll" required style="width: 100%; padding: 8px;">
        </p>

        <p>
            <label>Email</label><br>
            <input type="email" name="rcciit_email" required style="width: 100%; padding: 8px;">
        </p>

        <p>
            <label>How was your experience at the RCCIIT X WordPress Campus Connect program?</label><br>
            <textarea name="rcciit_feedback" rows="5" required style="width: 100%; padding: 8px;"></textarea>
        </p>

        <p>
            <?php wp_nonce_field('rcciit_form_action', 'rcciit_form_nonce'); ?>
            <input type="submit" name="rcciit_submit" value="Submit Feedback">
        </p>
    </form>

    <?php
    return ob_get_clean();
}

add_action('init', 'rcciit_handle_form');

function rcciit_handle_form() {

    if (!isset($_POST['rcciit_submit'])) {
        return;
    }

    if (!isset($_POST['rcciit_form_nonce']) ||
        !wp_verify_nonce($_POST['rcciit_form_nonce'], 'rcciit_form_action')) {
        return;
    }

    $name     = sanitize_text_field($_POST['rcciit_name']);
    $roll     = sanitize_text_field($_POST['rcciit_roll']);
    $email    = sanitize_email($_POST['rcciit_email']);
    $feedback = wp_kses_post($_POST['rcciit_feedback']);

    $post_id = wp_insert_post(array(
        'post_type'   => 'rcciit_test',
        'post_title'  => $name,
        'post_content'=> $feedback,
        'post_status' => 'publish'
    ));

    if ($post_id) {
        add_post_meta($post_id, 'roll_number', $roll);
        add_post_meta($post_id, 'email', $email);
    }

    wp_mail(
        $email,
        'Thank you for your feedback!',
        'Thank you for participating in the RCCIIT X WordPress Campus Connect program.'
    );

    wp_redirect(add_query_arg('rcciit_success', '1', wp_get_referer()));
    exit;
}



add_action('init', 'rcciit_register_cpt');
function rcciit_register_cpt() {
    register_post_type('rcciit_test', array(
        'labels' => array(
            'name'          => 'Feedback RCCIIT X Campus Connect',
            'singular_name' => 'RCCIIT Campus Feedback',
            'menu_name'     => 'RCCIIT X Campus Connect'
        ),
        'public'        => false,   // no front end
        'show_ui'       => true,   
        'show_in_menu'  => true,
        'supports'      => array('title','editor')
    ));
}



add_action('add_meta_boxes', 'rcciit_add_meta_box');

function rcciit_add_meta_box() {
    add_meta_box(
        'rcciit_student_details',
        'Student Details',
        'rcciit_meta_box_callback',
        'rcciit_test',
        'normal',
        'default'
    );
}

function rcciit_meta_box_callback($post) {
    $roll  = get_post_meta($post->ID, 'roll_number', true);
    $email = get_post_meta($post->ID, 'email', true);

    echo '<p><strong>Roll Number:</strong> ' . esc_html($roll) . '</p>';
    echo '<p><strong>Email:</strong> ' . esc_html($email) . '</p>';
}
add_filter('manage_rcciit_test_posts_columns', 'rcciit_add_columns');
function rcciit_add_columns($columns) {
    $columns['roll_number'] = 'Roll Number';
    $columns['email'] = 'Email';
    return $columns;
}

add_action('manage_rcciit_test_posts_custom_column', 'rcciit_render_columns', 10, 2);
function rcciit_render_columns($column, $post_id) {
    if ($column == 'roll_number') {
        echo esc_html(get_post_meta($post_id, 'roll_number', true));
    }
    if ($column == 'email') {
        echo esc_html(get_post_meta($post_id, 'email', true));
    }
}
