<?php
/** hooked in arbayiin_startDate filter
 * @return mixed|void
 */
function setStartDate() {
    $optionName = get_current_user_id() . '-' . get_the_ID();
    $startDate = get_option($optionName);
    // display startDate select datepicker if not set yet
//    echo '<form method="post">';
    echo '<label for="startDate" >تاریخ شروع: </label>';
    ?>
    <div>برای انتخاب تاریخ شروع اربعین:</div>
    <ol style="font-family: iranyekanwebregularfanum;">
        <li>روی کادر خالی کلیک کنید تا تقویم نمایان شود</li>
        <li>تاریخ شروع را از روی تقویم انتخاب کرده تا در کادر خالی نمایان شود</li>
        <li>سپس دکمه ی انتخاب را بزنید.</li>
    </ol>

    <input class="start-date" type="text" id="startDate" name="drddd" autocomplete="off" value=""
            data-userid = "<?php echo get_current_user_id(); ?>"  onkeydown="return false"
            data-arbid = "<?php the_ID(); ?>" readonly>
    <?php
    echo '<button name="sbmt" id="submit-date" >انتخاب</button>';
//    echo '</form>';
    echo '<div class="start-date__alert"></div>';
    echo '<script>';
    echo 'jQuery(function () {jQuery("#startDate").persianDatepicker();});';
    echo '</script>';

    echo '<hr class="section-break"/>';
//    if(isset($_POST['sbmt'])) {
//        $startDate = $startDate = $_POST['drddd'];
//
//        return $startDate;
//    } else {
//        $startDate = 'لطفا حتما تاریخ شروع را مشخص بفرمایید';
//    }
//    echo $startDate;

    return $startDate;

}

function testHelper($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}


/**hooked: after_some-page_wrapper hook in single-arbayiin.php
 * @param $argsArray
 */
