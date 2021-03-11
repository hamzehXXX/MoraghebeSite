<?php

require get_theme_file_path('/jdf.php');

require get_theme_file_path('/inc/moraghebehFunctions.php');
require get_theme_file_path('/inc/resultsTableFunctions.php');

require get_theme_file_path('/inc/post-route.php');
require get_theme_file_path('/inc/login-route.php');
require get_theme_file_path('/inc/results-form-route.php');
require get_theme_file_path('/inc/amal-route.php');
require get_theme_file_path('/inc/arbayiin-route.php');
require get_theme_file_path('/inc/task-route.php');
require get_theme_file_path('/inc/rate-route.php');
require get_theme_file_path('/inc/profile-route.php');
require get_theme_file_path('/inc/remove_wordpress_traces.php');
require get_theme_file_path('/inc/results-form-android-route.php');


require get_theme_file_path('/inc/moraghebehHooks.php');
require get_theme_file_path('/GooglesheetTest.php');
//require get_theme_file_path('/vendor/autoload.php');
require get_theme_file_path('/classes/ResultsTable.php');
require get_theme_file_path('/classes/ArchiveArbayiin.php');
require get_theme_file_path('/classes/CONSTANTS.php');



function moraghebeh_files() {
//    wp_enqueue_style('font-awsome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('font-awsomesss', get_theme_file_uri('/css/font-awesome/css/font-awesome.min.css'), NULL, '1.2');
    $styleVersion = '2.5';
    $jQueryVersion = '2.2';
    wp_enqueue_style('moraghebeh_main_styles', get_stylesheet_uri(), NULL, $styleVersion);


//    wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    //microtime() gozashtim beja version number ke harbar load kone
    wp_enqueue_script('jquery_moraghebeh-js', get_theme_file_uri('/js/jquery-3.5.1.min.js'), array('jquery'), '3.5.1', true);
    wp_enqueue_script('persiandatepicker-js', get_theme_file_uri('/js/persianDatepicker.min.js'), NULL, '1.1', true);
    wp_enqueue_script('main_moraghebeh-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, $jQueryVersion, true);
    wp_enqueue_script('prism-js', get_theme_file_uri('/js/prism.js'), NULL, '1.0', true);
//    wp_enqueue_script('main_moraghebeh-jsfd', get_theme_file_uri('/js/jquery-1.10.1.min.js'), NULL, microtime(), true);


//    wp_enqueue_script('sb-js', get_theme_file_uri('/js/slidebars.js'), array('jquery'), microtime(), true);

    wp_localize_script('main_moraghebeh-js', 'moraghebehData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ));
//    wp_localize_script('jqueryui_moraghebeh-js', 'moraghebehData', array(
//        'root_url' => get_site_url(),
//        'nonce' => wp_create_nonce('wp_rest')
//    ));

}


add_action('wp_enqueue_scripts', 'moraghebeh_files');


function moraghebeh_features() {
    // tell wp to generate a unique title for each page
    add_theme_support('title-tag');

    //add a menu location to our theme
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');


}

add_action('after_setup_theme', 'moraghebeh_features');


add_filter( 'manage_edit-salek_sortable_columns', 'smashing_salek_sortable_columns');
function smashing_salek_sortable_columns( $columns ) {
  $columns['khadem'] = 'khademid';
  return $columns;
}

// edit default queries
function moraghebeh_adjust_queries($query) {
    if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
        $query->set('order', 'ASC');
    }

    if (!is_admin() AND is_post_type_archive('arbayiin') AND $query->is_main_query()) {

        $query->set('posts_per_page', -1);
//        $query->set('order', 'ASC');
//        $query->set('orderby', 'meta_value_num');
//        $query->set('meta_key', 'arbayiin-duration');
    }

    if (!is_admin() AND is_post_type_archive('salek') AND $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }

if( ! is_admin() || ! $query->is_main_query() ) {
    return;
  }

  if ( 'khademid' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', 'khademid' );
  }
}
add_action('pre_get_posts', 'moraghebeh_adjust_queries');

// change posts_per_page for arbayiin

function custom_posts_per_page( $query ) {
    if (is_post_type_archive('arbayiin') ) {
        set_query_var('posts_per_page', -1);
    }
}
add_action( 'pre_get_posts', 'custom_posts_per_page' );

// Replace Posts label as Articles in Admin Panel

function change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'اطلاعیه ها';
    $submenu['edit.php'][5][0] = 'اطلاعیه ها';
    $submenu['edit.php'][10][0] = 'افزودن اطلاعیه';
    echo '';
}
function change_post_object_label() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'اطلاعیه ها';
    $labels->singular_name = 'اطلاعیه';
    $labels->add_new = 'افزودن اطلاعیه';
    $labels->add_new_item = 'افزودن اطلاعیه';
    $labels->edit_item = 'ویرایش اطلاعیه';
    $labels->new_item = 'اطلاعیه';
    $labels->view_item = 'مشاهده اطلاعیه';
    $labels->search_items = 'جستجوی اطلاعیه';
    $labels->not_found = 'اطلاعیه ای یافت نشد';
    $labels->not_found_in_trash = 'اطلاعیه ای در زباله دان یافت نشد';
}
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );



