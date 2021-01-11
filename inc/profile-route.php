<?php
add_action('rest_api_init', 'profileRoute');
function profileRoute() {
    register_rest_route('moraghebeh/v1', 'manageProfile/(?P<id>[\d]+)', array(
        'methods' => 'POST',
        'callback' => 'updateProfile'
    ), true);

    register_rest_route('moraghebeh/v1', 'manageProfile', array(
        'methods' => 'POST',
        'callback' => 'createProfile'
    ),true);

    register_rest_route('moraghebeh/v1', 'manageProfile', array(
        'methods' => 'DELETE',
        'callback' => 'deleteProfile'
    ));

    register_rest_route('moraghebeh/v1', 'manageProfile', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'getProfile'
    ));
}

function createProfile($data) {

        $name = sanitize_text_field($data['name']);
        $family = sanitize_text_field($data['family']);
        $birth = sanitize_text_field($data['birth']);
        $phone = sanitize_text_field($data['phone']);
        $codemeli = sanitize_text_field($data['codemeli']);
        $marriage = sanitize_text_field($data['marriage']);
        $gender = sanitize_text_field($data['gender']);
        $khadem = sanitize_text_field($data['khadem']);
        $province = sanitize_text_field($data['province']);
        $city = sanitize_text_field($data['city']);
        $email = sanitize_text_field($data['email']);
        $address = sanitize_text_field($data['address']);
        $phonehome = sanitize_text_field($data['phonehome']);
        $userId = sanitize_text_field($data['userid']);

        // CREATE USER BY PHONE AND CODEMELI
//    wp_create_user( $phone, $codemeli );
//    wp_insert_user(array(
//        'user_login' => $phone,
//        'user_pass' => $codemeli,
//        'first_name' => $name,
//        'last_name' => $family
//    ));

//        if (get_post_type($arbayiin) == 'arbayiin') {
        return wp_insert_post(array(
            'post_type' => 'profile',
            'post_status' => 'publish',
            'post_title' => ($name." ".$family),
            'meta_input' => array(
                'name' => $name,
                'family' => $family,
                'birth' => $birth,
                'phone' => $phone,
                'codemeli' => $codemeli,
                'marriage' => $marriage,
                'gender' => $gender,
                'khadem' => $khadem,
                'province' => $province,
                'city' => $city,
                'email' => $email,
                'address' => $address,
                'phonehome' => $phonehome,
                'user_id' => $userId
            )
        ));


//        } else {
//            die("Invalid arbayiin id");
//        }


}

function updateProfile($data) {
    if (is_user_logged_in()) {
        $name = sanitize_text_field($data['name']);
        $family = sanitize_text_field($data['family']);
        $birth = sanitize_text_field($data['birth']);
        $phone = sanitize_text_field($data['phone']);
        $codemeli = sanitize_text_field($data['codemeli']);
        $marriage = sanitize_text_field($data['marriage']);
        $gender = sanitize_text_field($data['gender']);
        $khadem = sanitize_text_field($data['khadem']);
        $province = sanitize_text_field($data['province']);
        $city = sanitize_text_field($data['city']);
        $email = sanitize_text_field($data['email']);
        $address = sanitize_text_field($data['address']);
        $phonehome = sanitize_text_field($data['phonehome']);
        $userId = sanitize_text_field($data['userid']);
        $profileId = sanitize_text_field($data['id']);



//        if (get_post_type($arbayiin) == 'arbayiin') {
        return wp_update_post(array(
            'ID' => $profileId,
            'post_type' => 'profile',
            'post_status' => 'publish',
            'post_title' => ($name." ".$family),
            'meta_input' => array(
                'name' => $name,
                'family' => $family,
                'birth' => $birth,
                'phone' => $phone,
                'codemeli' => $codemeli,
                'marriage' => $marriage,
                'gender' => $gender,
                'khadem' => $khadem,
                'province' => $province,
                'city' => $city,
                'email' => $email,
                'address' => $address,
                'phonehome' => $phonehome,
                'user_id' => $userId
            )
        ));


//        } else {
//            die("Invalid arbayiin id");
//        }

    } else {
        die("Only logged in users can create a like.");
    }
}


function getProfile($data) {
    $userId = get_user_by('login', $data['userName']);
    $profile = new WP_Query(array(
        'post_type' => 'profile',
        'author' => $userId->ID
    ));

    $hasProfile = false;
    while ($profile -> have_posts()){
        $profile -> the_post();
        $hasProfile = true;

    }
//    $profile ->
//    $profile->have_posts();

    return $data['userName'];

}