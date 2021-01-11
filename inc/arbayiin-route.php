<?php
add_action('rest_api_init', 'moraghebehArbayiinRoutes');
function moraghebehArbayiinRoutes() {
    register_rest_route('moraghebeh/v1', 'manageArbayiin', array(
        'methods' => 'POST',
        'callback' => 'createArbayiin'
    ));

    register_rest_route('moraghebeh/v1', 'manageUserArbayiin', array(
        'methods' => 'POST',
        'callback' => 'createUserArbayiin'
    ));

    register_rest_route('moraghebeh/v1', 'manageArbayiin', array(
        'methods' => 'DELETE',
        'callback' => 'deleteArbayiin'
    ));

    register_rest_route('moraghebeh/v1', 'manageArbayiin', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'getArbayiin'
    ));

    register_rest_route('moraghebeh/v1', 'getArbayiinSize', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'getArbayiinSize'
    ));
}
global $sizeOfResponse;

function createArbayiin($data) {
    if (is_user_logged_in()) {
        $title = sanitize_text_field($data['title']);
        $content = sanitize_text_field($data['content']);
        $duration = sanitize_text_field($data['duration']);



//        if (get_post_type($arbayiin) == 'arbayiin') {
            return wp_insert_post(array(
                'post_type' => 'arbayiin',
                'post_status' => 'publish',
                'post_title' => $title,
                'post_content' => $content,
                'meta_input' => array(
                    'arbayiin-duration' => $duration
                )
            ));


//        } else {
//            die("Invalid arbayiin id");
//        }

    } else {
        die("Only logged in users can create a like.");
    }
}
function createUserArbayiin($data) {
    if (!function_exists('write_log')) {

        function write_log($log) {
            if (true === WP_DEBUG) {
                if (is_array($log) || is_object($log)) {
                    error_log(print_r($log, true));
                } else {
                    error_log($log);
                }
            }
        }

    }
//    if (is_user_logged_in()) {
    $userId = sanitize_text_field($data['userId']);
        $title = sanitize_text_field($data['title']);
        $content = sanitize_text_field($data['content']);
        $duration = sanitize_text_field($data['duration']);
        write_log($title. '---'. $content. '---' .$duration . "--". $userId);
        write_log( '---'. '---' );

        $arbayiinId = wp_insert_post(array(
            'post_type' => 'arbayiin',
            'post_status' => 'publish',
            'post_title' => $title,
            'post_content' => $content,
            'meta_input' => array(
                'arbayiin-duration' => $duration
            )
        ));

    $my_post = array(
        'post_type' => 'salek',
        'meta_value' => $userId.''
    );

    $salekPosts = new WP_Query($my_post);
    $count = 0;
    while ($salekPosts -> have_posts()) {
        $salekPosts -> the_post();
        $userPostId = get_the_ID();
        $count++;
    }

    $new_array = array();
    $array = get_post_meta($userPostId,'arbayiin'); //do not put true as third parameter (this would return string and not array)
    write_log(print_r($array,true));
    $arbayiiniat = array();
    $arbayiiniat[0] = array();
    foreach ($array[0] as $val) {
        array_push($arbayiiniat[0], $val);
    }
    array_push($arbayiiniat[0], $arbayiinId);
    write_log(print_r($arbayiiniat,true));
//    array_push($array, $arbayiinId);
//    write_log($arbayiinId . '######'. $array['0']);
    sleep(2);
   update_post_meta($userPostId, 'arbayiin', $arbayiiniat[0]);
//    wp_update_post(array(
//        'ID' => $userPostId,
//        'post_status' => 'publish'
//    ));
    sleep(2);
    wp_publish_post( $userPostId );
   $newarray = get_post_meta($userPostId, 'arbayiin');
    write_log(print_r($newarray,true));


//        if (get_post_type($arbayiin) == 'arbayiin') {
        return $arbayiinId;


//        } else {
//            die("Invalid arbayiin id");
//        }

//    } else {
//        die("Only logged in users can create a like.");
//    }
}

function getArbayiin($request){
    $response = [];
    $arbayiins_data = array();
    $arbayiins = new WP_Query(array(
        'post_type' => 'arbayiin',
        'posts_per_page' => -1
    ));

    $counter = 0;
    while ($arbayiins->have_posts()) {
        $arbayiins->the_post();
        $id = get_the_ID();
        $title = get_the_title();
        $content = get_the_content();
        $duration = get_field('arbayiin-duration');
        $mp3_url = get_field('mp3_url');
        $mp3_duration = get_field('mp3_duration');


        //START >>>>>>********************************       [ ARBAYIINS FROM SALEKIN ]       **********************

        // Get users which their ID is current userID and have current arbayiin in their relation field
        $salekArbayiins = new WP_Query(array(
            'post_type' => 'salek',
            'meta_value' => $request["userId"].'',
            'orderby' => 'modified',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'arbayiin',
                    'compare' => 'LIKE',
                    'value' => '"' . $id . '"'
                )
            )
        ));

        if ($salekArbayiins->found_posts) {
            $arbayiins_data["arbayiinId"] = $id;
            $arbayiins_data["title"] = $title;
            $arbayiins_data["content"] = $content;
            $arbayiins_data["duration"] = $duration;
            $arbayiins_data["mp3_url"] = $mp3_url;
            $arbayiins_data["mp3_duration"] = $mp3_duration;
            $response[$counter] = $arbayiins_data;
            $counter++;
        }

        //####################################################################################### END <<<<<<<<<<<<<<<<<<
        //START >>>>>>********************************       [ ARBAYIINS FROM GROUPS ]       **********************
        $post_objects = get_field('groups');
        if( $post_objects ):
            foreach( $post_objects as $post_object): // variable must be called $post (IMPORTANT)
                // override $post
//                setup_postdata( $post_object );

//                $userss = get_field('userss');
                $userss = get_field('userss', $post_object->ID);

                if( $userss ):

                    foreach( $userss as $user ): 
                        if ($user['ID'] == $request["userId"]):
                            $arbayiins_data["arbayiinId"] = $id;
                            $arbayiins_data["title"] = $title;
                            $arbayiins_data["content"] = $content;
                            $arbayiins_data["duration"] = $duration;
                            $arbayiins_data["mp3_url"] = $mp3_url;
                            $arbayiins_data["mp3_duration"] = $mp3_duration;
                            $response[$counter] = $arbayiins_data;
                            $counter++;
                         endif; 
                     endforeach; 
                     endif; 
                     endforeach;
//            wp_reset_postdata();
            // IMPORTANT - reset the $post object so the rest of the page works correctly
            endif;
    }



//    if ( empty( $arbayiins ) ) {
//        return rest_ensure_response( $arbayiins );
//    }
//
////    foreach ( $arbayiins as $arbayiin ) {
////        $response = prefix_rest_prepare_comment( $arbayiin);
////        $data[] = prefix_prepare_for_collection( $response );
////    }
//    $counter = 0;
//    while ($arbayiins -> have_posts()) {
//        $arbayiins -> the_post();
//
//        $title = $request['ddr']." انجام شد";
//
//        $arbayiins_data['title'] = $title;
//        $response[$counter] = $arbayiins_data;
//        $counter++;
//    }
    // Return all of our comment response data.
    return $response;
}

function getArbayiinSize($userId) {
    return sizeof(getArbayiin($userId));
}