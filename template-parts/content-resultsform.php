<?php

//>>>>>>>>>>>>>>>>>>>>>>>>>       [ RESULTS FORM ]       >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> php  >>>
// Results form query for this user and current arbayiin
//testHelper($args);
$name = $args['name'];
$userID = $args['userid'];
$arbayiinID = $args['arbid'];
$resultsForm = get_posts(array(
    'post_type' => 'resultform',
    'posts_per_page' => -1,
    'post_author' => $userID,
    'meta_key' => 'arbayiinid',
    'meta_query' => array(
        'key' => 'arbayiinid',
        'compare' => '=',
        'value' =>  $arbayiinID
    )
));
//testHelper($resultsForm);
/*
 * If there is not result form echo this
 */
if (empty($resultsForm)){
    echo '<br>' . '<span style="font-size: .7em">فرم نتایج توسط سالک ثبت نشده است</span>';
} else { // else echo the result form
$lastResultForm = $resultsForm[sizeof($resultsForm) - 1];
//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<


    //********************************       [ RESULTS FORM ]       *********************************   >>>>>>
    $resultFormID = $lastResultForm->ID;
        ?>
        <div class="results-form__ui">
            <div class="display-arbcontent show-more" style="margin-right: 15px; cursor: pointer; color: blue;" id="display-arbcontent">فرم نتایج <span><?php the_title(); ?></span></div>

            <div class="hide" style="margin-right: 15px" id="arb-content">
                <h5 style="color: #c32929">حالات <?php echo $name; ?> در طی این اربعین</h5><p><?php the_field('halat', $resultFormID) ?></p>
                <h5 style="color: #c32929">وضعیت <?php echo $name; ?> در طی این اربعین از حیث «خوف و رجا» و «قبض و بسط»</h5><p><?php the_field('vaziyat', $resultFormID) ?></p>
                <h5 style="color: #c32929">خواب ها و رویاهای صادقه </h5><p><?php the_field('khab', $resultFormID) ?></p>
            </div>
            <br/>
            <div class="display-arbcontent show-less hide" style="margin-right: 15px; cursor: pointer; color: blue;" id="display-arbcontent">بستن فرم</div>
        </div>
        <?php

    ##################################################################################################################
}
?>