// Redirect subscriber acounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontEnd');

function redirectSubsToFrontEnd() {
    $ourCurrentUser = wp_get_current_user();
	$ourCurrentUserRoles = $ourCurrentUser->roles;
	if(!in_array('admin', $ourCurrentUserRoles) AND !in_array('administrator', $ourCurrentUserRoles) ) {
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();

    if (count($ourCurrentUser->roles) == 1 AND (($ourCurrentUser->roles[0] != 'admin') AND ($ourCurrentUser->roles[0] != 'administrator'))) {
        show_admin_bar(false);
    }
}


// CUstomize Login Screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl() {
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS() {
    wp_enqueue_style('moraghebeh_main_styles', get_stylesheet_uri());
//    wp_enqueue_style('custom_google_font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

}

add_filter('login_headertext', 'ourLoginTitle');

function ourLoginTitle() {
    return get_bloginfo('name');
}
// disable email requirement for register new users
remove_filter( 'authenticate', 'wp_authenticate_email_password', 20 );


//function reg_cat() {
//    register_taxonomy_for_object_type('category','arbayiin');
//}
//add_action('init', 'reg_cat');





// This will suppress empty email errors when submitting the user form
add_action('user_profile_update_errors', 'my_user_profile_update_errors', 10, 3 );
function my_user_profile_update_errors($errors, $update, $user) {
    $errors->remove('empty_email');
}

// This will remove javascript required validation for email input
// It will also remove the '(required)' text in the label
// Works for new user, user profile and edit user forms
add_action('user_new_form', 'my_user_new_form', 10, 1);
add_action('show_user_profile', 'my_user_new_form', 10, 1);
add_action('edit_user_profile', 'my_user_new_form', 10, 1);
function my_user_new_form($form_type) {
    ?>
    <script type="text/javascript">
        jQuery('#email').closest('tr').removeClass('form-required').find('.description').remove();
        // Uncheck send new user email option by default
        <?php if (isset($form_type) && $form_type === 'add-new-user') : ?>
        jQuery('#send_user_notification').removeAttr('checked');
        <?php endif; ?>
    </script>
    <?php
}

// Create a specific hook TO REMOVE "ALL" POSTS IN ADMIN PANEL KHADEM FOR KHADEM ROLES
if( is_user_logged_in() ) {
    $user = wp_get_current_user();
    $roles = ( array )$user -> roles;
    if (in_array("khadem-mard", $roles) or in_array("khadem-zan", $roles)) {

        add_filter("views_edit-salek", 'custom_editor_counts', 10, 1);

        function custom_editor_counts($views)
        {
            // var_dump($views) to check other array elements that you can hide.
//            unset($views['all']);
//            unset($views['publish']);
            unset($views['trash']);
            return $views;
        }
    }
}


/* add_filter(
    'posts_results',
    function (array $posts, WP_Query $query) {
        foreach ($posts as $post) {
            if (is_post_type_archive('salek')) {
                $post -> khademid = get_post_meta($post -> ID, 'khademid');
            }
            // and so on …
        }

        return $posts;
    },
    10,
    2
); */

// CHANGE POST_OBJECT custom field orderby to date modified
add_filter( 'acf/fields/post_object/query', 'change_posts_order' );
function change_posts_order( $args ) {
    $args['orderby'] = 'modified';
    $args['order'] = 'DESC';
    return $args;
}


function my_page_columns($columns) {
    $columns = array(
        'cb' => '< input type="checkbox" />',
        'title' => 'نام سالک',
        'salek' => 'سالک',
        'khadem' => 'خادم',
        'arb_after_app' => 'اربعینیات جاری',
        'city' => 'شهر'
    );
    return $columns;
}
function my_custom_columns($column) {
    global $post;
    if($column == 'khadem') {
		$firstName = get_field('khademid', $post->ID)['user_firstname'];
		$lastName = get_field('khademid', $post->ID)['user_lastname'];
		
		?> 
		<a href="<?php echo get_admin_url($post->ID) . 'edit.php?post_type=salek&khademid=' .  get_field('khademid', $post->ID)['ID']; ?>">
		<?php
        echo $firstName . ' ' . $lastName;
		echo '</a>';
    } else {
        echo '';
    }
    if($column == 'city') {
        echo get_field('city', $post->ID);
    } else {
        echo '';
    }

    if($column == 'salek') {
        echo get_field('salekid', $post->ID)['ID'];
    } else {
        echo '';
    }
	
	if($column == 'arb_after_app') {
		/* echo '<pre>';
		print_r(get_field($column, $post->ID));
		echo '</pre>'; */
		$dastoorRows = get_field($column, $post->ID);
	if(!($dastoorRows)) {
		echo '<h3 style="color:#ff0000">فاقد اربعین</h3>';
		return;
	}
		foreach($dastoorRows as $row) {
			echo $row['dastoor_takhsised']->post_title;
			echo '<br/>';
		}
	}
}
add_action("manage_salek_posts_custom_column", "my_custom_columns");
add_filter("manage_salek_posts_columns", "my_page_columns");


function arbayiin_columns($columns) {
    $columns = array(
        'cb' => '< input type="checkbox" />',
        'title' => 'نام دستور',
        'duration' => 'مدت',

    );

    unset( $columns['date']   );

    $columns['cb']     = '< input type="checkbox" />';
    $columns['title']     = 'نام دستور';
    $columns['duration']     = 'مدت';

    return $columns;
}

add_filter("manage_arbayiin_posts_columns", "arbayiin_columns");


function arbayiin_custom_columns($column) {
    global $post;
    if($column == 'duration') {
        echo get_field('arbayiin-duration', $post->ID);
    } else {
        echo '';
    }
}
add_action("manage_arbayiin_posts_custom_column", "arbayiin_custom_columns");

//add_filter("manage_amal_posts_columns", "my_amal_columns");
//add_action("manage_amal_posts_custom_column", "my_amal_custom_columns");


function my_amal_columns($columns) {
    $columns['arbId'] = 'اربعین';
    return $columns;
}

function my_amal_custom_columns($column) {
    global $post;
    if($column == 'arbId') {
        echo get_field('arbayiin', $post->ID);
    } else {
        echo '';
    }
}

// Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate');

function makeNotePrivate($data){
    if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash'){
        $data['post_status'] = "private";
    }
    return $data;
}



