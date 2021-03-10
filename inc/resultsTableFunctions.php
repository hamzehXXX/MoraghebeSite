<?php

################################  GET//////////////////////////////////////////////////

/** Gets All Day id, date, submitdate for a single arb for a user
 * @param $userid
 * @param $arbid
 * @param $arbrepeat
 * @return array|object|null    an array of stdClass objects of ID and date and submitdate
 */
function queryAllDaysForArb($userid, $arbid, $arbrepeat) {
    global $wpdb;
    return $wpdb->get_results(
        $wpdb->prepare(
            "SELECT 
                    ID, date, submitdate 
                    FROM 
                    result_days 
                    WHERE 
                        userid = %d AND 
                        arbid = %d AND 
                        arbrepeat = %d",
            $userid,
            $arbid,
            $arbrepeat),
        OBJECT
    );
}

function archiveArbDaysQuery($userid) {
    global $wpdb;
    return $wpdb->get_results(
        $wpdb->prepare(
            "SELECT 
                    ID, arbid, arbrepeat, date, submitdate, COUNT(*) AS count
                    FROM 
                    result_days 
                    WHERE 
                        userid = %d 
                    GROUP BY arbid, arbrepeat",
            $userid),
        OBJECT
    );
}

/** Gets All Day Ids for a single arb for a user
 * @param $wpdb
 * @param $userid
 * @param $arbid
 * @param $arbrepeat
 * @return mixed    an array of stdClass objects of ID
 */
function queryAllDayIDs($wpdb, $userid, $arbid, $arbrepeat) {
    return $wpdb->get_col(
        $wpdb->prepare(
            "SELECT  ID
             FROM    result_days 
             WHERE 
                        userid = %d AND 
                        arbid = %d AND 
                        arbrepeat = %d",
            $userid,
            $arbid,
            $arbrepeat)
    );
}

function queryAllResultIDs($wpdb, $userid, $arbid, $arbrepeat) {
    return $wpdb->get_results(
        $wpdb->prepare(
            "SELECT  dayid, amalid, result_matni, result_point, date, submitdate
             FROM    amal_results r
             INNER JOIN  result_days d
             ON  d.ID = r.dayid
             WHERE 
                        d.userid = %d AND 
                        d.arbid = %d AND 
                        d.arbrepeat = %d
            "
            ,
            $userid,
            $arbid,
            $arbrepeat)
    );
}

/** gets a single day id for an specific timestamp
 * @param $wpdb
 * @param $day
 * @return mixed a single integer
 */
function getDayIdByDateFromDB($wpdb,$userid, $arbid, $arbrepeat){
    return $wpdb->get_var(
        $wpdb->prepare(
            "
            SELECT ID
            FROM result_days
            WHERE userid = %d
             AND  arbid = %d
             AND  arbrepeat = %d
        ",
            $userid,
            $arbid,
            $arbrepeat
        )
    );
}


#################################### DELETE ////////////////////////////////////////

function deleteDaysByUserAndArbIdFromDB($wpdb, $userID, $arbId, $arbRepeat){
    $dbDelete = $wpdb->delete(
        'result_days',
        array(
            'userid' => $userID,
            'arbid' => $arbId,
            'arbrepeat' => $arbRepeat,
        ));
    return $dbDelete;
}

function deleteDaysByUserIdFromDB($wpdb, $userID){
    $dbDelete = $wpdb->delete(
        'result_days',
        array(
            'userid' => $userID,
        ));
    return $dbDelete;
}

function deleteDaysByArbIdFromDB($wpdb, $arbid){
    $dbDelete = $wpdb->delete(
        'result_days',
        array(
            'arbid' => $arbid,
        ));
    return $dbDelete;
}

function deleteAllDays($wpdb){
    $dbDelete = $wpdb->delete(
        'result_days',
        array(
            'arbrepeat' => 1
        ));
    return $dbDelete;
}

