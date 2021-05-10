<?php
add_action( 'publish_post', 'cacheFrontPagePosts', 10, 2 );
function cacheFrontPagePosts($ID, $post ) {
	if ( 'post' == $post->post_type ) {
		delete_transient( 'recent_posts' );
	}

}

add_action('publish_event', 'cacheFrontPageEvents', 10, 2);

function cacheFrontPageEvents($id, $post) {
	delete_transient( 'recent_events' );
}