function note_custom_columns_list($columns) {
    $columns['author']     = 'نویسنده';
    $columns['title']     = 'نوع یادداشت';
//    $columns['date']     = 'تاریخ';
//    unset( $columns['title']  );

    return $columns;
}
add_filter( 'manage_note_posts_columns', 'note_custom_columns_list' );

function amal_custom_columns_list($columns) {
    $columns['author']     = 'نام سالک';
    // $columns['title']     = 'نوع یادداشت';
//    $columns['date']     = 'تاریخ';
//    unset( $columns['title']  );

    return $columns;
}
add_filter( 'manage_amal_posts_columns', 'amal_custom_columns_list' );





add_filter( 'parse_query', 'wpse45436_posts_filter' );
/**
 * if submitted filter by post meta

 * @return Void
 */
function wpse45436_posts_filter( $query )
{
    global $pagenow;
    $type = 'note'; // change to custom post name.
    if (isset($_GET['note'])) {
        $type = $_GET['note'];
    }
    if ('note' == $type && is_admin() && $pagenow == 'edit.php' && isset($_GET['ADMIN_FILTER_FIELD_VALUE']) && $_GET['ADMIN_FILTER_FIELD_VALUE'] != '') {

//        $query -> query_vars['meta_key'] = 'note-cat'; // change to meta key created by acf.
//        $query -> query_vars['meta_value'] = $_GET['ADMIN_FILTER_FIELD_VALUE'];
    }
}

