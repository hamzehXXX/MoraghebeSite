<?php


class ResultsTable
{
private $salekID;

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
    public function showResultsTable(){
        echo '<div class="arbayiin-table">'; //block start
        echo '<ul class="min-list" style="display: inline-table" id="results">';
        $taskCount = $this->numbersColumn(); // echo out first column
        $this->namesColumn(); // echo out second column
        $this->resultsColumn($taskCount);
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
        $taskCount = 1;
        while (have_rows('amal')) {
            the_row();
            echo '<div class="table-num ">';
            echo $taskCount;
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
        while (have_rows('amal')) {
            the_row();
            $name = get_sub_field('amal_name');
            $content = get_sub_field('amal_desc');
            ?>
            <div class="resultname amal-js" data-content="<?php echo $content; ?>" data-name="<?php echo $name; ?>" >
            <?php
            echo $name;
            echo '</div>';
        }
        echo ' <div class="resultname" style="background-color:#7ad2ee; color: #FFFFFF"  >جمع امتیازات روز</div>';
        echo '<div class="resultname sabtdate"  style="background-color: #cbcbcb" >تاریخ ثبت</div>';

        echo '</li>'; // end of the second column;
    }

    private function resultsColumn($taskCount){
        $amalResults = $this->getAmalResults();
//        echo '<pre>';
//        print_r($amalResults);
//        echo '</pre>';
        while ($amalResults -> have_posts()) {
            $amalResults -> the_post();
            echo '<li class="row" style="display: table-cell; " >'; // start result column

            #header(day)
            echo '<div class="t-header" >';
            echo get_field('day');
            echo '</div>';
            $results = get_field('results'); // Get results field which is a string separating each result by a ','
            $array = explode('!@#', $results);  // explode result's String into an Array
            $sumDayPoints = 0;  // Initialize the sum of the day's result points
            foreach($array as $item) { // Loop through result's array
                preg_match_all('!\d+!', $item, $matches); // we get only numbers in each result from the array and store them into an array called $matches
                $sumDayPoints += intval(implode(' ', $matches[0]));
                $item = esc_html($item);
                echo "<div class='resultvalue' data-result='$item'>";
                echo $item;
                echo '</div>';
            } // foreach end

            #set color for sum points cell
            $sumPointsColor = $this ->resultsColorGrading($sumDayPoints, $taskCount);
            echo "<div class='resultvalue' style='border:0; background-color:{$sumPointsColor};'>";
            echo $sumDayPoints;
            echo '</div>';

            #insert day of amal submition
            echo ' <div class="resultvalue" style="background-color: #ECECEC; direction: ltr">';
            echo jdate('Y-m-d',strtotime(get_the_date()));
            echo '</div>';

            echo '</li>'; // end result column
        } wp_reset_postdata(); // while loop end
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

    /**
     * Returns WP_Query object
     * @return WP_Query
     */
    private function getAmalResults() {
        return new WP_Query(array(
            'post_type' => 'amal',
            'posts_per_page' => -1,
            'order' => 'ASC',
            'author' => $this->salekID,
            'meta_key' => 'arbayiin',
            'meta_query' => array(
                'key' => 'arbayiin',
                'compare' => '=',
                'value' => get_the_ID()
            )));
    }

    private function popUpPlaceHolder() {
        get_template_part('template-parts/content', 'popup');
    }
}