function insertResults($argsArray) {
//    global $wpdb;
//    echo '<br/>' . jdate('r', $timeStamp + 2*86400);
//    $title = $argsArray['title'];
//    $days = $argsArray['days'];
    $arbId = $argsArray['ID'];
    $userID = $argsArray['userID'];
//    $user = get_user_by( 'id', $userID );
    $startDate = $argsArray['startDate'];
    $amalSize = $argsArray['amalSize'];
    $arbRepeat = $argsArray['arbrepeat'];
    $optionName = get_current_user_id() . '-' . get_the_ID() . '-period';
    $days = get_option($optionName);
    $currentWeekDay = empty($days)?'':getCurrentWeekDay($days[$amalSize]);
    $displayDate = $amalSize == 0?'(' . $currentWeekDay . ', ' .$startDate.')':'(' . $currentWeekDay . ', '.$days[$amalSize].')';
    $rows = $argsArray['rows'];

//testHelper($rows);
$disableSubmitBtn = '';
$submitButtonText = 'ثبت اعمال';
if ($days[$amalSize] > jdate('Y/m/d')) {
    $disableSubmitBtn = 'disabled';
    $submitButtonText = 'امکان ثبت اعمال روز آینده وجود ندارد';

}
echo '<hr/>';
    ?>
    <table class="amal-table" data-arbid="<?php echo $arbId;?>"
                              data-author="<?php echo $userID;?>"
                              data-day="<?php echo $displayDate;?>"
                              data-arbrepeat="<?php echo $arbRepeat;?>"  >
<!--        <form method="post">-->
        <caption>اعمال در انتظار ثبت</caption>
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col" class="current-date"><?php echo 'روز ' . CONSTANTS::getDays()[$amalSize] . $displayDate; ?></th>
        </tr>
        </thead>
        <tbody>
       <?php if($rows): ?>
       <?php $rowNumber = 1;
            $amalIDArray = array();
       ?>
       <?php  ?>
       <?php
           $amalArr = array();
           foreach($rows as $row ):
               ?>
               <?php
               $amalTerm = $row['amal_term'];
               $amalid = $amalTerm->term_id;
//                    $amalName = $row['amal_name'];
                    $amal_repeat = $row['amal_repeat'];
                    $result_type = $row['result_type'];
                    $content = $row['amal_desc'];
                    $specificDay = $row['specific_day'];
                    $weekday = $row['weekday'];
                    $amalObject = $row['amal_term'];

                    $amalName = $amalObject->name;
                    $amalID = $amalObject->term_id;
//                    testHelper($amalObject);
//                    echo '<br/>';


//                    testHelper(get_term($amalID, 'arbAmal', OBJECT, true));
                    $amalIDArray[] = $amalID;
               $disableBox = 'enabled';
               $weeklyString =  $amal_repeat == 'هفتگی'? '(هفتگی)':'';
               $dimSelector = '';
//               echo $currentWeekDay;
//               echo '<pre>';
//                var_dump($weekday);
//               echo '<pre/>';
               if($specificDay){
                   $weeklyString = '(' . $weekday . ')';
                   if ($weekday != $currentWeekDay) {
                       $disableBox = 'disabled';
                       $dimSelector = 'dim-selector';
                   }
               }

               // For using HTML tags inside data attribute of amal-js
               $needleArr = array('<', '>', '"');
               $replaceArr = array('&lt;', '&gt;', '&quot;');
               $content = str_replace($needleArr, $replaceArr ,$content);
               ?>
        <tr class="zebra <?php echo $dimSelector;?>">
            <th scope="row" rowspan="2"><?php echo $rowNumber; ?></th>
            <td class="amal-js" scope="row" data-content="<?php echo $content; ?>" data-name="<?php echo $amalName; ?>">
                <?php echo $amalName; ?>
                <span style=""><?php echo $weeklyString; ?></span>
                <span class="amalDesc" style="color: mediumpurple; float: left; text-align: left !important;">(ت)</span>
            </td>
        </tr>
        <tr class="resInput">
            <td class="selector <?php echo $dimSelector;?>" data-rownumber="<?php echo $rowNumber ?>"
                                                            data-amalid="<?php echo $amalid ?>"
                                                            data-resulttype="<?php echo $result_type;?>">

                <?php
                switch ($result_type){
                    case 'متنی':
                        remove_action('insideSlector', 'printRadioButtons');
                        add_action('insideSlector', 'printTextarea', 10, 3);
                        break;
                    default:
                        remove_action('insideSlector', 'printTextarea');
                        add_action('insideSlector', 'printRadioButtons', 10, 3);
                }
                do_action( 'insideSlector', $rowNumber, $disableBox, $amalID);


                ?>

            </td>
        </tr>
           <?php $rowNumber++; ?>
    <?php endforeach; //$rows ?>
    <?php endif; //$rows ?>
        </tbody>
        <tfoot>
        <tr>
            <th scope="row" colspan="2" >
                <button class="button-large" id="submit-amal" name="<?php echo 'submit-amal'; ?>" value="<?php echo $submitButtonText;?>" <?php echo $disableSubmitBtn;?> ><?php echo $submitButtonText;?></button>
            </th>
        </tr>
        </tfoot>
<!--        </form>-->
    </table>

    <?php
    $resultsArray = '';


    if(isset($_POST['submit-amal'])) {
//        echo 'submited!!!';
//        for ($i=1; $i<$rowNumber; $i++) {
//            $result = $_POST['result-'.$i] == ''?'0':$_POST['result-'.$i];
//            $resultsArray .= $result . '!@#';
//            echo $_POST['result-'.$i] . ' ___';

//            $dbAdded = $wpdb->insert(
//                'wp_amalresults',
//                array(
//                    'userid' => get_current_user_id(),
//                    'arbid' => $arbId,
//                    'amalid' => $amalIDArray[$i-1],
//                    'arbrepeat' => 0,
//                    'resultval' => $result,
//                    'date' => jdate('Y-m-d H:i:s')
//                )
//            );
//        }

        $arbDate = 'روز ' . CONSTANTS::getDays()[$amalSize] . $displayDate;

//    wp_insert_post(array(
//            'post_type' => 'amal',
//            'post_status' => 'publish',
//            'post_title' => $user->user_login . ' - ' . $user->display_name . ' - ' . $arbDate . get_the_title($arbId),
//            'post_author' => $userID,
//            'meta_input' => array(
//                'arbayiin' => $amalId,
//                'day' => $arbDate,
//                'results' => $resultsArray
//            )
//        ));

//        $_POST['submit-amal'] = null;
//        echo "<meta http-equiv='refresh' content='0'>";

    }
    ?>
    <input type="hidden" class="jsResults" data-amalsize="<?php echo $amalSize; ?>">
    <script>
        jQuery('table').each(function() {
            // Note that, confusingly, jQuery's filter pseudos are 0-indexed
            // while CSS :nth-child() is 1-indexed
            jQuery('tr.zebra:even').addClass('odd');
        });
    </script>
    <script>
        jQuery('table').each(function() {
            // Note that, confusingly, jQuery's filter pseudos are 0-indexed
            // while CSS :nth-child() is 1-indexed
           var resInput = jQuery('input.resultInput:checked').val();
           // console.log(resInput);
        });
    </script>
<?php
//    var_dump($dbAdded);
}

