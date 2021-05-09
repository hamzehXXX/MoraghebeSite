<?php
if (!is_user_logged_in()) {
    wp_redirect(esc_url(site_url('/vorud')));
    exit;
}
?>
<!DOCTYPE html>
<html dir="rtl" <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <!-- this meta tag is for: responsive css -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    <header class="site-header" >
        <div class="container">
            <h1 class="school-logo-text float-left"><a href="<?php echo site_url(); ?>">صفحه اصلی</a></h1>
            <span class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
            <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
            <div class="site-header__menu group" >
                <nav class="main-navigation"  >

                   <ul>
                       <?php
                       $ourCurrentUser = wp_get_current_user();
                        if (in_array('administrator', $ourCurrentUser->roles) OR
                            in_array('admin', $ourCurrentUser->roles) OR
                            in_array('khadem-mard', $ourCurrentUser->roles) OR
                            in_array('khadem-zan', $ourCurrentUser->roles) OR
                            in_array('admin-mard', $ourCurrentUser->roles) OR
                            in_array('admin-zan', $ourCurrentUser->roles)){ ?>


                            <li <?php if (get_post_type() == 'salek')echo 'class="current-menu-item"'; ?>>
                                <a href="<?php  echo get_post_type_archive_link('salek'); ?>">شاگردان</a>
                            </li>

                        <?php }
//                       if (in_array('reporter', $ourCurrentUser->roles)){ ?>
                           <li <?php if (get_post_type() == 'questioncat')echo 'class="current-menu-item"'; ?>>
                               <a style="color: red" href="<?php echo get_post_type_archive_link('questioncat'); ?>">پرسش و پاسخ</a>
                           </li>
<!--                       --><?php //} ?>

					   <li <?php if (get_post_type() == 'arbayiin')echo 'class="current-menu-item"'; ?>><a href="<?php echo get_post_type_archive_link('arbayiin'); ?>">اربعینیات</a></li>
                        <li <?php if (get_post_type() == 'event' OR is_page('past-events')) echo 'class="current-menu-item"'?>><a href="<?php echo get_post_type_archive_link('event');?>">رویدادها</a></li>
                        <li <?php if (get_post_type() == 'post') echo 'class="current-menu-item"' ?>><a href="<?php echo site_url('/news');?>">اطلاعیه ها</a></li>


                </nav>
                <div class="site-header__util">
                    <?php if (is_user_logged_in()) { ?>
                        <a href="<?php echo esc_url(site_url('/my-notes')); ?>" class="btn btn--small btn--gray float-left push-right">یادداشت های من</a>
                        <a href="<?php echo esc_url(site_url('/profile')); ?>" class="btn btn--small btn--gray float-left push-right"><div>پروفایل <span style="color: #F4D35E;"><?php echo $ourCurrentUser->user_firstname;?></span><span style="color: #F4D35E;"><?php echo ' ' . $ourCurrentUser->user_lastname;?></span></div> </a>
                    <?php } else { ?>
                        <a href="<?php echo wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">ورود</a>
                        <a href="<?php echo esc_url(site_url('/profile')); ?>" class="btn btn--small  btn--dark-orange float-left">ثبت نام</a>
                    <?php } ?>



                </div>
            </div>
        </div>
    </header>
