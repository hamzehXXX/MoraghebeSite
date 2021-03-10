<?php



add_action('after_some-page_wrapper', 'insertResults');

add_filter( 'arbayiin_startDate', 'setStartDate');

add_action('admin_init', 'redirectToUnderConstruction');
add_action('wp', 'redirectToUnderConstruction');

function redirectToUnderConstruction() {

    if(get_current_user_id() !=1 && !is_page('construction')) {
        // Not logged in, not the login page and not the dashboard
        exit( wp_redirect( home_url( '/construction' ) ) );
    }
}