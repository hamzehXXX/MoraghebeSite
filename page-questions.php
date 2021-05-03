<?php
if (!is_user_logged_in()) {
    wp_redirect(esc_url(site_url('/')));
    exit;
}
get_header();
$ourCurrentUser = wp_get_current_user();
$currentUserRoles = $ourCurrentUser -> roles;
$currentUserId = get_current_user_id();
if (!is_user_logged_in() AND
    (!(in_array('administrator', $currentUserRoles)) OR
        !(in_array('admin', $currentUserRoles)) OR
        !(in_array('editor', $currentUserRoles)) OR
        !(in_array('admin-mard', $currentUserRoles)) OR
        !(in_array('admin-zan', $currentUserRoles))
    )
) {
    wp_redirect(esc_url(site_url('/')));
    exit;
}
?>
<a class="page-banner__link" href="<?php echo site_url(); ?>"><div class="page-banner">
        <div class="page-banner__bg-image"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro">
                <p></p>
            </div>
        </div>
    </div></a>

    <div class="container container--narrow page-section">
        <?php the_content();
        echo do_shortcode ( '[bartag foo="bar"]' );
        echo do_shortcode ( '[my_ad_code]' );
//        if (comments_open()){
//            comments_template();
//        }
        ?>
    </div>
<?php
get_footer();