function deleteAllResults($wpdb){
    $dbDelete = $wpdb->query(

            "
                    DELETE FROM amal_results
            WHERE result_point IN  (0,1,2,3)
           
        ");
    return $dbDelete;
}


function deleteAllResultsForArb($wpdb, $userid, $arbid, $arbrepeat){
    return $wpdb->get_col(
        $wpdb->prepare(
            "DELETE r, d
             FROM    amal_results r
             INNER JOIN  result_days d
             ON  d.ID = r.dayid
             WHERE 
                        d.userid = %d AND 
                        d.arbid = %d AND 
                        d.arbrepeat = %d",
            $userid,
            $arbid,
            $arbrepeat)
    );
}


function bulkDayInsertion($wpdb) {
    return $wpdb->query(
        "INSERT INTO result_days (userid, arbid, arbrepeat, date, submitdate)
                    VALUES (1, 607, 1, 1614413800, 1615070915), (1, 607, 1, 1614513800, 1615071415)"
    );
}

function bulkResInsertion($wpdb) {
    return $wpdb->query(
        "INSERT INTO amal_results (dayid, amalid, result_matni, result_point)
                    VALUES 
                     (260, 198, NULL, 0), 
                     (260, 199, NULL, 3), 
                     (260, 200, NULL, 2), 
                     (261, 198, 'heyyyyyy', 3), 
                     (261, 199, NULL, 1), 
                     (261, 200, NULL, 3)
                    "
    );
}



function createInsertDayQuery() {
    $allResults = get_posts(array(
        'post_type' => 'amal',
        'posts_per_page' => -1,
    ));
    $dayQuery = "INSERT INTO result_days (ID, userid, arbid, arbrepeat, date, submitdate) VALUES";
    $resQuery = "INSERT INTO amal_results (dayid, amalid, result_matni, result_point) VALUES";
    $dayVals = " ";
    $resVals = " ";
//        echo strtotime($allResults[0]->post_date);
    $arbidArr = array();
    $temp = 1;
    $tempAllRess = 0;
    $returnText = array();
    foreach ($allResults as $result) {
        $resultsArr = array();
        $dayID = $result -> ID;
        $arbId = get_field('arbayiin', $dayID);
        $arbidArr[] = $arbId;
        $amals = get_field('amal', $arbId);

//         POPULATE amal_results VALUES

//        $points = get_field('results', $dayID);
//        $resultsArr = explode('!@#', $points);
//        unset($resultsArr[sizeof($resultsArr)-1]);
////        for ($i = 0; $i <= abs(sizeof($amals)-sizeof($resultsArr)); $i++){
////            array_push($resultsArr, 3);
////        }
//        $amalTermARr = array();
//        if ($amals) {
//            if (sizeof($resultsArr) == sizeof($amals)) {
//                $resTemp = 0;
//                foreach ($amals as $amal) {
//                    $tempAllRess++;
//                    $amalTerm = $amal['amal_term'];
//                    $resType = $amal['result_type'];
//                    $amalid = $amalTerm -> term_id;
////                    $amalTermARr[] = $amalTerm;
////                    if ($resTemp == 300) return $amalTermARr;
////                    $result_matni = "";
//                    $result_point = isset($resultsArr[$resTemp])?$resultsArr[$resTemp]:3;
//                    if ($resType!='متنی') {
//                        $result_matni = "NULL";
//
//                    } else {
//                        if (strlen($result_point) < 3){
//                            $result_matni = "NULL";
//                            $result_point = 0;
//                        } else{
//                            $matni = esc_sql($result_point);
//                            $result_matni = "'$matni'";
//                            $result_point = 3;
//                        }
//
//                    }
//                    $resVals .= "(" . $dayID . ", " . $amalid . ", " . $result_matni . ", " . $result_point . "),";
////                if (($tempAllRess) == 1000) return $resQuery.$resVals;
////                    if ($resTemp < sizeof($amals) - 1) {
////                        $resVals .= ", ";
////                    }
//                    $resTemp++;
//                }
//            }
//            else {
//                $returnText[] = "notEqualSize: ". $arbId;
//            }
//
//
//        } else {
//            $returnText[] = "noAmal";
//        }

        // POPULATE result_days VALUES
        $dayField = get_field('day', $dayID);

//            testHelper($dayField . '- ' . $dayID);
        if (strlen($dayField) > 10) {
            if (strpos($dayField, 'شنبه') !== false) {
                $amalTimeStamp = getTimestampOfDayField($dayField);
            }
            else if (strpos($dayField, 'یکشنبه') !== false) {
                $amalTimeStamp = getTimestampOfDayField($dayField);
            }
            else if (strpos($dayField, 'دوشنبه') !== false) {
                $amalTimeStamp = getTimestampOfDayField($dayField);
            }
            else if (strpos($dayField, 'سه شنبه') !== false) {
                $amalTimeStamp = getTimestampOfDayField($dayField);
            }

            else if (strpos($dayField, 'چهارشنبه') !== false) {
                $amalTimeStamp = getTimestampOfDayField($dayField);
            }
            else if (strpos($dayField, 'پنجشنبه') !== false) {
                $amalTimeStamp = getTimestampOfDayField($dayField);
            }
            else if (strpos($dayField, 'جمعه') !== false) {
                $amalTimeStamp = getTimestampOfDayField($dayField);
            }
            else {
                $amalTimeStamp = 0;
            }
        } else {
            $amalTimeStamp = 0;
        }

//            $amalTimeStamp = strlen($dayField) > 10?getTimestampOfDayField($dayField):0;
        $dayVals .= "(" . $dayID . ', ' . $result->post_author . ', ' . intval(get_field('arbayiin', $dayID)) . ', ' . 1 . ', ' . $amalTimeStamp . ', ' . strtotime($allResults[0]->post_date) . ")";
        if ($temp < sizeof($allResults)){
            $dayVals .= ', ';
        }
        $temp++;
        if ($tempAllRess > 177135)break;
    }

    return $temp;
    return  $returnText;
    return ' count: '  . $tempAllRess;
//    return $resQuery . rtrim($resVals, ", ");
//    return $dayQuery . $dayVals ;
}

function queryDayIdFromAmalDay($wpdb) {
    return $wpdb->get_results(

            "SELECT  userid, arbid, date, ID
             FROM    result_days 
             ",
            OBJECT
    );
}