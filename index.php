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

            $hide = 'hide';
            if (has_term('خواهران', 'group', get_the_ID())) {
                if (in_array('salek-zan', $currentUserRoles) OR in_array('khadem-zan', $currentUserRoles))
                    $hide = '';
            }

            if (has_term('برادران', 'group', get_the_ID())) {
                if (in_array('salek-mard', $currentUserRoles) OR in_array('khadem-mard', $currentUserRoles))
                    $hide = '';
            }

            if (has_term('عمومی', 'category', get_the_ID())){
            $hide='';
        }

            $relatedArbs = get_field('related_arbayiin');
            if ($relatedArbs === ''):

            ?>

                <div class="post-item <?php echo $hide;?>">
                    <h2 class="headline headline--medium headline--post-title"><a
                                href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="metabox">
                        <p>نوشته شده در
                            <?php echo gregorian_to_jalali(get_the_date('Y'), get_the_date('m'), get_the_date('d'), '/'); ?> در <?php echo get_the_category_list(', '); ?></p>
                    </div>

                    <div class="generic-content">
                        <?php the_excerpt(); ?>
                        <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">ادامه خواندن &raquo;</a></p>
                    </div>

                </div>
                <?php
                endif;

        }
        echo paginate_links();
        ?>
    </div>
<?php get_footer();
?>