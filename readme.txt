=== Plugin Name ===
Contributors: imaginarymedia
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BNWNBPEK33UBA
Tags: performance, security, settings
Requires at least: 3.0.1
Tested up to: 6.2.2
Stable tag: 0.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin provides settings to modify WordPress and improve performance and security.

== Description ==

This plugin provides settings to modify WordPress and improve performance and security.

**General settings**

* Modify excerpt length, the "More" text, and allow excerpts on Pages
* Change the "Read more" settings, so that the anchors to articles don't jump
* Modify custom post types so that they appear in search results and RSS feeds
* Allow tags on pages and ensure all tags appear in search queries
* Remove relational links
* Remove the Windows Live Writer manifest link (`wlwmanifest`)
* Remove the RSD link
* Remove the shortlink
* Enable HTML5 support for forms, comment lists, images and captions.
* Enable or disable the Links Manager
* Disable auto-formatting of content and/or excerpts

**Performance**

* Enable GZIP on Apache
* Disable WordPress pings from internal links
* Remove the version query string on styles and scripts
* Remove the JetPack plugin `devicepx` script
* Disable emoji support and remove emoji styles and scripts
* Disable jQuery Migrate dependency
* Disable the Block Editor Library CSS
* Disable oEmbed support

**Security**

* Remove the WordPress version string
* Modify XMLRPC features - disable entirely and/or disable XMLRPC SSL testing
* Comment modifications:
	- Disable comments
	- Disable comments on media files
	- Disable links in comments
	- Remove the 'URL' field from the comments form
	- Hide existing comments

**Administration**

* Show statistics in the Admin section
* Change the WordPress greeting, even for non US English installs
* Remove dashboard widgets
* Remove menu items
* Include the "All Settings" menu item

**Login**

* Change the login page logo
* Change the login page logo URL
* Change the login page logo URL title
* Disable detailed login errors

If you have further suggestions, please contact us via the [plugin support page](https://wordpress.org/support/plugin/wp-performance-security).

If this plugin is useful for managing your WordPress settings, please [review the plugin](https://wordpress.org/support/view/plugin-reviews/wp-performance-security).

Developed by [James Robinson](https://jmr.codes).

== Installation ==

1. Unzip the plugin and copy the `wp-performance-security` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==

= 0.9.2 =

* Removed Google Analytics section now that Universal Analytics are no longer supported

= 0.9.1 =

* Fixed a bug on the login screen

= 0.9 =

* Fixed a bug with comments being disabled by default
* Remove oEmbed support option
* Remove jQuery migrate option
* Improved emoji removal to include dns-prefetch of image sources

= 0.8 =

* Tested against WP 5.0.1
* Open Sans was dropped from WP 4.6 in favour of system fonts - so this option will only show for older versions of WP
* Updated Google Analytics to support Google Tag Manager (gtag.js)
* Added the ability to hide existing comments
* Jetpack devicepx option only shown if Jetpack is active
* Improved handling of custom post type options
* Added support for enabling (and disabling) the Links Manager
* Removed SVG support due to changes in WP since 4.7
* Minor code improvements

= 0.7 =

* Added new feature to remove the styles and scripts that make up emoji support, which was added in WP 4.2

= 0.6 =

* Fixed a range of alerts that appear in debug mode

= 0.5 =

* Fixed issue where plugin might conflict with WP Super Cache

= 0.4.1 =

* Minor changes to plugin settings in WP

= 0.4 =

Minor code changes

* JS only loaded on plugin page
* Changed default settings, all plugin options set to the WordPress defaults

= 0.3 =

* Updated plugin to allow for internationalization
* Added icon

= 0.2 =

* Added support for adding Google Analytics tracking code
* Added a toggle to remove the admin bar from front-facing pages
* Added a setting to enforce and set the minimum number of characters required in a comment

= 0.1 =

* Initial launch
