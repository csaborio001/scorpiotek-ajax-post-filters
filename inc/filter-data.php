<?php

use ScorpioTek\WordPress\Util\FilterBuilder;
if ( class_exists( FilterBuilder::class ) ) {

    $filter_list = array(
        array(
            'content_type' => 'event',
            'taxonomy' => array( 
                'events-category',
                'event-region',
            ),
            'sort' => array( 
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'meta_key' => 'start_date',
            ),
            'filter_fields' => array(
                // Text to Display => Field Name
                // 'Suburb' => 'city',
                // 'Postal Code' => 'zip',
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
                // 'Suburb' => 'suburb',
                // 'Postal Code' => 'postal_code',
            ),
            'sort' => array( 
                'orderby' => 'title',
                'order' => 'ASC',
            ),            
            'meta_query' => '',
            'taxonomy' => array( 'contact-center-region' ),
            'post_count' => -1,
        ),
        array(
            'content_type' => 'resources',
            'filter_fields' => array(
                // Text to Display => Field Name
                // 'Resource Type' => '',
            ),
            'sort' => array( 
                'orderby' => 'title',
                'order' => 'ASC',
            ),             
            'meta_query' => '',
            'taxonomy' => array('resources-category'),
            'post_count' => -1,
        ),
    );


    global $filter_builder;

    foreach ($filter_list as $filter ) {
        $filter_builder[$filter['content_type']] = new FilterBuilder( 
            $filter[ 'content_type' ], $filter['filter_fields'], $filter['sort'], $filter['meta_query'], $filter['taxonomy'], $filter['post_count']
        );
    }
}