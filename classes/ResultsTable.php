<?php


class ResultsTable
{
private $salekID;
private $nameArray;
private $amalidArr;
    /**
     * ResultsTable constructor.
     */
    public function __construct($salekID)
    {
        $this->salekID = $salekID;
    }

    /**
     * Method to be called in order to display the ResultsTable
     */
    public function showResultsTable($display, $arbId, $days){
        echo "<div class='arbayiin-table $display'>"; //block start

        echo '<ul class="min-list" style="display: inline-table" id="results">';

        $taskCount = $this->numbersColumn(); // echo out first column
        $this->namesColumn(); // echo out second column
        $this->resultsColumn($taskCount, $arbId, 1, $days);
        echo '</ul></div>'; // block end

        $this->popUpPlaceHolder();
    }

    /**
     * First Column of the Table -- Numbers --
     * displays the number of each amal inside the first column
     */
    private function numbersColumn(){
        echo '<li class=" dd" >'; // start of the first column
        echo '<div class="t-header" >ردیف</div>';
        $taskCount = 0;
        while (have_rows('amal')) {
            the_row();
            echo '<div class="table-num ">';
            echo $taskCount+1;
            $taskCount++;
            echo '</div>';
        }
        echo '</li>'; // end of the first column;

        return $taskCount;
    }

    /**
     * Second Column of the Table -- Names
     * Displays the name of each amal inside the second column
     */
    private function namesColumn(){
        echo '<li class="ddd  " style="display: table-cell; " >';  // start of the second column
        echo '<div class="nameheader" >نام عمل</div>';
        $this->nameArray = array();
        $this->amalidArr = array();
        while (have_rows('amal')) {
            the_row();
//            $name = get_sub_field('amal_name');
            $amalTerm = get_sub_field('amal_term');
            $this->amalidArr[] = $amalTerm->term_id;
            $name = $amalTerm->name;
            $content = get_sub_field('amal_desc');
            $this->nameArray[] = $name;
            // For using HTML tags inside data attribute of amal-js
            // This is because amal_desc is a WYSWYG text editor field in acf
            $needleArr = array('<', '>', '"');
            $replaceArr = array('&lt;', '&gt;', '&quot;');
            $content = str_replace($needleArr, $replaceArr ,$content);
            ?>
            <div class="resultname amal-js" data-content="<?php echo $content; ?>" data-name="<?php echo $name; ?>" >
            <?php
//            echo '<pre>';
//            print_r(get_sub_field_object('amal_name')['ID']);
//            echo '</pre>';
            echo $name;
            echo '</div>';
        }
        echo ' <div class="resultname" style="background-color:#7ad2ee; color: #FFFFFF"  >جمع امتیازات روز</div>';
        echo '<div class="resultname sabtdate"  style="background-color: #cbcbcb" >تاریخ ثبت</div>';

        echo '</li>'; // end of the second column;
    }

