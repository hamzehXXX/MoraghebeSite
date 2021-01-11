<?php
add_action('rest_api_init', 'postRoute');
function postRoute() {
    register_rest_route('moraghebeh/v1', 'managePost',array(
       'method' => WP_REST_Server::READABLE,
       'callback' => 'getPost'
    ));

    function getPost() {
        $response = [];
        $post_data = array();

        $etelayieh = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => -1,
        ));

        $counter = 0;
        while ($etelayieh->have_posts()) {
            $etelayieh->the_post();

            $post_data['title'] = get_the_title();
            $post_data["content"] = get_the_content();
            $response[$counter] = $post_data;
            $counter++;
        }

        return $response;
    }
}