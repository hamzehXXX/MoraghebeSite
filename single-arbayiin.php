<?php get_header();
while(have_posts()) {
    the_post();
    global $wpdb;
 $start = microtime(true);
 $currentUserId = get_current_user_id();
    $duration = get_field('arbayiin-duration');
    $ruz = "روز";
    $nthday = CONSTANTS::getDays();
    $arbayiinID = get_the_ID();

$ruzNumber = new NumberToWord();

//    $amalSize = $amalResults -> found_posts;
    $today = jdate('Y-m-d');

    $amalDay = $today;
    $startAmal = $today;
    $days = [];
    $currentDayDate = '';
    $currentDayTimeStamp = '';
    $repeat = $_GET['arbrepeat'];
    //    $dayIDs = queryAllDayIDs($wpdb, $currentUserId, $arbayiinID, $repeat);
//    $resultsFromDb = queryAllDaysForArb($currentUserId, $arbayiinID, $repeat);
//        testHelper($resultsFromDb);
    $resultsOfDay = queryAllResultIDs($wpdb, $currentUserId, $arbayiinID, $repeat);
    $result = array();
    foreach ($resultsOfDay as $element) {
        $result[$element->dayid]['date'] = $element->date;
        $result[$element->dayid]['submitdate'] = $element->submitdate;
        $result[$element->dayid]['results'][$element->amalid]['result_point'] = $element->result_point;
        $result[$element->dayid]['results'][$element->amalid]['result_matni'] = $element->result_matni;
    }
//testHelper($result);
    $amalSize = sizeof($result);
//********************************       [ PAGE BANNER ]       ********************************* START >>>>>>
    ?>

<a class="page-banner__link" href="<?php echo site_url(); ?>"><div class="page-banner">
        <div class="page-banner__bg-image" ></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro"><?php echo $amalSize!=$duration?'روز ' . ($amalSize<41?$nthday[$amalSize]:$ruzNumber->numberToWords($amalSize+1)):'پایان اربعین';?>
            </div>
        </div>
    </div></a>

<?php ####################################################################################### END <<<<<<<<<<<<<<<<<<?>
    <!--    //======================================================================-->
    <!--    // CONTAINER - PAGE SECTION -->
    <!--    //======================================================================-->

    <div class="container container--narrow page-section">

        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('arbayiin'); ?>"><i class="fa fa-home" aria-hidden="true"></i> اربعینیات </a> <span>مدت اربعین:</span><span class="metabox__main"><?php  echo $duration; ?></span></p>
        </div>


        <?php
        $testDate = new DateTime();

        $startDate = '';
      ?>
        <?php
        $display = '';
        $optionName = $currentUserId . '-' . $arbayiinID . '-period';

//        $distinctDates = $wpdb->get_results(
//            $wpdb->prepare("SELECT COUNT(*) FROM result_days WHERE userid = %d AND arbid = %d", 1, $arbayiinID),
//            OBJECT
//        );
//        testHelper($distinctDates);
        if (empty($resultsOfDay)){
            $display = 'hide-table';
            $startDate = apply_filters('arbayiin_startDate', $amalSize);
//            echo '<br/>'  . '<br/>';
        }

        if (strlen($startDate) <=10 && strlen($startDate) != 0){
            $display = '';
        }
        ?>
        <!--        //------------------------------------------------------->
        <!--        // contents-->
        <!--        //------------------------------------------------------->

        <div class="generic-content <?php echo $display;?>" >
            <div style="margin-right: 15px">توضیحات اربعین: </div>
            <div style="margin-right: 15px" id="arb-excerpt"><?php the_excerpt(); ?></div>
            <div class="display-arbcontent show-more" style="margin-right: 15px; cursor: pointer; color: blue;" id="display-arbcontent">بیشتر...</div>
        <div class="hide" style="margin-right: 15px; font-family: iranyekanwebregularfanum;" id="arb-content"><?php the_content(); ?></div>
        <div class="display-arbcontent show-less hide" style="margin-right: 15px; cursor: pointer; color: blue;" id="display-arbcontent">بستن توضیحات</div>

<!--            /* If amalsize is less than arbayiin duration show the inputs for arbayiins */-->
    <?php if( $amalSize < $duration ): ?>
            <?php if( have_rows('amal') ): ?>

            <!--    Header Table -->
            <?php
//            echo '<pre>';
//            print_r(get_complete_meta($$arbayiinID, 'amal%amal_name'));
//            echo '</pre>';

            $argsArray = array(
                'title' => get_the_title(),
                "amalSize" => $amalSize,
                'duration' => $duration,
                'ID' => $arbayiinID,
                'userID' => $currentUserId,
                'rows' => get_field('amal'),
                'days' => $days,
                'currentDayTimeStamp' => $currentDayTimeStamp,
                "startDate" => $startDate,
                "amalID" => get_sub_field('amal_term'),
                "arbrepeat" => $repeat
            ); ?>
                <div class='some-page-wrapper'>
                    <?php
                    /**
                     * Functions hooked into after_some-page_wrapper
                     *
                     * @hooked insertResults()
                     */
                    do_action('after_some-page_wrapper', $argsArray); ?>

                </div>

                <hr class="section-break"/>

            <?php endif; // ($amalsize<$duration) ?>
    <?php endif; ?>
            <?php get_template_part('template-parts/content', 'popup'); ?>

            <?php

        //======================================================================
        // ARBAYIIN RESULTS FORM
        //======================================================================

        $resultsForm = new WP_Query(array(
              'post_type' => 'resultform',
              'posts_per_page' => -1,
                'author' => $currentUserId,
                 'meta_key' => 'arbayiinid',
                 'meta_query' => array(
                         'relation' => 'AND',
                     array(
                         'key' => 'arbayiinid',
                         'compare' => '=',
                         'value' =>  $arbayiinID
                     ),
                     array(

                         'key' => 'repeat',
                         'compare' => '=',
                         'value' =>  $repeat
                     )
            )
            ));
 ?>

        <?php if (($amalSize >= $duration) AND (!($resultsForm->found_posts))): ?>

     <div class="results-form">
            <h5 class="results-form__header" >فرم نتایج اربعینیات</h5>
            <div class="results-form__forms">
            <h6 >حالات شما در طی این اربعین</h6>
            <textarea class="new-note-body field-long field-textarea results-form__textarea" id="halat" placeholder="به طور کلی شرایط روحی خود در طی این اربعین را ذکر بفرمایید. لزوما نباید شامل موارد خارق العاده ای باشد لذا حالات خود را به تفصیل و مشروح بیان نمایید."></textarea>
            </div>
         <div class="results-form__forms">
            <h6 >وضعیت شما در طی این اربعین از حیث «خوف و رجا» و «قبض و بسط»</h6>

            <textarea class="new-note-body field-long field-textarea results-form__textarea" id="vaziyat" placeholder=""></textarea>
            </div>
         <div class="results-form__forms">
            <h6 >خواب ها و رویاهای صادقه (در صورت رخ دادن)</h6>

            <textarea class="new-note-body field-long field-textarea results-form__textarea" id="khab" placeholder=""></textarea>
            </div>
            <span class="results-form__submit" data-arbayiinid="<?php echo $arbayiinID; ?>" data-repeat="<?php echo $repeat;?>" >ثبت نتایج</span>
        </div>
        <?php endif; ?>

</div>
    <hr class="section-break">

<?php
        //======================================================================
        // ARBAYIIN RESULTS TABLE
        //======================================================================
/*
 * In a ul List which ul display is set to table
 * and .arbayiin-table's overflow-x: auto;
 *
*/
?>
        <div>
            <span class="arbayiin-results-title <?php echo $display;?>">نتایج اربعین</span>
              </div>


<?php
                //-----------------------------------------------------
                // First Column of the Table -- NUMBERS
                //-----------------------------------------------------

$resultsTable = new ResultsTable($currentUserId);     // Instantiate ResultsTable class
$resultsTable->showResultsTable($display, $arbayiinID, $result);

        //********************************       [ RESULTS FORM ]       ********************************* START >>>>>>


    while ($resultsForm -> have_posts()) {
        $resultsForm -> the_post();
        ?>
        <div>
        <h5>حالات شما در طی این اربعین</h5><p><?php esc_attr(the_field('halat')) ?></p>
        <h5>وضعیت شما در طی این اربعین از حیث «خوف و رجا» و «قبض و بسط»</h5><p><?php esc_attr(the_field('vaziyat')) ?></p>
        <h5>خواب ها و رویاهای صادقه (در صورت رخ دادن)</h5><p><?php esc_attr(the_field('khab')) ?></p>
        </div>
        <?php
} wp_reset_postdata();
       //############################### END <<<<<<<<<<<<<<<<<<
 ?>
                <hr class="section-break"/>

        <?php
        //======================================================================
        // RELATED POSTS
        //======================================================================
        $currentUserRoles = wp_get_current_user() -> roles;
        $homepagePosts = new WP_Query(array(
            'posts_per_page' => -1,
            'meta_key' => 'related_arbayiin',
            'meta_query' => array(
                    'key' => 'related_arbayiin',
                'compare' => 'LIKE',
                'value' => '"' . $arbayiinID . '"'
            )
        ));
        if ($homepagePosts->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline--medium headline">اطلاعیه های ' . get_the_title() . '</h2>';

            while ($homepagePosts->have_posts()) {
                $homepagePosts->the_post();

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

                ?>
                <div class="event-summary <?php echo $hide;?>">
                    <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                        <span class="event-summary__month"><?php echo jdate('F', strtotime(get_the_date())); ?></span>
                        <span class="event-summary__day"><?php echo jdate('d', strtotime(get_the_date())); ?></span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h5>
                        <p><?php if (has_excerpt()) {
                                echo get_the_excerpt();
                            } else {
                                echo wp_trim_words(get_the_content(), 18);
                            } ?><a href="<?php the_permalink(); ?>" class="nu gray">بیشتر بخوانید</a></p>
                    </div>
                </div>
            <?php
                echo $hide='hide'?'اطلاعیه ای موجود نیست':'';
            } wp_reset_postdata();
        }


        ?>
    </div>

<?php } wp_reset_postdata();
get_footer();
?>


