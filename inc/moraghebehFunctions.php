<?php

function setStartDate() {
    $optionName = get_current_user_id() . '-' . get_the_ID();
    $startDate = get_option($optionName);
    // display startDate select datepicker if not set yet
//    echo '<form method="post">';
    echo '<label for="startDate" >تاریخ شروع: </label>';
    ?>
    <input class="start-date" type="text" id="startDate" name="drddd" autocomplete="off" value=""
            data-userid = "<?php echo get_current_user_id(); ?>"
            data-arbid = "<?php the_ID(); ?>">
    <?php
    echo '<button name="sbmt" id="submit-date" >انتخاب</button>';
//    echo '</form>';
    echo '<div class="start-date__alert"></div>';
    echo '<script>';
    echo 'jQuery(function () {$("#startDate").persianDatepicker();});';
    echo '</script>';

//    if(isset($_POST['sbmt'])) {
//        $startDate = $startDate = $_POST['drddd'];
//
//        return $startDate;
//    } else {
//        $startDate = 'لطفا حتما تاریخ شروع را مشخص بفرمایید';
//    }


//    echo $startDate;

    if ($startDate == '') {

    }
    return $startDate;

}

function startDateHelper() {}

function insertResults($argsArray) {
    $title = $argsArray['title'];
    $days = $argsArray['days'];
//    echo '<pre>';
//    var_dump($days);
//    echo '</pre>';
    $arbId = $argsArray['ID'];
    $userID = $argsArray['userID'];
    $user = get_user_by( 'id', $userID );
    $startDate = $argsArray['startDate'];
    $amalSize = $argsArray['amalSize'];

    $optionName = get_current_user_id() . '-' . get_the_ID() . '-period';
    $days = get_option($optionName);
    $currentWeekDay = empty($days)?'':getCurrentWeekDay($days[$amalSize]);
    $displayDate = $amalSize == 0?'(' . $currentWeekDay . ', ' .$startDate.')':'(' . $currentWeekDay . ', '.$days[$amalSize].')';
    $rows = $argsArray['rows'];



    ?>
    <table class="amal-table">
        <form method="post">
        <caption>اعمال در انتظار ثبت</caption>
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col" class="current-date"><?php echo 'روز ' . CONSTANTS::getDays()[$amalSize] . $displayDate; ?></th>
        </tr>
        </thead>
        <tbody>
       <?php if($rows): ?>
       <?php $rowNumber = 1; ?>
       <?php  ?>
       <?php foreach($rows as $row ): ?>
               <?php
                    $amalName = $row['amal_name'];
                    $amal_repeat = $row['amal_repeat'];
                    $result_type = $row['result_type'];
                    $content = $row['amal_desc'];
                    $specificDay = $row['specific_day'];
                    $weekday = $row['weekday'];

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
               ?>
        <tr class="zebra <?php echo $dimSelector;?>">
            <th scope="row" rowspan="2"><?php echo $rowNumber; ?></th>
            <td class="amal-js" scope="row" data-content="<?php echo $content; ?>" data-name="<?php echo $amalName; ?>"> <?php echo $amalName; ?><span style=""><?php echo $weeklyString; ?></span><span class="amalDesc" style="color: mediumpurple; float: left; text-align: left !important;">(ت)</span></td>
        </tr>
        <tr class="resInput">
            <td class="selector <?php echo $dimSelector;?>" data-rownumber="<?php echo $rowNumber ?>">

                <?php
                switch ($result_type){
                    case 'متنی':
                        remove_action('insideSlector', 'printRadioButtons');
                        add_action('insideSlector', 'printTextarea', 10, 2);
                        break;
                    default:
                        remove_action('insideSlector', 'printTextarea');
                        add_action('insideSlector', 'printRadioButtons', 10, 2);
                }
                do_action( 'insideSlector', $rowNumber, $disableBox );


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
                <input class="" type="submit" id="submit-amal" name="<?php echo 'submit-amal'; ?>" value="ثبت اعمال" >
            </th>
        </tr>
        </tfoot>
        </form>
    </table>

    <?php
    $resultsArray = '';
    if(isset($_POST['submit-amal'])) {
        echo 'submited!!!';
        for ($i=1; $i<$rowNumber; $i++) {
            $result = $_POST['result-'.$i] == ''?'0':$_POST['result-'.$i];
            $resultsArray .= $result . '!@#';
            echo $_POST['result-'.$i] . ' ___';
        }

        $arbDate = 'روز ' . CONSTANTS::getDays()[$amalSize] . $displayDate;
    wp_insert_post(array(
            'post_type' => 'amal',
            'post_status' => 'publish',
            'post_title' => $user->first_name . ' ' . $arbDate . get_the_title($arbId),
            'post_author' => $userID,
            'meta_input' => array(
                'arbayiin' => $arbId,
                'day' => $arbDate,
                'results' => $resultsArray
            )

        ));

        $_POST['submit-amal'] = null;
        echo "<meta http-equiv='refresh' content='0'>";
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

}

function printRadioButtons($rowNumber, $disableBox) {
?>
    <div dir="rtl">
        <input class="resultInput resultInput-<?php echo $rowNumber;?> " class="radioresult" type="radio" id="<?php echo "nope-" . $rowNumber;?>" name="<?php echo "result-" . $rowNumber; ?>" data-num="<?php echo $rowNumber;?>" value="0" checked <?php echo $disableBox; ?>>
        <label for="<?php echo 'nope-' . $rowNumber;?>">انجام ندادم</label>
    </div>
    <div>
        <input class="resultInput resultInput-<?php echo $rowNumber;?>" type="radio" id="<?php echo 'bad-' . $rowNumber;?>" name="<?php echo 'result-' . $rowNumber; ?>" data-num="<?php echo $rowNumber;?>" value="1" <?php echo $disableBox; ?>>
        <label for="<?php echo 'bad-' . $rowNumber;?>">ضعیف</label>
    </div>
    <div>
        <input class="resultInput resultInput-<?php echo $rowNumber;?>" type="radio" id="<?php echo 'medium-' . $rowNumber;?>" name="<?php echo 'result-' . $rowNumber; ?>" data-num="<?php echo $rowNumber;?>" value="2" <?php echo $disableBox; ?>>
        <label for="<?php echo 'medium-' . $rowNumber;?>">متوسط</label>
    </div>
    <div>
        <input class="resultInput resultInput-<?php echo $rowNumber;?>" type="radio" id="<?php echo 'good-' . $rowNumber;?>" name="<?php echo 'result-' . $rowNumber; ?>" data-num="<?php echo $rowNumber;?>" value="3" <?php echo $disableBox; ?>>
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

