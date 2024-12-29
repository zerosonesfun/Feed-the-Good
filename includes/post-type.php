<?php
/**
 * This file is part of Feed The Good, a WordPress plugin by Billy Wilcosky.
 */

  // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
 
// Feed the good post type
if ( ! function_exists( 'feed_the_good_create_gratitudes' ) ) {
function feed_the_good_create_gratitudes() {
    $labels = array(
        'name' => __('Gratitude Journal', 'feed-the-good'),
        'singular_name' => __('Gratitude', 'feed-the-good'),
        'add_new' => __('Add New', 'feed-the-good'),
        'add_new_item' => __('Add New Gratitude', 'feed-the-good'),
        'edit_item' => __('Edit Gratitude', 'feed-the-good'),
        'new_item' => __('New Gratitude', 'feed-the-good'),
        'view_item' => __('View Gratitude', 'feed-the-good'),
        'search_items' => __('Search Gratitudes', 'feed-the-good'),
        'not_found' => __('No gratitudes found', 'feed-the-good'),
        'not_found_in_trash' => __('No gratitudes found in Trash', 'feed-the-good'),
        'parent_item_colon' => '',
        'menu_name' => __('Gratitude Journal', 'feed-the-good'),
    );
 
    $args = array(
        'labels' => $labels,
        'description' => __('Gratitudes', 'feed-the-good'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
        'slug' => 'gratitude',
        'with_front' => false,
        'feeds' => false,
        'pages' => false,
                       ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail'),
        'taxonomies' => array('mood', 'gratitudes'),
        'exclude_from_search' => false,
        'show_in_rest' => false,
        'show_in_nav_menus' => true,
        'menu_icon' => 'dashicons-smiley',
        'can_export' => true,
        'delete_with_user' => true,
        'show_in_admin_bar' => true,
    );
 
    register_post_type('gratitude', $args);
}
add_action('init', 'feed_the_good_create_gratitudes', 99);
}

function feed_the_good_flush_rewrite_rules() {
    feed_the_good_create_gratitudes();
    flush_rewrite_rules();
}
register_activation_hook( plugin_dir_path( __FILE__ ), 'feed_the_good_flush_rewrite_rules' );

// Feed the good Moods
if ( ! function_exists( 'feed_the_good_create_taxonomies' ) ) {
function feed_the_good_create_taxonomies() {
    $labels = array(
        'name' => __('Moods', 'feed-the-good'),
        'singular_name' => __('Mood', 'feed-the-good'),
        'search_items' => __('Search Moods', 'feed-the-good'),
        'all_items' => __('All Moods', 'feed-the-good'),
        'parent_item' => __('Parent Mood', 'feed-the-good'),
        'parent_item_colon' => __('Parent Mood:', 'feed-the-good'),
        'edit_item' => __('Edit Mood', 'feed-the-good'),
        'update_item' => __('Update Mood', 'feed-the-good'),
        'add_new_item' => __('Add New Mood', 'feed-the-good'),
        'new_item_name' => __('New Mood Name', 'feed-the-good'),
        'menu_name' => __('Moods', 'feed-the-good'),
);

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'mood', 'with_front' => false),
        'show_in_rest' => false,
);

register_taxonomy('mood', array('gratitude'), $args);

// Feed the good Gratitudes (tags)
    $labels = array(
        'name' => __('Gratitudes', 'feed-the-good'),
        'singular_name' => __('Gratitude', 'feed-the-good'),
        'search_items' => __('Search Gratitudes', 'feed-the-good'),
        'all_items' => __('All Gratitudes', 'feed-the-good'),
        'parent_item' => __('Parent Gratitude', 'feed-the-good'),
        'parent_item_colon' => __('Parent Gratitude:', 'feed-the-good'),
        'edit_item' => __('Edit Gratitude', 'feed-the-good'),
        'update_item' => __('Update Gratitude', 'feed-the-good'),
        'add_new_item' => __('Add New Gratitude', 'feed-the-good'),
        'new_item_name' => __('New Gratitude Name', 'feed-the-good'),
        'menu_name' => __('Gratitudes', 'feed-the-good'),
);

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'gratitudes', 'with_front' => false),
        'show_in_rest' => false,
);

register_taxonomy('gratitudes', array('gratitude'), $args);
}
add_action('init', 'feed_the_good_create_taxonomies', 10);
}