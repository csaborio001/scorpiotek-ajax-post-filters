<?php

namespace ScorpioTek\WordPress\Util;

include_once( ABSPATH . 'wp-includes/class-wp-query.php' );
use WP_Query;
use DateTime;

class FilterBuilder {

    private $content_type;
    private $filter_fields;
    private $post_count;

    public function __construct( $content_type, $filter_fields, $meta_query, $taxonomy, $post_count ) {
        if ( !empty ( $content_type ) ) {
            $this->set_content_type( $content_type );
            $this->set_filter_fields( $filter_fields );
            $this->set_meta_query( $meta_query );
            $this->set_taxonomy( $taxonomy );
            $this->set_post_count( $post_count );
            add_action("wp_ajax_myfilter_{$content_type}", array( $this, 'scorpiotek_filter_function' ) );
            add_action("wp_ajax_nopriv_myfilter_{$content_type}", array( $this, 'scorpiotek_filter_function' ) );              
        }
        else {
            error_log( __( 'Trying to create a new FilterBuilder using an empty content type.', 'scorpiotek' ) );
        }
    }

    public function generate_form() { ?>
    <div class="filter-groups">
        <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">   

    <?php
    // Try and generate taxonomy drop down only if $taxonomy_name was specified.
    if ( is_array( $this->get_taxonomy() ) && !empty( $this->get_taxonomy() ) ) {
        foreach ( $this->get_taxonomy() as $single_taxonomy ) {
            $taxonomy_name = ucwords( get_taxonomy( $single_taxonomy )->label );
            // Retrieves all the terms from the passed down taxonomy
            $terms = get_terms( $single_taxonomy );
            if( !empty( $terms ) ) {
                echo sprintf( '<select name="%1$s" id="%2$s" class="chosen-select"><option value="-1">%3$s</option>',
                                $single_taxonomy . '_taxonomy_filter',
                                $single_taxonomy . '_id' ,
                                __( 'Filter by ', 'scorpiotek' ) . $taxonomy_name
                            );
                foreach ( $terms as $term ) :
                    echo '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the category as the value of an option
                endforeach;
                echo '</select>';
            }
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
                'meta_query' => $this->get_meta_query(),
            );
            $query  = new WP_Query( $query_args );
            $field_array = array();
            if ( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();
                $field_array[] = get_field( $value ); 
                endwhile;
            // Get rid of any duplicates.
            $field_array = array_unique( $field_array );
            // Trim all elements in the array.
            $field_array = array_map( function( $item_to_trim ) {
                return trim( $item_to_trim );
            }, $field_array);
            
            $this->print_field_values( $value, $field, $field_array );
            endif;
        }
    }

    ?>
    <!-- <button id="filter-button"><?php //echo __('Reset filters', 'scorpiotek' ); ?></button> -->
	<input type="hidden" name="action" value="myfilter_<?php echo $this->get_content_type(); ?>">
    </form>
    </div> <!-- Filter Groups -->
    
    <?php

    }

