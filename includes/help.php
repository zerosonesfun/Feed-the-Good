<?php
/**
 * This file is part of Feed The Good, a WordPress plugin by Billy Wilcosky.
 */

  // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Help Tab content
if ( ! function_exists( 'feed_the_good_help_tabs' ) ) {
function feed_the_good_help_tabs() {
    $screen = get_current_screen();
    $screen_ids = array( 'edit-gratitude', 'gratitude' );
    if ( ! in_array( $screen->id, $screen_ids ) ) {
        return;
    }

    $screen->add_help_tab(
        array(
            'id'      => 'ftg_overview',
            'title'   => 'Overview',
            'content' => '<p><strong>Feed The Good</strong> is a basic gratitude journal with a few differences when compared to WordPress\' default posts. Although you can manually change this, gratitude posts default to private. Many times we want to practice gratitude but we don\'t need the whole world to know. Titles default to the current day\'s date. Also, if you #hashtag a word it will become a tag, which are also called Gratitudes. And, you can set up Moods (like Categories) to log how you\'re feeling. Finally, head over to the settings to set up your daily email reminder.</p>'
        )
    );

    $screen->add_help_tab(
        array(
            'id'      => 'ftg_faq',
            'title'   => 'FAQ',
            'content' => '<p><strong>Why should I hashtag (or add Gratitudes/Tags)?</strong></p><p>To keep track of what you are most grateful over time. You might type out that you had fun with your pet today. A tag (or Gratitude) for that entry might be your pet\'s name, or "pets." For example, today\'s gratitude might be: "I\'m grateful for #pets."</p>'
        )
    );

    $screen->add_help_tab(
        array(
            'id'      => 'ftg_support',
            'title'   => 'Support',
            'content' => '<p>I can\'t promise a ton of support, but you can find me at <a href="https://wilcosky.com">wilcosky.com</a>.</p>'
        )
    );

    // Add a sidebar to the help tab
    $screen->set_help_sidebar( '<h3>Feed The Good</h3><p>There are two wolves fighting inside of everyone. One evil, one good. Which one wins? The one you feed. Feed the good.</p>' );
}

add_action( "load-edit.php", 'feed_the_good_help_tabs' );
add_action( "load-post.php", 'feed_the_good_help_tabs' );
}