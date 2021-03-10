<?php get_header();
include_once('jdf.php');
while(have_posts()) {
    the_post(); ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ostad.jpeg') ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php esc_html(the_title()); ?></h1>
            <div class="page-banner__intro">

            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo site_url('/news'); ?>"><i class="fa fa-home" aria-hidden="true"></i> اطلاعیه ها </a> <span class="metabox__main"><?php echo gregorian_to_jalali(get_the_date('Y'), get_the_date('m'), get_the_date('d'), '/'); ?></span>
        </div>

        <div>

            <?php  the_content(); ?>
            </div>

    </div>

<?php }
get_footer();
?>