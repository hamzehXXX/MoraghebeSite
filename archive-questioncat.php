<?php get_header();
//include('jdf.php');
?>
    <a class="page-banner__link" href="<?php echo site_url(); ?>"><div class="page-banner">
            <div class="page-banner__bg-image" ></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php the_archive_title(); ?></h1>
                <div class="page-banner__intro">
                    <p><?php the_archive_description(); ?></p>
                </div>
            </div>
        </div></a>
    <div class="container container--narrow page-section ">
<?php
while(have_posts()) {
    the_post();
    ?>

        <h2 class="headline headline--medium headline--post-title"><a
                href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    
    <?php
}
?>
    </div>
<?php
get_footer();
?>