<?php
add_filter("manage_resultform_posts_columns", "resultform_columns");
function resultform_columns( $columns ) {
    if (get_current_user_id()==1){
        $columns['salek'] = 'سالک';
        $columns['repeat'] = 'تکرار';
    }

    return $columns;
}

add_action("manage_resultform_posts_custom_column", "ressultform_custom_columns");
function ressultform_custom_columns($column) {
    if (get_current_user_id()==1){
        global $post;
        if ($column=='salek'){
            testHelper(get_userdata($post->post_author)->display_name);
        }

        if ($column == 'repeat') {
            echo get_field('repeat', $post->ID);
        }
    }


}