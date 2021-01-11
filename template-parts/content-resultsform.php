<?php

//>>>>>>>>>>>>>>>>>>>>>>>>>       [ RESULTS FORM ]       >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> php  >>>
// Results form query for this user and current arbayiin
global $name;
global $userID;
global $arbayiinID;
$resultsForm = new WP_Query(array(
    'post_type' => 'resultform',
    'posts_per_page' => -1,
    'author' => $userID,
    'meta_key' => 'arbayiinid',
    'meta_query' => array(
        'key' => 'arbayiinid',
        'compare' => '=',
        'value' =>  $arbayiinID
    )
));
/*
 * If there is not result form echo this
 */
if (!( $resultsForm -> found_posts)){
    echo '<br>' . '<span style="font-size: .7em">فرم نتایج توسط سالک ثبت نشده است</span>';
} else { // else echo the result form

//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<


    //********************************       [ RESULTS FORM ]       *********************************   >>>>>>
    while ($resultsForm -> have_posts()) {
        $resultsForm -> the_post();
        ?>
        <div class="results-form__ui">
            <h3>فرم نتایج <span><?php the_title(); ?></span></h3>
            <h5>حالات <?php echo $name; ?> در طی این اربعین</h5><p><?php the_field('halat') ?></p>
            <h5>وضعیت <?php echo $name; ?> در طی این اربعین از حیث «خوف و رجا» و «قبض و بسط»</h5><p><?php the_field('vaziyat') ?></p>
            <h5>خواب ها و رویاهای صادقه </h5><p><?php the_field('khab') ?></p>
        </div>
        <?php
    }
    ##################################################################################################################
}
?>
<hr />
