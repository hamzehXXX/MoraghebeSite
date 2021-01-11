<?php
if (!is_user_logged_in()) {
    wp_redirect(esc_url(site_url('/wp-login.php')));
    exit;
}
get_header();
include('classes/CONSTANTS.php');
include_once('jdf.php');
?>

    <div class="page-banner">
        <div class="page-banner__bg-image"
             style="background-image: url(<?php echo get_theme_file_uri("/images/library-hero.jpg"); ?> );"></div>
        <div class="page-banner__content container t-center c-white">
            <h1 class="headline headline--large">مراقبه سلوکی</h1>
            <h2 class="headline headline--medium">مراقبه سلوکی شاگردان</h2>
            <h3 class="headline headline--small"> آیت الله کمیلی خراسانی</h3>
            <a href="<?php echo get_post_type_archive_link('arbayiin'); ?>" class="btn btn--large btn--blue">اربعینیات من</a>
        </div>
    </div>
<?php

?>


    <div class="full-width-split group">
        <div class="full-width-split__one">
            <div class="full-width-split__inner">
                <h2 class="headline headline--small-plus t-center">رویدادهای پیش رو</h2>

                <?php

                    $homepageEvents = new WP_Query(array(
                        'posts_per_page' => 2,
                        'post_type' => 'event',

                        )
                    );

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
                                        echo wp_trim_words(get_the_content(), 18);
                                    } ?>
                                    <a href="<?php the_permalink(); ?>" class="nu gray">بیشتر بخوانید</a>
                                </p>
                            </div>
                        </div>
                    <?php }
                ?>


                <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">مشاهده همه ی رویدادها</a></p>

            </div>
        </div>
        <div class="full-width-split__two">
            <div class="full-width-split__inner">
                <h2 class="headline headline--small-plus t-center">اطلاعیه ها</h2>
                <?php
                $homepagePosts = new WP_Query(array(
                        'posts_per_page' => 2
                ));

                    while ($homepagePosts->have_posts()) {
                        $homepagePosts->the_post();
                        ?>
                        <div class="event-summary">
                            <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                                <span class="event-summary__month"><?php echo jdate('F', strtotime(get_the_date())); ?></span>
                                <span class="event-summary__day"><?php echo jdate('d', strtotime(get_the_date())); ?></span>
                            </a>
                            <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                <p><?php if (has_excerpt()) {
                                    echo esc_html(get_the_excerpt());
                                    } else {
                                    echo wp_trim_words(esc_html(get_the_content()), 18);
                                    } ?><a href="<?php the_permalink(); ?>" class="nu gray">بیشتر بخوانید</a></p>
                            </div>
                        </div>
                    <?php } wp_reset_postdata();
                ?>



                <p class="t-center no-margin"><a href="<?php echo site_url('/news'); ?>" class="btn btn--yellow">مشاهده همه ی اطلاعیه ها</a></p>
            </div>
        </div>
    </div>

  <!--  <div class="hero-slider" dir="ltr">
        <div class="hero-slider__slide"
             style="background-image: url(<?php echo get_theme_file_uri('/images/bus.jpg') ?>);">
            <div class="hero-slider__interior container">
                <div class="hero-slider__overlay">
                    <h2 class="headline headline--medium t-center">Free Transportation</h2>
                    <p class="t-center">All students have free unlimited bus fare.</p>
                    <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
                </div>
            </div>
        </div>
        <div class="hero-slider__slide"
             style="background-image: url(<?php echo get_theme_file_uri('/images/apples.jpg'); ?>);">
            <div class="hero-slider__interior container">
                <div class="hero-slider__overlay">
                    <h2 class="headline headline--medium t-center">An Apple a Day</h2>
                    <p class="t-center">Our dentistry program recommends eating apples.</p>
                    <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
                </div>
            </div>
        </div>
        <div class="hero-slider__slide"
             style="background-image: url(<?php echo get_theme_file_uri('/images/bread.jpg'); ?>);">
            <div class="hero-slider__interior container">
                <div class="hero-slider__overlay">
                    <h2 class="headline headline--medium t-center">Free Food</h2>
                    <p class="t-center">Fictional University offers lunch plans for those in need.</p>
                    <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
                </div>
            </div>
        </div>
    </div>  -->
<!--<pre>-->
<!--    --><?php
//    print_r(get_post_type_object('event'));
//    ?>
<!--</pre>-->
<?php get_footer();
?>