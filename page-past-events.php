<?php get_header();
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">رویدادهای گذشته</h1>
            <div class="page-banner__intro">
                <p>لیست همه ی رویداد ها و گردهمایی های گذشته</p>
            </div>
        </div>
    </div>
    <div class="container container--narrow page-section">
        <?php
        $month_array = array(
            1 => 'فروردین',
            2 => 'اردیبهشت',
            3 => 'هرذلذ',
            4 => 'تیر',
            5 => 'مرداد',
            6 => 'شهریور',
            7 => 'مهر',
            8 => 'آبان',
            9 => 'آذر',
            10 => 'دی',
            11 => 'بهمن',
            12 => 'اسفند');
        include_once 'jdf.php';
        $today = jdate('n');
        $pastEvents = new WP_Query(array(
            'paged' => get_query_var('paged', 1),
            'post_type' => 'event',
            'meta_key' => 'event_month',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'event_month',
                    'compare' => '<=',
                    'value' => $today,
                    'type' => 'numeric'
                )
            )
        ));
        while($pastEvents->have_posts()) {
            $pastEvents->the_post();
            $month_value=get_field('event_month');
            ?>

            <div class="event-summary">
                <a class="event-summary__date t-center" href="#">
                    <span class="event-summary__month"><?php echo $month_array[$month_value]; ?></span>
                    <span class="event-summary__day"><?php the_field('event_day'); ?></span>
                </a>
                <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <p><?php echo wp_trim_words(get_the_content(), 18); ?>
                        <a href="<?php the_permalink(); ?>" class="nu gray">بیشتر بخوانید</a></p>
                </div>
            </div>


        <?php }
        echo paginate_links(array(
            'total' => $pastEvents->max_num_pages
        ));
        ?>
    </div>
<?php get_footer();
?>