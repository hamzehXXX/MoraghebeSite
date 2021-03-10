
<?php
//-----------------------------------------------------
// Second Column of the Table -- NAMES
//-----------------------------------------------------

//include_once('jdf.php');

global $userID;
global $arbayiinID;
$duration = get_field('arbayiin-duration');
$ruz = "روز";
$nthday = [
    "اول", "دوم", "سوم", "چهارم", "پنجم", "ششم", "هفتم", "هشتم", "نهم", "دهم",
    "یازدهم", "دوازدهم", "سیزدهم", "چهاردهم", "سیزدهم", "چهاردهم", "پانزدهم", "شانزدهم",
    "هفدهم", "هجدهم", "نوزدهم", "بیستم", "بیست و یکم", "بیست و دوم", "بیست و سوم", "بیست و چهارم",
    "بیست و پنجم", "بیست و ششم", "بیست و هفتم", "بیست و هشتم", "بیست و نهم", "سیم", "سی و یکم",
    "سی و دوم", "سی و سوم", "سی و چهارم", "سی و پنجم", "سی و ششم", "سی و هفتم", "سی و هشتم",
    "سی و نهم", "چهلم"
];
$amalResults = new WP_Query(array(
    'post_type' => 'amal',
    'posts_per_page' => -1,
    'order' => 'ASC',
    'author' => $userID, // Amals for this user
    'meta_key' => 'arbayiin',
    'meta_query' => array(
        'key' => 'arbayiin',
        'compare' => '=',
        'value' => get_the_ID() // the Id for this arbayiin(we just set postdata to arbayiins)
    )));
$amalSize = $amalResults -> found_posts;
$today = date('Y-m-d');
$amalDay = $today;
$startAmal = $today;
$days = [];
if ($amalSize) {
    $startAmal = get_the_date('Y-m-d', $amalResults -> posts[0] -> ID); // get the date of the first submitted amal results
    $amalDay = get_the_date('Y-m-d', $amalResults -> posts[$amalSize - 1] -> ID); // Get the date of the last submited amal results

    $period = new DatePeriod(
        new DateTime($startAmal), // Start date of the period
        new DateInterval('P1D'), // Define the intervals as Periods of 1 Day
        $duration // Apply the interval 6 times on top of the starting date
    );
    foreach ($period as $day) {
        $days[] = $day -> format('Y-m-d');
    }
    $currentDayTimeStamp = strtotime($days[$amalSize]);
    $currentDayDate = jdate('l, d M', $currentDayTimeStamp);
}
?>
<li class=" dd" >
    <div class="t-header" >ردیف</div>

    <?php
    $taskCount = 1;
    while (have_rows('amal')): the_row();
        // vars
//        $name = get_sub_field('amal_name');
        $amalTerm = get_sub_field('amal_term');
        $name = $amalTerm->name;
        $content = get_sub_field('amal_desc');
        $repeat = get_sub_field('amal_repeat');
        $weekDay = get_sub_field('weekday');
        ?>
        <div class="table-num "><?php echo $taskCount; $taskCount++ ?></div>
    <?php endwhile; ?>
    <div class="table-num" style="color:  #fffFFF; background-color: #fffFFF"  >-</div>
    <div class="table-num" style="color:  #fffFFF; background-color: #fffFFF" >-</div>
</li>
<li class="ddd  " style="display: table-cell; " >
    <div class="nameheader" >نام عمل</div>

    <?php
    $taskCount = 1;
    while (have_rows('amal')): the_row();
        // vars
//        $name = get_sub_field('amal_name');
        $amalTerm = get_sub_field('amal_term');
        $name = $amalTerm->name;
        $content = get_sub_field('amal_desc');
        $repeat = get_sub_field('amal_repeat');
        $weekDay = get_sub_field('weekday');
        $taskCount++;
        ?>
        <div class="resultname" ><?php echo $name;  ?></div>
    <?php endwhile; ?>
    <div class="resultname" style="background-color:#7ad2ee"  >جمع امتیازات روز</div>

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
        $array = explode(',', $results);  // explode result's String into an Array
        $sumDayPoints = 0;  // Initialize the sum of the day's result points

        foreach($array as $item): // Loop through result's array

            preg_match_all('!\d+!', $item, $matches); // we get only numbers in each result from the array and store them into an array called $matches
//                            array_push($dayPointsArray, intval(implode(' ', $matches[0]))); // get numbers from matches and convert their type to integer and push in $sumDayPoints
            $sumDayPoints += intval(implode(' ', $matches[0]));
            ?>
            <!-- DAY COLUMNS RESULT VALUES -->
            <div class="resultvalue" ><?php echo $item; ?></div>
        <?php endforeach; ?>

        <?php

        // COLOR GRADING FOR THE WHOLE DAY POINTS
        if ($sumDayPoints >= ($taskCount*3)*0.6)$sumPointsColor = '#d6ffe7';
        if ($sumDayPoints >= ($taskCount*3)*0.3 AND $sumDayPoints <($taskCount*3)*0.6)$sumPointsColor = '#fff9d1';
        if ($sumDayPoints < ($taskCount*3)*0.3)$sumPointsColor = '#ffdbdb';
        ?>

        <!-- DAY COLUMNS SUM POINTS -->
        <div class="resultvalue" style=" border:0; background-color: <?php echo $sumPointsColor ?>"><?php echo $sumDayPoints; ?></div>

        <!-- DAY COLUMNS DATE OF SUBMIT -->
        <div class="resultvalue" style="background-color: #ECECEC; direction: ltr"><?php echo jdate('Y-m-d',strtotime(get_the_date())); ?></div>

    </li>
<?php }  wp_reset_postdata();  ?>

