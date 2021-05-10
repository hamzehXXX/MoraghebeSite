<?php
$start_time = microtime(true); // record the start time of transaction
if (!is_user_logged_in()) {
    wp_redirect(esc_url(site_url('/vorud')));
    exit;
}
get_header();

?>

    <div class="page-banner">
        <div class="page-banner__bg-image"></div>
        <div class="page-banner__content container t-center c-white">
            <h1 class="headline headline--large"></h1>
            <br/>
            <h2 class="headline headline--medium">مراقبه سلوکی شاگردان</h2>
            <h3 class="headline headline--small"> آیت الله کمیلی خراسانی</h3>
            <a href="<?php echo get_post_type_archive_link('arbayiin'); ?>" class="btn btn--large btn--blue">اربعینیات من</a>
        </div>
    </div>
<?php

//Detect special conditions devices
//$iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
//$iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
//$iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
//$Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
//$webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");
//
////do something with this information
//if ($iPod || $iPhone) {
//    //browser reported as an iPhone/iPod touch -- do something here
//    echo 'iPhone';
//} else if ($iPad) {
//    //browser reported as an iPad -- do something here
//    echo 'ipad';
//} else if ($Android) {
//    //browser reported as an Android device -- do something here
////    echo 'android';
//} else if ($webOS) {
//    //browser reported as a webOS device -- do something here
//    echo 'webOS';
//} else {
////    echo 'PC or Laptop';
//}


?>


    <div class="full-width-split group">

        <div class="full-width-split__two">
            <div class="full-width-split__inner">
                <h2 class="headline headline--small-plus t-center">اطلاعیه ها</h2>
                <?php
//				delete_transient('recent_posts');
				if (false === ($homepagePosts = get_transient('recent_posts'))){
					$homepagePosts = new WP_Query(array(
						'post_type' => 'post',
						'meta_query' => array(
							'key' => 'related_arbayiin',
							'compare' => '=',
							'value' => ''
						),
						'posts_per_page' => -1,
					));

					set_transient('recent_posts', $homepagePosts, DAY_IN_SECONDS);
				}

                $counter = 0;
                    while ($homepagePosts->have_posts()) {
                        $homepagePosts->the_post();
                        $relatedArb = get_field('related_arbayiin', get_the_ID());

                        if ($relatedArb == ''):;
                        if (  $counter < 3):;
                        $counter++;
                        ?>
                        <div class="event-summary">
                            <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                                <span class="event-summary__month"><?php echo jdate('F', strtotime(get_the_date())); ?></span>
                                <span class="event-summary__day"><?php echo jdate('d', strtotime(get_the_date())); ?></span>
                            </a>
                            <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                <p><?php if (has_excerpt()) {
                                    echo (get_the_excerpt());
                                    } else {
                                    echo wp_trim_words(esc_html(get_the_content()), 18) . '...';
                                    } ?><a href="<?php the_permalink(); ?>" class="nu gray"> بیشتر بخوانید</a></p>
                            </div>
                        </div>
                    <?php
                    endif;
                    endif;
                    } wp_reset_postdata();
                ?>



                <p class="t-center no-margin"><a href="<?php echo site_url('/news'); ?>" class="btn btn--yellow">مشاهده همه ی اطلاعیه ها</a></p>
            </div>
        </div>

        <div class="full-width-split__one">
            <div class="full-width-split__inner">
                <h2 class="headline headline--small-plus t-center">رویدادهای پیش رو</h2>

                <?php

				if (false === ($homepageEvents = get_transient('recent_events'))){
					$homepageEvents = new WP_Query(array(
							'posts_per_page' => 2,
							'post_type' => 'event',
						)
					);

					set_transient('recent_events', $homepageEvents, DAY_IN_SECONDS);
				}


                while ($homepageEvents->have_posts()) {
                    $homepageEvents->the_post();

                    $month_value=get_field('event_month');

                    ?>
                    <div class="event-summary">
                        <a class="event-summary__date t-center" href="#">
                            <span class="event-summary__month"><?php echo jdate('F', strtotime(get_the_date())); ?></span>
                            <span class="event-summary__day"><?php echo jdate('d', strtotime(get_the_date())); ?></span>
                        </a>
                        <div class="event-summary__content">
                            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                            <p><?php if (has_excerpt()) {
                                    echo get_the_excerpt();
                                } else {
                                    echo wp_trim_words(get_the_content(), 18)  . '...';
                                } ?>
                                <a href="<?php the_permalink(); ?>" class="nu gray"> بیشتر بخوانید</a>
                            </p>
                        </div>
                    </div>
                <?php } wp_reset_postdata();
                ?>


                <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">مشاهده همه ی رویدادها</a></p>

            </div>
        </div>
    </div>

<?php get_footer();

$end_time = microtime(true); //
$run_time = ($end_time - $start_time);
echo '<p style="display: none"> It took: <strong>'.$run_time.'</strong> to run this script.';
?>
