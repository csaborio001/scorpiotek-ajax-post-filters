<?php

use ScorpioTek\WordPress\Util\PostUtilities;

$event_image = wp_get_attachment_image( get_field( 'event_image' ), array('800', '398'), "", '' );
$event_date = new DateTime( get_field( 'start_date' ) );
$day = $event_date->format('d');
$month = $event_date->format('F');
$year = $event_date->format('Y');
$event_title = get_the_title();
$short_description = get_field( 'short_description' );
$event_column_count = $query->post_count == 1 ? 'col-lg-12' : 'col-lg-6';
?>

<!-- Single Blog -->
<div class="<?php echo $event_column_count ?> blog-masonry-item">
    <div class="tm-blog mt-50">
        <div class="tm-blog-top">
            <a href="<?php the_permalink(); ?>">
                <?php
                    if ( method_exists( PostUtilities::class, 'get_featured_image' ) ) {
                            PostUtilities::get_featured_image( $query->post, array( 800, 398), 'event_image', DEFAULT_IMAGE_PATH );
                    }
                ?>
            </a>
            <span class="tm-blog-date"><?php echo sprintf( '%s %s, %s', $day, $month, $year ); ?></span>
        </div>
        <div class="post-border-hover tm-blog-bottom">
            <div class="tm-blog-meta">
                <span><a href="#"><i class="zmdi zmdi-label"></i> <?php echo __( 'Event Type: ', 'vinnieslac' ); ?></a>
                    <?php
                        $categories = get_the_terms( $query->post->ID, 'events-category' );
                        if (!empty( $categories ) ) {
                            foreach ( $categories as $term ) {
                                // Need to figure if this is the last item in the list or if this is a list that 
                                // only has one element in it. 
                                $last_item = ( ( $categories[ count( $categories ) - 1 ])->name == $term->name ) ||
                                ( count( $categories) == 1 )  ? true : false;
                                // If it is not the last element, append comma at the end, otherwise nothing.
                                echo $term->name . ( $last_item ? '' : ', ' );
                            }
                        }
                    ?>
                </span>
            </div>
            <h5 class="tm-blog-title"><a href="<?php the_permalink(); ?>"><?php echo $event_title; ?></a></h5>
            <span class="italic"><?php echo sprintf( '%s %s, %s', $day, $month, $year ); ?></span>
            <p>
                <?php
                    if ( method_exists( PostUtilities::class, 'get_excerpt_max_words' ) ) {
                        $short_description = !empty ( $short_description ) ? $short_description . '...' : $short_description;
                        echo wp_kses(PostUtilities::get_excerpt_max_words( $short_description, 25 ),
                            array(
                                'p' => array()
                            )
                        );
                    }
                ?>
            </p>
            <a href="<?php the_permalink(); ?>" class="tm-button tm-button-dark">
            <?php
    echo sprintf(
    wp_kses(
        /* translators: %s: Name of current post. Only visible to screen readers */
        __( 'Read More<span class="screen-reader-text"> “\%s”</span>', 'https://link.scorpiotek.com/an3d8tvinnieslac' ),
        array(
            'span' => array(
                'class' => array(),
            ),
        )
    ),
    get_the_title()
    );
?>

            </a>
        </div>
    </div>
</div>
<!--// Single Blog -->