<?php

use ScorpioTek\WordPress\Util\FilterBuilder;
if ( class_exists( FilterBuilder::class ) ) {

    $filter_list = array(
        array(
            'content_type' => 'event',
            'taxonomy' => 'events-category',
            'filter_fields' => array(
                // Text to Display => Field Name
                'Suburb' => 'city',
                'Postal Code' => 'zip',
            ),
            'meta_query' => array(
                array(
                    'key' => 'start_date',
                    'value' => (new DateTime('now'))->format('Ymd'),
                    'compare' => '>=',
                    'type' => 'date',
                ),
            ),
            'post_count' => -1,
        ),
        array(
            'content_type' => 'contact_center',
            'filter_fields' => array(
                // Text to Display => Field Name
                'Suburb' => 'suburb',
                'Postal Code' => 'postal_code',
            ),
            'meta_query' => '',
            'taxonomy' => '',
            'post_count' => -1,
        ),
        array(
            'content_type' => 'resources',
            'filter_fields' => array(
                // Text to Display => Field Name
                // 'Resource Type' => '',
            ),
            'meta_query' => '',
            'taxonomy' => 'resources-category',
            'post_count' => -1,
        ),
    );


    global $filter_builder;

    foreach ($filter_list as $filter ) {
        $filter_builder[$filter['content_type']] = new FilterBuilder( 
            $filter[ 'content_type' ], $filter[ 'filter_fields' ], $filter['meta_query'], $filter['taxonomy'], $filter['post_count']
        );
    }
}