function printRadioButtons($rowNumber, $disableBox, $amalID) {
?>
    <div dir="rtl">
        <input class="resultInput resultInput-<?php echo $rowNumber;?> " class="radioresult" type="radio" id="<?php echo "nope-" . $rowNumber;?>" name="<?php echo "result-" . $rowNumber; ?>" data-num="<?php echo $rowNumber;?>" value=0 checked <?php echo $disableBox; ?>>
        <label for="<?php echo 'nope-' . $rowNumber;?>">انجام ندادم</label>
    </div>
    <div>
        <input class="resultInput resultInput-<?php echo $rowNumber;?>" type="radio" id="<?php echo 'bad-' . $rowNumber;?>" name="<?php echo 'result-' . $rowNumber; ?>" data-num="<?php echo $rowNumber;?>" value=1 <?php echo $disableBox; ?>>
        <label for="<?php echo 'bad-' . $rowNumber;?>">ضعیف</label>
    </div>
    <div>
        <input class="resultInput resultInput-<?php echo $rowNumber;?>" type="radio" id="<?php echo 'medium-' . $rowNumber;?>" name="<?php echo 'result-' . $rowNumber; ?>" data-num="<?php echo $rowNumber;?>" value=2 <?php echo $disableBox; ?>>
        <label for="<?php echo 'medium-' . $rowNumber;?>">متوسط</label>
    </div>
    <div>
        <input class="resultInput resultInput-<?php echo $rowNumber;?>" type="radio" id="<?php echo 'good-' . $rowNumber;?>" name="<?php echo 'result-' . $rowNumber; ?>" data-num="<?php echo $rowNumber;?>" value=3 <?php echo $disableBox; ?>>
        <label for="<?php echo 'good-' . $rowNumber;?>">قوی</label>
    </div>
<?php
}

function printTextarea($rowNumber, $disableBox) {
    ?>
    <textarea style="width: 100%" name="result-<?php echo $rowNumber;?>" id="textarea-<?php echo $rowNumber;?>" class="resultInput" rows="1" cols="1" data-num="<?php echo $rowNumber;?>" placeholder="ثبت یادداشت..." <?php echo $disableBox; ?>></textarea>

    <?php
}

/**
 * GETS A DATE AND RETURNS THE DAY OF WEEK IN JALALI
 * @param $currentJalaliDate
 * @return mixed|string weekDay
 */
function getCurrentWeekDay($currentJalaliDate) {
    $jalaliArray = explode('/', $currentJalaliDate);
    $gregorian = jalali_to_gregorian(intval($jalaliArray[0]),intval($jalaliArray[1]),intval($jalaliArray[2]), '/');
    $time = strtotime($gregorian);
    $newFormat = date('Y-m-d', $time);
    return jdate('l', $time);
}

/**get Or Create Category Id
 * @param $title
 * @param $taxonomy
 * @param $arbTitle
 * @return array|false|WP_Term
 */
function getOrCreateCatId($title, $taxonomy, $description) {
    if (term_exists($title, $taxonomy)) {

        $catID = get_term_by('name', $title, 'arbAmal');
//        testHelper($catID);

    } else {

        $args = array(
                'description' => $description
        );
        $catArray = wp_insert_term($title, $taxonomy, $args);

        $catID = $catArray['term_id'];
    }

    return $catID;
}


/**Populate an associative array by Amals  as $key and Arbayiins as $value
 * @param $amal         amals of arbayiin
 * @param $amalArray    the associative array
 */
function populateAmalArray($amal, $amalArray) {
    if (array_key_exists($amal, $amalArray)){
        if (is_string($amalArray[$amal])){
            $amalArray[$amal] = array($amalArray[$amal], get_the_title());
        } else if (is_array($amalArray[$amal])) {
            $amalArray[$amal][] = get_the_title();
        }

//                        wp_set_post_terms(get_page_by_title($amal, OBJECT, 'task')->ID, array($catID), 'arbayiin', true);
//                        $amalArray[$amal][] =  get_the_title();
    } else {

        // Create post object
//                        $my_post = array(
//                            'post_title'    => $amal,
//                            'post_type'     => 'task',
//                            'post_content'  => get_sub_field('amal_desc'),
//                            'post_status'   => 'publish',
//                            'post_category' => array($catID)
//                        );

//                        wp_insert_post($my_post);

        $amalArray[$amal] = get_the_title();
    }

    return $amalArray;
}

function showAmalsAndArbs($amalArray) {
    echo 'تعداد اعمال: ' . sizeof($amalArray) . '<br/>';
    foreach ($amalArray as $amalName => $arbName) {
        echo $amalName;
        echo '<ul>';
        if (is_array($arbName)){
            foreach ($arbName as $value){
                echo '<li>';
                echo $value;
                echo '</li>';
            }
        } else {
            echo '<li>';
            echo $arbName;
            echo '</li>';
        }
        echo '</ul>';
    }
}

