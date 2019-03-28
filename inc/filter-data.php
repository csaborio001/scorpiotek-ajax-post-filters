<?php

use ScorpioTek\WordPress\Util\FilterBuilder;
if ( class_exists( FilterBuilder::class ) ) {

    $filter_fields = array(
        // Text to Display => Field Name
        'Suburb' => 'city',
        'Postal Code' => 'zip',
    );
    $post_fields_to_print = array(
        'event_image' => 'image',
        'start_date' => 'text',
        'title' => 'title',
        'short_description' => 'text',
    );

    global $filter_builder;

    $filter_builder = new FilterBuilder( 'event', $filter_fields, $post_fields_to_print );
}