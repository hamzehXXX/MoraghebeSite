<?php
// get users with specified roles
function getUsersWithRole( $roles ) {
    global $wpdb;
    if ( ! is_array( $roles ) )
        $roles = array_walk( explode( ",", $roles ), 'trim' );
    $sql = '
        SELECT  a.ID
        FROM        ' . $wpdb->users . ' as a INNER JOIN ' . $wpdb->usermeta . ' as b 
        ON          ' . 'a.ID             =       ' . 'b.user_id
        WHERE       ' . 'b.meta_key        =       \'' . $wpdb->prefix . 'capabilities\'
        AND     (
    ';
    $i = 1;
    foreach ( $roles as $role ) {
        $sql .= ' ' . 'b.meta_value    LIKE    \'%"' . $role . '"%\' ';
        if ( $i < count( $roles ) ) $sql .= ' OR ';
        $i++;
    }
    $sql .= ' ) ';
    $sql .= ' ORDER BY display_name ';
    $userIDs = $wpdb->get_results( $sql, ARRAY_N);
    return $userIDs;
}


function getSaleksWithKhadem( $roles, $field, $fieldVal) {
    global $wpdb;
    if ( ! is_array( $roles ) )
        $roles = array_walk( explode( ",", $roles ), 'trim' );
    $sql = '
        SELECT  a.ID, a.display_name, p.post_title
        FROM        ' . $wpdb->users . ' as a INNER JOIN ' . $wpdb->usermeta . ' as b 
        ON          ' . 'a.ID             =       ' . 'b.user_id       INNER JOIN ' . $wpdb->posts . ' as p
         INNER JOIN ' . $wpdb->postmeta . ' as pm ON pm.post_id = p.ID 
        WHERE    b.meta_key        =       \'' . $wpdb->prefix . 'capabilities\'
        AND      pm.meta_key       =       \'' . $field . '\' 
        AND      pm.meta_value     =       a.ID
        AND p.post_type = "salek"  AND     (
    ';
    $i = 1;
    foreach ( $roles as $role ) {
        $sql .= ' ' . 'b.meta_value    LIKE    \'%"' . $role . '"%\' ';
        if ( $i < count( $roles ) ) $sql .= ' OR ';
        $i++;
    }
    $sql .= ' ) ';
    $sql .= ' ORDER BY display_name ';
    $userIDs = $wpdb->get_results( $sql, OBJECT );
    return $userIDs;
}

//add_filter( 'pre_get_posts', 'filterSalekinAdmin' );
function filterSalekinAdmin($query) {
    //GLOBAL VARIABLES
    global $pagenow, $typenow;

    //MY VARIABLES
    global $current_user;
//    get_currentuserinfo();

    //FILTERING
    if (current_user_can('edit_others_posts') && ('edit.php' == $pagenow) &&  $typenow == 'salek' ) {
        global $user_ID;
        $query->set('author', 1);
    }

    if (current_user_can('formchecker2') && ('edit.php' == $pagenow) &&  $typenow == 'salek' ) {
        $query->set('author', 'formusers2');
    }

    if ((current_user_can('formusers1') || current_user_can('formusers2')) && ('edit.php' == $pagenow) &&  $typenow == 'mycustomcpt') {
        $query->set('author', $current_user->ID);
    }
    if ($query->query['post_type'] == 'salek') {
        echo 'heeeeeeeeeeeeeey';
        testHelper($query);
    }


}

