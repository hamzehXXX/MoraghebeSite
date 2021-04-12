<?php get_header();
while(have_posts()) {
    the_post(); ?>
<a class="page-banner__link" href="<?php echo site_url(); ?>"><div class="page-banner">
        <div class="page-banner__bg-image"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro">
                <p></p>
            </div>
        </div>
    </div></a>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i> رویدادها </a></p>
        </div>

        <div class="generic-content"><?php  the_content(); ?></div>
    </div>

<?php }
get_footer();
?>