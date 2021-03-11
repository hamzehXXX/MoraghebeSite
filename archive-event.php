<?php get_header();
?>
<a class="page-banner__link" href="<?php echo site_url(); ?>"><div class="page-banner">
        <div class="page-banner__bg-image" ></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">همه ی رویدادها</h1>
            <div class="page-banner__intro">
                <p>لیست همه ی رویداد ها و گردهمایی های گذشته و پیش رو</p>
            </div>
        </div>
    </div></a>
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
while(have_posts()) {
    the_post();
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
echo paginate_links();
?>
    <hr class="section-break">
    <p><a href="<?php echo site_url('/past-events'); ?>">مشاهده ی رویداد های گذشته</a></p>
</div>
<?php get_footer();
?>