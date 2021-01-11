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
//        echo '<pre>';
//        var_dump($amalResults);
//        echo '</pre>';
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
//            $startDateFromOptions = get_option($optionName);
            echo '<br/>'  . '<br/>';
//            var_dump($startDateFromOptions);
        }

//        update_option( $optionName, $startDate);
//        $startDate = get_option($optionName);
//        var_dump($startDate);
        if (strlen($startDate) <=10){
            $period = new DatePeriod(
                new DateTime($startDate), // Start date of the period
                new DateInterval('P1D'), // Define the intervals as Periods of 1 Day
                $duration-1 // Apply the interval $duration times on top of the starting date
            );
            foreach ($period as $day) {
                $days[] = $day -> format('Y/m/d');
            }

//            print_r($days);
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



            <div class="arbayiin-table <?php echo $display;?>">
            <ul class="min-list" style="display: inline-table" id="results">
<?php
                //-----------------------------------------------------
                // First Column of the Table -- NUMBERS
                //-----------------------------------------------------
?>
                <li class=" dd" >
                    <div class="t-header" >ردیف</div>

                    <?php
                        $taskCount = 1;
                        while (have_rows('amal')): the_row();
                            // vars
                            $name = get_sub_field('amal_name');
                            $content = get_sub_field('amal_desc');
                            $repeat = get_sub_field('amal_repeat');
                            $weekDay = get_sub_field('weekday');
                    ?>
                    <div class="table-num "><?php echo $taskCount; $taskCount++ ?></div>
                        <?php endwhile; ?>
                        <div class="table-num" style="color:  #fffFFF; background-color: #fffFFF"  >-</div>
                        <div class="table-num" style="color:  #fffFFF; background-color: #fffFFF" >-</div>
                </li>

                <?php
                //-----------------------------------------------------
                // Second Column of the Table -- NAMES
                //-----------------------------------------------------
                ?>

                <li class="ddd  " style="display: table-cell; " >
                    <div class="nameheader" >نام عمل</div>

                    <?php
                    $taskCount = 1;
                    $nameArray = array();
                    while (have_rows('amal')): the_row();
                        // vars
                        $name = get_sub_field('amal_name');
                        $content = get_sub_field('amal_desc');
                        $repeat = get_sub_field('amal_repeat');
                        $weekDay = get_sub_field('weekday');
                        $nameArray[] = $name;
                        $taskCount++;
                        ?>
                        <div class="resultname amal-js" data-content="<?php echo $content; ?>" data-name="<?php echo $name; ?>"><?php echo $name;  ?></div>
                    <?php endwhile; ?>
                    <div class="resultname" style="background-color:#7ad2ee; color: #FFFFFF"  >جمع امتیازات روز</div>

                    <!-- NAME COLUMN: DATE CELL -->

                    <div class="resultname sabtdate"  style="background-color: #cbcbcb" >تاریخ ثبت</div>

                </li>


                <?php
                //-----------------------------------------------------
                // RESULT Columns of the Table
                //-----------------------------------------------------
                ?>
                <?php

                while ($amalResults-> have_posts()) {  // loop through results posts
                    $amalResults -> the_post();
                ?>
                <li class="row" style="display: table-cell; " >

                  <?php #header ?>
                            <div class="t-header" ><?php echo get_field('day') ?></div>

                            <?php
                            $results = get_field('results'); // Get results field which is a string seperating each result by a ','

                            $array = explode('!@#', $results);  // explode result's String into an Array
                            $sumDayPoints = 0;  // Initialize the sum of the day's result points
                            $dayCounter = 0 ;
//                            echo '<pre>';
//                            print_r(array_chunk($array, sizeof($array)-1));
//                            echo '</pre>';
                            unset($array[sizeof($array)-1]);
                            foreach($array as $item): // Loop through result's array

                                preg_match_all('!\d+!', $item, $matches); // we get only numbers in each result from the array and store them into an array called $matches
//                            array_push($dayPointsArray, intval(implode(' ', $matches[0]))); // get numbers from matches and convert their type to integer and push in $sumDayPoints
                                $sumDayPoints += intval(implode(' ', $matches[0]));
                                $resultString = '';

                                    switch ($item){
                                        case 0:
                                            $resultString = 'انجام ندادم';
                                            break;
                                        case 1:
                                            $resultString = 'ضعیف';
                                            break;
                                        case 2:
                                            $resultString = 'متوسط';
                                            break;
                                        case 3:
                                            $resultString = 'خوب';
                                            break;
                                        case '':
                                            $resultString = '';
                                            break;
                                    }



                        ?>
                        <!-- DAY COLUMNS RESULT VALUES -->
                        <div class="resultvalue" data-result="<?php echo $item; ?>"  data-name="<?php echo $nameArray[$dayCounter]; ?>" ><?php echo $resultString; ?></div>
                      <?php $dayCounter++; endforeach; ?>

                    <?php

                    // COLOR GRADING FOR THE WHOLE DAY POINTS
                    if ($sumDayPoints >= ($taskCount*3)*0.6)$sumPointsColor = '#d6ffe7';
                    if ($sumDayPoints >= ($taskCount*3)*0.3 AND $sumDayPoints <($taskCount*3)*0.6)$sumPointsColor = '#fff9d1';
                    if ($sumDayPoints < ($taskCount*3)*0.3)$sumPointsColor = '#ffdbdb';
                    ?>

                    <!-- DAY COLUMNS SUM POINTS -->
                    <div class="resultvalue" style="background-color: <?php echo $sumPointsColor ?>"><?php echo $sumDayPoints; ?></div>

                    <!-- DAY COLUMNS DATE OF SUBMIT -->
                    <div class="resultvalue" style="background-color: #ECECEC; direction: ltr"><?php echo jdate('Y-m-d',strtotime(get_the_date())); ?></div>

                </li>
                <?php  }  wp_reset_postdata();  ?>
            </ul>
            </div>
                <hr class="section-break"/>

<!--        <div class="resultsContainer">-->
<!--            <div class="firstContainer">-->
<!--                <div class="firstContainer__static">-->
<!--                <div class="radif">ردیف</div>-->
<!--                <div class="amaltitle">نام عمل</div>-->
<!--                </div>-->
<!--                <div class="firstContainer__dynamic">-->
<!--                <div class="radif">1</div>-->
<!--                <div class="amaltitle">نماز شب</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="secondContainer">-->
<!--            --><?php //for($i = 0; $i < 40; $i++): ?>
<!--            <div class="amalday">shugulu --><?php //echo $i + 1; ?><!--</div>-->
<!--           --><?php //endfor; ?>
<!--            </div>-->
<!--        </div>-->

        <?php


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
       //####################################################################################### END <<<<<<<<<<<<<<<<<<
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


