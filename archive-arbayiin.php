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
            <?php
                // $apple = new GooglesheetTest();
                // $apple -> runSpreadSheet();
            ?>
<!--            <hr/>-->
            <?php
//            echo locate_template( 'content-select.php' );
//            $args = ['some data', 'to use'];
//            $args = array( 'section_title' => 'hello world',
//                            'section_content' => 'babay');
//            require 'template-parts/content-select.php';
//            set_query_var( 'section_title', 'hello world' );
//                get_template_part('template-parts/content', 'select');

            ?>
<!--            <hr/>-->
<!--    <form action="#" method="post">-->
<!--        <select name="Color[]" multiple> // Initializing Name With An Array-->
<!--            <option value="عدم انجام">عدم انجام</option>-->
<!--            <option value="ضعیف">ضعیف</option>-->
<!--            <option value="متوسط">متوسط</option>-->
<!--            <option value="خوب">خوب</option>-->
<!--        </select>-->
<!--        <input type="submit" name="submit" value="انتقال به spreadsheet" />-->
<!--    </form>-->
<?php
// $apple = new GooglesheetTest();
// echo $apple->runSpreadSheet();
//if(isset($_POST['submit'])) {
//// As output of $_POST['Color'] is an array we have to use foreach Loop to display individual value
//    foreach ($_POST['Color'] as $select) {
//        echo "نتیجه ی  :" . $select . ' به spreadsheet منتقل شد.' . '<br/>'; // Displaying Selected Value
//        echo $apple->runSpreadSheet($select);
//    }
//}
    ?>
<!--            <hr/>-->
<!--    <form action="" method="post">-->
<!--        <input type="radio" name="radio" value="Radio 1">Radio 1-->
<!--        <input type="radio" name="radio" value="Radio 2">Radio 2-->
<!--        <input type="radio" name="radio" value="Radio 3">Radio 3-->
<!--        <input type="submit" name="submit" value="Get Selected Values" />-->
<!--    </form>-->
<?php
//if (isset($_POST['submit'])) {
//    if (isset($_POST['radio'])) {
//        echo "You have selected :" . $_POST['radio'];  //  Displaying Selected Value
//        echo $apple->runSpreadSheet($_POST['radio']);
//    }
//}
    ?>
<!--            <hr/>-->

<!--            <form method="post" >-->
<!--                Name: <input type="text" name="fname">-->
<!--                <input type="submit">-->
<!--            </form>-->
<!--            --><?php
//            $allowed_keys = [ 'author', 'post_author', 'date', 'post_date' ];
//            $orderby      = sanitize_key( $_POST['fname'] );
//            if ( in_array( $orderby, $allowed_keys, true ) ) {
//                // Modify the query to sort by the orderby key.
//                echo 'in array bud';
//            } else {
//                echo 'nabud';
//            }
//            ?>
            <h4 class="headline--post-title">اربعین های جاری من</h4>
            <ul class="" id="my-arbayiin">
            <?php //################################################### NEW ARBAYIINS################################
            //########################################## salekin pas az application
            // args
            $myargs = array(
                'numberposts'	=> -1,
                'posts_per_page'	=> -1,
                'post_type'		=> 'salek',
            );
            $the_query = new WP_Query( $myargs);
            while ($the_query->have_posts()){
                $the_query->the_post();
//                echo get_field('salekid')['ID'] . " - " . get_current_user_id() . '<br/>';
                if (get_field('salekid')['ID'] == get_current_user_id()){

                    // arbayiin haye bad az application
                    /**
                     * ARBAYIIN HAYE BAD AZ APPLICATION
                     * Field Structure:
                     *
                     * - parent_repeater (Repeater)
                     *   - parent_title (Text)
                     *   - child_repeater (Repeater)
                     *     - child_title (Text)
                     */
                    if( have_rows('arb_after_app') ):
                        while( have_rows('arb_after_app') ) : the_row();

                            // Get parent value.
                            $dastoor_takhsised_obj = get_sub_field('dastoor_takhsised');
                            $dastoor_title = $dastoor_takhsised_obj->post_title;
                            $dastoor_link = $dastoor_takhsised_obj->guid;
                            $dastoor_ID = $dastoor_takhsised_obj->ID;

                            if ($dastoor_takhsised_obj): ?>
                                <li class="arbayiin-title" data-id="<?php echo $dastoor_ID; ?>">
                                    <a class="" href="<?php echo $dastoor_link; ?>"> <strong><?php echo esc_html($dastoor_title); ?></strong></a>
                                </li>
                            <br/>

                            <?php endif; // $dastoor_takhsised_obj ?>
                        <?php
                        endwhile; //have_rows
                    endif; // have_rows
                }
            }
            ?>
            </ul>
            <hr/>
        <h4>اربعین های سابق</h4>

        <?php
        while(have_posts()) {
            the_post();
            $id = get_the_ID();
            $permalink = get_the_permalink();
            $title = get_the_title();
            $duration = get_field('arbayiin-duration');
      ?>
            <ul class="" id="my-arbayiin">
    <?php

            //START >>>>>>********************************       [ ARBAYIINS FROM SALEKIN ]       **********************
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
                    <li class="arbayiin-title_sabegh" data-id="<?php echo $id; ?>">
                      <strong><?php echo $title; ?></strong>
                    </li>
        <?php }
    //####################################################################################### END <<<<<<<<<<<<<<<<<<


    //START >>>>>>********************************       [ ARBAYIINS FROM GROUPS ]       **********************
    $post_objects = get_field('groups');
    if( $post_objects ):
        foreach( $post_objects as $post): // variable must be called $post (IMPORTANT)
        // override $post
        setup_postdata( $post );
        $userss = get_field('userss');
        if( $userss ): ?>
                <?php foreach( $userss as $user ): ?>
                    <?php if ($user['ID'] == get_current_user_id()): ?>
                           <li  class="arbayiin-title_sabegh" data-id="<?php echo $id; ?>">
                               <strong><?php echo $title; ?></strong>
                           </li>
                    <?php endif; ?>
                <?php endforeach; ?>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
    <?php endif;
    //####################################################################################### END <<<<<<<<<<<<<<<<<<
    ?>

        </ul>

            <?php

            if( have_rows('arb_after_app') ) {
                echo 'dkjiii';
            }
            ?>
<?php } wp_reset_postdata(); ?>



<?php
echo paginate_links();
?>

    </div>
    </div>
<?php get_footer();
?>