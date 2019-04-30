<?php
    use ScorpioTek\WordPress\Util\FilterBuilder;
    use ScorpioTek\WordPress\Util\PostUtilities;

    global $post;
    
    $resource_file_type = get_field( 'resource_type' ) ;

    switch ( $resource_file_type ) {
        case 'video':
            $resource_caption_text = __( 'View video', 'vinnieslac' );
            $resource_link = get_field( 'resource_url' );
            break;
        case 'link':
            $resource_caption_text = __( 'Visit website', 'vinnieslac' );
            $resource_link = get_field( 'resource_url' );
            break;
        case 'document':
            $resource_caption_text = __( 'Download document', 'vinnieslac' );
            $resource_link = get_field( 'file_upload' );
            break;            
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
        <div class="tm-resource-content">
            <h4 class="tm-resource-title">
                <?php
                    echo sprintf( '<a href="%1$s" target="_blank" title="%2$s">%2$s</a>',
                        $resource_link,
                        get_the_title()
                    );
                ?>
            </h4>

        <div class="tm-product-price"><?php the_field( 'resource_description' ); ?></div>
            <div class="resource-caption-button">            
            <?php
            echo sprintf('<a href="%1$s" target="_new" rel="noopener noreferrer">%2$s</a>',
            $resource_link,
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        $resource_caption_text . __( '<span class="screen-reader-text">%1$s</span>', 'vinnieslac' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        $resource_caption_text . '( ' . get_the_title() . ' )'
                )
            );
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
<!--// Single Resource -->
