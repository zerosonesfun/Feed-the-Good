<?php
/**
 * This file is part of Feed The Good, a WordPress plugin by Billy Wilcosky.
 */

// Add settings page to Gratitude Journal admin menu
if (!function_exists('feed_the_good_add_gratitude_journal_settings_page')) {
    function feed_the_good_add_gratitude_journal_settings_page() {
        add_submenu_page(
            'edit.php?post_type=gratitude',
            'Gratitude Journal Settings',
            'Settings',
            'manage_options',
            'gratitude-journal-settings',
            'gratitude_journal_settings_page'
        );
    }
    add_action('admin_menu', 'feed_the_good_add_gratitude_journal_settings_page');
}

// Display settings page content
function gratitude_journal_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['gratitude_journal_settings'])) {
        update_option('gratitude_journal_reminder_enabled', isset($_POST['gratitude_journal_reminder_enabled']));
        update_option('gratitude_journal_reminder_time', sanitize_text_field($_POST['gratitude_journal_reminder_time']));
        update_option('gratitude_journal_reminder_timezone', sanitize_text_field($_POST['gratitude_journal_reminder_timezone']));
        update_option('gratitude_journal_reminder_message', sanitize_textarea_field(stripslashes($_POST['gratitude_journal_reminder_message'])));
        add_settings_error('gratitude_journal_settings', 'settings_saved', 'Settings saved.', 'updated');
    }

    $reminder_enabled = get_option('gratitude_journal_reminder_enabled', false);
    $reminder_time = get_option('gratitude_journal_reminder_time', '12:00');
    $reminder_timezone = get_option('gratitude_journal_reminder_timezone', 'UTC');
    $reminder_message = get_option('gratitude_journal_reminder_message', "Hello, It's time to write in your gratitude journal!");

    $timezones = timezone_identifiers_list();
    ?>
    <div class="wrap">
        <h1>Gratitude Journal Settings</h1>
        <?php 
        // Get timestamp of next scheduled event
        $next_timestamp = wp_next_scheduled('gratitude_journal_reminder');

        // Calculate time difference
        $time_diff = $next_timestamp ? round(($next_timestamp - time()) / 60, 0) : 'N/A'; // in minutes

        if ($time_diff !== 'N/A') {
            if ($time_diff >= 60) {
                $time_diff = round($time_diff / 60, 0) . ' hours';
            } else {
                $time_diff = $time_diff . ' minutes';
            }
        }

        echo '<p><strong>The next gratitude journal reminder will run in about ' . esc_html($time_diff) . '.</strong></p>';
        ?>
        <?php
        // Check for settings update success message
        settings_errors('gratitude_journal_settings');
        ?>
        <form method="post" action="">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="gratitude_journal_reminder_enabled">Enable reminders:</label></th>
                        <td><input type="checkbox" id="gratitude_journal_reminder_enabled" name="gratitude_journal_reminder_enabled" <?php echo checked($reminder_enabled); ?>></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="gratitude_journal_reminder_time">Reminder time:</label></th>
                        <td><input type="time" id="gratitude_journal_reminder_time" name="gratitude_journal_reminder_time" value="<?php echo esc_attr($reminder_time); ?>" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="gratitude_journal_reminder_timezone">Timezone:</label></th>
                        <td>
                            <select id="gratitude_journal_reminder_timezone" name="gratitude_journal_reminder_timezone" required>
                                <?php foreach ($timezones as $timezone) : ?>
                                    <option value="<?php echo esc_attr($timezone); ?>" <?php selected($reminder_timezone, $timezone); ?>><?php echo esc_html($timezone); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="gratitude_journal_reminder_message">Reminder message:</label></th>
                        <td><textarea id="gratitude_journal_reminder_message" name="gratitude_journal_reminder_message" rows="5"><?php echo esc_textarea($reminder_message); ?></textarea></td>
                    </tr>
                </tbody>
            </table>
            <?php wp_nonce_field('gratitude_journal_settings', 'gratitude_journal_settings'); ?>
            <?php submit_button(); ?>
        </form>
        <?php do_action('gratitude_journal_settings_page_submitbox_end'); ?>
    </div>
    <?php
}

// Schedule daily reminder email
if (!function_exists('feed_the_good_schedule_gratitude_journal_reminder')) {
    function feed_the_good_schedule_gratitude_journal_reminder() {
        if (!wp_next_scheduled('gratitude_journal_reminder')) {
            $reminder_time = get_option('gratitude_journal_reminder_time', '12:00');
            $reminder_timezone = get_option('gratitude_journal_reminder_timezone', 'UTC');
            $reminder_datetime = new DateTime($reminder_time, new DateTimeZone($reminder_timezone));
            $reminder_datetime->setTimezone(new DateTimeZone('UTC'));
            $timestamp = $reminder_datetime->getTimestamp();

            if ($timestamp) {
                wp_schedule_event($timestamp, 'daily', 'gratitude_journal_reminder');
            }
        }
    }
    add_action('wp', 'feed_the_good_schedule_gratitude_journal_reminder');
}