/**
 * This section makes posts in the admin filterable by the author.
 */
add_action('restrict_manage_posts', 'rose_filter_by_author');
function rose_filter_by_author() {
//    $type = 'note'; // change to custom post name.
    $type = 'good';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }

    //only add filter to post type you want
    if ('note' == $type) {
        $params = array(
            'name' => 'author',
            'show_option_all' => 'همه شاگردان'
        );
        if (isset($_GET['user'])) {
            $params['selected'] = $_GET['user'];
        }
        wp_dropdown_users($params);
    }
}

//add_action('restrict_manage_posts', 'salek_filter_by_khadem');
function salek_filter_by_khadem() {
	if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }
	
	if('salek' == $type) {
		$params = array(
			'name' => 'khademid',
			'show_option_all' => 'همه ی سالکان'
		);
		
		if (isset($_GET['user'])) {
            $params['selected'] = $_GET['user'];
        }
        wp_dropdown_users($params);
	}
}


  
  
  //add_filter( 'parse_query', 'filter_request_query' , 10);
function filter_request_query($query){
    //modify the query only if it admin and main query.
    if( !(is_admin() AND $query->is_main_query()) ){ 
      return $query;
    }
    //we want to modify the query for the targeted custom post and filter option
    if( !('salek' === $query->query['post_type'] AND isset($_GET['khademid']) ) ){
      return $query;
    }
    //for the default value of our filter no modification is required
    if(0 == $_GET['khademid']){
      return $query;
    }
   //modify the query_vars.
    $query->query_vars = array(array(
      'field' => 'khademid',
      'value' => $_GET['khademid'],
      'compare' => '=',
      'type' => 'CHAR'
    ));
    return $query;
  }
  

// Enable font size and font family selects in the editor
if ( ! function_exists( 'am_add_mce_font_buttons' ) ) {
    function am_add_mce_font_buttons( $buttons ) {
        array_unshift( $buttons, 'fontselect' ); // Add Font Select
        array_unshift( $buttons, 'fontsizeselect' ); // Add Font Size Select
        return $buttons;
    }
}
add_filter( 'mce_buttons_2', 'am_add_mce_font_buttons' ); // you can use mce_buttons_2 or mce_buttons_3 to change the rows where the buttons will appear

// Add custom Fonts to the Fonts list
if ( ! function_exists( 'am_add_google_fonts_array_to_tiny_mce' ) ) {
    function am_add_google_fonts_array_to_tiny_mce( $initArray ) {
        $initArray['font_formats'] = 'Lato=Lato;Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
        return $initArray;
    }
}
add_filter( 'tiny_mce_before_init', 'am_add_google_fonts_array_to_tiny_mce' );

