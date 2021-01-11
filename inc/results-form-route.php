<?php
add_action('rest_api_init', 'resultsFormRoute');
function resultsFormRoute() {

    register_rest_route('moraghebeh/v1', 'manageResultsForm', array(
        'methods' => 'POST',
        'callback' => 'createForm'
    ),true);




    function createForm($data) {
        if (is_user_logged_in()) {
            $halat = sanitize_text_field($data['halat']);
            $vaziyat = sanitize_text_field($data['vaziyat']);
            $khab = ($data['khab']);
            $arbayiinID = ($data['arbayiinid']);



            return wp_insert_post(array(
                'post_type' => 'resultform',
                'post_status' => 'publish',
                'post_title' => get_the_title($arbayiinID),
                'meta_input' => array(
                    'halat' => $halat,
                    'vaziyat' => $vaziyat,
                    'khab' => $khab,
                    'arbayiinid' => $arbayiinID
                )
            ));


        } else {
            die("Only logged in users can create a like.");
        }




    }


}