if (!function_exists('feed_the_good_reset_gratitude_journal_reminder')) {
    function feed_the_good_reset_gratitude_journal_reminder() {
        wp_clear_scheduled_hook('gratitude_journal_reminder');
        feed_the_good_schedule_gratitude_journal_reminder();
    }

    add_action('update_option_gratitude_journal_reminder_time', 'feed_the_good_reset_gratitude_journal_reminder');
    add_action('update_option_gratitude_journal_reminder_timezone', 'feed_the_good_reset_gratitude_journal_reminder');
}

// Send reminder email
if (!function_exists('feed_the_good_send_gratitude_journal_reminder')) {
    function feed_the_good_send_gratitude_journal_reminder() {
        $reminder_enabled = get_option('gratitude_journal_reminder_enabled', false);
        if (!$reminder_enabled) {
            return;
        }

        $reminder_message = get_option('gratitude_journal_reminder_message', "Hello, it's time to write in your gratitude journal!");
        $to = get_option('admin_email');
        $subject = 'Gratitude Journal Reminder';
        $headers = array(
            'Content-Type: text/html; charset=UTF-8'
        );
        
        // Debugging logs
        error_log('Sending reminder to: ' . $to);
        error_log('Reminder subject: ' . $subject);
        error_log('Reminder message: ' . $reminder_message);
        error_log('Reminder headers: ' . implode(', ', $headers));
        
        $sent = wp_mail($to, $subject, $reminder_message, $headers);
        
        // Log success or failure
        if ($sent) {
            error_log('Reminder email sent successfully.');
        } else {
            error_log('Reminder email failed to send.');
        }
    }
    add_action('gratitude_journal_reminder', 'feed_the_good_send_gratitude_journal_reminder');
}

// Test if email is being sent
if (!function_exists('feed_the_good_test_gratitude_journal_reminder')) {
    function feed_the_good_test_gratitude_journal_reminder() {
        check_ajax_referer('gratitude_journal_test_reminder', 'nonce');

        $reminder_message = get_option('gratitude_journal_reminder_message', 'Grateful reminder test.');
        $to = get_option('admin_email');
        $subject = 'Gratitude Journal Reminder Test';
        $headers = array(
            'Content-Type: text/html; charset=UTF-8'
        );
        
        // Debugging logs
        error_log('Sending test reminder to: ' . $to);
        error_log('Test reminder subject: ' . $subject);
        error_log('Test reminder message: ' . $reminder_message);
        error_log('Test reminder headers: ' . implode(', ', $headers));
        
        $sent = wp_mail($to, $subject, $reminder_message, $headers);

        // Log success or failure
        if ($sent) {
            error_log('Test reminder email sent successfully.');
            wp_send_json_success('Test email sent!');
        } else {
            error_log('Test reminder email failed to send.');
            wp_send_json_error('Error sending test email.');
        }
        wp_die();
    }
    add_action('wp_ajax_gratitude_journal_test_reminder', 'feed_the_good_test_gratitude_journal_reminder');
    add_action('wp_ajax_nopriv_gratitude_journal_test_reminder', 'feed_the_good_test_gratitude_journal_reminder');
}

// Add test button to settings page
if (!function_exists('feed_the_good_add_gratitude_journal_test_reminder_button')) {
    function feed_the_good_add_gratitude_journal_test_reminder_button() {
        ?>
        <form method="post" id="gratitude-journal-test-reminder-form">
            <?php wp_nonce_field('gratitude_journal_test_reminder', 'gratitude_journal_test_reminder'); ?>
            <?php submit_button('Test Reminder', 'primary', 'gratitude_journal_test_reminder_button', false, array('id' => 'gratitude-journal-test-reminder-button')); ?>
        </form>
        <div id="gratitude-journal-test-reminder-result"></div>

        <script>
            jQuery(document).ready(function($) {
                $('#gratitude-journal-test-reminder-button').click(function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                            action: 'gratitude_journal_test_reminder',
                            nonce: $('#gratitude_journal_test_reminder').val(),
                        },
                        success: function(response) {
                            $('#gratitude-journal-test-reminder-result').html('<div class="notice notice-success"><p>' + response.data + '</p></div>');
                        },
                        error: function(response) {
                            $('#gratitude-journal-test-reminder-result').html('<div class="notice notice-error"><p>' + response.responseJSON.data + '</p></div>');
                        }
                    });
                });
            });
        </script>
        <?php
    }
    add_action('gratitude_journal_settings_page_submitbox_end', 'feed_the_good_add_gratitude_journal_test_reminder_button');
}
?>