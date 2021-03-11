<?php get_header();
while(have_posts()) {
    the_post(); ?>
<a class="page-banner__link" href="<?php echo site_url(); ?>"><div class="page-banner">
        <div class="page-banner__bg-image"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro">

            </div>
        </div>
    </div></a>



    <div class="generic-content">
        <?php
        // check if profile for current user exists
        $existStatus = 'no';

        if (is_user_logged_in()) {
            $existQuery = new WP_Query(array(
                'post_type' => 'profile',
                'meta_query' => array(
                    array(
                        'key' => 'user_id',
                        'compare' => '=',
                        'value' => get_current_user_id()
                    )
                )
            ));

            if ($existQuery->found_posts) {
                $existStatus = 'yes';

                while ($existQuery->have_posts()) {
                    $existQuery->the_post();

            }
        }
        }
        ?>
        <form aria-disabled="true">

            <ul class="form-style-1" data-exists="<?php if (is_user_logged_in()) echo $existStatus; ?>" data-id="<?php if (is_user_logged_in()) echo $existQuery->posts[0]->ID; ?>" data-userid="<?php if (is_user_logged_in()) echo get_current_user_id(); ?>">
                <li>
                    <span class="edit-profile"><i class="fa fa-pencil" aria-hidden="true"></i> ویرایش</span>
                    <span class="save-profile">ذخیره <i class="fa fa-arrow-left" aria-hidden="true"></i></span>

                </li>
                <li><label>نام و نام خانوادگی <span class="required">*</span></label>
                    <input readonly type="text" name="name" id="name" class="field-divided " placeholder="نام" value="<?php if (is_user_logged_in()) esc_attr(the_field('name')); ?>" />
                    <input readonly type="text" name="family" id="family" class="field-divided " placeholder="نام خانوادگی" value="<?php if (is_user_logged_in()) esc_attr(the_field('family')); ?>" />
                </li>
                <li><label>سال تولد<span class="required">*</span></label>
                    <input readonly type="text" name="birth" id="birth" class="field-divided " placeholder="مثال: 1350" value="<?php the_field('birth'); ?>" />
                </li>
                <li><label>شماره تماس <span class="required">*</span></label>
                    <input readonly type="text" name="phone" id="phone" class="field-divided " placeholder="09XX-XXX-XXXX" value="<?php if (is_user_logged_in()) esc_attr(the_field('phone')); ?>" />
                </li>
                <li>
                    <label>کد ملی <span class="required">*</span></label>
                    <input readonly type="text" name="codemeli" id="codemeli" class="field-divided " placeholder="کد ملی" value="<?php if (is_user_logged_in()) esc_attr(the_field('codemeli')); ?>" />

                </li>
                <li>
                    <?php
                    if (is_user_logged_in()){
                        $marriage = get_field('marriage');
                        $gender = get_field('gender'); }
                    ?>
                    <label>وضعیت تاهل</label>
                    <select disabled name="marriage" class="field-select" id="marriage">
                        <option value="Advertise" <?php if (is_user_logged_in()) if($marriage == 'متاهل') {echo "selected";}?> >متاهل</option>
                        <option value="Partnership" <?php if (is_user_logged_in()) if($marriage == 'مجرد') {echo "selected";}?> >مجرد</option>
                    </select>
                </li>
                <li>
                    <label>جنسیت</label>
                    <select disabled name="gender" class="field-select" id="gender">
                        <option value="Advertise" <?php if (is_user_logged_in()) if ($gender == 'مرد') echo "selected";?> >مرد</option>
                        <option value="Partnership" <?php if (is_user_logged_in()) if ($gender == 'زن') echo "selected";?> >زن</option>
                    </select>
                </li>
                <li>
                    <label>نام خادم <span class="required">*</span></label>

                    <input readonly type="text" name="khadem" class="field-divided" id="khadem" placeholder="نام خادم" value="<?php if (is_user_logged_in()) esc_attr(the_field('khadem')); ?>" />
                </li>
                <li><label>محل سکونت<span class="required">*</span></label>
                    <input readonly type="text" name="province" class="field-divided" id="province" placeholder="استان" value="<?php if (is_user_logged_in()) esc_attr(the_field('province')); ?>" />
                    <input readonly type="text" name="city" class="field-divided" id="city" placeholder="شهر" value="<?php if (is_user_logged_in()) esc_attr(the_field('city')); ?>" />
                </li>

                <li>
                    <label>پست الکترونیکی </label>
                    <input readonly type="email" name="email" id="email" class="field-long" value="<?php if (is_user_logged_in()) esc_attr(the_field('email')); ?>" />
                </li>

                <li>
                    <label>آدرس <span class="required">*</span></label>
                    <textarea readonly name="address" id="address" class="field-long field-textarea" rows="4" cols="10" ><?php if (is_user_logged_in()) esc_attr(the_field('address')); ?></textarea>
                </li>
                <li><label>شماره تلفن سکونت<span class="required">*</span></label>
                    <input readonly type="text" name="phonehome" id="phonehome" class="field-divided " placeholder="021xxxxxxxx" value="<?php if (is_user_logged_in()) esc_attr(the_field('phonehome')); ?>" />
                </li>

                <li>
                    <span class="edit-profile"><i class="fa fa-pencil" aria-hidden="true"></i> ویرایش</span>
                    <span class="save-profile">ذخیره <i class="fa fa-arrow-left" aria-hidden="true"></i></span>
                </li>
            </ul>
        </form>



    </div>

<?php }
get_footer();
?>