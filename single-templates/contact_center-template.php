<?php

use ScorpioTek\WordPress\Util\PostUtilities;

global $post;

$contact_center_map = get_field( 'contact_center_map' );
$directions_available = !empty( $contact_center_map['lat'] ) &&  !empty ( $contact_center_map['lng'] );
if ( $directions_available ) {
    $map_url_link = sprintf('%1$s%2$s,%3$s',
        'https://www.google.com/maps?saddr=My+Location&daddr=',
        $contact_center_map['lat'],
        $contact_center_map['lng']
    );
}
$contact_center_address = get_field( 'contact_center_address' );
$suburb = get_field( 'suburb' );
$state = get_field( 'state' )['value'];
$postal_code = get_field( 'postal_code' );
?>

<!-- Single Contact Center -->
<div class="col-lg-3 col-md-6 col-sm-6 col-12 mt-30">
    <div class="tm-product">
        <div class="tm-product-content">
            <h5 class="tm-contact-center-title"><a href="<?php echo $map_url_link; ?>" target="_blank"><?php the_title(); ?></a></h5>
            <p>
            <?php 
                echo sprintf(
                    '%1$s<br>%2$s, %3$s %4$s',
                    $contact_center_address,
                    $suburb,
                    $state,
                    $postal_code                    
                ); 
            ?>
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
            <?php if ( current_user_can ( 'edit_post' , $post ) )  : ?>
                <button class="edit-this">
                    <?php edit_post_link(); ?>
                </button>
            <?php endif; ?>         
        </div>


        
    </div>
</div>
<!--// Single Contact Center -->