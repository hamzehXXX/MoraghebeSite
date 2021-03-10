<?php
/**
 * A Simple Category Template
 */

get_header();
//include('jdf.php'); ?>
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
    <section id="primary" class="site-content">
        <div id="content" role="main">
            <?php
            // Check if there are any posts to display
            if ( have_posts() ) : ?>
            <?php

            // The Loop
            while ( have_posts() ) : the_post();
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
                    if (!in_array('salek-mard', $currentUserRoles) AND !in_array('khadem-mard', $currentUserRoles))
                        $hide = 'hide';
                }
            ?>
            <div class="post-item <?php echo $hide;?>">
            <h2>
                <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
            </h2>
<!--            <small>--><?php //echo jdate('jS F, Y') ?><!-- توسط --><?php //the_author_posts_link() ?><!--</small>-->

            <div class="entry">
            <?php the_excerpt(); ?>

             <p class="postmetadata"><?php
//              comments_popup_link( 'No comments yet', '1 comment', '% comments', 'comments-link', 'Comments closed');
            ?></p>
            </div>
            </div>
            <?php endwhile; // End Loop

            else: ?>
            <p>Sorry, no posts matched your criteria.</p>
            <?php endif; ?>
        </div>
    </section>
    </div>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>