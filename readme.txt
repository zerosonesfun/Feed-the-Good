=== Feed The Good ===
Contributors: wilcosky
Tags: journal, gratitude, hashtagging, private, diary
Requires at least: 6.0
Tested up to: 6.6.0
Stable tag: 1.8.4
Requires PHP: 7.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This WordPress plugin adds a new post type that allows you to quickly and privately keep track of what you're grateful for.

== Description ==
Feed The Good gratitude journal posts are private by default, but you may change that and post publicly if you'd like. Titles populate automatically as the current day's date, hashtags convert to WordPress style tags (which this plugin calls Gratitudes), and instead of Categories you set up Moods. You may also set up a daily email reminder.

= 🪄 Recommended way to post =

I'm grateful for #wordpress and #water.

= 💡 Not feeling grateful? =

An inspirational message will appear at the top of the editor page. There are over 200 inspirational prompts built in.

= 👁️ Love visuals? =

Quickly see what you're most grateful for and your top mood in graph format on your dashboard.

== Installation ==

1. Within the admin dashboard go to Plugins --> Add New
2. Search for "Feed The Good"
3. Choose activate, and then install
4. Go to Gratitude Journal in the admin menu and choose Moods - create moods like you would create post categories (e.g. Happy, Sad, Anxious, Mad)
5. Go to Gratitude Journal and choose Settings - set up your daily email reminder

Or, from the Add Plugins page choose Upload Plugin and upload this plugin's zip file.

== Frequently Asked Questions ==

= Does this plugin have any settings? =
Yes. Find "Gratitude Journal" in your admin menu and within there is a Settings link.

= Are gratitude posts private? =
Yes, by default. But, at any point, you may change a post to public by using the normal WordPress privacy/status settings.

= How do I set up moods? =
Just like you set up post categories. But instead of going to Categories, you go to the Moods submenu item under Gratitude Journal.

== Changelog ==

= 1.8.4 =
* Fixed a reminder email bug
* Removed RSS feed widget because it slowed down the dashboard

= 1.8.1 =
* Dashboard graphs optimized

= 1.8.0 =
* Dashboard widgets have been changed from text to visual graphs

= 1.7.1 =
* Now with 200 gratitude writing prompts
* Reset prompts on activation
* Go to /wp-admin/index.php?reset_feed_the_good=1 to reset prompts

= 1.6.2 =
* Minor adjustment to writing prompt code

= 1.6.1 =
* Adjusted how the writing prompt works

= 1.6.0 =
* Removed the hide dashboard widgets setting (not needed because WP has this feature built-in under the "Screen Options" tab)
* Removing the hide widgets setting also fixed a bug related to saving options
* Verbiage updates within the help tab
* Tested with WP 6.3

= 1.5.1 =
* Readme update

= 1.5 =
* Prompt added above title field
* Hide dashboard widgets setting added
* Security updates

= 1.4.1 =
* Added daily reminder with settings page
* Security updates

= 1.3 =
* Breaking change! Fixed gratitudes (tags) bug; old tags will no longer appear, however, any new tags created going forward will appear. This breaking change was necessary to ensure the gratitudes archive works properly, and to ensure the plugin works well long into the future.
* URLs now include time and mood
* Simplified date as title function
* Made hashtagging compatible with all languages

= 1.2 =
* Adjusted JavaScript that controls visibility

= 1.1 =
* Changed the way the post is set to private to prevent duplicate post creation which began with the release of WordPress 6.2
* Tested with WordPress 6.2
* Code clean up

= 1.0 =
* Initial release