<?php
add_action('rest_api_init', 'moraghebehArbayiinTaskRoutes');
function moraghebehArbayiinTaskRoutes() {
    register_rest_route('moraghebeh/v1', 'manageTask', array(
        'methods' => 'POST',
        'callback' => 'createTask'
    ));

    register_rest_route('moraghebeh/v1', 'manageTask', array(
        'methods' => 'DELETE',
        'callback' => 'deleteTask'
    ));

    register_rest_route('moraghebeh/v1', 'manageTask', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'getTask'
    ));
}

function createTask($data) {
    if (is_user_logged_in()) {
        $title = sanitize_text_field($data['title']);
        $content = sanitize_text_field($data['content']);
        $arbayiin = sanitize_text_field($data['arbayiinId']);
        $period = sanitize_text_field($data['period']);



        if (get_post_type($arbayiin) == 'arbayiin') {
            return wp_insert_post(array(
                'post_type' => 'task',
                'post_status' => 'publish',
                'post_title' => $title,
                'post_content' => $content,
                'meta_input' => array(
                    'task_arbayiin_id' => $arbayiin,
                    'period' => $period
                )
            ));
            echo 'eival be in custom rest api';

        } else {
            die("Invalid arbayiin id");
        }

    } else {
        die("Only logged in users can create a like.");
    }
}

function deleteTask($data) {

}

function getTask() {
//    $title = sanitize_text_field($data['title']);
//    $content = sanitize_text_field($data['content']);
//    $arbayiin = sanitize_text_field($data['arbayiinId']);
//    $period = sanitize_text_field($data['period']);

    $tasks = new WP_Query(array(
        'post_type' => 'task',
        'posts_per_page' => -1
    ));
    // get the posts
//    $arbayiin_list = $tasks->get_posts( array( 'type' => 'task' ) );
//    $arbayiin_data = array();
//
//    foreach( $arbayiin_list as $arbayiin) {
//        $arbayiin_id = $arbayiin->ID;
//        $arbayiin_author = $arbayiin->post_author;
//        $arbayiin_title = $arbayiin->post_title;
//        $arbayiin_content = $arbayiin->post_content;
//        if (get_post_meta($arbayiin_id, "task_arbayiin_id")) {
//            $task_arbayiin_id = get_post_meta($arbayiin_id, "task_arbayiin_id");
//            $arbayiin_data[$arbayiin_id]['arbayiin-id'] = $task_arbayiin_id;
//        }
//        $arbayiin_data[ $arbayiin_id ][ 'author' ] = $arbayiin_author;
//        $arbayiin_data[ $arbayiin_id ][ 'title' ] = $arbayiin_title;
//        $arbayiin_data[ $arbayiin_id ][ 'content' ] = $arbayiin_content;
//
//
//    }
	$object =[];
    $task_data = array();
	$counter = 0;
    while($tasks-> have_posts()){
        $tasks->the_post();

        $task_id = get_the_ID();
        $task_title = get_the_title();
        $task_content = get_the_content();
        $task_arbayiin_id = get_post_custom_values('task_arbayiin_id', $task_id);
        $task_period = get_post_custom_values("period", $task_id);
        $task_version = get_post_custom_values("version", $task_id);
		

        $task_data['title'] = $task_title;
        $task_data['content'] = $task_content;
        $task_data['arbayiin-id'] = $task_arbayiin_id[0];
        $task_data['period'] = $task_period[0];
        $task_data['version'] = $task_version[0];
		
		
		$object[$counter] = $task_data;
		$counter++;
		



    }



    return $object;
}



?>