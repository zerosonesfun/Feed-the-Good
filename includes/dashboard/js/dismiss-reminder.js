jQuery(document).ready(function($) {
    $('#feed-the-good-notice').on('click', '.notice-dismiss', function(e) {
        e.preventDefault(); // Prevent default action (dismissal) until confirmation

        if (confirm("Are you sure? Prompts will be hidden for 24 hours. Cancel if you don't want this.")) {
            $.post(feedTheGoodAjax.ajax_url, {
                action: 'feed_the_good_dismiss_notice',
            }).done(function(response) {
                if (response.success) {
                    $('#feed-the-good-notice').fadeOut();
                }
            });
        }
    });
});