// Customize Tiny mce editor font sizes for WordPress
if ( ! function_exists( 'am_tiny_mce_font_size' ) ) {
    function am_tiny_mce_font_size( $initArray ){
        $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";// Add as needed
        return $initArray;
    }
}
add_filter( 'tiny_mce_before_init', 'am_tiny_mce_font_size' );


function wpse_40897_filter_get_editable_roles_for_new_user($editable_roles)
{
    global $pagenow;
    $ourCurrentUser = wp_get_current_user();
    if ('user-new.php' == $pagenow AND $ourCurrentUser->roles[0] != 'administrator') {
        unset($editable_roles['administrator']);
    }
    return $editable_roles;

}

add_filter('editable_roles', 'wpse_40897_filter_get_editable_roles_for_new_user');


register_post_meta(
    'posts',
    'release',
    array(
        'single'       => true,
        'type'         => 'object',
        'show_in_rest' => array(
            'schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'version' => array(
                        'type' => 'string',
                    ),
                    'artist'  => array(
                        'type' => 'string',
                    ),
                ),
            ),
        ),
    )
);
/**
 * Perform automatic login.
 */
//function wpdocs_custom_login() {
//    $creds = array(
//        'user_login'    => 'ghazinouri',
//        'user_password' => '123',
//        'remember'      => true
//    );
//
//    $user = wp_signon( $creds, false );
//
//    if ( is_wp_error( $user ) ) {
//        echo $user->get_error_message();
//    }
//}
//
//// Run before the headers and cookies are sent.
//add_action( 'after_setup_theme', 'wpdocs_custom_login' );

// Register our routes.
function prefix_register_my_comment_route() {
    register_rest_route( 'my-namespace/v1', '/comments', array(
        // Notice how we are registering multiple endpoints the 'schema' equates to an OPTIONS request.
        array(
            'methods'  => 'GET',
            'callback' => 'prefix_get_comment_sample',
        ),
        // Register our schema callback.
        'schema' => 'prefix_get_comment_schema',
    ) );
}
 
add_action( 'rest_api_init', 'prefix_register_my_comment_route' );
 
/**
 * Grabs the five most recent comments and outputs them as a rest response.
 *
 * @param WP_REST_Request $request Current request.
 */
function prefix_get_comment_sample( $request ) {
    $args = array(
        'number' => 5,
    );
    $comments = get_comments( $args );
 
    $data = array();
 
    if ( empty( $comments ) ) {
        return rest_ensure_response( $data );
    }
 
    foreach ( $comments as $comment ) {
        $response = prefix_rest_prepare_comment( $comment, $request );
        $data[] = prefix_prepare_for_collection( $response );
    }
 
    // Return all of our comment response data.
    return rest_ensure_response( $data );
}
 
/**
 * Matches the comment data to the schema we want.
 *
 * @param WP_Comment $comment The comment object whose response is being prepared.
 */
function prefix_rest_prepare_comment( $comment, $request ) {
    $comment_data = array();
 
    $schema = prefix_get_comment_schema();
 
    // We are also renaming the fields to more understandable names.
    if ( isset( $schema['properties']['id'] ) ) {
        $comment_data['id'] = (int) $comment->comment_ID;
    }
 
    if ( isset( $schema['properties']['author'] ) ) {
        $comment_data['author'] = (int) $comment->user_id;
    }
 
    if ( isset( $schema['properties']['content'] ) ) {
        $comment_data['content'] = apply_filters( 'comment_text', $comment->comment_content, $comment );
    }
 
    return rest_ensure_response( $comment_data );
}
 
