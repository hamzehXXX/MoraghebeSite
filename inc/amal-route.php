<?php

add_action('rest_api_init', 'amalRoute');
function amalRoute() {

    register_rest_route('moraghebeh/v1', 'createAmal', array(
        'methods' => 'POST',
        'callback' => 'createAmal'
    ),true);

    register_rest_route('moraghebeh/v1', 'manageProfile', array(
        'methods' => 'DELETE',
        'callback' => 'deleteProfile'
    ));

    register_rest_route('moraghebeh/v1', 'manageAmal', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'getAmal'
    ));
    register_rest_route('moraghebeh/v1', 'getResults', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'getResults'
    ));
    register_rest_route('moraghebeh/v1', 'manageStartDate', array(
        'methods' => WP_REST_SERVER::CREATABLE,
        'callback' => 'manageStartDate'
    ));
    register_rest_route('moraghebeh/v1', 'deleteResults', array(
        'methods' => WP_REST_SERVER::DELETABLE,
        'callback' => 'deleteResults'
    ));

    register_rest_route('moraghebeh/v1', 'deleteLastDay', array(
        'methods' => WP_REST_SERVER::DELETABLE,
        'callback' => 'deleteLastDay'
    ));

    function manageStartDate($data) {
//        include(get_stylesheet_directory() . '/jdf.php');
        $optionName = sanitize_text_field($data['optionName']);
        $startDate = sanitize_text_field($data['startDate']);
        $arbid = sanitize_text_field($data['arbid']);
        $optionsStartDate = get_option($optionName);
        $duration = get_post_meta($arbid, 'arbayiin-duration', true);

//        if (strlen($startDate) <=10 AND strlen($startDate > 3)) {
//            $period = new DatePeriod(
//                new DateTime($startDate), // Start date of the period
//                new DateInterval('P1D'), // Define the intervals as Periods of 1 Day
//                $duration - 1 // Apply the interval $duration times on top of the starting date
//            );
//            foreach ($period as $day) {
//                //$days[] = $day -> format('Y/m/d');
//            }
//        }

        $timeArray = explode( '/', $startDate );
        $timeStamp = jmktime(0,0,0,$timeArray[1], $timeArray[2], $timeArray[0]);
        for($i = 0; $i < $duration; $i++) {
            $days[] = jdate('Y/m/d', $timeStamp + $i*86400);
        }

        if (isset($optionsStartDate)){
            update_option($optionName, $startDate);
            update_option($optionName . '-period', $days);
            $output = 'from update: ' . get_option($optionName);
        } else {
            add_option($optionName, $startDate);
            add_option($optionName . '-period', $days);
            $output = 'from add: ' . get_option($optionName);
        }
        return  get_option($optionName . '-period');
    }


    function createAmal($data) {
//        var_dump(1);
//        if (is_user_logged_in()) {
            global $wpdb;
            $day = sanitize_text_field($data['day']);
            $arbayiin = sanitize_text_field($data['arbayiin']);
            $results = sanitize_text_field($data['results']);
            $resultType = ($data['resulttype']);
            $resultsArray = ($data['resultsArray']);
            $arbrepeat = ($data['repeat']);
            $author = ($data['author']);
            // get the user info by author id, to display the first name in the post_title
            $user = get_user_by( 'id', $author );
            $amals = ($data['amals']);
        $returnArray = array();
        $returnString = '';
//        if ($resultsArray AND sizeof($resultsArray) == sizeof($amals)){
        $userid = get_current_user_id();
        insertDayIntoDB($wpdb, $userid, $arbayiin,$arbrepeat, getTimestampOfDayField($day), getNowTimestamp());
        $insertedID = $wpdb->insert_id;
//        $dateID = getDayIdByDateFromDB($wpdb, $userid, $arbayiin, 1);
        $resQuery = "INSERT INTO amal_results (dayid, amalid, result_matni, result_point) VALUES";
            $rowNumber = 0;
            $resVals = " ";
            foreach ($amals as $amalid) {

                $resultPoint = $resultsArray[$rowNumber];
                if ($resultType[$rowNumber] != 'متنی') {
                    $resultMatni = "NULL";
                } else {
                    if (strlen($resultPoint) >= 2){
                        $matni = esc_sql($resultPoint);
                        $resultMatni = "'$matni'";
                        $resultPoint = 3;
                    }
                    else {
                        $resultMatni = "NULL";
                        $resultPoint = 0;
                    }
                }
                $rowNumber++;
                $resVals .= "(" . $insertedID . ', ' . $amalid . ', ' . $resultMatni . ', ' . $resultPoint .  ")";
                if ($rowNumber < sizeof($amals)){
                    $resVals .= ', ';
                }
//                $returnArray[] = insertSingleResultIntoDB($wpdb, $insertedID,$amal, $resultMatni, $resultPoint);

            }

            $resInsertionQuery = $resQuery . $resVals;
        $resultsInsertedCount = $wpdb->query($resInsertionQuery);
//        }
//        wp_insert_post(array(
//            'post_type' => 'amal',
//            'post_status' => 'publish',
//            'post_title' => $user->first_name . ' ' . $day . ' ' . get_the_title($arbayiin),
//            'post_author' => $author,
//            'meta_input' => array(
//                'arbayiin' => $arbayiin,
//                'day' => $day,
//                'results' => $results
//            )
//
//        ));



        return $resultsInsertedCount;

//        insertSingleResultIntoDB($wpdb, $author, $arbayiin, );
//                return wp_insert_post(array(
//                    'post_type' => 'amal',
//                    'post_status' => 'publish',
//                    'post_title' => $user->first_name . ' ' . $day . ' ' . get_the_title($arbayiin),
//                    'post_author' => $author,
//                    'meta_input' => array(
//                        'arbayiin' => $arbayiin,
//                        'day' => $day,
//                        'results' => $results
//                    )
//
//                ));



//        } else {
//            die("Only logged in users can create a like.");
//        }

    }


    function getAmal($request) {
        $response = [];
        $amal_data = array();
        $i = 0;

        $arbayiinId = $request["arbayiinId"];
        $query = new WP_Query( array(
            'post_type' => 'arbayiin',
            'p' => $request["arbayiinId"] ) );

        while ($query -> have_posts()) {
            $query -> the_post();
            $arbayiinName = get_the_title();
            $arbayiinContent = get_the_content();
            $mp3_url = get_field('mp3_url');
            $mp3_duration = get_field('mp3_duration');
            $amal_data['userId'] = get_current_user_id();

            if( have_rows('amal') ){

                while ( have_rows('amal')) {
                    the_row();
//                    $name = get_sub_field('amal_name');
                    $amalTerm = get_sub_field('amal_term');
                    $name = $amalTerm->name;
                    $content = get_sub_field('amal_desc');
                    $repeat = get_sub_field('amal_repeat');
                    $resultType = get_sub_field('result_type');

                    $amal_data['arbayiinName'] = $arbayiinName;
                    $amal_data['arbayiinId'] = $arbayiinId;
                    $amal_data['content'] = $content;
                    $amal_data['arbayiinContent'] = $arbayiinContent;
                    $amal_data['repeat'] = $repeat;
                    $amal_data['weekDay'] = null;
                    $amal_data['mp3_url'] = $mp3_url;
                    $amal_data['mp3_duration'] = $mp3_duration;
                    $amal_data['result_type'] = $resultType;
                    if ($repeat == 'روزانه'){
                    $amal_data['title'] = $name;
                    }
                    else {
                        $specificDay = get_sub_field('specific_day');
                        if ($specificDay) {
                            $weekDay = get_sub_field('weekday');
                            $amal_data['title'] = $name . ' (' . $weekDay . ' ها'. ')';
                            $amal_data['weekDay'] = $weekDay;

                        } else {
                            $amal_data['title'] = $name;
                            }
                    }
                    $response[$i] = $amal_data;
                    $i++;
                }
            } else {
                $amal_data['title'] = "پیدا نشد";
                $amal_data['arbayiinName'] = "یافت نشد";
                $response[$i] = $amal_data;
                $i++;
            }

        }

//        $amalResults = new WP_Query(array(
//            'post_type' => 'amal',
//            'posts_per_page' => -1,
//            'order' => 'ASC',
//            'author' => $request["userId"],
//            'meta_key' => 'arbayiin',
//            'meta_query' => array(
//                'key' => 'arbayiin',
//                'compare' => '=',
//                'value' => $request["arbayiinId"]
//            )));
//        $amalSize = $amalResults -> found_posts;

//        $post = get_post($request['arbayiinId']);
//        while ($amalResults -> have_posts()) {
//            $amalResults -> the_post();
//            $amal_data['title'] = $post->post_title;
//            $response[$i] = $amal_data;
//            $i++;
//        }



        return $response;
    }

    function getResults($request) {

        $response = [];
        $amal_data = array();
        $i = 0;
        $amalResults = new WP_Query(array(
            'post_type' => 'amal',
            'posts_per_page' => -1,
            'order' => 'ASC',
            'author' => $request["userId"],
            'meta_key' => 'arbayiin',
            'meta_query' => array(
                'key' => 'arbayiin',
                'compare' => '=',
                'value' => $request["arbayiinId"]
            )));

        while ($amalResults -> have_posts()) {
            $amalResults -> the_post();
            $amal_data['arbayiinId'] = get_field('arbayiin')."";
            $amal_data['day'] = get_field('day');
            $results = get_field('results');
            $amal_data['results'] = getResultArray($results);
//            $amal_data['id'] = get_the_ID();
//          $amal_data['userId'] = get_field('arbayiin')."";
//            $amal_data['userId'] = get_current_user_id() ."";
            $response[$i] = $amal_data;
            $i++;
        }
//        $amal_data['day'] = "emruuuozzzz";
//        $amal_data['results'] = [2, 1];
//        $response[0] = $amal_data;
//        $amal_data['day'] = "fardaaaaa";
//        $amal_data['results'] = [1, 3];
//        $response[1] = $amal_data;
//        $i++;
        return $response;
    }

    function deleteResults($dayArray) {
        global $wpdb;
//        deleteAllResultsFromDB($wpdb, 1, 607, 1);
//        $amalResults =get_posts(array(
//            'post_type' => 'amal',
//            'posts_per_page' => -1,
//            'author' => get_current_user_id(),
//            'meta_key' => 'arbayiin',
//            'meta_query' => array(
//                'key' => 'arbayiin',
//                'compare' => '=',
//                'value' => $dayArray['arbid']
//            )));

//        $deletedResults = array();

//        foreach ($amalResults as $amalResult) {
//            $deletedResults[] = wp_delete_post($amalResult->ID);
//        }

        $userid = get_current_user_id();
        $arbid = $dayArray['arbid'];
        $arbrepeat = $dayArray['arbrepeat'];
        global $wpdb;
        $deletedResults = deleteAllResultsForArb($wpdb, $userid, $arbid, $arbrepeat);
        $deletedDays = deleteDaysByUserAndArbIdFromDB($wpdb, $userid, $arbid, $arbrepeat);

        return $deletedDays;
    }

    function getResultArray($results) {
        $array = explode(',', $results);
        $result_array = [];
        $j = 0;
        foreach($array as $item) {
            preg_match_all('!\d+!', $item, $matches);
            $result_array[$j] = intval(implode(' ', $matches[0]));
            $j++;

        }
        return $array;
    }


    function deleteLastDay($data) {
        $dayId = $data['dayid'];
        global $wpdb;
        $deletedResults = deleteResultsByDayid($wpdb, $dayId);
        $deletedDay = deleteDayDayid($wpdb, $dayId);

        return $deletedDay . '-' . $deletedResults;
    }

}