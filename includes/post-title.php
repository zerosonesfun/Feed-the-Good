<?php
/**
 * This file is part of Feed The Good, a WordPress plugin by Billy Wilcosky.
 */

 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Below makes the date appear in the editor title
if ( ! function_exists( 'feed_the_good_set_default_title' ) ) {
    function feed_the_good_set_default_title($title, $post) {
        if ($post->post_type == 'gratitude') {
            $title = date_i18n( get_option( 'date_format' ) );
        }
        return $title;
    }
    add_filter('default_title', 'feed_the_good_set_default_title', 10, 2);
}