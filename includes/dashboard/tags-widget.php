<?php
/**
 * This file is part of Feed The Good, a WordPress plugin by Billy Wilcosky.
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Add Dashboard Widget
if ( ! function_exists( 'feed_the_good_gratitude_top_terms_widget' ) ) {
function feed_the_good_gratitude_top_terms_widget() {
    wp_add_dashboard_widget(
        'feed_the_good_gratitude_top_terms',
        'Top Gratitudes️️️',
        'feed_the_good_gratitude_top_terms_output'
    );
}
add_action( 'wp_dashboard_setup', 'feed_the_good_gratitude_top_terms_widget' );
}

function feed_the_good_get_gratitude_colors() {
    // Retrieve stored colors or initialize an empty array if none exist
    return get_option('feed_the_good_gratitude_colors', array());
}

function feed_the_good_set_gratitude_colors($colors) {
    // Update the gratitude colors in the database
    update_option('feed_the_good_gratitude_colors', $colors);
}

function feed_the_good_get_color_for_gratitude($gratitude) {
    // Try to fetch existing colors
    $colors = feed_the_good_get_gratitude_colors();

    if (!array_key_exists($gratitude, $colors)) {
        // Generate a new random color if not already existing
        $color = 'rgba(' . rand(0, 255) . ', ' . rand(0, 255) . ', ' . rand(0, 255) . ', 0.5)';
        $colors[$gratitude] = $color;
        feed_the_good_set_gratitude_colors($colors);  // Update the stored colors
    }

    return $colors[$gratitude];
}

// Output Dashboard Widget
if ( ! function_exists( 'feed_the_good_gratitude_top_terms_output' ) ) {
    function feed_the_good_gratitude_top_terms_output() {
        global $wpdb;

        $query = "
            SELECT t.name, COUNT(DISTINCT p.ID) as count
            FROM {$wpdb->terms} t
            INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
            INNER JOIN {$wpdb->term_relationships} tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
            INNER JOIN {$wpdb->posts} p ON p.ID = tr.object_id
            WHERE tt.taxonomy = %s
            AND p.post_type = %s
            AND p.post_status IN ('publish', 'private')
            GROUP BY t.term_id
            ORDER BY count DESC
            LIMIT 10
        ";

        $results = $wpdb->get_results($wpdb->prepare($query, 'gratitudes', 'gratitude'));

        $labels = [];
        $data = [];
        $backgroundColors = [];

        foreach ($results as $result) {
            $labels[] = $result->name;
            $data[] = $result->count;
            $backgroundColors[] = feed_the_good_get_color_for_gratitude($result->name);
        }

        // Combine arrays for sorting
        $combined = array_map(null, $labels, $data, $backgroundColors);
        // Sort combined array by the first element of each tuple (the labels), ignoring case
        usort($combined, function($a, $b) {
            return strcasecmp($a[0], $b[0]);
        });

        // Separate sorted arrays
        $labels = array_column($combined, 0);
        $data = array_column($combined, 1);
        $backgroundColors = array_column($combined, 2);

        // Debugging output
        error_log('Labels: ' . print_r($labels, true));
        error_log('Data: ' . print_r($data, true));
        error_log('BackgroundColors: ' . print_r($backgroundColors, true));

        echo '<canvas id="gratitudeChart" width="400" height="400"></canvas>';
        echo '<script>
    jQuery(document).ready(function($) {
        var ctx = document.getElementById("gratitudeChart").getContext("2d");
        var gratitudeChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ' . json_encode($labels) . ',
                datasets: [{
                    label: "Number of Occurrences",
                    data: ' . json_encode($data) . ',
                    backgroundColor: ' . json_encode($backgroundColors) . ',
                    borderColor: ' . json_encode(array_map(function ($color) {
                        return str_replace("0.5", "1", $color);
                    }, $backgroundColors)) . ',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>';
    }
}