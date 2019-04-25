<?php

use ScorpioTek\WordPress\Util\PostUtilities;

global $post;

$event_date = get_field( 'start_date' );
$contact_center_map = get_field( 'contact_center_map' );
$directions_available = !empty( $contact_center_map['lat'] ) &&  !empty ( $contact_center_map['lng'] );
if ( $directions_available ) {
    $map_url_link = sprintf('%1$s%2$s,%3$s',
        'https://www.google.com/maps?saddr=My+Location&daddr=',
        $contact_center_map['lat'],
        $contact_center_map['lng']
    );
}
?>

<!-- Single Contact Center -->
<div class="col-lg-3 col-md-6 col-sm-6 col-12 mt-30">
    <div class="tm-product">
        <div class="tm-product-image">
            <a href="<?php echo $map_url_link; ?>" class="tm-product-imagelink" target="_blank">
                <?php
                    if ( method_exists( PostUtilities::class, 'get_featured_image' ) ) {
                        PostUtilities::get_featured_image( $post, array( 322, 375), 'vinnies_location_image', DEFAULT_IMAGE_PATH );
                    }
                ?>                
            </a>

            <?php if ( $directions_available ) : ?>
            <ul class="tm-product-actions">
                <li><a target="_blank" title ="<?php echo __('Get Directions', 'vinnieslac' );?>" href="<?php echo $map_url_link; ?>"><i class="zmdi zmdi-google-maps"></i></a></li>
            </ul>
            <?php endif; ?>
        </div>
        <div class="tm-product-content">
            <h5 class="tm-product-title"><a href="<?php echo $map_url_link; ?>" target="_blank"><?php the_title(); ?></a></h5>
            <div class="get-directions-link">
            <?php 
                if ( $directions_available ) {
                    echo sprintf('<a href="%1$s" target="_blank" title="%3$s">%2$s</a>',
                    $map_url_link,
                    __('Get Directions', 'vinnieslac'),
                    __('Get Directions to', 'vinnieslac') . ' Vinnies ' . get_the_title() 
                    );
                }
            ?>
            </div>
            <button class="edit-this">
                <?php edit_post_link(); ?>
            </button>             
        </div>


        
    </div>
</div>
<!--// Single Contact Center -->