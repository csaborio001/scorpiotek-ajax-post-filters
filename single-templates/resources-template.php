<?php
    use ScorpioTek\WordPress\Util\FilterBuilder;
    use ScorpioTek\WordPress\Util\PostUtilities;

    global $post;
    
    $resource_link = ( get_field( 'resource_type' === 'link')  || get_field( 'resource_type' === 'video') ) ? get_field( 'resource_url' ) : '';
    
    if ( get_field( 'resource_type' ) == 'document' )  {
        $resource_link = get_field( 'file_upload' );
    }


?>

<!-- Single Resource -->
<div class="col-lg-4 col-md-6 col-sm-6 col-12 mt-30">
    <div class="tm-product">
        <div class="tm-product-image">
            <a class="tm-product-imagelink" href="<?php echo $resource_link; ?>">
            <?php
                if ( method_exists( PostUtilities::class, 'get_featured_image' ) ) {
                    PostUtilities::get_featured_image($post, array(200, 200), 'featured_image', DEFAULT_IMAGE_PATH );
                }
            ?>
            </a>
        </div>
        <div class="tm-product-content">
            <h4 class="tm-product-title">
                <?php
                    echo sprintf('<a href="%1$s" title="%2$s">%2$s</a>',
                        $resource_link,
                        get_the_title()
                    );
                ?>
            </h4>

        <div class="tm-product-price"><?php the_field( 'resource_description' ); ?></div>
            <?php if ( current_user_can ( 'edit_post' , $post ) )  : ?>
            <button class="edit-this">
                <?php edit_post_link(); ?>
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--// Single Resource -->
