<?php
if (!is_user_logged_in()) {
    wp_redirect(esc_url(site_url('/')));
    exit;
}
get_header();
while(have_posts()) {
    the_post(); ?>


    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro">
                <p></p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">


        <div class="create-note">
            <h2 class="headline headline--medium">ایجاد یادداشت جدید</h2>
           <select class="new-note-title">

                                                    <option>نتیجه تفکر</option>
                                                    <option>رویای صادقه</option>
                                                    <option>مکاشفه</option>
                                                    <option>دریافت باطنی/ وارده قلبی</option>
                                                </select></span>

            <textarea class="new-note-body" placeholder="یادداشت خود را اینجا بنویسید..."></textarea>
            <span class="submit-note">ایجاد یادداشت</span>
        </div>

        <ul class="min-list link-list" id="my-notes">
            <?php
            $userNotes = new WP_Query(array(
                    'post_type' => 'note',
                    'posts_per_page' => -1,
                    'author' => get_current_user_id()
            ));

            while ($userNotes-> have_posts()) {
                $userNotes->the_post(); ?>
                <li data-id="<?php the_ID(); ?>">
                    <input readonly class="note-title-field" value="<?php echo str_replace('خصوصی: ', '', esc_attr(get_the_title())); ?>">
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> ویرایش</span>
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> حذف </span>

                    <textarea readonly class="note-body-field"><?php echo esc_textarea(get_the_content()); ?></textarea>
                    <span class="update-note btn btn--blue btn--small">ذخیره <i class="fa fa-arrow-left" aria-hidden="true"></i></span>
                </li>
                <?php }
            ?>
        </ul>


    </div>



<?php }

get_footer();
?>