<?php
/**
 * This file is part of Feed The Good, a WordPress plugin by Billy Wilcosky.
 */

  // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Make sure things save and are sanitized
if ( ! function_exists( 'feed_the_good_save_gratitude_meta_box' ) ) {
function feed_the_good_save_gratitude_meta_box($post_id) {
if (!isset($_POST['feed_the_good_gratitude_meta_box_nonce'])) {
return;
}
if (!wp_verify_nonce($_POST['feed_the_good_gratitude_meta_box_nonce'], 'feed_the_good_save_gratitude_meta_box')) {
return;
}
if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
return;
}
if (!current_user_can('edit_post', $post_id)) {
return;
}
if (!isset($_POST['gratitude'])) {
return;
}

$gratitude = sanitize_text_field($_POST['gratitude']);
update_post_meta($post_id, '_gratitude', $gratitude);

$mood = sanitize_text_field($_POST['mood']);
wp_set_object_terms($post_id, $mood, 'mood');

$gratitude = sanitize_text_field($_POST['gratitudes']);
wp_set_object_terms($post_id, $gratitudes, 'gratitudes');
}
add_action('save_post_gratitude', 'feed_the_good_save_gratitude_meta_box');
}

// Force default to private post because we're grateful but not egotistical maniacs
    if ( ! function_exists( 'modify_gratitude_post_status' ) ) {
    add_action('admin_head', 'modify_gratitude_post_status');
    function modify_gratitude_post_status() {
        global $pagenow, $post;

        if ( $pagenow == 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'gratitude' ) {
            ?>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $(window).on('load', function() {
                        // Trigger click on Edit link for post visibility
                        $('.post-visibility.misc-pub-section .edit-visibility').trigger('click');

                        // Set post to Private and delay click on OK button for 2 seconds
                        $('input[name="visibility"][value="private"]').prop('checked', true);
                        setTimeout(function() {
                            $('#post-visibility-select').find('.button').trigger('click');
                        }, 500);
                        setTimeout(function() {
                            $('html, body').animate({ scrollTop: 0 }, 'fast'); 
                        }, 1000);
                    });
                });
            </script>

            <?php
        }
    }
}

if ( ! function_exists( 'feed_the_good_update_gratitude_post_slug_on_update' ) ) {
function feed_the_good_update_gratitude_post_slug_on_update( $post_ID, $post, $update ) {
    // Check if the post type is "gratitude" and if this is not a revision
    if ( $post->post_type === 'gratitude' && ! wp_is_post_revision( $post_ID ) ) {
        // Get the mood term slug (if any)
        $mood_slug = '';
        $mood_terms = wp_get_post_terms( $post_ID, 'mood' );
        if ( ! is_wp_error( $mood_terms ) && ! empty( $mood_terms ) ) {
            $mood_slug = $mood_terms[0]->slug;
        }
        // Generate the new slug with today's date, time, and mood term slug
        $new_slug = date_i18n( 'Y-m-d' );
        if ( ! empty( $mood_slug ) ) {
            $new_slug .= '-' . $mood_slug;
        }
        // Update the post slug
        remove_action( 'save_post', 'feed_the_good_update_gratitude_post_slug_on_update' );
        wp_update_post( array(
            'ID' => $post_ID,
            'post_name' => $new_slug
        ) );
        add_action( 'save_post', 'feed_the_good_update_gratitude_post_slug_on_update', 10, 3 );
    }
}
// Hook the function to the 'save_post' action
add_action( 'save_post', 'feed_the_good_update_gratitude_post_slug_on_update', 10, 3 );
}