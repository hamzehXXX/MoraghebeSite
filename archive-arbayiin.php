<?php get_header();
//include_once('jdf.php');
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">اربعینیات</h1>
            <div class="page-banner__intro">
                <p>لیست همه ی اربعینیات</p>
            </div>
        </div>
    </div>
    <div class="container container--narrow page-section">
        <div class="generic-content">

            <?php
            //_____________________ START TEST____________
            $start = microtime(true);
//            convertAllResults();
//            echo microtime(true) - $start;


            //_____________________ END TEST  ____________
            ?>

            <h4 class="headline--post-title">اربعین های جاری من</h4>
            <ul class="" id="my-arbayiin">
            <?php //################################################### NEW ARBAYIINS################################
            //########################################## salekin pas az application
            // args
//            $amalArray = array();
//            while (have_posts()){
//                the_post();
//
//                $rowNumber = 1;
//                while (have_rows('amal')){
//                    the_row('amal');
//                    $amal = get_sub_field('amal_name');
//                    $description = get_sub_field('amal_desc');
//                    $catID = getOrCreateCatId($amal, 'arbAmal', $description);
//                    update_sub_field(array('amal', $rowNumber, 'amal_term'), $catID, get_the_ID());
////                    $amalArray = populateAmalArray($amal, $amalArray);
//
//                    $rowNumber++;
//                }
//
//
//            }wp_reset_postdata();
//            showAmalsAndArbs($amalArray);

            $myargs = array(
                'numberposts'	=> -1,
                'posts_per_page'	=> -1,
                'post_type'		=> 'salek',
				 'meta_query' => array(
                array(
                    'key' => 'salekid',
                    'compare' => '=',
                    'value' => get_current_user_id()
                )
            )
            );

            $dayObjects = archiveArbDaysQuery(get_current_user_id());
//            testHelper($dayObjects);
            foreach ($dayObjects as $count) {
                $arbid = $count->arbid;
                $repeatNum =$count->arbrepeat;
                $date = $count->date;
                $dayCountArb[$arbid.'-'.$repeatNum]['count'] = $count->count;
                $dayCountArb[$arbid.'-'.$repeatNum]['date'] = $count->date;
//                $dayCountArb['date'] =
                $newLink = esc_url( add_query_arg( 'arbrepeat', $repeatNum, get_permalink($arbid) ) );


//                $dastoor_title =  get_the_title($arbid) . ' - ' . $repeatNum
//                . ' -> ' . $count->count;
//
//                echo "<a href='$newLink'> <strong>($dastoor_title)  .  ' ' . $repeatNum </strong></a>";
//                echo '<br/>';
            }
