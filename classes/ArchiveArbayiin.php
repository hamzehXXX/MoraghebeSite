<?php


class ArchiveArbayiin
{
    public static function getAmals($salekID, $arbId ) {
                    return get_posts(array(
                        'post_type' => 'amal',
                        'posts_per_page' => -1,
                        'author' => $salekID,
                        'meta_key' => 'arbayiin',
                        'meta_query' => array(
                            'key' => 'arbayiin',
                            'compare' => 'LIKE',
                            'value' => $arbId
                        )
                    ));
                }
}