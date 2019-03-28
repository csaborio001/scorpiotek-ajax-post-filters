<?php

use ScorpioTek\WordPress\Util\FilterBuilder;
if ( class_exists( FilterBuilder::class ) ) {

    $filter_list = array(
        array(
            'content_type' => 'event',
            'filter_fields' => array(
                // Text to Display => Field Name
                'Suburb' => 'city',
                'Postal Code' => 'zip',
            ),
        ),
    );


    global $filter_builder;

    foreach ($filter_list as $filter ) {
        $filter_builder[$filter['content_type']] = new FilterBuilder( $filter[ 'content_type' ], $filter[ 'filter_fields' ] );
    }
}