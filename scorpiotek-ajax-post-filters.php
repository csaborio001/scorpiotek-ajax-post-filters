<?php
/*  	
    Plugin Name: ScorpioTek Ajax Post Filters
    Description: Creates dropdown menus to filter specific content types fields
    @since  1.0
    Version: 1.0.2.4
	Text Domain: scorpiotek.com
*/



// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Enqueue the required ajax code to handle the requests.
function scorpiotek_events_enqueue_scripts() {
    wp_enqueue_script('data-request-js', plugins_url( 'js/data-request.js', __FILE__), '', false, true );
    wp_script_add_data( 'data-request-js', 'defer', true );
    wp_enqueue_script('chosen-loader-js', plugins_url( 'js/chosen-loader.js', __FILE__), array('jquery'), false, false) ;
    wp_script_add_data( 'chosen-loader-js', 'defer', true );
    wp_enqueue_script('chosen-js', plugins_url( 'js/chosen/chosen.jquery.min.js', __FILE__), array('jquery'), '1.8.7', false );
    wp_script_add_data( 'chosen-js', 'async', true );
}
add_action('wp_enqueue_scripts', 'scorpiotek_events_enqueue_scripts');

function scorpiotek_events_enqueue_styles() {
    wp_register_style( 'chosen-css', plugins_url( 'css/chosen.css', __FILE__), array(), '1.8.7' );
    wp_enqueue_style( 'chosen-css' );
}
add_action( 'wp_enqueue_scripts', 'scorpiotek_events_enqueue_styles' );

if ( file_exists( plugin_dir_path( __FILE__) . '/inc/filter-builder.php') )
    require_once( 'inc/filter-builder.php');


