<?php
    use ScorpioTek\WordPress\Util\FilterBuilder;
    use ScorpioTek\WordPress\Util\PostUtilities;

    global $post;
?>

<!-- Single Resource -->
<div class="col-lg-4 col-md-6 col-sm-6 col-12 mt-30">
    <div class="tm-product">
        <div class="tm-product-image">
            <a class="tm-product-imagelink" href="<?php the_field( 'resource_url' ); ?>">
            <?php
                if ( method_exists( PostUtilities::class, 'get_featured_image' ) ) {
                    PostUtilities::get_featured_image($post, array(200, 200), 'featured_image', DEFAULT_IMAGE_PATH );
                }

            ?>
            </a>
        </div>
        <div class="tm-product-content">
            <h4 class="tm-product-title"><a href="<?php the_field( 'resource_url' ); ?>"><?php the_title(); ?></a></h4>

        <div class="tm-product-price"><?php the_field( 'resource_description' ); ?></div>
        </div>
    </div>
</div>
<!--// Single Resource -->