//            testHelper($dayCountArb);
//            $sampleTimestamp = jmktime(jdate('H'), jdate('i'), jdate('s'), jdate('m'), jdate('d'), jdate('Y'), '', 'Asia/Tehran');

			$post = get_posts($myargs);

			$post = $post[0];  // get_posts in $posts gets an array with only one element in this case which is the current salek post
			if ($post) {

				setup_postdata( $post);

				$currentUserId = get_the_ID();
                $arbsBeforeApp = get_field('arbayiin');
//                testHelper($arbsBeforeApp);
//				echo '<pre>';
//				print_r($arbsBeforeApp);
//				print_r(get_field('arb_after_app')[0]['dastoor_takhsised']);
//				echo '</pre>';
//                function get_complete_meta( $post_id, $meta_key ) {
//                    global $wpdb;
//                    $mid = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key LIKE %s", $post_id, $meta_key) );
//                    if( $mid != '' )
//                        return $mid;
//
//                    return false;
//                }
				if( have_rows('arb_after_app') ):
                    $arbIdArray = array();
				$arbRepeatCount = 1;
                    $compeleteMeta = get_complete_meta($currentUserId,'arb_after_app_%_dastoor_takhsised');
//                    echo '<pre>';
//                    print_r(get_field_objects($currentUserId)['arb_after_app']['value']);
//                print_r($compeleteMeta);
//                    echo '</pre>';
                    $salekidField = get_field('salekid');
                    $salekID = isset($salekidField) ? $salekidField['ID'] : '0';
                        $rowNum = 1;
                        $finishedArbsArr = array();
                    $finishedArbsCounter = 0;
                        while( have_rows('arb_after_app') ) : the_row();

//                        print_r(get_field('salekid'));
                            // Get parent value.
                            $dastoor_takhsised_obj = get_sub_field('dastoor_takhsised');
                            $dastoor_title = $dastoor_takhsised_obj->post_title;

                            $dastoor_link = $dastoor_takhsised_obj->guid;
                            $dastoor_ID = $dastoor_takhsised_obj->ID;
                            $arbrepeat = get_sub_field('repeat');
                            $amalsForSalek = ArchiveArbayiin::getAmals($salekID, $dastoor_ID);
                            $amalsSize = sizeof($amalsForSalek);
                            $arbDuration = intval(get_field('arbayiin-duration', $dastoor_ID));

//                            if ($amalsSize == $arbDuration) {
////                                $arbsBeforeApp[] = $dastoor_takhsised_obj;
////                                update_field('arbayiin', $arbsBeforeApp);
//                                delete_row('arb_after_app', $rowNum, get_the_ID());
//                                continue;
//                            }
//                            echo '<pre>';
//                                var_dump(intval(sizeof($amalsForSalek)/$arbDuration));
//                            echo '</pre>';

                            if ($dastoor_takhsised_obj):
//                                if (in_array($dastoor_ID, $arbIdArray)) {
//                                    delete_row('arb_after_app', $rowNum, get_the_ID());
//                                }
                                $repeatNum = in_array($dastoor_ID, $arbIdArray)?' (' . 'تکرار ' . array_count_values($arbIdArray)[$dastoor_ID]. ')':'';
//                            echo get_permalink($dastoor_ID);
                            $newLink = esc_url( add_query_arg( 'arbrepeat', $arbrepeat, get_permalink( $dastoor_ID ) ) );
//                            echo '<br/>' . $newLink;
                            $submitedDayCount = isset($dayCountArb[$dastoor_ID.'-'.$arbrepeat]['count'])?$dayCountArb[$dastoor_ID.'-'.$arbrepeat]['count']:0;
                            $submitedDaydate = isset($dayCountArb[$dastoor_ID.'-'.$arbrepeat]['date'])?$dayCountArb[$dastoor_ID.'-'.$arbrepeat]['date']:0;
                            if ($submitedDayCount < $arbDuration) {
                                ?>
                                <li class="arbayiin-title arbayiin-title__current" data-id="<?php echo $dastoor_ID; ?>">
                                    <a class="" href="<?php echo $newLink; ?>">
                                        <strong><?php echo esc_html($dastoor_title) . $repeatNum . ' ' . $arbrepeat; ?></strong></a>
                                    <div class="arbayiin-items">
                                        <?php
                                        if (!($submitedDayCount)) {
                                            echo 'هنوز اربعین را آغاز نکرده اید';
                                        } else {
//                                                var_dump($dayCountArb);
//                                                var_dump($dastoor_ID);
//                                                var_dump($arbrepeat);
//                                                echo $submitedDayCount;
                                            echo 'روز ' . CONSTANTS ::getDays()[$submitedDayCount] . ' | ' . jdate('l, Y/m/d', $submitedDaydate + (86400 * $submitedDayCount));
                                        }
                                        ?>
                                    </div>
                                </li>
                                <br/>

                                <?php
                            } else {
                                $finishedArbsArr[$finishedArbsCounter]['link'] = $newLink;
                                $finishedArbsArr[$finishedArbsCounter]['id'] = $dastoor_ID;
//                                $finishedArbsArr[$finishedArbsCounter]['repeat'] = $dastoor_ID;
                                $finishedArbsArr[$finishedArbsCounter]['title'] = $arbrepeat>1?$dastoor_title . '(' . ' تکرار' . $arbrepeat . ')':$dastoor_title;
                                $finishedArbsCounter++;
                            }
                            endif; // $dastoor_takhsised_obj ?>
                        <?php
                            $arbIdArray[] = $dastoor_ID;
                            $rowNum++;
                        endwhile; //have_rows
                    endif; // have_rows
//                var_dump($finishedArbsArr);
//                print_r(array_count_values($arbIdArray));
                echo '<br/>';
                echo '<hr/>';
                echo '<br/>';
                echo '<h4 class="headline--post-title">'.'اربعینیات قبلی: '.'</h4>';
                $counter = 0;

            ?>
                <?php

                for ($i = 0; $i < $finishedArbsCounter; $i++) {
                    ?>
                    <li class="arbayiin-title arbayiin-title__finished" data-id="<?php echo $finishedArbsArr[$i]['id']; ?>">
                    <a class="" href="<?php echo $finishedArbsArr[$i]['link']; ?>">
                        <strong><?php echo $finishedArbsArr[$i]['title']; ?></strong></a>
                    </li>
                    <br/>
                    <?php
                }
                foreach ($arbsBeforeApp as $item) {

                        $permaLink = esc_url( add_query_arg( 'arbrepeat', $item->ID, get_permalink( $item->ID ) ) );
                        $duration = get_field('arbayiin-duration', $item->ID);
                        $amalsOfSabegh = ArchiveArbayiin::getAmals($salekID, $item->ID);

                        ?>
                    <li class="arbayiin-title_sabegh" data-id="<?php echo $item -> ID; ?>">
                        <strong><?php echo $item -> post_title; ?></strong></a>
                    </li>
                <?php
//                    echo sizeof($amalsOfSabegh) . ' vs ' . '<br/>' . $duration;
                            ?>
<!--                            <li class="arbayiin-title_sabegh" data-id="--><?php //echo $item -> ID; ?><!--">-->
<!--                                <a class="" href="--><?php //echo $permaLink; ?><!--"> <strong>--><?php //echo $item -> post_title; ?><!--</strong></a>-->
<!--                            </li>-->

                            <?php
                        $counter++;

                        echo '<br/>';
                }
			}
            ?>
            </ul>	

			<?php wp_reset_postdata(); 


			?>


<?php
echo paginate_links();
?>

    </div>
    </div>
<?php get_footer();
?>