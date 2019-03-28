<?php

use ScorpioTek\WordPress\Util\PostUtilities;

$event_image = wp_get_attachment_image( get_field( 'event_image' ), array('800', '398'), "", '' );
$event_date = new DateTime( get_field( 'start_date' ) );
$day = $event_date->format('d');
$month = $event_date->format('F');
$year = $event_date->format('Y');
$event_title = get_the_title();
$short_description = get_field( 'short_description' );

?>

<!-- Single Blog -->
<div class="col-lg-6 blog-masonry-item">
    <div class="tm-blog mt-50">
        <div class="tm-blog-top">
            <a href="blog-details.html">
                <?php echo $event_image; ?>
            </a>
            <span class="tm-blog-date">18 Nov, 2019</span>
        </div>
        <div class="tm-blog-bottom">
            <div class="tm-blog-meta">
                <span><a href="blog.html"><i class="zmdi zmdi-account"></i> Admin</a></span>
                <span><a href="blog-details.html"><i class="zmdi zmdi-comments"></i> Comments (0)</a></span>
                <span><a href="blog.html"><i class="zmdi zmdi-folder"></i> Services</a></span>
            </div>
            <h5 class="tm-blog-title"><a href="blog-details.html"><?php echo $event_title; ?></a></h5>
            <span class="italic"><?php echo sprintf( '%s %s, %s', $day, $month, $year ); ?></span>
            <p>
                <?php
                    if ( method_exists( PostUtilities::class, 'get_excerpt_max_words' ) ) {
                        $short_description = !empty ( $short_description ) ? $short_description . '...' : $short_description;
                        echo PostUtilities::get_excerpt_max_words( $short_description, 25 );
                    }
                ?>
            </p>
            <a href="blog-details.html" class="tm-button tm-button-dark">Read more </a>
        </div>
    </div>
</div>
<!--// Single Blog -->