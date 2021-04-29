<?php
get_header();
$ourCurrentUser = wp_get_current_user();
$currentUserRoles = $ourCurrentUser -> roles;
$currentUserId = get_current_user_id();
if (!is_user_logged_in() AND
    (!(in_array('khadem-mard', $currentUserRoles)) OR
        !(in_array('khadem-zan', $currentUserRoles)) OR
        !(in_array('admin-mard', $currentUserRoles)) OR
        !(in_array('admin-zan', $currentUserRoles))
    )
) {
    wp_redirect(esc_url(site_url('/')));
    exit;
}

// $args = array(
//        'post_type' => 'salek',
//        'numberposts' => -1
//    );
//    $myposts = get_posts($args);
//    foreach ($myposts as $mypost){
//        $mypost->post_title = $mypost->post_title.'';
//        wp_update_post($mypost->ID);
//    }
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
<!--        <div class="metabox metabox--position-up metabox--with-home-link">-->
<!--            <p>-->
<!--                 <div class="finishedArbToggle metabox__blog-home-link" id="showFinished" style="background-color: #dba88a;" >نمایش اربعینیات پایان یافته</div>-->
<!--            <div class="haaseb-invisible finishedArbToggle metabox__blog-home-link" style="background-color: #8EBF96" id="showFinished">نمایش اربعینیات جاری</div>-->
<!--        </div>-->

        <div id="showFinished" style="cursor: pointer; color: #fff; font-size: 1.2rem; text-align: center; background-color: #2e674d; font-family: iranyekanwebregularfanum;" ><i style="color: #F8119A" class="fa fa-refresh" aria-hidden="true"></i> اربعینیات جاری </div>
        <div class="haaseb-invisible" id="showFinished" style="cursor: pointer; color: yellow; font-size: 1.2rem; text-align: center; background-color: #A94B30; font-family: iranyekanwebregularfanum;"><i style="color:#F8119A;" class="fa fa-refresh" aria-hidden="true"></i> اربعینیات پایان یافته</div>
        <?php


        /**
         * ///////////////////////////////////////////////////////////////////////////////
         */

//        $khademUsers = get_users(array(
//            'role__in' => ['khadem-mard', 'khadem-zan', 'admin', 'administrator'],
//            'orderby' => 'first_name',
//            'order' => 'ASC',
//        ));

        // khadems whose saleks will display
        $availableKhademsID[] = $currentUserId;

//        mainFunction($availableKhademsID);


        ///////////////////// Logic for Display Salek and Khadems \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

//
//
//######## Display All Khadem-mards for admin-mard
//        if ($currentUserId == 93 or $currentUserId == 245 or $currentUserId == 1) {
            ?>

            <div class="shagerdan-container">

                <div class="shagerdan-table">
                    <div class="shagerdan-table-header">
                        <div class="shagerdan-header__item"><a id="name" class="filter__link" href="#"><i class="fa fa-sort" aria-hidden="true"> خادم</i></a></div>
                        <div class="shagerdan-header__item"><a id="wins" class="filter__link" href="#"><i class="fa fa-sort" aria-hidden="true"> اربعین</i></a></div>
                        <div class="shagerdan-header__item"><a id="draws" class="filter__link" href="#"><i class="fa fa-sort" aria-hidden="true"> سالک</i></a></div>
                        <div class="shagerdan-header__item"><a id="losses" class="filter__link" href="#"><i class="fa fa-sort" aria-hidden="true"> (تاریخ |
                                روز)</i></a></div>
                        <!--                    <div class="shagerdan-header__item"><a id="total" class="filter__link filter__link--number" href="#">Total</a></div>-->
                    </div>
                    <div class="shagerdan-table-content">
                        <?php
                        if (in_array('admin-mard', $currentUserRoles)) {
                            $khademUsers = queryKhademsByRole(['khadem-mard'], ['khadem-zan', 'salek-zan']);

                            if (!empty($khademUsers)) {
                                foreach ($khademUsers as $khadem) {                         // loop through khadm-mards
                                    $khademID = $khadem -> ID;
                                    $khademSalekinPostObj = getSalekByUserId($khademID);
                                    $khademObj = empty($khademSalekinPostObj)?0:$khademSalekinPostObj[0];
                                    displayResultsForKhademArbs($khademID, $khadem -> display_name, $khademObj);
                                    displayResultsForSaleksInMahfel($khademID, $khadem -> display_name, $khademObj);
                                }
                            }

                        } else if (in_array('khadem-mard', $currentUserRoles)) {     // if highest level of role is khadem-mard
                            $khademArbs = get_khadem_arbs($currentUserId);
                            $khademSalekinPostObj = getSalekByUserId($currentUserId);
                            displayResultsForKhademArbs($currentUserId, $ourCurrentUser -> display_name, $khademSalekinPostObj[0]);

                            $saleksInMahfel = displayResultsForSaleksInMahfel($currentUserId, $ourCurrentUser -> display_name, $khademSalekinPostObj[0]);

                            if (empty($khademArbs) AND empty($saleksInMahfel)) {
                                echo 'شما خادم هیچ اربعین نیستید';
                            }

                        }


                        ######## Display All khadem-zans for admin-zan
                        if (in_array('admin-zan', $currentUserRoles)) {

                            $khademUsers = queryKhademsByRole(['khadem-zan'], ['khadem-mard', 'salek-mard']);

                            if (!empty($khademUsers)) {
                                foreach ($khademUsers as $khadem) {                         // loop through khadm-mards
                                    $khademID = $khadem -> ID;
                                    $khademSalekinPostObj = getSalekByUserId($khademID);
                                    displayResultsForKhademArbsFemale($khademID, $khadem -> display_name, $khademSalekinPostObj[0]);
                                    displayResultsForSaleksInMahfel($khademID, $khadem -> display_name, $khademSalekinPostObj[0]);
                                }
                            }
                        } else if (in_array('khadem-zan', $currentUserRoles)) {     // Display All saleks for khadem-zan
                            $khademArbs = get_khadem_arbs($currentUserId);
                            $khademSalekinPostObj = getSalekByUserId($currentUserId);
                            displayResultsForKhademArbsFemale($currentUserId, $ourCurrentUser -> display_name, $khademSalekinPostObj[0]);

                            $saleksInMahfel = displayResultsForSaleksInMahfel($currentUserId, $ourCurrentUser -> display_name, $khademSalekinPostObj[0]);

                        }
                        //
                        //

                        ?>
                    </div>
                </div>
            </div>

            <?php
