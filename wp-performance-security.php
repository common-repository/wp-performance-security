<?php
/**
 * Plugin Name: Performance & Security
 * Plugin URI: https://jmr.codes/wordpress/plugin/wp-performance-security/
 * Description: Change WordPress settings that can improve the performance and security of your site. Reduce load times, vulnerabilities, and control comments and hidden WordPress features. <a href="https://wordpress.org/support/plugin/wp-performance-security">Need help?</a>
 * Version: 0.9.2
 * Author: James Robinson
 * Author URI: https://jmr.codes/
 * License: GPL2
 */

/*  Copyright 2018 James Robinson (email : support@jmr.codes)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !defined( 'ABSPATH' ) ) exit;

// Add settings and styles files
include 'modules/settings.php';
include 'modules/scripts.php';

// Gets all public post types
global $wp_post_types;
$public_post_types = array();
foreach($wp_post_types as $post_type) {
	if ( true === $post_type->public ) {
		array_push($public_post_types, $post_type->name);
	}
}

// Init settings values on activation
function wpps_activate(){
	$config = get_option('wpps_options');

	$wpps_options['stats_admin_footer'] = 0;
	$wpps_options['wpps_custom_upload_mimes'] = 0;
	$wpps_options['wpps_excerpt_length'] = '55';
	$wpps_options['wpps_page_excerpts'] = 0;
	$wpps_options['output_compression'] = 0;
	$wpps_options['xmlrpc_enabled'] = 0;
	$wpps_options['atom_service_url_filter'] = 0;
	$wpps_options['wpps_searchAll'] = 0;
	$wpps_options['wpps_custom_feed_request'] = 0;
	$wpps_options['tags_support_all'] = 0;
	$wpps_options['tags_support_query'] = 0;
	$wpps_options['wpps_self_ping'] = 0;
	$wpps_options['wpps_html5_support'] = 0;
	$wpps_options['wpps_remove_script_version'] = 0;
	$wpps_options['wpps_remove_wp_version'] = 0;
	$wpps_options['wpps_all_settings_link'] = 0;
	$wpps_options['wpps_replace_howdy'] = 'Welcome, ';

	// Links
	$wpps_options['wpps_link_manager'] = 0; // Default on new installs is disabled

	$wpps_options['wpps_auto_content'] = 0;
	$wpps_options['wpps_auto_excerpt'] = 0;

	// Scripts and Styles
	$wpps_options['wpps_jetpack_devicepx'] = 0;
	$wpps_options['wpps_emoji_support'] = 0;
	$wpps_options['wpps_jquery_migrate'] = 0;
	$wpps_options['wpps_block_library_css'] = 0;
	$wpps_options['wpps_embeds'] = 0;

	//Admin Bar
	$wpps_options['wpps_admin_bar'] = 0;

	// Comments
	$wpps_options['wpps_hide_existing_comments'] = 0;
	$wpps_options['wpps_clickable_comments'] = 0;
	$wpps_options['wpps_media_comment_status'] = 0;
	$wpps_options['wpps_closeCommentsGlobaly'] = 0;
	$wpps_options['wpps_comment_url'] = 0;
	$wpps_options['wpps_minimum_comment_length'] = 0;

	// Login Options
	$wpps_options['wpss_custom_login_logo'] = '';
	$wpps_options['wpps_custom_login_url'] = '';
	$wpps_options['wpps_custom_login_title'] = '';
	$wpps_options['login_errors'] = 0;

	$wpps_options['wpps_excerpt_more'] = '[...]';
	$wpps_options['wpps_read_more'] = 0;

	// Header links
	$wpps_options['wpps_rel_links'] = 0;
	$wpps_options['wpps_wlw_manifest'] = 0;
	$wpps_options['wpps_rsd_link'] = 0;
	$wpps_options['wpps_short_link'] = 0;

	// Dashboard Widgets
	$wpps_options['wpps_dash_activity'] = 0;
	$wpps_options['wpps_dash_primary'] = 0;
	$wpps_options['wpps_dash_secondary'] = 0;
	$wpps_options['wpps_dash_right_now'] = 0;
	$wpps_options['wpps_dash_incoming_links'] = 0;
	$wpps_options['wpps_dash_quick_press'] = 0;
	$wpps_options['wpps_dash_recent_drafts'] = 0;
	$wpps_options['wpps_dash_recent_comments'] = 0;
	$wpps_options['wpps_dash_plugins'] = 0;

	// Admin Menu Items
	$wpps_options['wpps_menu_wp'] = 0;
	$wpps_options['wpps_menu_about'] = 0;
	$wpps_options['wpps_menu_wporg'] = 0;
	$wpps_options['wpps_menu_documentation'] = 0;
	$wpps_options['wpps_menu_forums'] = 0;
	$wpps_options['wpps_menu_feedback'] = 0;
	$wpps_options['wpps_menu_site'] = 0;

	update_option('wpps_options', $wpps_options );
}
register_activation_hook( __FILE__, 'wpps_activate' );

function wpps_init() {

	// Get settings
	$config = get_option('wpps_options');

	// Hide the admin bar from front-facing pages
	if ( isset( $config['wpps_admin_bar'] ) && $config['wpps_admin_bar'] == 1 ) {
		add_filter('show_admin_bar', '__return_false');
	}

	// Remove Jetpack devicepx script
	if( isset( $config['wpps_jetpack_devicepx'] ) && $config['wpps_jetpack_devicepx'] == 1 ){
		function wpps_dequeue_devicepx() {
			wp_dequeue_script( 'devicepx' );
		}
		add_action( 'wp_enqueue_scripts', 'wpps_dequeue_devicepx', 20 );
	}

	// Display DB Queries, Time Spent and Memory Consumption in Admin footer
	if( isset( $config['stats_admin_footer'] ) && $config['stats_admin_footer'] == 1 ){
		function stats_admin_footer() {
			$stat = sprintf(  '%d queries in %.3f seconds, using %.2fMB memory', get_num_queries(), timer_stop( 0, 3 ), memory_get_peak_usage() / 1024 / 1024 );
			return '<p class="alignleft">' . $stat . '</p>';
		}
		add_filter('admin_footer_text', 'stats_admin_footer');
	}

	// Remove Emoji support
	function wpps_disable_emojis_tinymce( $plugins ) {
		$tinymce_plugins = is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
		return $tinymce_plugins;
	}
	if( isset( $config['wpps_emoji_support'] ) && $config['wpps_emoji_support'] == 1 ){
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', 'wpps_disable_emojis_tinymce' );
		add_filter( 'emoji_svg_url', '__return_false' );
	}

	// Remove jQuery migrate
	function dequeue_jquery_migrate( &$scripts ){
		if ( isset($scripts->registered['jquery']) ) {
			$script = $scripts->registered['jquery'];
			// Check whether the script has any dependencies
			if ( $script->deps ) {
				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
			}
		}
	}
	if( isset( $config['wpps_jquery_migrate'] ) && $config['wpps_jquery_migrate'] == 1 ) {
		add_filter( 'wp_default_scripts', 'dequeue_jquery_migrate' );
	}

	// Remove Block Library CSS
	if( isset( $config['wpps_block_library_css'] ) && $config['wpps_block_library_css'] == 1 ) {
		add_filter( 'wp_print_styles', function(){
			wp_dequeue_style('wp-block-library');
		}, 100 );
	}

	// Disable Embeds
	function wpps_disable_embeds_rewrites( $rules ) {
		foreach ( $rules as $rule => $rewrite ) {
			if ( false !== strpos( $rewrite, 'embed=true' ) ) {
				unset( $rules[ $rule ] );
			}
		}

		return $rules;
	}
	if( isset( $config['wpps_embeds'] ) && $config['wpps_embeds'] == 1 ) {

		// Remove the REST API lines from the HTML Header
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

		// Remove the REST API endpoint.
		remove_action( 'rest_api_init', 'wp_oembed_register_route' );

		// Turn off oEmbed auto discovery.
		add_filter( 'embed_oembed_discover', '__return_false' );

		// Don't filter oEmbed results.
		remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

		// Remove oEmbed discovery links.
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

		// Remove oEmbed-specific JavaScript from the front-end and back-end.
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );

		// Don't filter oEmbed results.
		remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

		// Remove oEmbed-specific JavaScript from the front-end and back-end.
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		add_filter( 'tiny_mce_plugins', function( $plugins ) {
			return array_diff( $plugins, array( 'wpembed' ) );
		} );

		// Remove all embeds rewrite rules.
		add_filter( 'rewrite_rules_array', 'wpps_disable_embeds_rewrites' );

		// Remove filter of the oEmbed result before any HTTP requests are made.
		remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
	}

	// Change the length of the default excerpt (number of words, default is 55)
	// **** Allow setting the specific length ***//
	function wpps_excerpt_length($length) {
		$config = get_option('wpps_options');
		return $config['wpps_excerpt_length'];
	}
	add_filter('excerpt_length', 'wpps_excerpt_length');

	// Allow excerpts on Pages
	function wpps_page_excerpts() {
		add_post_type_support( 'page', 'excerpt' );
	}
	if( isset( $config['wpps_page_excerpts'] ) && $config['wpps_page_excerpts'] == 1 ) {
		add_action('init', 'wpps_page_excerpts');
	}

	// Custom excerpt ellipses
	function custom_excerpt_more( $more ) {
		$config = get_option('wpps_options');
		return $config['wpps_excerpt_more'];
	}
	add_filter( 'excerpt_more', 'custom_excerpt_more' );


	// Header Stuff
	if( isset( $config['wpps_rel_links'] ) && $config['wpps_rel_links'] == 1 ){
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'parent_post_rel_link' );
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	}

	if( isset( $config['wpps_wlw_manifest'] ) && $config['wpps_wlw_manifest'] == 1 ){
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}

	if( isset( $config['wpps_rsd_link'] ) && $config['wpps_rsd_link'] == 1 ){
		remove_action( 'wp_head', 'rsd_link' );
	}

	if( isset( $config['wpps_short_link'] ) && $config['wpps_short_link']== 1 ){
		remove_action( 'wp_head', 'wp_shortlink_wp_head');
	}


	// Enable GZIP compression
	if( isset( $config['output_compression'] ) && $config['output_compression'] == 1 ){
		if(extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) {
			add_action('wp', create_function('', '@ob_end_clean();@ini_set("zlib.output_compression", 1);'));
		}
	}


	// Disable XMLRPC
	if( isset( $config['xmlrpc_enabled'] ) && $config['xmlrpc_enabled'] == 1 ){
		add_filter('xmlrpc_enabled', '__return_false');
	}


	// Prevents WordPress from testing SSL capability on domain.com/xmlrpc.php?rsd when XMLRPC not in use
	if( isset( $config['atom_service_url_filter'] ) && $config['atom_service_url_filter'] == 'on' ){
		remove_filter('atom_service_url','atom_service_url_filter');
	}


	// Hide login form error messages
	if( isset( $config['login_errors'] ) && $config['login_errors'] == 1 ){
		add_filter('login_errors', create_function('$a', "return 'Error';"));
	}


	// Show Custom Post Types in search results
	if( isset( $config['wpps_searchAll'] ) && $config['wpps_searchAll'] == 1 ){
		function wpps_searchAll( $query ) {
			global $public_post_types;
			if ( count($public_post_types) > 0 && $query->is_search ) {
				$query->set( 'post_type', $public_post_types );
			}
			return $query;
		}
		add_filter( 'the_search_query', 'wpps_searchAll' );
	}


	// Add Custom Post Types to the default RSS feed
	if( isset( $config['wpps_custom_feed_request'] ) && $config['wpps_custom_feed_request'] == 1 ){
		function wpps_custom_feed_request( $vars ) {
			global $public_post_types;
			if (isset($vars['feed']) && !isset($vars['post_type']))
				$vars['post_type'] = $public_post_types;
			return $vars;
		}
		add_filter( 'request', 'wpps_custom_feed_request' );
	}


	// Allow tags on pages
	if( isset( $config['tags_support_all'] ) && $config['tags_support_all'] == 1 ){
		function tags_support_all() {
			register_taxonomy_for_object_type('post_tag', 'page');
		}
		add_action('init', 'tags_support_all');
	}


	// Ensure all tags are included in queries
	if( isset( $config['tags_support_query'] ) && $config['tags_support_query'] == 1 ){
		function tags_support_query($wp_query) {
			if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
		}
		add_action('pre_get_posts', 'tags_support_query');
	}


	// Disable self-ping
	if( isset( $config['wpps_self_ping'] ) && $config['wpps_self_ping'] == 1 ){
		function wpps_self_ping( &$links ) {
			$home = get_option( 'home' );
			foreach ( $links as $l => $link ) {
				if ( 0 === strpos( $link, $home ) ){
					unset($links[$l]);
				}
			}
		}
		add_action( 'pre_ping', 'wpps_self_ping' );
	}


	// Use HTML5
	if( isset( $config['wpps_html5_support'] ) && $config['wpps_html5_support'] == 1 && function_exists( 'add_theme_support' ) ) {
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	}


	// Links Manager
	if( isset( $config['wpps_link_manager'] ) ) {
		if ( 1 == $config['wpps_link_manager'] ) {
			add_filter( 'pre_option_link_manager_enabled', '__return_true' );
		} else {
			update_option( 'link_manager_enabled', 0 );
		}
	}


	// Remove the version query string from scripts and styles - allows for better caching
	if( isset( $config['wpps_remove_script_version'] ) && $config['wpps_remove_script_version'] == 1 ){
		function wpps_remove_script_version( $src ){
			return remove_query_arg( 'ver', $src );
		}
		add_filter( 'script_loader_src', 'wpps_remove_script_version', 15, 1 );
		add_filter( 'style_loader_src', 'wpps_remove_script_version', 15, 1 );
	}


	// Remove WordPress version number
	if( isset( $config['wpps_remove_wp_version'] ) && $config['wpps_remove_wp_version'] == 1 ){
		function wpps_remove_wp_version() {
			return '';
		}
		remove_action('wp_head', 'wp_generator');
		add_filter('the_generator', 'wpps_remove_wp_version');
	}


	// Add new Admin menu item "All Settings"
	if( isset( $config['wpps_all_settings_link'] ) && $config['wpps_all_settings_link'] == 1 ){
		function wpps_all_settings_link() {
			add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
		}
		add_action('admin_menu', 'wpps_all_settings_link');
	}


	// Change the default WordPress greeting in Admin
	function wpps_replace_howdy( $wp_admin_bar ) {
		$config = get_option('wpps_options');

		$my_account=$wp_admin_bar->get_node('my-account');
		$newtitle = preg_replace( "/^(.*?),/", $config['wpps_replace_howdy'], $my_account->title );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle,
		) );
	}
	add_filter( 'admin_bar_menu', 'wpps_replace_howdy', 25 );


	// Disable Auto-Formatting in Excerpt
	if( isset( $config['wpps_auto_excerpt'] ) && $config['wpps_auto_excerpt'] == 1 ){
		remove_filter( 'the_excerpt', 'wpautop' );
	}


	// Disable Auto-Formatting in Content
	if( isset( $config['wpps_auto_content'] ) && $config['wpps_auto_content'] == 1 ){
		remove_filter( 'the_content', 'wpautop' );
	}


	// Hide existing comments
	if( isset( $config['wpps_hide_existing_comments'] ) && $config['wpps_hide_existing_comments'] == 1 ){
		function wpps_disable_comments_existing($comments) {
			$comments = array();
			return $comments;
		}
		add_filter('comments_array', 'wpps_disable_comments_existing', 10, 2);
	}


	// Disable Auto Linking of URLs in comments
	if( isset( $config['wpps_clickable_comments'] ) && $config['wpps_clickable_comments'] == 1 ){
		remove_filter('comment_text', 'make_clickable', 9);
	}

	// Removes URL field from comments
	if( isset( $config['wpps_comment_url'] ) && $config['wpps_comment_url'] == 1 ) {
		function wpps_remove_comment_url($fields) {
			unset($fields['url']);
			return $fields;
		}
		add_filter('comment_form_default_fields','wpps_remove_comment_url');
	}


	// Disable comments on media files
	if( isset( $config['wpps_media_comment_status'] ) && $config['wpps_media_comment_status'] == 1 ){
		function wpps_media_comment_status( $open, $post_id ) {
			$post = get_post( $post_id );
			if( $post->post_type == 'attachment' ) {
				return false;
			}
			return $open;
		}
		add_filter( 'comments_open', 'wpps_media_comment_status', 10 , 2 );
	}


	// Close comments globally
	if( isset( $config['wpps_closeCommentsGlobaly'] ) && $config['wpps_closeCommentsGlobaly'] == 1 ){

		function wpps_close_comments_globally($data) {
			return false;
		}
		add_filter('comments_number', 'wpps_close_comments_globally');
		add_filter('comments_open', 'wpps_close_comments_globally');

		function wpps_disable_comments_status() {
			return false;
		}
		add_filter('comments_open', 'wpps_disable_comments_status', 20, 2);
		add_filter('pings_open', 'wpps_disable_comments_status', 20, 2);

		// Remove support for comments from each post type
		$post_types = get_post_types();
		foreach ($post_types as $post_type) {
			if ( post_type_supports($post_type, 'comments') ) {
				remove_post_type_support($post_type, 'comments');
				remove_post_type_support($post_type, 'trackbacks');
			}
		}

		// Remove comments page in menu
		function wpps_disable_comments_menu() {
			remove_menu_page('edit-comments.php');
		}
		add_action('admin_menu', 'wpps_disable_comments_menu');


		// Redirect any user trying to access comments page
		function wpps_disable_comments_menu_redirect() {
			global $pagenow;
			if ($pagenow === 'edit-comments.php') {
				wp_redirect(admin_url()); exit;
			}
		}
		add_action('admin_init', 'wpps_disable_comments_menu_redirect');


		// Remove comments metabox from dashboard
		function wpps_disable_comments_dashboard() {
			remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
		}
		add_action('admin_init', 'wpps_disable_comments_dashboard');


		// Remove comments from the admin toolbar
		function wpps_disable_comments_toolbar() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu('comments');
		}
		add_action( 'wp_before_admin_bar_render', 'wpps_disable_comments_toolbar' );

	}


	// Enforce minimum comment length
	if ( isset( $config['wpps_minimum_comment_length'] ) && $config['wpps_minimum_comment_length'] > 0 ) {
		function wpps_minimum_comment( $commentdata ) {
			$minimalCommentLength = $config['wpps_minimum_comment_length'];
			if ( strlen( trim( $commentdata['comment_content'] ) ) < $minimalCommentLength ) {
				wp_die( 'All comments must be at least ' . $minimalCommentLength . ' characters long.' );
			}
			return $commentdata;
		}
		add_filter( 'preprocess_comment', 'wpps_minimum_comment' );
	}


	// No more jumping for read more link
	function wpps_no_jump( $more ) {
		return '<a href="' . get_permalink( get_the_ID() ) . '" class="more-link">'.'Continue Reading'.'</a>';
	}

	if( isset( $config['wpps_read_more'] ) && $config['wpps_read_more'] == 1 ){
		add_filter('excerpt_more', 'wpps_no_jump');
		add_filter('the_content_more_link', 'wpps_no_jump');
	}


	// Custom login logo
	if( ! empty( $config['wpss_custom_login_logo'] ) ) {
		function wpss_custom_login_logo() {
			echo '<style>
			.login #login { padding-top: 0;}
			.login h1 { width: 320px; height: 200px; }
			.login h1 a { background:url('. $config['wpss_custom_login_logo'].') 50% 50% no-repeat; background-size:contain; height: 200px; width: 320px; }
			</style>';
		}
		add_action('login_head', 'wpss_custom_login_logo');
	}


	// Custom login URL
	if( ! empty( $config['wpps_custom_login_url'] ) ){
		function wpps_custom_login_url(){
			if ( isset( $config['wpps_custom_login_url'] ) ) {
				return  $config['wpps_custom_login_url'];
			}
		}
		add_filter('login_headerurl', 'wpps_custom_login_url');
	}


	// Custom login URL Title Attribute
	if( ! empty ( $config['wpps_custom_login_title'] ) ) {
		function wpps_custom_login_title(){
			if ( isset( $config['wpps_custom_login_title'] ) ) {
				return  $config['wpps_custom_login_title'];
			}
		}
		add_filter('login_headertext', 'wpps_custom_login_title');
	}


	// Admin Menu Items
	// Admin Menu Items - WP
	function wpps_menu_wp() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
	}

	if( isset( $config['wpps_menu_wp'] ) && $config['wpps_menu_wp'] == 1 ){
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_wp' );
	}

	// Admin Menu Items - About
	if( isset( $config['wpps_menu_about'] ) && $config['wpps_menu_about'] == 1 ){
		function wpps_menu_about() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu('about');
		}
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_about' );
	}

	// Admin Menu Items - WP.org
	if( isset( $config['wpps_menu_wporg'] ) && $config['wpps_menu_wporg'] == 1 ){
		function wpps_menu_wporg() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu('wporg');
		}
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_wporg' );
	}

	// Admin Menu Items - Documentation
	if( isset( $config['wpps_menu_documentation'] ) && $config['wpps_menu_documentation'] == 1 ){
		function wpps_menu_documentation() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu('documentation');
		}
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_documentation' );
	}

	// Admin Menu Items - Support Forums
	if( isset( $config['wpps_menu_forums'] ) && $config['wpps_menu_forums'] == 1 ){
		function wpps_menu_forums() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu('support-forums');
		}
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_forums' );
	}

	// Admin Menu Items - Feedback
	if( isset( $config['wpps_menu_feedback'] ) && $config['wpps_menu_feedback'] == 1 ){
		function wpps_menu_feedback() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu('feedback');
		}
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_feedback' );
	}

	// Admin Menu Items - View Site
	if( isset( $config['wpps_menu_site'] ) && $config['wpps_menu_site'] == 1 ){
		function wpps_menu_site() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu('view-site');
		}
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_site' );
	}


	// Remove Dashboard Items in Admin - Functions
	function wpps_remove_widgets() {
		$config = get_option('wpps_options');
		if ( isset( $config['wpps_dash_activity'] ) && $config['wpps_dash_activity'] == 1 ){
			remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		}
		if ( isset( $config['wpps_dash_primary'] ) && $config['wpps_dash_primary'] == 1 ){
			remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		}
		if ( isset( $config['wpps_dash_secondary'] ) && $config['wpps_dash_secondary'] == 1 ){
			remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
		}
		if ( isset( $config['wpps_dash_right_now'] ) && $config['wpps_dash_right_now'] == 1 ){
			remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		}
		if ( isset( $config['wpps_dash_incoming_links'] ) && $config['wpps_dash_incoming_links'] == 1 ){
			remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
		}
		if ( isset( $config['wpps_dash_plugins'] ) && $config['wpps_dash_plugins'] == 1 ){
			remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
		}
		if ( isset( $config['wpps_dash_quick_press'] ) && $config['wpps_dash_quick_press'] == 1 ){
			remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		}
		if ( isset( $config['wpps_dash_recent_drafts'] ) && $config['wpps_dash_recent_drafts'] == 1 ){
			remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
		}
		if ( isset( $config['wpps_dash_recent_comments'] ) && $config['wpps_dash_recent_comments'] == 1 ){
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
		}
	}
	add_action( 'wp_network_dashboard_setup', 'wpps_remove_widgets' );
	add_action( 'wp_user_dashboard_setup', 'wpps_remove_widgets' );
	add_action( 'wp_dashboard_setup', 'wpps_remove_widgets' );

}

add_action( 'plugins_loaded', 'wpps_init' );