/**
 * Prepare a response for inserting into a collection of responses.
 *
 * This is copied from WP_REST_Controller class in the WP REST API v2 plugin.
 *
 * @param WP_REST_Response $response Response object.
 * @return array Response data, ready for insertion into collection data.
 */
function prefix_prepare_for_collection( $response ) {
    if ( ! ( $response instanceof WP_REST_Response ) ) {
        return $response;
    }
 
    $data = (array) $response->get_data();
    $server = rest_get_server();
 
    if ( method_exists( $server, 'get_compact_response_links' ) ) {
        $links = call_user_func( array( $server, 'get_compact_response_links' ), $response );
    } else {
        $links = call_user_func( array( $server, 'get_response_links' ), $response );
    }
 
    if ( ! empty( $links ) ) {
        $data['_links'] = $links;
    }
 
    return $data;
}
 
/**
 * Get our sample schema for comments.
 */
function prefix_get_comment_schema() {
    $schema = array(
        // This tells the spec of JSON Schema we are using which is draft 4.
        '$schema'              => 'http://json-schema.org/draft-04/schema#',
        // The title property marks the identity of the resource.
        'title'                => 'comment',
        'type'                 => 'object',
        // In JSON Schema you can specify object properties in the properties attribute.
        'properties'           => array(
            'id' => array(
                'description'  => esc_html__( 'Unique identifier for the object.', 'my-textdomain' ),
                'type'         => 'integer',
                'context'      => array( 'view', 'edit', 'embed' ),
                'readonly'     => true,
            ),
            'author' => array(
                'description'  => esc_html__( 'The id of the user object, if author was a user.', 'my-textdomain' ),
                'type'         => 'integer',
            ),
            'content' => array(
                'description'  => esc_html__( 'The content for the object.', 'my-textdomain' ),
                'type'         => 'string',
            ),
        ),
    );
 
    return $schema;
}
// Add the filter to manage the p tags
//add_filter( 'the_content', 'wti_remove_autop_for_image', 0 );

function wti_remove_autop_for_image( $content )
{

    // Check for single page and image post type and remove
        remove_filter('the_content', 'wpautop');

    return $content;
}

/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function wpexplorer_add_dashboard_widgets() {
    wp_add_dashboard_widget(
        'wpexplorer_dashboard_widget', // Widget slug.
        'My Custom Dashboard Widget', // Title.
        'wpexplorer_dashboard_widget_function' // Display function.
    );
}
//add_action( 'wp_dashboard_setup', 'wpexplorer_add_dashboard_widgets' );

/**
 * Create the function to output the contents of your Dashboard Widget.
 */
function wpexplorer_dashboard_widget_function() {
    echo "Hello there, I'm a great Dashboard Widget. Edit me!";
}


!defined('ABSPATH') AND exit;
/** Plugin Name: (#64933) »kaiser« Add post/page note */

function wpse64933_add_posttype_note()
{
    global $post, $pagenow;

    // Abort in certain conditions, based on the global $pagenow
    if (!in_array(
        $pagenow
        , array(
            'post-new.php',
            'post.php',
            'edit.php'
        )
    ))
        return;

    // Abort in certain conditions, based on the global $post
    if (!in_array(
        $post -> post_type
        , array(
            
            'amal'
        )
    ))
        return;

    // You can use the global $post here
    echo '<p>این صفحه مخصوص استفاده در کد های وب سایت می باشد. درصورت تغییر فیلد های این بخش نمایش نتایج اعمال با مشکل مواجه خواهد شد، لذا تغییر فیلد های این بخش به هیچ عنوان توصیه نمی شود. بهترین استفاده ای که در این بخش برای شما توصیه می شود، حذف کردن نتایج شاگردان در صورت لزوم می باشد. منتها دقّت کافی داشته باشید که نتایج را از آخرین تاریخ ثبت شده حذف کنید. مثلا سالکی 10 روز را ثبت کرده است، و شما می خواهید روز پنجم را حذف کنید. برای این کار لازم است روز دهم، نهم، هشتم، هفتم، ششم، و پنجم را حذف بفرمایید.</p>';
}

