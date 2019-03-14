<div class="edu-event">
    <div class="event-img"><?php echo wp_get_attachment_image( get_field( 'event_image' ), array('570', '379'), "", '' ); ?></div>
    <div class="event-detail">
        <div class="event-date">
            <?php
                $event_date = new DateTime(get_field('start_date'));
                $day = $event_date->format('d');
                $month = $event_date->format('F');
                $year = $event_date->format('Y');
                echo sprintf( '<span>%s</span> %s, %s</div>', $day, $month, $year );
            ?>
        <div class="event-description">
            <i>Upcoming Event</i>
            <h3><a href="<?php the_permalink(); ?>" title="<?php echo get_the_title(); ?>"><?php the_title(); ?></a></h3>
            <!-- <span class="loc"><i class="fa fa-map-marker"></i> 363 Oakwood Avenue Irmo, SC 29063 </span> -->
            <p class="short-description"><?php the_field( 'short_description' ); ?></p>
            <br />
                <a class="read-more" href="<?php the_permalink(); ?>">>> Read more about <?php echo get_the_title(); ?></a>
            </span>                
        </div>
    </div>
</div>
