<?php
add_action('rest_api_init', 'resultsFormAndroidRoute');
function resultsFormAndroidRoute() {

    register_rest_route('moraghebeh/v1', 'resultsAndroidForm', array(
        'methods' => 'POST',
        'callback' => 'createAndroidForm'
    ),true);

register_rest_route('moraghebeh/v1', 'getResultsAndroidForm', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'getAndroidForm'
    ),true);


    function createAndroidForm($data) {
//        if (is_user_logged_in()) {
        $author = ($data['userId']);
        $arbayiinID = ($data['arbayiinId']);
            $halat = sanitize_text_field($data['halat']);
            $vaziyat = sanitize_text_field($data['vaziyat']);
            $khab = sanitize_text_field($data['khab']);





            return wp_insert_post(array(
                'post_type' => 'resultform',
                'post_status' => 'publish',
                'post_title' => get_the_title($arbayiinID),
                'post_author' => $author,
                'meta_input' => array(
                    'halat' => $halat,
                    'vaziyat' => $vaziyat,
                    'khab' => $khab,
                    'arbayiinid' => $arbayiinID
                )
            ));
//        if (!function_exists('write_log')) {
//
//            function write_log($log) {
//                if (true === WP_DEBUG) {
//                    if (is_array($log) || is_object($log)) {
//                        error_log(print_r($log, true));
//                    } else {
//                        error_log($log);
//                    }
//                }
//            }
//
//        }

//        write_log('THIS IS THE START OF MY CUSTOM DEBUG');
//i can log data like objects
//        write_log($data);
//
//        } else {
//            die("Only logged in users can create a like.");
//        }




    }

function getAndroidForm($data) {
    $response = [];
    $results_form = array();
    $id = $data["arbayiinId"];
    $resultform = new WP_Query(array(
        'post_type' => 'resultform',
        'posts_per_page' => -1,
        'author' => $data["userId"],
        'meta_query' => array(
                array(
                    'key' => 'arbayiinid',
                    'compare' => 'LIKE',
                    'value' => '"' . $id . '"'
                )
            )
    ));

    while ($resultform -> have_posts()) {
        $resultform -> the_post();

        $results_form['halat'] = get_field('halat');
        $results_form['vaziyat'] = get_field('vaziyat');
        $results_form['khab'] = get_field('khab');
        $results_form['arbayiinid'] = get_field('arbayiinid');
        $response[0] = $results_form;


    }

    return $response;
}

}