function convertAllResults() {
    global $wpdb;
    $salekinArgs = array(
        'posts_per_page'	=> -1,
        'post_type' => 'salek'
    );

//    $salekin = new WP_Query($salekinArgs);
    $salekin = get_posts($salekinArgs);
//    testHelper($salekin); die;

    foreach ($salekin as $post) {

        $salekArray = get_field('salekid', $post->ID);
        $salekID = $salekArray['ID'];
        $salekTitle = $salekArray['nickname'];
//        testHelper($salekID);
        testHelper($salekTitle . '__________________________________________________');
        if (have_rows('arb_after_app', $post->ID)){

            while (have_rows('arb_after_app', $post->ID)){
                the_row();

                $dastoor_takhsised_obj = get_sub_field('dastoor_takhsised');

                $dastoor_ID = ($dastoor_takhsised_obj)?$dastoor_takhsised_obj->ID:0;
                echo $dastoor_takhsised_obj?'title: '  . '__________________________' . $dastoor_takhsised_obj->post_title:'no title';
                $amals = get_field('amal', $dastoor_ID);
//                testHelper($amals);
                if ($amals) {
                    $amalCounter = 0;
                    $amalArr = array();
                    foreach ($amals as $amal) {
                        $amalTerm = $amal['amal_term'];
//                        testHelper($amalTerm);
                        $amalArr[] = $amalTerm->term_id;
                    }
                    testHelper($amalArr);
                }
                $argsOfResults = array(
                    'post_type' => 'amal',
                    'posts_per_page' => -1,
                    'order' => 'ASC',
                    'author' => $salekID,
                    'meta_key' => 'arbayiin',
                    'meta_query' => array(
                        'key' => 'arbayiin',
                        'compare' => '=',
                        'value' => $dastoor_ID
                    ));

                $results = get_posts($argsOfResults);
                foreach ($results as $result) {
                    $day = get_field('day', $result->ID);                      // get date field of the result
                    $timestamp = getTimestampOfDayField($day);
                    $results = get_field('results', $result->ID);
                    $resultsArr = explode('!@#', $results);
                    unset($resultsArr[sizeof($resultsArr)-1]);
                    testHelper($resultsArr);
                    $resCounter = 0;
                    foreach ($resultsArr as $res) {
//                        insertSingleResultIntoDB($wpdb, $salekID, $dastoor_ID, $amalArr[$resCounter],null, $res, $timestamp);
                        $resCounter++;
                    }
                }
//                testHelper($results);
            }
        }

    }

}

/** get timestamp out of day field
 * @param $day
 * @return false|int|timestamp
 */
function getTimestampOfDayField($day) {

    preg_match('#,\s(.*?)\)#', $day, $match); // get everything between ", " and ")" -> date of result
    $day = $match[1];
    $dayArr = explode('/', $day);                      // explode the result date into array
    return jmktime('8', '0', '0', $dayArr[1], $dayArr[2], $dayArr[0]);  // get timestamp out of date array
}


function getNowTimestamp() {
    $day = jdate('H/i/s/m/d/Y');
    $dayArr = explode('/', $day);                      // explode the result date into array
    return jmktime($dayArr[0], $dayArr[1], $dayArr[2], $dayArr[3], $dayArr[4], $dayArr[5]);  // get timestamp out of date array
}

function insertAllResultsIntoDB($results, $userID, $arbId, $amalID, $resultVal, $resultpoint, $day) {

}


function insertSingleResultIntoDB($wpdb, $dayid, $amalID, $resultVal, $resultpoint) {
    $dbAdded = $wpdb->insert(
                'amal_results',
                array(
                    'dayid' => $dayid,
                    'amalid' => $amalID,
                    'result_matni' => $resultVal,
                    'result_point' => $resultpoint,
                ),
                    array(
                            '%d',
                            '%d',
                            '%s',
                            '%d',
                    )
            );
            return $dbAdded;
}

function insertDayIntoDB($wpdb, $userID, $arbId, $arbrepeat, $date, $submitDate) {
    $dbAdded = $wpdb->insert(
        'result_days',
        array(
            'userid' => $userID,
            'arbid' => $arbId,
            'arbrepeat' => $arbrepeat,
            'date' => $date,
            'submitdate' => $submitDate
        ),
        array(
            '%d',
            '%d',
            '%d',
            '%d',
            '%d',
        )
    );
    return $dbAdded;
}





