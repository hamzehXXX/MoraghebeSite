<?php get_header();
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">اربعینیات</h1>
            <div class="page-banner__intro">
                <p>لیست همه ی اربعینیات</p>
            </div>
        </div>
    </div>
    <div class="container container--narrow page-section">
        <div class="generic-content">

            <ul class="" id="my-arbayiin">

                <?php
                while(have_posts()) {
                    the_post();
                    $id = get_the_ID();
                    $permalink = get_the_permalink();
                    $title = get_the_title();
                    $duration = get_field('arbayiin-duration');

                    ?>
                    <?php


                    //********************************       [ ARBAYIINS FROM SALEKIN ]       **********************START >>>>>>

                    // Get users which their ID is current userID and have current arbayiin in their relation field
                    $salekArbayiins = new WP_Query(array(
                        'post_type' => 'salek',

                        'orderby' => 'modified',
                        'posts_per_page' => -1,
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'arbayiin',
                                'compare' => 'LIKE',
                                'value' => '"' . $id . '"'
                            ),
                            array(
                                'key' => 'salekid',
                                'compare' => '=',
                                'value' => get_current_user_id()
                            )
                        )
                    ));


                    if ($salekArbayiins->found_posts) { ?>
                        <li class="arbayiin-title" data-id="<?php echo $id; ?>">
                            <a class="" href="<?php echo $permalink; ?>"> <strong><?php echo $title; ?></strong></a>
                        </li>
                    <?php }
                    //####################################################################################### END <<<<<<<<<<<<<<<<<<


                    //********************************       [ ARBAYIINS FROM GROUPS ]       **********************START >>>>>>
                    $post_objects = get_field('groups');
                    if( $post_objects ):
                        foreach( $post_objects as $post): // variable must be called $post (IMPORTANT)
                            // override $post
                            setup_postdata( $post );
                            $userss = get_field('userss');
                            if( $userss ): ?>
                                <?php foreach( $userss as $user ): ?>
                                    <?php if ($user['ID'] == get_current_user_id()): ?>
                                        <li  class="arbayiin-title" data-id="<?php echo $id; ?>">
                                            <a  href="<?php echo $permalink; ?>"> <strong><?php echo $title; ?></strong></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
                    <?php endif;
                    //####################################################################################### END <<<<<<<<<<<<<<<<<<
                    ?>



                <?php } ?>
            </ul>
            <?php
            echo paginate_links();
            ?>

        </div>
    </div>
<?php get_footer();
?>