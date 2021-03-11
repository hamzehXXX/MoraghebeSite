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

<?php
while(have_posts()) {
    the_post();
    $currentUserRoles = wp_get_current_user() -> roles;
    $currentUser = wp_get_current_user();
    $termIds = array();

    $termArray = get_the_terms(get_the_ID(), 'category');
    if ($termArray)
        foreach ($termArray as $customTerm) {
            $termIds[] = $customTerm -> slug;
        }
    $currentRole = '';
    $hide = '';
    if (has_term('خواهران', 'category', get_the_ID())) {
        if (!in_array('salek-zan', $currentUserRoles) AND !in_array('khadem-zan', $currentUserRoles))
            $hide = 'hide';
    }

    if (has_term('برادران', 'category', get_the_ID())) {
        print_r(in_array('salek-mard', $currentUserRoles));
        if (!in_array('salek-mard', $currentUserRoles) AND !in_array('khadem-mard', $currentUserRoles))
            $hide = 'hide';
    }
        ?>

        <div class="container container--narrow page-section <?php echo $hide;?>">
            <h2 class="headline headline--medium headline--post-title"><a
                        href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="metabox">
                    <?php echo jdate('d F Y'); ?> در <?php echo get_the_category_list(', '); ?>
            </div>
            <div class="generic-content"><?php the_excerpt(); ?></div>
        </div>

        <?php
}
get_footer();
?>