add_action('all_admin_notices', 'wpse64933_add_posttype_note');

// extend expiretime of login session
add_filter('auth_cookie_expiration', 'my_expiration_filter', 99, 3);
function my_expiration_filter($seconds, $user_id, $remember){

    //if "remember me" is checked;
    if ( $remember ) {
        //WP defaults to 2 weeks;
        $expiration = 14*24*60*60; //UPDATE HERE;
    } else {
        //WP defaults to 48 hrs/2 days;
        $expiration = 2*24*60*60; //UPDATE HERE;
    }

    //http://en.wikipedia.org/wiki/Year_2038_problem
    if ( PHP_INT_MAX - time() < $expiration ) {
        //Fix to a little bit earlier!
        $expiration =  PHP_INT_MAX - time() - 5;
    }

    return $expiration;
}



// filter
function my_posts_where( $where ) {

    $where = str_replace("meta_key = 'arb_after_app_$", "meta_key LIKE 'arb_after_app_%", $where);

    return $where;
}

add_filter('posts_where', 'my_posts_where');

//add_action( 'save_post', 'set_post_default_category', 10,3 );

function set_post_default_category( $post_id, $post, $update ) {

    // Only set for post_type = salek!
    if ( 'salek' !== $post->post_type ) {
        return;
    }

    $arbsBeforeApp = get_field('arbayiin', $post_id);
    $beforeAppArbIDArr = array();
    foreach ($arbsBeforeApp as $item) {
        $beforeAppArbIDArr[] = $item->ID;
    }
    if( have_rows('arb_after_app', $post_id) ) {
        $i = 0;
        $arbIdArray = array();
        $compeleteMeta = get_complete_meta($post_id,'arb_after_app_%_dastoor_takhsised');
        while( have_rows('arb_after_app', $post_id) ) {
            the_row();
//            $i++;
//            update_sub_field('caption', "This caption is in row {$i}");
            $dastoor_takhsised_obj = get_sub_field('dastoor_takhsised');
            $dastoor_ID = $dastoor_takhsised_obj->ID;
            $salekidField = get_field('salekid');
            $salekID = isset($salekidField) ? $salekidField['ID'] : '0';
            $repeatNum = in_array($dastoor_ID, $arbIdArray)?$dastoor_ID . '-' .array_count_values($arbIdArray)[$dastoor_ID]:$dastoor_ID;
            $metaByValue = get_meta_by_value($post_id, 'arb_after_app_%_dastoor_takhsised', $dastoor_ID);

            $amalsForSalek = get_posts(array(
                'post_type' => 'amal',
                'posts_per_page' => -1,
                'author' => $salekID,
                'meta_query' => array(
                    'key' => 'arbayiin',
                    'compare' => '=',
                    'value' => $dastoor_ID
                )
            ));



//            $repeat = -1;
//            $counter = 1;
//            $duration = intval(get_field('arbayiin-duration', $dastoor_ID));
//            sizeof($amalsForSalek/$duration);

            update_sub_field('arbId', 0, $post_id);
            if (in_array($dastoor_ID, $beforeAppArbIDArr)){

            }
            $arbIdArray[] = $dastoor_ID;
            $i++;
        }
    }


}


function get_complete_meta( $post_id, $meta_key ) {
    global $wpdb;
    $mid = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key LIKE %s", $post_id, $meta_key) );
    if( $mid != '' )
        return $mid;

    return false;
}

/**
 * @param $post_id
 * @param $meta_key  like arb_after_app_%_dastoor_takhsised
 * @param $meta_value   arbayiin id
 * @return array|bool|object|null
 */
function get_meta_by_value( $post_id, $meta_key, $meta_value ) {
    global $wpdb;
    $mid = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key LIKE %s AND meta_value = %d",
        $post_id,
        $meta_key, $meta_value) );
    if( $mid != '' )
        return $mid;

    return false;
}


