<?php
get_header();
$ourCurrentUser = wp_get_current_user();
$currentUserRoles = $ourCurrentUser->roles;
if (!is_user_logged_in() AND ( !(in_array('khadem-mard', $currentUserRoles)) OR !(in_array('khadem-zan', $currentUserRoles)) OR !(in_array('admin', $currentUserRoles)))){
    wp_redirect(esc_url(site_url('/')));
    exit;
}


?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">شاگردان</h1>
            <div class="page-banner__intro">
                <p>لیست همه ی شاگردان</p>
            </div>
        </div>
    </div>
    <div class="container container--narrow page-section">

<?php
$args = array(
    'role__in' => ['khadem-mard', 'khadem-zan', 'admin', 'administrator'],
    'orderby' => 'first_name',
    'order' => 'ASC',
);
$users = get_users($args);

$khademIDArray = [];
foreach ($users as $user){
    array_push($khademIDArray, $user->ID);
    // echo $user->ID . '<br/>';
}
//echo '<pre>';
//var_dump($users);
//echo '</pre>';
echo '<ul class="khadem-ul">';
foreach ($users as $user) { // loop through each khadem for display
    $khademin = get_posts(array(
        'post_type' => 'salek',
        'posts_per_page'	=> -1,
        'meta_key' => 'salekid',
        'meta_value' => $user -> ID
    ));
    $myKhadem = get_post($user->ID);

//    if (in_array('khadem-mard', $currentUserRoles)
//        OR in_array('khadem-zan', $currentUserRoles)
//        ){
//        if (get_current_user_id() == $user -> ID  ) {
//           get_users_in_UI($khademin, $user, $khademIDArray);
//        }
//}
//    else {
////        if ( in_array('admin', $currentUserRoles)  )
////        if (get_current_user_id() == $user -> ID  ) {
//            get_users_in_UI($khademin, $user, $khademIDArray);
////        }
//    }

    if (get_current_user_id() == $user -> ID OR in_array('administrator', $currentUserRoles) ) {
        get_users_in_UI($khademin, $user, $khademIDArray);
    }
}
echo '</ul>';

?>

        <?php
//        echo paginate_links();


        ?>

    </div>

<?php
    function get_users_in_UI($khademin, $user, $khademIDArray){
        $arbayiins = get_posts(array(
            'post_type' => 'arbayiin',
            'posts_per_page'	=> -1,
            'meta_key' => 'khadem',
            'meta_compare' => 'LIKE',
            'meta_value' => '"' .get_current_user_id() .'"'
        ));
//        echo 'khadeMMiIiInNnNnN: ';
//        var_dump($khademin); die;

//        foreach ($arbayiins as $arbayiin) {
//            ?>
<!--            wefwe-->
<!--            --><?php //if ($user -> ID == get_current_user_id())  ?><!--3-->
<!--            <div class="khadem"> --><?php //echo esc_html($arbayiin -> post_title) ?><!--</div>-->
<!--            <hr/>-->
<!--            --><?php
//
//            while (have_posts()) {
//                the_post();
//
//                $salekID = get_field('salekid')['ID'];
//                $khademID = get_field('khademid')['ID'];
//                $featuredArbayiins = get_field('arbayiin');
//                if ($featuredArbayiins) {
////                    foreach ($featuredArbayiins as $mArbayiin) {
////                        echo $mArbayiin -> post_title;
////                    }
//                    if (in_array($arbayiin, $featuredArbayiins)){
////                        echo $arbayiin->post_title;
//                        ?>
<!--                    <a href="--><?php //echo get_the_permalink(); ?><!--">-->
<!--                        <li class=" "> --><?php //the_title(); ?>
<!--                       --><?php //if (in_array($salekID, $khademIDArray)) echo ' (عضو خادمین) '; ?>
<!--                        </li> </a>-->
<!--                        --><?php
//
//                    }
//                }
//
//            }
//            wp_reset_postdata();
//            echo '<br><br>';
//        }

            ?>

        <?php
//        echo '<pre>';
//        var_dump($khademin);
//        echo '</pre>';
        ?>
          <a href="<?php if ($khademin) echo $khademin[0] -> guid; ?>">
                <div class="khadem"> <?php echo esc_html($user -> display_name) ?></div>
            </a>
            <?php

                if (empty($khademin)) {
                echo '<span style="color: #ff3434">' . 'خادم در بخش سالکین تعریف نشده است. ' . '</span>';
            } ?>
            <hr/>
            <?php
            while (have_posts()) {
                the_post();
                $salekidField = get_field('salekid');
                $khademidField = get_field('khademid');
//                echo 'khdmFieieieielddddddddddddddd: <br/>';
//                var_dump($khademidField);
//                echo 'slkfieieieielddddddddddddddd: <br/>';
//                var_dump($salekidField);
                $salekID = isset($salekidField)?$salekidField['ID']:'0';
                $khademID = $khademidField?$khademidField['ID']:'0';
                // echo '<br/>'.   $khademID;
//                echo '<br/>'. $user->ID;
                if ($khademID == $user -> ID) {
                    ?>
                <a href="<?php echo get_the_permalink(); ?>">
                    <li class=" "> <?php the_title(); ?>
                    </a><?php if (in_array($salekID, $khademIDArray)) echo ' (عضو خادمین) '; ?>
                    </li>
                    <?php
                }
            }
            wp_reset_postdata();
            echo '<br><br>';
    }


?>
<?php get_footer();
?>