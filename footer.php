<footer class="site-footer">

    <div class="site-footer__inner container container--narrow">

        <div class="group">

            <div class="site-footer__col-one">
                    <h3 class="headline headline--small">صفحات</h3>
                    <nav class="nav-list">
                        <?php
                            wp_nav_menu(array(
                                    'theme_location' => 'footerLocationOne'
                            ));
                        ?>

                    </nav>

            </div>

            <div class="site-footer__col-two-three-group">
<!--                <h1 class="school-logo-text school-logo-text--alt-color"><a href="--><?php //echo site_url();?><!--"><strong>خانه</strong></a></h1>-->
                <nav>
                    <ul class="min-list ">
                        <li>
                            <h3 class="headline headline--small">
                                خروج
                            </h3>
                        </li>
                        <li>
                            <p>
                                اگر با دستگاه شخصی خودتان وارد سایت شده اید نیازی به خروج نیست.
                            </p>
                        </li>
                        <li>
                            <a href="<?php echo wp_logout_url(); ?>" class="btn btn--small btn--dark-orange btn--with-photo"><span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span><span class="btn__text">خروج</span></a>
                        </li>
                    </ul>
                </nav>

            </div>

            <div class="site-footer__col-four">
                <h3 class="headline headline--small">صفحات اینستاگرام</h3>
                <nav>
                    <ul class="min-list social-icons-list group">
                        <li><a href="https://www.instagram.com/markaztohid_ir/" class="social-color-instagram"><i class="fa fa-instagram" aria-hidden="true"> عمومی</i></a></li>
                        <li><a href="https://www.instagram.com/markaztohid_org/" class="social-color-instagram"><i class="fa fa-instagram" aria-hidden="true"> خصوصی</i></a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>
</footer>
<?php wp_footer();  ?>
</body>
</html>