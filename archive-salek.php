<?php
get_header();
$ourCurrentUser = wp_get_current_user();
$currentUserRoles = $ourCurrentUser -> roles;
if (!is_user_logged_in() AND (!(in_array('khadem-mard', $currentUserRoles)) OR !(in_array('khadem-zan', $currentUserRoles)) OR !(in_array('admin', $currentUserRoles)))) {
    wp_redirect(esc_url(site_url('/')));
    exit;
}


?>
<a class="page-banner__link" href="<?php echo site_url(); ?>"><div class="page-banner">
        <div class="page-banner__bg-image"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">شاگردان</h1>
            <div class="page-banner__intro">
                <p>لیست همه ی شاگردان</p>
            </div>
        </div>
    </div></a>
    <div class="container container--narrow page-section">

        <?php



//        global $wpdb;
//        $mid = $wpdb->get_results( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key LIKE %s AND meta_value = '1101'",
//             'arb_after_app_%_dastoor_takhsised') );



        $args = array(
            'role__in' => ['khadem-mard', 'khadem-zan', 'admin', 'administrator'],
            'orderby' => 'first_name',
            'order' => 'ASC',
        );
        $users = get_users($args);


        /**
         * ///////////////////////////////////////////////////////////////////////////////
         */

        $khademUsers = get_users(array(
            'role__in' => ['khadem-mard', 'khadem-zan', 'admin', 'administrator'],
            'orderby' => 'first_name',
            'order' => 'ASC',
        ));

        // khadems whose saleks will display
        $availableKhademsID[] = get_current_user_id();

        mainFunction($availableKhademsID);



        function mainFunction($availableKhademsID) {
            foreach ($availableKhademsID as $khademId) {
                $newKhademArray = $availableKhademsID;

                $mahfelSaleks = get_mahfel_saleks($khademId);                   // saleks which their khademId is $khademId
                $khademArbs = get_khadem_arbs($khademId);                       // Arbayiins which their khademId is $khademId
                $arbayiinSaleks = get_arbayiin_saleks($khademArbs);               // Saleks which have above arbayiins
            }

//testMyResults($arbayiinSaleks);
        }



        /**Saleks which their khademId is $khademId
         * @param $khademId
         * @return int[]|WP_Post[]
         */
        function get_mahfel_saleks($khademId) {
            return get_posts(array(
                'post_type' => 'salek',
                'posts_per_page' => -1,
                'meta_key' => 'khademid',
                'meta_value' => $khademId
            ));

        }

        /**Arbayiins which their khademId is $khademId
         * @param $khademId
         * @return int[]|WP_Post[]
         */
        function get_khadem_arbs($khademId) {
            return get_posts(array(
                'numberposts' => -1,
                'post_type' => 'arbayiin',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'khadem',
                        'compare' => 'LIKE',
                        'value' => '"' . $khademId . '"'
                    )
                )
            ));
        }


        /**Saleks which have above arbayiins
         * @param $khademArbs
         * @return array
         */
        function get_arbayiin_saleks($khademArbs) {
            $arbSaleks = array();
            foreach ($khademArbs as $khademArb) {
                $args = array(
                    'numberposts'	=> -1,
                    'post_type'		=> 'salek',
                    'suppress_filters' => false,
                    'orderby' => 'title',
                    'meta_query'	=> array(
                        array(
                            'key'		=> 'arb_after_app_$_dastoor_takhsised',
                            'compare'	=> '=',
                            'value'		=>  $khademArb->ID ,
                        )
                    )
                );

                $arbSaleks[$khademArb->post_title] = get_posts($args);
            }

            return $arbSaleks;
        }


        /**For test purposes only
         * @param $results
         */
        function testMyResults($results) {
            echo '<pre>';
            print_r($results);
            echo '<pre>';
        }
        /**
         * ///////////////////////////////////////////////////////////////////////////////
         */

//        die;


        $khademIDArray = [];

        echo '<ul class="khadem-ul">';
        foreach ($users as $user) { // loop through each khadem for display
            $khademin = get_posts(array(
                'post_type' => 'salek',
                'posts_per_page' => -1,
                'meta_key' => 'salekid',
                'meta_value' => $user -> ID
            ));
//            echo '<pre>';
//            var_dump($user->ID);
//            echo '<pre/>';
            // args
            $khademArbs = get_posts(array(
                'numberposts' => -1,
                'post_type' => 'arbayiin',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'khadem',
                        'compare' => 'LIKE',
                        'value' => '"' . $user -> ID . '"'
                    )
                )
            ));

            foreach ($khademArbs as $khademArb) {
                $args = array(
                    'numberposts'	=> -1,
                    'post_type'		=> 'salek',
                    'suppress_filters' => false,
                    'orderby' => 'title',
                    'meta_query'	=> array(
                        array(
                            'key'		=> 'arb_after_app_$_dastoor_takhsised',
                            'compare'	=> '=',
                            'value'		=>  $khademArb->ID ,
                        )
                    )
                );
                echo '<pre>';
//                print_r(get_posts($args));
                echo '</pre>';
            }





            $myKhadem = get_post($user -> ID);


            if (get_current_user_id() == $user -> ID OR in_array('administrator', $currentUserRoles)) {
                get_users_in_UI($khademin, $user, $khademIDArray);
            }
            array_push($khademIDArray, $user -> ID);
        }
        echo '</ul>';

        ?>

    </div>

