<?php get_header();
include_once('jdf.php');
include('classes/CONSTANTS.php');
while(have_posts()) {
    the_post();


    $duration = get_field('arbayiin-duration');
    $ruz = "روز";
    $nthday = CONSTANTS::getDays();
    // GET AMALS WHERE CURRENT USER ID HAVE POSTED FOR CURRENT ARBAYIIN ================================================================================================
    $amalResults = new WP_Query(array(
        'post_type' => 'amal',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'author' => get_current_user_id(),
        'meta_key' => 'arbayiin',
        'meta_query' => array(
            'key' => 'arbayiin',
            'compare' => '=',
            'value' => get_the_ID()
        )));
    $amalSize = $amalResults -> found_posts;
    $today = jdate('Y-m-d');

    $amalDay = $today;
    $startAmal = $today;
    $days = [];
    $currentDayDate = '';
    $currentDayTimeStamp = '';
//    if ($amalSize) {
//        $startAmal = get_the_date('Y-m-d', $amalResults -> posts[0] -> ID); // get the date of the first submitted amal results
//        var_dump($startAmal);
//        $period = new DatePeriod(
//            new DateTime($startAmal), // Start date of the period
//            new DateInterval('P1D'), // Define the intervals as Periods of 1 Day
//            $duration // Apply the interval $duration times on top of the starting date
//        );
//
//        foreach ($period as $day) {
//            $days[] = $day -> format('Y-m-d');
//        }
//        $currentDayTimeStamp = strtotime($days[$amalSize]);
//        $currentDayDate = jdate('Y/m/d', $currentDayTimeStamp);
//    }

//********************************       [ PAGE BANNER ]       ********************************* START >>>>>>
    ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro"><?php echo 'روز ' . $nthday[$amalSize];?>
            </div>
        </div>
    </div>

<?php ####################################################################################### END <<<<<<<<<<<<<<<<<<?>
    <!--    //======================================================================-->
    <!--    // CONTAINER - PAGE SECTION -->
    <!--    //======================================================================-->
    <?php
    $month = CONSTANTS::$month_array;
    $daysArray = CONSTANTS::getDays();
    $year = CONSTANTS::year();
    ?>

    <div class="container container--narrow page-section">
        <?php

        ?>
<!--        //------------------------------------------------------->
<!--        // Meta-Box-->
<!--        //------------------------------------------------------->

        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('arbayiin'); ?>"><i class="fa fa-home" aria-hidden="true"></i> اربعینیات </a> <span>مدت اربعین:</span><span class="metabox__main"><?php  echo $duration; ?></span></p>
        </div>
        <?php
        $startDate = '';
      ?>
        <?php
        $display = '';

        if (!$amalSize){
            $display = 'hide-table';
            $startDate = apply_filters('arbayiin_startDate', $amalSize);
            echo '<br/>'  . '<br/>';
        }

        if (strlen($startDate) <=10){
            $period = new DatePeriod(
                new DateTime($startDate), // Start date of the period
                new DateInterval('P1D'), // Define the intervals as Periods of 1 Day
                $duration-1 // Apply the interval $duration times on top of the starting date
            );
            foreach ($period as $day) {
                $days[] = $day -> format('Y/m/d');
            }

        }
        if (strlen($startDate) <=10 && strlen($startDate) != 0){
            $display = '';
        }
        ?>
        <!--        //------------------------------------------------------->
        <!--        // contents-->
        <!--        //------------------------------------------------------->

        <div class="generic-content <?php echo $display;?>" >
        <div style="margin-right: 15px"><?php the_content(); ?></div>

<!--            /* If amalsize is less than arbayiin duration show the inputs for arbayiins */-->
    <?php if( $amalSize < $duration ): ?>
            <?php if( have_rows('amal') ): ?>

            <!--    Header Table -->

                <div class='some-page-wrapper'>
                    <?php
                    $argsArray = array(
                        'title' => get_the_title(),
                        "amalSize" => $amalSize,
                        'duration' => $duration,
                        'ID' => get_the_ID(),
                        'userID' => get_current_user_id(),
                        'rows' => get_field('amal'),
                        'days' => $days,
                        'currentDayTimeStamp' => $currentDayTimeStamp,
                        "startDate" => $startDate,
                    );

                    /**
                     * Functions hooked into after_some-page_wrapper
                     *
                     * @hooked insertResults()
                     */
                    do_action('after_some-page_wrapper', $argsArray); ?>

                    <?php
                    $taskCount = 1;
                    $dayPointsArray = [];
                    while (have_rows('amal')): the_row();
                        // vars
                        $name = get_sub_field('amal_name');
                        $content = get_sub_field('amal_desc');
                        $repeat = get_sub_field('amal_repeat');
                        $weekDay = get_sub_field('weekday');
                        $resultType = get_sub_field('result_type');
                        ?>

                    <?php endwhile; // have_row ?>

                </div>

                <hr class="section-break"/>

            <?php endif; ?>
    <?php endif; ?>
            <?php get_template_part('template-parts/content', 'popup'); ?>

            <?php

        //======================================================================
        // ARBAYIIN RESULTS FORM
        //======================================================================

        $resultsForm = new WP_Query(array(
              'post_type' => 'resultform',
              'posts_per_page' => -1,
                'author' => get_current_user_id(),
                 'meta_key' => 'arbayiinid',
                 'meta_query' => array(
                    'key' => 'arbayiinid',
                'compare' => '=',
                'value' =>  get_the_ID()
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
            <span class="results-form__submit" data-arbayiinid="<?php echo get_the_ID(); ?>">ثبت نتایج</span>
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
        <div class="arbayiin-results-title <?php echo $display;?>">نتایج اربعین</div>

<?php
                //-----------------------------------------------------
                // First Column of the Table -- NUMBERS
                //-----------------------------------------------------
$resultsTable = new ResultsTable(get_current_user_id());     // Instantiate ResultsTable class
$resultsTable->showResultsTable($display);

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
}
       //############################### END <<<<<<<<<<<<<<<<<<
 ?>
                <hr class="section-break"/>

        <?php
        //======================================================================
        // RELATED POSTS
        //======================================================================
        $homepagePosts = new WP_Query(array(
            'posts_per_page' => -1,
            'meta_key' => 'related_arbayiin',
            'meta_query' => array(
                    'key' => 'related_arbayiin',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'
            )
        ));
        if ($homepagePosts->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline--medium headline">اطلاعیه های ' . get_the_title() . '</h2>';

            while ($homepagePosts->have_posts()) {
                $homepagePosts->the_post();
//                echo the_date();
//                echo strtotime(get_the_date());
                echo jdate('F', strtotime(get_the_date()));
                ?>
                <div class="event-summary">
                    <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                        <span class="event-summary__month"><?php echo jdate('F', strtotime(get_the_date())); ?></span>
                        <span class="event-summary__day"><?php echo jdate('d', strtotime(get_the_date())); ?></span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <p><?php if (has_excerpt()) {
                                echo get_the_excerpt();
                            } else {
                                echo wp_trim_words(get_the_content(), 18);
                            } ?><a href="<?php the_permalink(); ?>" class="nu gray">بیشتر بخوانید</a></p>
                    </div>
                </div>
            <?php } wp_reset_postdata();
        }
        ?>
    </div>

<?php } wp_reset_postdata();
get_footer();
?>


