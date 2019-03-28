<?php

namespace ScorpioTek\WordPress\Util;

include_once( ABSPATH . 'wp-includes/class-wp-query.php' );
use WP_Query;
use DateTime;

class FilterBuilder {

    private $content_type;
    private $filter_fields;
    private $post_fields_to_print;

    public function __construct( $content_type, $filter_fields, $post_fields_to_print ) {
        if ( !empty ($content_type ) ) {
            $this->set_content_type( $content_type );
            $this->set_filter_fields( $filter_fields );
            $this->set_post_fields_to_print( $post_fields_to_print );
            add_action('wp_ajax_myfilter', array( $this, 'scorpiotek_filter_function' ) );
            add_action('wp_ajax_nopriv_myfilter', array( $this, 'scorpiotek_filter_function' ) );              
        }
        else {
            error_log( __( 'Trying to create a new FilterBuilder using an empty content type.', 'scorpiotek' ) );
        }
    }

    public function generate_form( $taxonomy_name = 'none' ) { ?>
    <div class="filter-groups">
        <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">   

    <?php
    // Try and generate taxonomy drop down only if $taxonomy_name was specified.
    if ( $taxonomy_name != 'none' && !empty ( $taxonomy_name ) ) {
        // Generate the category fields if it generates any results.
        if( $terms = $this->get_categories_by_post_type( $taxonomy_name, $this->get_content_type() ) ) {
            echo '<select name="categoryfilter" class="chosen-select"><option value="">Filter by category</option>';
            foreach ( $terms as $term ) :
                echo '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the category as the value of an option
            endforeach;
            echo '</select>';
        }
    }

    // Only generate filter fields if they were initially set.
    if ( is_array( $this->get_filter_fields() ) ) {
        foreach ( $this->get_filter_fields() as $field => $value ) {
            $query_args = array(
                'post_type' => $this->get_content_type(),
                'post_status' => 'publish',
                'posts_per_page' => -1,
                // Only look for posts that will happen today or in the future.
                'meta_query' => array(
                    array(
                        'key' => 'start_date',
                        'value' => (new DateTime('now'))->format('Ymd'),
                        'compare' => '>=',
                        'type' => 'date',
                    ),
                )
            );
            $query  = new WP_Query( $query_args );
            $field_array = array();
            if ( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();
                $field_array[] = get_field( $value ); 
                endwhile;
            array_unique( $field_array );    
            $this->print_field_values( $value, $field, $field_array );
            endif;
        }
    }

    ?>
    <button id="filter-button"><?php echo __('Apply filter', 'scorpiotek' ); ?></button>
	<input type="hidden" name="action" value="myfilter">
    </form>
    </div> <!-- Filter Groups -->
    
    <?php

    }

    public function print_post_list( $post_count = 0, $wp_query = null ) {
        if ( $post_count == 0 || empty ( $post_count ) ) {
            include( 'no-query-found.php' );
            return;
        }

        if ( ( is_array( $this->get_post_fields_to_print() ) && !empty( $this->get_post_fields_to_print() ) ) ) : ?>
                <?php
                    $query_args = array(
                        'post_type' => $this->get_content_type(),
                        'post_status' => 'publish',
                        'posts_per_page' => $post_count,
                    );
                    $query = '';
                    if ( !is_null( $wp_query ) && !empty ( $wp_query ) ) {
                        $query = $wp_query;
                    }
                    else {
                        $query = new WP_Query( $query_args );
                    }
                    if ( $query->have_posts() ) : while ( $query-> have_posts() ): $query->the_post() ?>
                    <?php   
                        include( 'single-template.php' );
                    ?>
                        
                    <?php
                        endwhile;
                    endif;
                ?>
        <?php
        endif;
    }


    function get_categories_by_post_type($taxonomy, $post_type ) {
        $customPostTaxonomies = get_object_taxonomies( $post_type );

        if(count($customPostTaxonomies) > 0) {
            foreach($customPostTaxonomies as $tax) {
            $args = array(
                'orderby' => 'name',
                'show_count' => 0,
                'pad_counts' => 0,
                'hierarchical' => 1,
                'taxonomy' => $tax,
                'title_li' => ''
                );
        
            return get_categories( $args );
            }
        }  
    }

    private function print_field_values( $field_name, $field_label, $field_array ) {
        echo '<select name="' . $field_name . '" class="chosen-select"><option value="">Filter by ' . $field_label . '</option>';
        foreach ( $field_array as $field_value ) :
            echo '<option value="' . $field_value . '">' . $field_value . '</option>'; // ID of the category as the value of an option
        endforeach;
        echo '</select>';        
    }

    public function scorpiotek_filter_function() {
        $args = array(
            'post_type' => $this->get_content_type(),
            'post_status' => 'publish',            
            'orderby' => 'date', 
            // 'order'	=> $_POST['date'] ,
        );
        
        /* This will only work if the 'Enable Events Categories System' of the squarecandy-acf-events
         * is enabled */
        
        if( !empty( $_POST['categoryfilter'] ) )
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'events-category',
				'field' => 'term_id',
                'terms' => $_POST['categoryfilter'],
                'operator'=> 'IN',
			)
        );
        
        foreach ( $this->get_filter_fields() as $filter_label => $filter_name ) {
            if( !empty( $_POST[ $filter_name ] ) )  {
                $args['meta_query'][] = array(
                    'key' => $filter_name,
                    'value' => $_POST[ $filter_name ],
                    'compare' => '='
                );
            }
        }

        $query = new WP_Query( $args );
 
        $this->print_post_list( $query->post_count, $query );
     
        die();        
    }

    /**
     * Setter for content_type
     *
     * @param string $content_type the new value of the content_type property.
     */
    public function set_content_type( $content_type ) {
        $this->content_type = $content_type;
    }
    /**
     * Getter for the content_type property.
     */
    public function get_content_type() {
        return $this->content_type;
    }

    /**
     * Setter for filter_fields
     *
     * @param string $filter_fields the new value of the filter_fields property.
     */
    public function set_filter_fields( $filter_fields ) {
        $this->filter_fields = $filter_fields;
    }
    /**
     * Getter for the filter_fields property.
     */
    public function get_filter_fields() {
        return $this->filter_fields;
    }

    /**
     * Setter for post_fields_to_print
     *
     * @param string $post_fields_to_print the new value of the post_fields_to_print property.
     */
    public function set_post_fields_to_print( $post_fields_to_print ) {
        $this->post_fields_to_print = $post_fields_to_print;
    }
    /**
     * Getter for the post_fields_to_print property.
     */
    public function get_post_fields_to_print() {
        return $this->post_fields_to_print;
    }

}

require_once( 'filter-data.php');