<?php
function get_users_in_UI($khademin, $user, $khademIDArray) {
    //var_dump($arbayiinsQuery);

    //$myArbayiins = new WP_Query( $arbayiinsQuery);
    $khademArbs = get_posts(array(
        'numberposts' => -1,
        'post_type' => 'arbayiin',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'khadem',
                'compare' => 'LIKE',
                'value' => '"' . $user -> ID . '"'
            )
        )
    ));

    $arbsArray = array();

    ?>
    <a href="<?php if ($khademin) echo $khademin[0] -> guid; ?>">
        <div class="khadem">
            <?php echo esc_html($user -> display_name) ?>
        </div>

    </a>
    <?php

    if (empty($khademin)) {
        echo '<span style="color: #ff3434">' . 'خادم در بخش سالکین تعریف نشده است. ' . '</span>';
    } ?>
    <hr/>
    <?php
    if (!empty($khademArbs)):
        ?>
        <?php
        echo 'خادم اربعینیات: ';
        foreach ($khademArbs as $arb) {
            $arbsArray[] = $arb -> ID;
            echo '<strong>' . $arb -> post_title . '</strong>' . '  | ';
        }

    endif; // $arbayiinsQuery
    echo '<br/><br/>';
    ?>
    <?php

    echo 'سالکان زیر مجموعه: ';
    $saleksOfMahfel = array();
    $saleksOfArbs = array();
    $commonSaleks = array();
    while (have_posts()) {  // loop through all saleks
        the_post();
        $salekidField = get_field('salekid');
        //print_r($salekidField);
        $khademidField = get_field('khademid');
//        $salekID = isset($salekidField) ? $salekidField['ID'] : '0';
//        $salekName = isset($salekidField) ? $salekidField['user_firstname'] : 'shugulu';
//        $salekFamily = isset($salekidField) ? $salekidField['user_lastname'] : 'shugulu';
        $khademID = $khademidField ? $khademidField['ID'] : '0';

        if (have_rows('arb_after_app')):
            while (have_rows('arb_after_app')) : the_row();  // loop through arbayiins of salek

                // Get parent value.
                $dastoor_takhsised_obj = get_sub_field('dastoor_takhsised');
                $dastoor_title = ($dastoor_takhsised_obj) ? $dastoor_takhsised_obj -> post_title : '0';
                $dastoor_ID = ($dastoor_takhsised_obj) ? $dastoor_takhsised_obj -> ID : '0';

                foreach ($khademArbs as $arbItem) {  // loop through current khadem's arbayiins
                    if ($dastoor_ID == $arbItem->ID) { // if salek has current khadem's arbayiins
                        if (array_key_exists(get_post()->ID, $saleksOfArbs)) {
                            if (!in_array($arbItem->post_title, $saleksOfArbs[get_post()->ID]))
                                $saleksOfArbs[get_post()->ID][] = $arbItem->post_title;
                        } else {
                            $saleksOfArbs[get_post()->ID][] = $arbItem->post_title;
                        }
                    }
                }

            endwhile; //have_rows
        endif; // have_rows

        if ($khademID == $user -> ID) {   // if khadem of salek is current khadem echo the salek
            $currentSalekId = get_the_ID();
            if (!array_key_exists($currentSalekId, $saleksOfArbs)){
                $saleksOfMahfel[] = $currentSalekId;
            }  else {
                $commonSaleks[] = $currentSalekId;
            }

        }
    }

        wp_reset_postdata();
    echo '<br><br>';
  /**  Array
    (
       [694] => Array
            (
                [0] => جدول مراقبات اعمال ماه رمضان 1441
                                          [1] => چله
                                         [2] => یسی
            )

        [630] => Array
            (
                [0] => جدول مراقبات اعمال ماه رمضان 1441
            )

    )*/
        foreach ($saleksOfMahfel as $salekMahfelID) {
            ?>
                    <a href="<?php echo get_permalink($salekMahfelID); ?>">
                        <li class=" "> <?php echo get_the_title($salekMahfelID) . ' (محفل) '; ?>
                            <?php if (in_array($salekMahfelID, $khademIDArray)) echo ' (عضو خادمین) '; ?>
                        </li>
                    </a>
                <br/>
            <?php
//            echo get_the_title($salekMahfelID) . ' (محفل) ' . '<br/>';
        }
        foreach ($saleksOfArbs as $key => $saleksOfArb) {
            ?>
            <a href="<?php echo get_permalink($key); ?>">
                <li class=" ">
                    <?php
                    echo !in_array($key, $commonSaleks)?get_the_title($key):get_the_title($key) . ' ( محفل )';
                    foreach ($saleksOfArb as $salekArb){
                        echo ' ( ' . $salekArb . ' )';
                    }
                    ?>
<!--                    --><?php //if (in_array($salekMahfelID, $khademIDArray)) echo ' (عضو خادمین) '; ?>
                </li>
            </a>
            <br/>
            <?php


//                echo !in_array($key, $commonSaleks)?get_the_title($key):get_the_title($key) . ' ( محفل )';
//                foreach ($saleksOfArb as $salekArb){
//                    echo ' ( ' . $salekArb . ' )';
//                }
//            echo '<br/>';

    }
//    echo '<pre>';
//    print_r(($saleksOfArbs));
//    echo '<pre/>';
}


?>
<?php get_footer();
?>