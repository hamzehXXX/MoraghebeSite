<?php
$ourCurrentUser = wp_get_current_user();
$currentUserRoles = $ourCurrentUser->roles;
if (!is_user_logged_in() AND ( !(in_array('khadem-mard', $currentUserRoles)) OR !(in_array('khadem-zan', $currentUserRoles)) OR !(in_array('admin', $currentUserRoles)))){
    wp_redirect(esc_url(site_url('/')));
    exit;
}
get_header();
include_once('jdf.php');
//while(have_posts()) {
//    the_post();
    $name = get_the_title();
    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro">

            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo site_url('/salek'); ?>"><i class="fa fa-home" aria-hidden="true"></i> شاگردان </a> <span class="metabox__main"><?php  the_title(); ?></span>
        </div>

        <div class="generic-content">

            <?php  the_content(); ?>
            <div><span>خادم: </span><a><?php echo get_field('khademid')['display_name']; ?></a></div>
            <div><span>شهر: </span><a><?php echo get_field('city'); ?></a></div>



        </div>
        <?php
        //======================================================================
        //          AFTER APPLICATION ARBAYIINS
        //======================================================================
        $salekID = get_field('salekid')['ID']; // for using in ResultsTable to query the current user Results
        if (have_rows('arb_after_app')){
            while (have_rows('arb_after_app')){
                the_row();
                // Get parent value.
                $post = get_sub_field('dastoor_takhsised');
                $dastoor_title = $post->post_title;
                $dastoor_link = $post->guid;
                $dastoor_ID = $post->ID;

                if ($post) {

                    ?>
                    <div class="arbayiin-results-title"><?php echo $dastoor_title;?></div>

                    <?php
                setup_postdata($post); // Set the Main Query to the 'arbayiin'
                        $resultsTable = new ResultsTable($salekID);     // Instantiate ResultsTable class
                        $resultsTable->showResultsTable('');      // Show the results table
                wp_reset_postdata();                            // Sets the Main Query back to the 'salek'
                    ?>
                    <br/>
                    <?php
                }
            }
        }

        //======================================================================
        //          BEFORE APPLICATION ARBAYIINS
        //======================================================================
        $posts = get_field('arbayiin');
        $userID = get_field('salekid')['ID']; // Get the current user ID
        if ($posts) {
            echo '<hr class="section-break">';

            echo '<ul class="results-ul">';
            ?>
            <li><?php echo '<h2 class="results-title">اربعین های اخذ شده سابق</h2>'; ?></li>
            <?php


            foreach($posts as $post) {
                setup_postdata($post);?>
                <li><h3 class=""><?php echo get_the_title(); ?></h3></li>

                <?php
                $arbayiinID = get_the_ID();
                ?>
    <li>
    <div class="arbayiin-table">
        <ul class="min-list" id="results">

                <?php
//                set_query_var( 'userID', $userID );
//                echo get_template_part( 'template-parts/content', 'results' );
                ?>
        </ul>
    </div>
    </li>
               <?php
//                echo get_template_part( 'template-parts/content', 'resultsform' );
                ?>
                <?php

            }    wp_reset_postdata();

        }


            $arbayiinByGroups = new WP_Query(array(
                'post_type' => 'arbayiin',
                'posts_per_page' => -1,


            ));

           while ($arbayiinByGroups->have_posts()){
               $arbayiinByGroups->the_post();
               $arbayiinID = get_the_ID();
               $permalink = get_the_permalink();
               $title= get_the_title();
               $post_objects = get_field('groups');

               if( $post_objects ):
                   foreach( $post_objects as $post_object): // variable must be called $post (IMPORTANT)

                       $userss = get_field('userss', $post_object->ID);
                       if( $userss ): ?>
                       <?php $arbayiinTitlesArray = [] ?>
                           <?php foreach( $userss as $user ): ?>
                               <?php if ($user['ID'] == $userID): ?>
                                   <li><h3><strong><?php echo the_title(); ?></strong></h3></li>
                                   <li>
                                       <div class="arbayiin-table">
                                           <ul class="min-list" id="results">
                                               <?php
                                               set_query_var( 'userID', $userID );
                                               set_query_var( 'arbayiinID', $arbayiinID );
//                                               echo get_template_part( 'template-parts/content', 'results' );
                                               ?>
                                           </ul>
                                       </div>
                                   </li>
<!--                                   --><?php //echo get_template_part( 'template-parts/content', 'resultsform' ); ?>
                               <?php endif; ?>
                           <?php endforeach; ?>
                       <?php endif; ?>
                   <?php endforeach; ?>

                   <?php
               foreach ($arbayiinTitlesArray as $title){
//                   setup_postdata( $arbayiinByGroups->the_post() );
                   ?>

                   <?php
               }
                   ?>

               <?php endif;

           }  wp_reset_postdata();

        echo '</ul>';


        ?>

    </div>

<?php //} wp_reset_postdata();

get_footer();
?>