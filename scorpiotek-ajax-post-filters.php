<?php
/*  	
    Plugin Name: ScorpioTek Ajax Post Filters
    Description: Creates dropdown menus to filter specific content types fields
    @since  1.0
    Version: 1.0
	Text Domain: scorpiotek.com
*/



// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Enqueue the required ajax code to handle the requests.
function scorpiotek_events_enqueue_scripts() {
    wp_enqueue_script('data-request-js', plugins_url( 'js/data-request.js', __FILE__), array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'scorpiotek_events_enqueue_scripts');

if ( file_exists( plugin_dir_path( __FILE__) . '/inc/filter-builder.php') )
    require_once( 'inc/filter-builder.php');


