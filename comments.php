<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since 1.0.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
//if ( post_password_required() ) {
//    return;
//}
$currentUserId = get_current_user_id();

$comment_array = get_approved_comments(get_the_ID());
//testHelper($comment_array);
$moraghebehtheme_comment_count = 0;
$comments = array();
foreach ($comment_array as $comment){
//    testHelper($comment);
    if ($comment->user_id == $currentUserId || get_comment($comment->comment_parent)->user_id == $currentUserId){
        $comments[] = $comment;
        $moraghebehtheme_comment_count++;
    }
}

?>

<div id="comments" class="comments-area default-max-width <?php echo get_option( 'show_avatars' ) ? 'show-avatars' : ''; ?>">

    <?php
    if ( have_comments() ) :
        ;
        ?>
        <h2 class="comments-title">
            <?php if ( '1' === $moraghebehtheme_comment_count ) : ?>
                <?php esc_html_e( '1 comment', 'twentytwentyone' ); ?>
            <?php else : ?>
                <?php
                printf(
                /* translators: %s: comment count number. */
                    esc_html( _nx( '%s comment', '%s comments', $moraghebehtheme_comment_count, 'Comments title', 'twentytwentyone' ) ),
                    esc_html( number_format_i18n( $moraghebehtheme_comment_count ) )
                );
                ?>
            <?php endif; ?>
        </h2><!-- .comments-title -->

        <ol class="comment-list">
            <?php

            wp_list_comments(
                array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'max_depth' => 2,
                    'callback' => 'moraghebehtheme_comment'
                ),
                $comments
            );
            ?>
        </ol><!-- .comment-list -->

        <?php
        the_comments_pagination(
//            array(
//                /* translators: There is a space after page. */
//                'before_page_number' => esc_html__( 'Page ', 'twentytwentyone' ),
//                'mid_size'           => 0,
//                'prev_text'          => sprintf(
//                    '%s <span class="nav-prev-text">%s</span>',
//                    is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' ),
//                    esc_html__( 'Older comments', 'twentytwentyone' )
//                ),
//                'next_text'          => sprintf(
//                    '<span class="nav-next-text">%s</span> %s',
//                    esc_html__( 'Newer comments', 'twentytwentyone' ),
//                    is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' )
//                ),
//            )
        );
        ?>

        <?php if ( ! comments_open() ) : ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'twentytwentyone' ); ?></p>
    <?php endif; ?>
    <?php endif; ?>

    <?php
    comment_form(
        array(
            'logged_in_as'       => null,
            'title_reply'        => esc_html__( 'سوال خود را مطرح کنید', 'twentytwentyone' ),
            'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
            'title_reply_after'  => '</h2>',
            'comment_notes_before' => 'ثب',
            'comment_field' => sprintf(
                '<p class="comment-form-comment">%s %s</p>',
                sprintf(
                    '<label for="comment">%s</label>',
                    _x( '', 'noun' )
                ),
                '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required" style="max-width:-webkit-fill-available;"></textarea>'
            ),
            'submit_button' => '<input style="border: 0px; background-color: #ef9912; border-color: #ef9912; color: #ffffff;     padding: .6180469716em 1.41575em; text-decoration: none; font-weight: 600; text-shadow: none; display: inline-block; -webkit-appearance: none;
    word-break: break-all; " name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
            'label_submit' => 'ارسال سـوال'
        )
    );
    ?>

</div><!-- #comments -->
