<?php get_header();
while (have_posts()){
    the_post();
    ?>
    <a class="page-banner__link" href="<?php echo site_url(); ?>"><div class="page-banner">
            <div class="page-banner__bg-image"></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php the_title(); ?></h1>
                <div class="page-banner__intro">

                </div>
            </div>
        </div></a>

    <div class="container container--narrow page-section">
        <?php the_content(); ?>
    </div>
<?php
}
get_footer();
