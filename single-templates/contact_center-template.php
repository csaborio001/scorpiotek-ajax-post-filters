<?php

use ScorpioTek\WordPress\Util\PostUtilities;

$event_date = get_field( 'start_date' );

?>

<!-- Single Contact Center -->
<div class="col-lg-3 col-md-6 col-sm-6 col-12 mt-30">
    <div class="tm-product">
        <div class="tm-product-image">
            <a class="tm-product-imagelink" href="product-details.html">
                <?php
                    if ( method_exists( PostUtilities::class, 'get_featured_image' ) ) {
                        PostUtilities::get_featured_image( $post, array( 322, 375), 'vinnies_location_image', DEFAULT_IMAGE_PATH );
                    }
                ?>                
            </a>
            <!-- <ul class="tm-product-actions">
                <li><button type="button" data-toggle="modal" data-target="#tm-product-quickview"><i
                            class="zmdi zmdi-eye"></i></button></li>
                <li><a href="cart.html"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                <li><a href="cart.html"><i class="zmdi zmdi-favorite"></i></a></li>
            </ul> -->
        </div>
        <div class="tm-product-content">
            <h5 class="tm-product-title"><a href="product-details.html"><?php the_title(); ?></a></h5>
            <!-- <h6 class="tm-product-price">$99.99 <del>$120.00</del></h6> -->
        </div>
    </div>
</div>
<!--// Single Contact Center -->