function change_all_amals_arbayiins() {
    $query = new WP_Query(array(
        'post_type' => 'salek',
        'posts_per_page' => -1,
    ));


    $salekIDArray = array();
    while ($query->have_posts()) {
        $query->the_post();
        if( have_rows('arb_after_app') ):
                    $arbIdArray = array();
                        $i = 0;
//                        $compeleteMeta = get_complete_meta(get_the_ID(),'arb_after_app_%_dastoor_takhsised');

                        while( have_rows('arb_after_app') ) : the_row();

                            // Get parent value.
                            $dastoor_takhsised_obj = get_sub_field('dastoor_takhsised');
                            $dastoor_ID = $dastoor_takhsised_obj->ID;
                            $salekidField = get_field('salekid');
                            $salekID = isset($salekidField) ? $salekidField['ID'] : '0';
                            $arbIdForResults = get_sub_field('repeat');


                            if ($salekID) {

                                $amalsForSalek = get_posts(array(
                                    'post_type' => 'amal',
                                    'posts_per_page' => -1,
                                    'author' => $salekID,
                                    'meta_query' => array(
                                        'key' => 'arbayiin',
                                        'compare' => '=',
                                        'value' => $dastoor_ID
                                    )
                                ));
                                $salekIDArray[] = sizeof($amalsForSalek);

//                                if (!empty($amalsForSalek)) {
//                                update_field('arbayiin', $arbIdForResults, );
                                    foreach ($amalsForSalek as $post) {

                                        setup_postdata( $post );
                                        update_field('arbayiin', $arbIdForResults, get_the_ID());
                                    }
                                    wp_reset_postdata();
//                                }
                            }


//                            update_sub_field('arbId', $dastoor_ID . '-' . $compeleteMeta[$i]->meta_id, get_the_ID());
//                            $i++;

                        endwhile; //have_rows

                    endif; // have_rows

    } wp_reset_postdata();
}
//add_action( 'init', 'change_all_amals_arbayiins' );


function my_acf_update_value( $value, $post_id, $field, $original ) {
    if( is_string($value) ) {

        $value = $field;
    }
//    if ($value != $original) {
//        $value = $value . ' - ' . $original;
//    }
    return $value;
}

// Apply to all fields.
//add_filter('acf/update_value', 'my_acf_update_value', 10, 4);

//if(!is_page(2812)) {
//    // Not logged in, not the login page and not the dashboard
//    exit( wp_redirect( home_url( '/construction' ) ) );
//}

add_filter( 'arbAmal_row_actions', 'wpse31545522_restrict_edit_delete_in_category', 10, 2 );

function wpse31545522_restrict_edit_delete_in_category($actions, $tag) {
//    unset($actions['edit']); // Edit link
//    unset($actions['inline hide-if-no-js']); //Inline Edit link
    unset($actions['delete']); // Delete link

    return $actions;
}

add_action( 'admin_head', function () {
    $current_screen = get_current_screen();

    // Hides the "Move to Trash" link on the post edit page.
//    if ( 'arbAmal' === $current_screen->taxonomy &&
//    'arbayiin' === $current_screen->post_type ) :
//    ?>
<!--        <style>#delete-link { display: none; }</style>-->
<!--    --><?php
//    endif;

    // Hides the "Delete" link on the term edit page.
    if ( 'term' === $current_screen->base &&
    'arbAmal' === $current_screen->taxonomy ) :
    ?>
        <style>#delete-link { display: none; }</style>
    <?php
    endif;
} );


        add_action( 'pre_delete_term', 'restrict_taxonomy_deletion', 10, 2 );
        function restrict_taxonomy_deletion( $term, $taxonomy ) {
            if ( 'arbAmal' === $taxonomy ) {
                wp_die( 'The taxonomy you were trying to delete is protected.' );
            }
        }





