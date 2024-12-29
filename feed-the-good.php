<?php
/**
 * Plugin Name: Feed The Good
 * Plugin URI: https://wilcosky.com
 * Description: This WordPress plugin adds a new post type which allows you to quickly and privately keep track of what you're grateful for.
 * Version: 1.8.4
 * Author: Billy Wilcosky
 * Author URI: https://wilcosky.com
 * Text Domain: feed-the-good
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Better safe than sorry
if (!defined('ABSPATH')) { exit; }

// Global define magic time
define( 'FTG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Reset dismissal timestamp on plugin activation
register_activation_hook(__FILE__, 'feed_the_good_reset_on_activation');
function feed_the_good_reset_on_activation() {
    delete_option('feed_the_good_dismissed_timestamp');
}

// Add chart.js for the dashboard widgets
function feed_the_good_enqueue_chart() {
    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '2.9.4', true);
}
add_action('admin_enqueue_scripts', 'feed_the_good_enqueue_chart');

// Include new custom post type
include( FTG_PLUGIN_PATH . 'includes/post-type.php' );

// Include post title as date function
include( FTG_PLUGIN_PATH . 'includes/post-title.php');

// Include auto hashtagging
include( FTG_PLUGIN_PATH . 'includes/hashtags.php');

// Include post saving functions
include( FTG_PLUGIN_PATH . 'includes/save-post.php' );

// Include tags widget
include( FTG_PLUGIN_PATH . 'includes/dashboard/tags-widget.php' );

// Include moods widget
include( FTG_PLUGIN_PATH . 'includes/dashboard/moods-widget.php' );

// Include prompt
include( FTG_PLUGIN_PATH . 'includes/dashboard/prompt.php' );

// Include settings
include( FTG_PLUGIN_PATH . 'includes/dashboard/settings.php' );

// Include help tab content
include( FTG_PLUGIN_PATH . 'includes/help.php');