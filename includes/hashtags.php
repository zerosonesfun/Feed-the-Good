<?php
/**
 * This file is part of Feed The Good, a WordPress plugin by Billy Wilcosky.
 */

 // Exit if accessed directly.
 if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Magical hashtagging for gratitude posts
if ( ! function_exists( 'feed_the_good_gratitude_post_convert_hashtags_to_gratitudes' ) ) {
    function feed_the_good_gratitude_post_convert_hashtags_to_gratitudes( $post_id ) {
        $post = get_post( $post_id );
        $post_type = get_post_type( $post_id );
        
        if ( $post_type !== 'gratitude' ) {
            return;
        }
        
        $content = $post->post_content;
        preg_match_all('/#(\p{L}+)/u', $content, $matches);
        $hashtags = array_unique($matches[1]);

        // Retrieve existing terms
        $existing_terms = wp_get_post_terms( $post_id, 'gratitudes', array( 'fields' => 'names' ) );

        // Merge existing and new terms
        $terms_to_add = array_unique( array_merge( $existing_terms, $hashtags ) );

        // Update terms
        wp_set_post_terms( $post_id, $terms_to_add, 'gratitudes' );
    }
    add_action( 'save_post', 'feed_the_good_gratitude_post_convert_hashtags_to_gratitudes' );
    add_filter( 'rest_api_allowed_post_types', function( $allowed_post_types ) {
        $allowed_post_types[] = 'gratitude';
        return $allowed_post_types;
    });
}