    private function resultsColumn($taskCount, $arbID, $arbrepeat, $days){
//            testHelper($days);

        // depict day columns in row
        $dbDate=0;
        $dayCounter = 0;
        $amalidArray = $this->amalidArr;
        foreach ($days as $dayId => $day){
//            echo $day->ID;
            echo '<li class="row" style="display: table-cell;">';
            echo '<div class="t-header" >';
            $ruzNumber = new NumberToWord();
            $ruzNum = $dayCounter<42? CONSTANTS::getDays()[$dayCounter]:$ruzNumber->numberToWords($dayCounter+1);
            $dateTitle = 'روز' . $ruzNum;
            echo $dateTitle;
            echo '(' . jdate('l, Y/m/d', $day['date']) . ')';
            echo '</div>';


//            $resultVals = $GLOBALS['wpdb']->get_results(
//                "SELECT * FROM amal_results WHERE dayid = $day->ID", OBJECT
//            );
            $resultVals = $day['results'];
//            testHelper($resultVals);

            // results of each day column
            $sumDayPoints = 0;  // Initialize the sum of the day's result points
            $amalCounter = 0;

            foreach ($amalidArray as $amalid){
//            foreach ($resultVals as $resVal){
                $point = isset($resultVals[$amalid]['result_point'])?$resultVals[$amalid]['result_point']:0;
                $sumDayPoints += $point;

                $matn = isset($resultVals[$amalid]['result_matni'])?$resultVals[$amalid]['result_matni']:0;
                $myval = $this->getResultString($point);
                $amalName = $this->nameArray[$amalCounter];
                $displayRes = $matn == NULL?$myval[0]:$matn;
                echo "<div class='resultvalue $myval[1]' data-result='$displayRes' data-name='$amalName'>";
                echo $displayRes;
                echo '</div>';
                $amalCounter++;
            }
            #set color for sum points cell
            $sumPointsColor = $this ->resultsColorGrading($sumDayPoints, $taskCount);

            echo "<div class='resultvalue' style='border:0; background-color:{$sumPointsColor};'>";
            echo $sumDayPoints;
            echo '</div>';

            #insert day of amal submition
            echo ' <div class="resultvalue" style="background-color: #ECECEC; direction: ltr">';
            echo jdate('Y/m/d H:i', $day['submitdate']);
            echo '</div>';

            $dayCounter++;
//          echo $this->salekID . ' vs ' . get_current_user_id();
//&& get_current_user_id() == $this->salekID
            if (sizeof($days) == $dayCounter ){
                ?>
                <div class="resultvalue "><i class="fa fa-trash delete-lastday" data-dayid="<?php echo $dayId;?>"
                 data-datetitle="<?php echo $dateTitle;?>" aria-hidden="true"></i></div>
                <?php
            }
            echo '</li>';

        }


    }

    private function dbResultsColumn() {
        $dbResults = $GLOBALS['wpdb']->get_results(
            "SELECT * FROM wp_amalresults WHERE userid = 11 AND arbid = 210 AND arbrepeat = 0", OBJECT
        );

        foreach ($dbResults as $result){
            echo '<li class="row" style="display: table-cell; " >'; // start result column
            #header(day)
            echo '<div class="t-header" >';
            echo jdate('Y/m/d H:i:s', $result->date);
            echo '</div>';
        }



    }

    /**
     * HELPER METHOD FOR COLOR GRADING
     * it has three levels, high->green, mediocore->yellow and low->red
     * @param $sumDayPoints
     * @param $taskCount
     * @return string
     */
    private function resultsColorGrading($sumDayPoints, $taskCount) {
        $maxPoints = $taskCount * 3;
        $highPoints = $maxPoints * 0.7;
        $averagePoints = $maxPoints * 0.5;
        if ($sumDayPoints >= $highPoints) return '#d6ffe7'; //green
        else if ($sumDayPoints >= $averagePoints AND $sumDayPoints < $highPoints) return '#fff9d1';  //yellow
        else  return '#ffdbdb'; //red

    }

    public function getResultString($item) {
        $resultString = '';
        $resultvalue__background = 'resultvalue__green';
        switch ($item){
            case 0:
                $resultString = 'انجام ندادم';
                $resultvalue__background = 'resultvalue__red';
                break;
            case 1:
                $resultString = 'ضعیف';
                $resultvalue__background = 'resultvalue__orange';
                break;
            case 2:
                $resultString = 'متوسط';
                $resultvalue__background = 'resultvalue__yellow';
                break;
            case 3:
                $resultString = 'خوب';
                $resultvalue__background = 'resultvalue__green';
                break;
            case '':
                break;
        }

        return array($resultString, $resultvalue__background);
    }

    /**
     * Returns WP_Query object
     * @return WP_Query
     */
//    private function getAmalResults($amalId) {
//        return new WP_Query(array(
//            'post_type' => 'amal',
//            'posts_per_page' => -1,
//            'order' => 'ASC',
//            'author' => $this->salekID,
//            'meta_key' => 'arbayiin',
//            'meta_query' => array(
//                'key' => 'arbayiin',
//                'compare' => '=',
//                'value' => $amalId
//            )));
//    }

    private function popUpPlaceHolder() {
        get_template_part('template-parts/content', 'popup');
    }

}