    public function print_post_list( $post_result_count = -1, $wp_query = null ) {
        if ( $post_result_count == 0 || empty ( $post_result_count ) ) { // Nothing was found.
            include( 'no-query-found.php' );
            return;
        }
        $query_args = array(
            'post_type' => $this->get_content_type(),
            'post_status' => 'publish',
            'posts_per_page' => $this->get_post_count(),
            'meta_query' => $this->get_meta_query(),
        );
        
        $query = '';
        // If the main query has already been set and it has any results, we'll print those results.
        // This is what will be called when no dropdown AJAX menu has been selected.
        if ( $GLOBALS['wp_query']->found_posts > 0 ) {
            $query = $GLOBALS['wp_query'];
        }
        // If we have an incoming parameter that has a query result set, we'll print those instead.
        else if ( !is_null( $wp_query ) && !empty ( $wp_query ) ) {
            $query = $wp_query;
        }
        // If it's neither of those cases, we'll just display the results from a new query. 
        else {
            $query = new WP_Query( $query_args );
        }
        if ( $query->have_posts() ) : while ( $query-> have_posts() ): $query->the_post() ?>
            <?php   
            if ( file_exists( plugin_dir_path( __FILE__ ) . '../single-templates/' . $this->get_content_type() . '-template.php' ) ) {
                include(  plugin_dir_path( __FILE__ ) . '../single-templates/' . $this->get_content_type() . '-template.php' );
            }
            else {
                error_log( 'Error loading file ' . $this->get_content_type() . '-template.php' );
            }
            ?>
            
        <?php
            endwhile;
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
        echo '<select name="' . $field_name . '" class="chosen-select"><option value="-1">Filter by ' . $field_label . '</option>';
        foreach ( $field_array as $field_value ) :
            echo '<option value="' . $field_value . '">' . ucwords( $field_value ). '</option>'; // ID of the category as the value of an option
        endforeach;
        echo '</select>';        
    }
   /**
    * @summary Callback function when a filter is selected
    *
    * @description This is the callback function that is invoked when a selection is made on any of the filters. 
    *
    * @author Christian Saborio <csaborio@scorpiotek.com>
    *
    */
    public function scorpiotek_filter_function() {
        // Get all post types represented by this page.
        $args = array(
            'post_type' => $this->get_content_type(),
            'post_status' => 'publish',            
            'orderby' => 'name',
            'order' => 'ASC', 
            'posts_per_page' => $this->get_post_count(),
            // 'order'	=> $_POST['date'] ,
        );
        // Iterate through the array of taxonomies that has been set to see if any has been set
        foreach ( $this->get_taxonomy() as  $single_taxonomy ) {
            $select_name = $single_taxonomy . '_taxonomy_filter';
            $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            // Make sure it's not empty and that it's not default value (-1)
            if (!empty( $_POST[$select_name] )  && ( intval($_POST[$select_name] )  !== -1 ) ) {
                $args['tax_query'][] = array(
                    array(
                        'taxonomy' => $single_taxonomy,
                        'field' => 'term_id',
                        'terms' => $_POST[$select_name],
                        'operator'=> 'IN',
                    )
                );
            }
        }
        
        // Check if other non-category fields were set and iterate through them.
        foreach ( $this->get_filter_fields() as $filter_label => $filter_name ) {
            if( !empty( $_POST[ $filter_name ] ) && ( intval($_POST[$filter_name] )  !== -1 ) )  {
                // Set the meta query to filter results based on selected filters.
                $args['meta_query'][] = array(
                    'key' => $filter_name,
                    'value' => $_POST[ $filter_name ],
                    'compare' => 'like'
                );
            }
        }

        // Join the meta query by the one specified by the user.
        if (!empty( $this->get_meta_query() ) ) {
            $args['meta_query'][] =  $this->get_meta_query();
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
     * Setter for meta_query
     *
     * @param string $meta_query the new value of the meta_query property.
     */
    public function set_meta_query( $meta_query ) {
        $this->meta_query = $meta_query;
    }
    /**
     * Getter for the meta_query property.
     */
    public function get_meta_query() {
        return $this->meta_query;
    }

    /**
     * Setter for taxonomy
     *
     * @param string $taxonomy the new value of the taxonomy property.
     */
    public function set_taxonomy( $taxonomy ) {
        $this->taxonomy = $taxonomy;
    }
    /**
     * Getter for the taxonomy property.
     */
    public function get_taxonomy() {
        return $this->taxonomy;
    }

    /**
     * Setter for post_count
     *
     * @param string $post_count the new value of the post_count property.
     */
    public function set_post_count( $post_count ) {
        $this->post_count = $post_count;
    }
    /**
     * Getter for the post_count property.
     */
    public function get_post_count() {
        return $this->post_count;
    }
}

require_once( 'filter-data.php');



