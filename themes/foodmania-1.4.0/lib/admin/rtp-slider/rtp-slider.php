<?php
/**
 * Defining Constants and Including files
 *
 * @package Foodmania
 */

// Setting some constants.
define( 'RTCAMP_SLIDER_VERSION', '1.0.0' );
define( 'RTCAMP_SLIDER__PLUGIN_URL', get_template_directory_uri() . RTP_ADMIN_FOLDER_PATH . 'rtp-slider/' );
define( 'RTCAMP_SLIDER__PLUGIN_DIR', get_template_directory() . RTP_ADMIN_FOLDER_PATH . 'rtp-slider/' );


// Including file.
require_once RTCAMP_SLIDER__PLUGIN_DIR . 'class.rtcamp-slider.php';
require_once RTCAMP_SLIDER__PLUGIN_DIR . 'class.rtcamp-slider-admin.php';
