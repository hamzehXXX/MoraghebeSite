<?php get_header();
//include_once('jdf.php');
while(have_posts()) {
    the_post(); ?>
<a class="page-banner__link" href="<?php echo site_url(); ?>"><div class="page-banner">
        <div class="page-banner__bg-image"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php esc_html(the_title()); ?></h1>
            <div class="page-banner__intro">

            </div>
        </div>
    </div></a>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo site_url('/news'); ?>"><i class="fa fa-home" aria-hidden="true"></i> اطلاعیه ها </a> <span class="metabox__main"><?php echo gregorian_to_jalali(get_the_date('Y'), get_the_date('m'), get_the_date('d'), '/'); ?></span>
        </div>

        <div>

            <?php  the_content(); ?></div>
    <?php
    $relatetArbayiin = get_field('related_arbayiin');
    if ($relatetArbayiin) {
        echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">اربعین های مربوطه</h2>';
        echo '<ul class="link-list min-list">';
        foreach($relatetArbayiin as $arbayiin) { ?>
            <li>
                    <?php echo get_the_title($arbayiin); ?>
            </li>
            <?php

        }
        echo '</ul>';
    }

    ?>
    </div>

<?php }
get_footer();
?>