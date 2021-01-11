<?php
add_action('rest_api_init', 'loginRoute');
function loginRoute()
{

    register_rest_route('moraghebeh/v1', 'login', array(
        'method' => WP_REST_SERVER::READABLE,
        'callback' => 'login'
    ));
}

    function login($data) {
//        echo 'qwrwrwrasdf';
// Run before the headers and cookies are sent.
//        add_action( 'after_setup_theme', 'wpdocs_custom_login' );
        $creds = array(
            'user_login'    => $data['user'],
            'user_password' => $data['pass'],
            'remember'      => true
        );
        $user_data = array();
        $user = wp_signon( $creds, false );

        if ( is_wp_error( $user ) ) {
//            echo $user->get_error_message();
            $user_data['userID'] = 0;
            return $user_data;

        }
//        if ($user) {
//            $id = get_current_user_id();
//        }

        $response = [];

//        $user_info = get_userdata($id);
        $user_data['userID'] =$user->ID;
//        $response[0] = $user_data;

        // CREATE USER BY PHONE AND CODEMELI
//    wp_create_user( 'haj', 'hamzeh' );
//        $response = wp_insert_user(array(
//        'user_login' => 'aasnnfdapp',
//        'user_pass' => 'hamzaeh',
//        'first_name' => 'hadj',
//        'last_name' => 'asd'
//    ));


        return $user_data;


    }



function wpdocs_custom_login() {
    $creds = array(
        'user_login'    => 'ghazinouri',
        'user_password' => '123',
        'remember'      => true
    );

    $user = wp_signon( $creds, false );

    if ( is_wp_error( $user ) ) {
        echo $user->get_error_message();

    }
}