<?php
add_action('rest_api_init', 'moraghebehArbayiinRateRoutes');

function moraghebehArbayiinRateRoutes() {
    register_rest_route('moraghebeh/v1', 'manageRate', array(
        'methods' => 'POST',
        'callback' => 'createRate'
    ));

    register_rest_route('moraghebeh/v1', 'manageRate', array(
        'methods' => 'DELETE',
        'callback' => 'deleteRate'
    ));

    register_rest_route('moraghebeh/v1', 'manageRate', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'getRate'
    ));
}

function createRate($data) {
    $author = sanitize_text_field($data['author']);
    $arbayiin = sanitize_text_field($data['arbayiin']);
    $task = sanitize_text_field($data['task']);
    $day = sanitize_text_field($data['day']);

    $rate_data = array();


    return wp_insert_post(array(
        'post_type' => 'rate',
        'post_status' => 'publish',
        'post_title' => '',
        'meta_input' => array(
            'author' => $author,
            'arbayiin' => $arbayiin,
            'task' => $task,
            'day' => $day
        )
    ));
}