<?php
// in blog hast, ya hamun etelayiye ha
get_header();
include('jdf.php');
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">اطلاعیه ها</h1>
            <div class="page-banner__intro">
                <p>آخرین اخبار و اطلاعیه ها</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <?php

        while (have_posts()) {
            the_post(); ?>
            <?php
            $currentUserRoles = wp_get_current_user() -> roles;
            $currentUser = wp_get_current_user();
            $termIds = array();

            $termArray = get_the_terms(get_the_ID(), 'group');
            if ($termArray)
                foreach ($termArray as $customTerm) {
                    $termIds[] = $customTerm -> slug;
                }

                $hide = '';
            if (has_term('خواهران', 'group', get_the_ID())) {
                if (!in_array('salek-zan', $currentUserRoles) AND !in_array('khadem-zan', $currentUserRoles))
                $hide = 'hide';
            }

            if (has_term('برادران', 'group', get_the_ID())) {
                if (!in_array('salek-mard', $currentUserRoles) AND !in_array('khadem-mard', $currentUserRoles))
                    $hide = 'hide';
            }

            ?>

                <div class="post-item <?php echo $hide;?>">
                    <h2 class="headline headline--medium headline--post-title"><a
                                href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="metabox">
                        <p>نوشته شده توسط <?php the_author_posts_link(); ?> در
                            <?php echo jdate('d F Y'); ?> در <?php echo get_the_category_list(', '); ?></p>
                    </div>

                    <div class="generic-content">
                        <?php the_excerpt(); ?>
                        <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">ادامه خواندن &raquo;</a></p>
                    </div>

                </div>
                <?php

        }
        echo paginate_links();
        ?>
    </div>
<?php get_footer();
?>