//        }

        ///////////////////// Functions Being Used \\\\\\\\\\\\\\\\\\\\\\\\\

        function displayResultsForKhademArbs($khademUserId, $khademName, $khademSalekinPostObj) {
            $khademArbs = get_khadem_arbs($khademUserId);
            if (!empty($khademArbs)) {
//                $khademSalekinPostObj = getSalekByUserId($khademUserId);
                $saleksInArbs = get_arbayiin_saleks($khademArbs);
                foreach ($saleksInArbs as $arbid => $saleksInArb) {
                    foreach ($saleksInArb as $salek) {
                        $salekUserObj = get_field('salekid', $salek -> ID);

                        if (in_array('salek-mard', $salekUserObj -> roles)) {
                            displayResultsForKhademArbayiins($salek, $arbid, $salekUserObj, $khademSalekinPostObj, $khademName);
                        }

                    }
                }
            }
        }

        function displayResultsForKhademArbsFemale($khademUserId, $khademName, $khademSalekinPostObj) {
            $khademArbs = get_khadem_arbs($khademUserId);
            if (!empty($khademArbs)) {
//                $khademSalekinPostObj = getSalekByUserId($khademUserId);
                $saleksInArbs = get_arbayiin_saleks($khademArbs);
                foreach ($saleksInArbs as $arbid => $saleksInArb) {
                    foreach ($saleksInArb as $salek) {
                        $salekUserObj = get_field('salekid', $salek -> ID);

                        if (in_array('salek-zan', $salekUserObj -> roles)) {
                            displayResultsForKhademArbayiins($salek, $arbid, $salekUserObj, $khademSalekinPostObj, $khademName);
                        }

                    }
                }
            }
        }

        function displayResultsForKhademArbayiins($salek, $arbid, $salekUserObj, $khademSalekinPostObj, $khademName) {
            $repeatArr = array();
            while (have_rows('arb_after_app', $salek -> ID)) {
                the_row();
                if (get_sub_field('dastoor_takhsised') -> ID == $arbid) {
                    $repeatArr[] = get_sub_field('repeat');

                }
            }
            $maxRepeat = max($repeatArr);
            $repeat = $maxRepeat > 1 ? '(' . 'تکرار ' . $maxRepeat . ')' : '';
            $duration = get_field('arbayiin-duration', $arbid);
            $dayInfo = getDayInfoInByUserId($salekUserObj -> ID, $arbid, $maxRepeat)[0];
            $date = $dayInfo -> maxdate;
            $submitdate = $dayInfo -> submitdate;
            $dayCount = $dayInfo -> count;
            $finishedColor = '';
            $hide = '';
            if ($dayCount >= $duration) {
//                $finishedColor = "#F0F7EE";
                $hide = "haaseb-invisible";
                $display = "none;";
            }
                ?>
                <div class="shagerdan-table-row <?php echo $hide;?>" style="background-color: <?php echo $finishedColor;?>" id="salekinTable">
                    <div class="shagerdan-table-data"><a style="text-decoration: none;"
                                                         href="<?php echo get_permalink($khademSalekinPostObj -> ID); ?>"><?php echo $khademName; ?></a>
                    </div>
                    <div class="shagerdan-table-data"><?php echo get_the_title($arbid) . $repeat; ?></div>
                    <div class="shagerdan-table-data"><a style="text-decoration: none;"
                                                         href="<?php echo get_permalink($salek -> ID); ?>"><?php echo $salekUserObj -> data -> display_name; ?></a>
                    </div>
                    <div class="shagerdan-table-data"><?php echo(($dayCount ? jdate('Y/m/d', $date) : '<span style="color: #ff0000">شروع نشده</span>') . ' | ' . $dayCount . '/' . $duration); ?></div>
                </div>
                <?php
//            }
        }

        function displayResultsForSaleksInMahfel($khademID, $khademName, $khademSalekinPostObj) {
            $saleksInMahfel = get_mahfel_saleks($khademID);       // get saleks of each khadem-mard
            if (!empty($saleksInMahfel)){

                $mahfelSaleks = get_saleks_and_arbs_from_salekin($saleksInMahfel);
                foreach ($mahfelSaleks as $salekid => $arbayiins){
                    foreach ($arbayiins as $arbayiin) {
                        $arbRepeat = $arbayiin[1];
                        $finalRepeat = $arbRepeat > 1?'(' . 'تکرار ' . $arbRepeat . ')':'';
                        $userid = get_field('salekid', $salekid);
                        $dayInfo = getDayInfoInByUserId($userid->ID, $arbayiin[0], $arbRepeat)[0];
                        $date = $dayInfo->maxdate;
                        $duration = get_field('arbayiin-duration', $arbayiin[0]);
                        $submitdate = $dayInfo->submitdate;
                        $dayCount = $dayInfo->count;
                        $finishedColor = '';
                        $hide = '';
                        if ($dayCount >= $duration) {
//                            $finishedColor = "#F0F7EE";
                            $hide = "haaseb-invisible";
                            $display = "none;";
                            $idFinished = "";
                        }

                            ?>
                            <div class="shagerdan-table-row <?php echo $hide;?>" style="background-color: <?php echo $finishedColor;?>"  id="salekinTable">
                                <div class="shagerdan-table-data"><a style="text-decoration: none;"
                                                                     href="<?php echo get_permalink($khademSalekinPostObj -> ID); ?>"><?php echo $khademName; ?></a>
                                </div>
                                <div class="shagerdan-table-data"><?php echo get_the_title($arbayiin[0]) . $finalRepeat; ?></div>
                                <div class="shagerdan-table-data"><a style="text-decoration: none;"
                                                                     href="<?php echo get_permalink($salekid); ?>"><?php echo get_the_title($salekid); ?></a>
                                </div>
                                <div class="shagerdan-table-data"><?php echo(($dayCount ? jdate('Y/m/d', $date) : '<span style="color: #ff0000">شروع نشده</span>') . ' | ' . $dayCount . '/' . $duration); ?></div>
                            </div>
                            <?php
//                        }
                    }
                }

            }
            return $saleksInMahfel;
        }


        function mainFunction($availableKhademsID) {
            foreach ($availableKhademsID as $khademId) {
                $newKhademArray = $availableKhademsID;

                $mahfelSaleks = get_mahfel_saleks($khademId);                   // saleks which their khademId is $khademId
                $khademArbs = get_khadem_arbs($khademId);                       // Arbayiins which their khademId is $khademId
                $arbayiinSaleks = get_arbayiin_saleks($khademArbs);               // Saleks which have above arbayiins
            }

        }


        function queryKhademsByRole($roleIn, $roleNotIn) {
            return get_users(array(
                'role__in' => $roleIn,
                'role__not_in' => $roleNotIn,
                'orderby' => 'first_name',
                'order' => 'ASC',
                'fields' =>  array('ID', 'display_name')
            ));
        }

        /**Saleks which their khademId is $khademId
         * @param $khademId
         * @return int[]|WP_Post[]
         */
        function get_mahfel_saleks($khademId) {
            return get_posts(array(
                'post_type' => 'salek',
                'posts_per_page' => -1,
                'meta_query' => array(
                        'relation' => 'OR',
                    array(
                        'key' => 'khademid',
                         'value' => '"' . $khademId . '"',
                        'compare' => 'LIKE',

                    ),
                    array(
                        'key' => 'khademid',
                         'value' => array($khademId),
                        'compare' => 'IN',

                    ),
                    array(
                        'key' => 'khademid',
                         'value' => $khademId,
                        'compare' => '=',
                    )

                )
            ));

        }

        function getSalekByUserId($userId) {
            return get_posts(array(
                'post_type' => 'salek',
                'numberposts' => 1,
                'posts_per_page' => 1,
                'meta_query' => array(
                    array(
                        'key' => 'salekid',
                        'compare' => '=',
                        'value' =>  $userId
                    )
                )
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

        function get_saleks_and_arbs_from_salekin($saleksInMahfel) {
            $salekArbsArray= array();
            foreach ($saleksInMahfel as $salek) {
                $salekName = $salek->post_title;
                $salekid = $salek->ID;
                $userObj = get_field('salekid');
                get_field('arb_after_app', $salekid);
                if (have_rows('arb_after_app', $salekid)){
                    while (have_rows('arb_after_app', $salekid)){
                        the_row();
                        $arb = get_sub_field('dastoor_takhsised');
                        $repeat = get_sub_field('repeat');

//                        $dayInfo = getDayInfoInByUserId($userObj->ID, $arb->ID, $repeat);
//                        testHelper($dayInfo);
                        $finalRepeat = $repeat > 1?'(' . 'تکرار ' . $repeat . ')':'';
                        $salekArbsArray[$salekid][] = isset($arb->post_title)?array($arb->ID, $repeat):'نام اربعین خالی می باشد!';
                    }
                }
            }

            return $salekArbsArray;
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

                $arbSaleks[$khademArb->ID] = get_posts($args);
            }

            return $arbSaleks;
        }

        /**
         * ///////////////////////////////////////////////////////////////////////////////
         */

//        die;

//        if ($currentUserId != 93 AND $currentUserId != 245 AND $currentUserId != 1) {
//            $khademIDArray = [];
//            $args = array(
//                'role__in' => ['khadem-mard', 'khadem-zan', 'admin', 'administrator'],
//                'orderby' => 'first_name',
//                'order' => 'ASC',
//            );
//            $users = get_users($args);
//
//            echo '<ul class="khadem-ul">';
//            foreach ($users as $user) { // loop through each khadem for display
//                $khademin = get_posts(array(
//                    'post_type' => 'salek',
//                    'posts_per_page' => -1,
//                    'meta_key' => 'salekid',
//                    'meta_value' => $user -> ID
//                ));
//                $khademArbs = get_posts(array(
//                    'numberposts' => -1,
//                    'post_type' => 'arbayiin',
//                    'posts_per_page' => -1,
//                    'meta_query' => array(
//                        array(
//                            'key' => 'khadem',
//                            'compare' => 'LIKE',
//                            'value' => '"' . $user -> ID . '"'
//                        )
//                    )
//                ));
//
//                foreach ($khademArbs as $khademArb) {
//                    $args = array(
//                        'numberposts' => -1,
//                        'post_type' => 'salek',
//                        'suppress_filters' => false,
//                        'orderby' => 'title',
//                        'meta_query' => array(
//                            array(
//                                'key' => 'arb_after_app_$_dastoor_takhsised',
//                                'compare' => '=',
//                                'value' => $khademArb -> ID,
//                            )
//                        )
//                    );
//                }
//
//
//                $myKhadem = get_post($user -> ID);
//
//
//                if ($currentUserId == $user -> ID OR in_array('administrator', $currentUserRoles)) {
//                    get_users_in_UI($khademin, $user, $khademIDArray);
//                }
//                array_push($khademIDArray, $user -> ID);
//            }
//            echo '</ul>';
//}
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
//        $salekidField = get_field('salekid');
        //print_r($salekidField);
        $khademidField = get_field('khademid');
//        $salekID = isset($salekidField) ? $salekidField['ID'] : '0';
//        $salekName = isset($salekidField) ? $salekidField['user_firstname'] : 'shugulu';
//        $salekFamily = isset($salekidField) ? $salekidField['user_lastname'] : 'shugulu';
        $khademID = $khademidField ? $khademidField->ID : '0';

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
                        <li class=" "> <?php echo get_the_title($salekMahfelID); ?>
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
                    echo !in_array($key, $commonSaleks)?get_the_title($key):get_the_title($key);
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