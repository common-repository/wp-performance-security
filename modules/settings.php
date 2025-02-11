<?php

	// Process settings update
	add_action('plugins_loaded', 'wpps_update_settings');
	function wpps_update_settings(){
		if ( ! isset( $_POST['_wpnonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'] ) ) {
			return;
		}

		$config = get_option('wpps_options');
		foreach( $_POST as $key=>$value ){
			$wpps_options[$key] = sanitize_text_field( $value );
		}
		update_option('wpps_options', $wpps_options );
	}


	// Add settings page
	add_action('admin_menu', 'wpps_item_menu');
	function wpps_item_menu() {
		add_options_page(  __('WP Performance & Security', 'wp-performance-security'), __('Performance & Security', 'wp-performance-security'), 'edit_published_posts', 'wpps_config', 'wpps_config');
	}


	// Add settings link on plugin page
	function wpps_plugin_settings_link($links) {
		$settings_link = '<a href="options-general.php?page=wpps_config">Settings</a>';
		array_unshift($links, $settings_link);
		return $links;
	}
	$plugin = 'wp-performance-security/wp-performance-security.php';
	add_filter("plugin_action_links_$plugin", "wpps_plugin_settings_link");


	// Settings output function
	function wpps_config(){
?>
	<div class="wrap">

		<h2><?php _e('WP Performance &amp; Security Settings', 'wp-performance-security'); ?></h2>

	<?php if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce($_POST['_wpnonce']) ) : ?>
		<div class="updated fade" >
			<p><?php _e('Settings saved successfully', 'wp-performance-security'); ?></p>
		</div>
	<?php endif; ?>

		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

		<?php
			wp_nonce_field();
			$config = get_option('wpps_options');
		?>

			<h2 class="nav-tab-wrapper wpps-nav-tab-wrapper">
				<a href="#tabs-1" class="nav-tab nav-tab-active"><?php _e('General', 'wp-performance-security'); ?></a>
				<a href="#tabs-2" class="nav-tab"><?php _e('Performance', 'wp-performance-security'); ?></a>
				<a href="#tabs-3" class="nav-tab"><?php _e('Security', 'wp-performance-security'); ?></a>
				<a href="#tabs-4" class="nav-tab"><?php _e('Administration', 'wp-performance-security'); ?></a>
				<a href="#tabs-5" class="nav-tab"><?php _e('Login', 'wp-performance-security'); ?></a>
			</h2>

			<div class="wpps-settings">

				<div id="tabs-1" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Excerpts', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<span><?php _e('Number of words in excerpts: ', 'wp-performance-security'); ?></span>
										<input type="text" class="small-text" name="wpps_excerpt_length" value="<?php if ( isset( $config['wpps_excerpt_length'] ) ) echo $config['wpps_excerpt_length']; ?>">
									</label>
								</fieldset>
								<fieldset>
									<label>
										<span><?php _e('“More” text string in excerpts: ', 'wp-performance-security'); ?></span>
										<input type="text" class="small-text" name="wpps_excerpt_more" value="<?php if ( isset( $config['wpps_excerpt_more'] ) ) echo $config['wpps_excerpt_more']; ?>">
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_page_excerpts" value="1" <?php if ( isset( $config['wpps_page_excerpts'] ) ) checked( $config['wpps_page_excerpts'], 1 ); ?>>
										<span><?php _e('Allow excerpts on Pages', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('“Read More”', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_read_more" value="1" <?php if ( isset( $config['wpps_read_more'] ) ) checked( $config['wpps_read_more'], 1 ); ?>>
										<span><?php _e('Disable “Read more” links from jumping to anchor', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('When creating a <code>&lt;!--more--&gt;</code> link in WordPress the default action is to jump to the ‘next’ section.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Custom Post Types', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_custom_feed_request" value="1" <?php if ( isset( $config['wpps_custom_feed_request'] ) ) checked( $config['wpps_custom_feed_request'], 1 ); ?>>
										<span><?php _e('Show Custom Post Types in the RSS feed', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_searchAll" value="1" <?php if ( isset( $config['wpps_searchAll'] ) ) checked( $config['wpps_searchAll'], 1 ); ?>>
										<span><?php _e('Show Custom Post Types in the search results', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('This will only apply to custom post types that are public. Some plugins use private custom post types to store values and these should not appear in search queries.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Tags', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="tags_support_all" value="1" <?php if ( isset( $config['tags_support_all'] ) ) checked( $config['tags_support_all'], 1 ); ?>>
										<span><?php _e('Allow tags on pages', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="tags_support_query" value="1" <?php if ( isset( $config['tags_support_query'] ) ) checked( $config['tags_support_query'], 1 ); ?>>
										<span><?php _e('Ensure all tags are included in queries', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Header', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_rel_links" value="1" <?php if ( isset( $config['wpps_rel_links'] ) ) checked( $config['wpps_rel_links'], 1 ); ?>>
										<span><?php _e('Remove relational links for the posts adjacent to the current post', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_wlw_manifest" value="1" <?php if ( isset( $config['wpps_wlw_manifest'] ) ) checked( $config['wpps_wlw_manifest'], 1 ); ?>>
										<span><?php _e('Remove <em>Windows Live Writer</em> manifest link (wlwmanifest)', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_rsd_link" value="1" <?php if ( isset( $config['wpps_rsd_link'] ) ) checked( $config['wpps_rsd_link'], 1 ); ?>>
										<span><?php _e('Remove <abbr title="Really Simple Discovery">RSD</abbr> link', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_short_link" value="1" <?php if ( isset( $config['wpps_short_link'] ) ) checked( $config['wpps_short_link'], 1 ); ?>>
										<span><?php _e('Remove Shortlink', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('HTML5 Support', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_html5_support" value="1" <?php if ( isset( $config['wpps_html5_support'] ) ) checked( $config['wpps_html5_support'], 1 ); ?>>
										<span><?php _e('Use HTML5 markup for the comment forms, search forms, comment lists, images and captions.', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Links Manager', 'wp-performance-security'); ?></th>
							<td>
								<p class="description"><?php _e('As of Version 3.5, the Links Manager and blogroll are hidden for new installs and any existing WordPress installs that do not have any links. If you have upgraded from a previous version of WordPress with active links, the Links Manager will continue to function as normal. The following options allow you to disable an existing Links Manager or enable it on more recent versions of WordPress.'); ?></p><br>
								<fieldset>
									<label>
										<input type="radio" name="wpps_link_manager" value="0" <?php checked( empty(get_option('link_manager_enabled')) ); ?>>
										<span><?php _e('Disable “Links Manager”', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="radio" name="wpps_link_manager" value="1" <?php checked( get_option('link_manager_enabled') ); ?>>
										<span><?php _e('Enable “Links Manager”', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Auto-Formatting', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_auto_content" value="1" <?php if ( isset( $config['wpps_auto_content'] ) ) checked( $config['wpps_auto_content'], 1 ); ?>>
										<span><?php _e('Disable auto-formatting content', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_auto_excerpt" value="1" <?php if ( isset( $config['wpps_auto_excerpt'] ) ) checked( $config['wpps_auto_excerpt'], 1 ); ?>>
										<span><?php _e('Disable auto-formatting excerpts', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

				</div>

				<div id="tabs-2" class="tab-content">
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Compression', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="output_compression" value="1" <?php if ( isset( $config['output_compression'] ) ) checked( $config['output_compression'], 1 ); ?>>
										<span><?php _e('Enable GZIP compression', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><strong><?php _e('Warning:', 'wp-performance-security'); ?></strong> <?php _e('this can sometimes interfere with other plugins. You can often enable GZIP compression from cPanel or Plesk, or request activation from your website hosting company.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Pings', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_self_ping" value="1" <?php if ( isset( $config['wpps_self_ping'] ) ) checked( $config['wpps_self_ping'], 1 ); ?>>
										<span><?php _e('Disable self-ping', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('Stops WordPress from registering internal links as ‘pings’.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Version strings', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_remove_script_version" value="1" <?php if ( isset( $config['wpps_remove_script_version'] ) ) checked( $config['wpps_remove_script_version'], 1 ); ?>>
										<span><?php _e('Remove the version query strings from scripts and styles', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('Query strings can cause problems for browser caching. Some browsers don’t cache files with query strings.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Styles & Scripts', 'wp-performance-security'); ?></th>
							<td>
								<p class="description"><?php _e('WordPress core and most plugins often include extra CSS and JavaScript. These additional HTTP requests can slow your page load times and increase your overall page weight. If you aren’t using the features these files provide, disable the scripts and styles.', 'wp-performance-security'); ?></p><br>
								<?php if (is_plugin_active('jetpack/jetpack.php')) : ?>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_jetpack_devicepx" value="1" <?php if ( isset( $config['wpps_jetpack_devicepx'] ) ) checked( $config['wpps_jetpack_devicepx'], 1 ); ?>>
										<span><?php _e('Remove <code>devicepx</code> script', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('The Jetpack plugin includes a script called <code>devicepx</code> that handles support for retina/HiDPI versions of files  such as Gravatars. Remove if unnecessary.', 'wp-performance-security'); ?></p>
								</fieldset>
								<br>
							<?php endif; ?>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_emoji_support" value="1" <?php if ( isset( $config['wpps_emoji_support'] ) ) checked( $config['wpps_emoji_support'], 1 ); ?>>
										<span><?php _e('Remove emoji support', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('If you don’t use emojis, disable support. This will remove unnecessary styles and scripts.', 'wp-performance-security'); ?></p>
								</fieldset>
								<br>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_jquery_migrate" value="1" <?php if ( isset( $config['wpps_jquery_migrate'] ) ) checked( $config['wpps_jquery_migrate'], 1 ); ?>>
										<span><?php _e('Disable jQuery Migrate', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('jQuery Migrate is a plugin that restores deprecated jQuery APIs and alerts developers if they are using legacy code. It isn’t meant for production sites, however jquery-migrate.js is a jQuery dependency in WordPress. Disable it, if possible.', 'wp-performance-security'); ?></p>
								</fieldset>
								<br>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_block_library_css" value="1" <?php if ( isset( $config['wpps_block_library_css'] ) ) checked( $config['wpps_block_library_css'], 1 ); ?>>
										<span><?php _e('Disable Block Library stylesheet', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('WordPress includes a stylesheet for blocks added via the Gutenberg editor. If your theme provides the necessary front-end styles you can disable the Block Library stylesheet.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Embeds', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_embeds" value="1" <?php if ( isset( $config['wpps_embeds'] ) ) checked( $config['wpps_embeds'], 1 ); ?>>
										<span><?php _e('Disable embeds and associated scripts', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('In version 4.4, WordPress introduced support for oEmbed endpoints, allowing your WordPress content to be embedded in other sites. If you don’t want this functionality, disable it.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>
<?php /*
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Revisions', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_revisions" value="1" <?php if ( isset( $config['wpps_revisions'] ) ) checked( $config['wpps_revisions'], 1 ); ?>>
										<span><?php _e('Disable revisions', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('WordPress revisions allow you to view changes and restore previous versions of posts. However revisions will increase the size of your database and on large sites with numerous revisions, this may impact performance.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>
*/ ?>
				</div>

				<div id="tabs-3" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('WordPress Version', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_remove_wp_version" value="1" <?php if ( isset( $config['wpps_remove_wp_version'] ) ) checked( $config['wpps_remove_wp_version'], 1 ); ?>>
										<span><?php _e('Remove the WordPress version number', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('This stops potential hackers from being able to identify which version of WordPress you are using and what vulnerabilities you might be exposed to.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('XMLRPC', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="xmlrpc_enabled" value="1" <?php if ( isset( $config['xmlrpc_enabled'] ) ) checked( $config['xmlrpc_enabled'], 1 ); ?>>
										<span><?php _e('Disable XMLRPC', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><strong><?php _e('Warning:', 'wp-performance-security'); ?></strong><?php _e('This will disable external editors that rely on XMLRPC to connect with your WordPress installion.', 'wp-performance-security'); ?></p>
								</fieldset>
								<br>
								<fieldset>
									<label>
										<input type="checkbox" name="atom_service_url_filter" value="1" <?php if ( isset( $config['atom_service_url_filter'] ) ) checked( $config['atom_service_url_filter'], 1 ); ?>>
										<span><?php _e('Disable XMLRPC SSL Testing', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('Prevents WordPress from testing XMLRPC SSL capability when XMLRPC not in use.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Comments', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_hide_existing_comments" value="1" <?php if ( isset( $config['wpps_hide_existing_comments'] ) ) checked( $config['wpps_hide_existing_comments'], 1 ); ?>>
										<span><?php _e('Hide existing comments', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('Selecting this option doesn’t remove comments from the database, it stops comments being returned by WordPress queries.', 'wp-performance-security'); ?></p>
								</fieldset>
								<br>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_closeCommentsGlobaly" value="1" <?php if ( isset( $config['wpps_closeCommentsGlobaly'] ) ) checked( $config['wpps_closeCommentsGlobaly'], 1 ); ?>>
										<span><?php _e('Disable comments', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<div class="wpps_menu_comments_sub">
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_media_comment_status" value="1" <?php if ( isset( $config['wpps_media_comment_status'] ) ) checked( $config['wpps_media_comment_status'], 1 ); ?>>
											<span><?php _e('Disable comments on media files', 'wp-performance-security'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_clickable_comments" value="1" <?php if ( isset( $config['wpps_clickable_comments'] ) ) checked( $config['wpps_clickable_comments'], 1 ); ?>>
											<span><?php _e('Disable active links in comments', 'wp-performance-security'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_comment_url" value="1" <?php if ( isset( $config['wpps_comment_url'] ) ) checked( $config['wpps_comment_url'], 1 ); ?>>
											<span><?php _e('Remove the ‘URL’ field from the comments form', 'wp-performance-security'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="number" class="small-text" min="0" name="wpps_minimum_comment_length" value="<?php if ( isset( $config['wpps_minimum_comment_length'] ) ) echo $config['wpps_minimum_comment_length']; ?>">
											<span><?php _e('Minimum number of characters required in a comment', 'wp-performance-security'); ?></span>
										</label>
									</fieldset>
								</div>
							</td>
						</tr>
					</table>

					<hr>

				</div>

				<div id="tabs-4" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Admin Bar', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_admin_bar" value="1" <?php if ( isset( $config['wpps_admin_bar'] ) ) checked( $config['wpps_admin_bar'], 1 ); ?>>
										<span><?php _e('Hide the Admin bar from front-facing pages', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="wpps_replace_howdy"><?php _e('WordPress greeting', 'wp-performance-security'); ?></label>
							</th>
							<td>
								<input type="text" class="regular-text" name="wpps_replace_howdy" value="<?php if ( isset( $config['wpps_replace_howdy'] ) ) echo $config['wpps_replace_howdy']; ?>">
								<p class="description"><?php _e('Change the default WordPress greeting on Admin pages.', 'wp-performance-security'); ?></p>
							</td>
						</tr>
					</table>

					<hr>

				<?php
					global $wp_version;
					$major_version = substr($wp_version, 0, 3);
					if ($major_version < 4.7) :
				?>
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Open Sans font', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_remove_wp_open_sans" value="1" <?php if ( isset( $config['wpps_remove_wp_open_sans'] ) ) checked( $config['wpps_remove_wp_open_sans'], 1 ); ?>>
										<span><?php _e('Remove ‘Open Sans’ font from Admin pages', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('WordPress uses the Open Sans font from Google webfonts on Admin pages. Remove this if it is causing errors or you don’t want the additional overhead.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

				<?php endif; ?>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Dashboard Widgets', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_activity" value="1" <?php if ( isset( $config['wpps_dash_activity'] ) ) checked( $config['wpps_dash_activity'], 1 ); ?>>
										<span><?php _e('Remove ‘Activity’ widget', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_primary" value="1" <?php if ( isset( $config['wpps_dash_primary'] ) ) checked( $config['wpps_dash_primary'], 1 ); ?>>
										<span><?php _e('Remove ‘WordPress Blog’ widget', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_secondary" value="1" <?php if ( isset( $config['wpps_dash_secondary'] )  ) checked( $config['wpps_dash_secondary'], 1 ); ?>>
										<span><?php _e('Remove ‘Other WordPress News’ widget', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_right_now" value="1" <?php if ( isset( $config['wpps_dash_right_now'] ) ) checked( $config['wpps_dash_right_now'], 1 ); ?>>
										<span><?php _e('Remove ‘Right Now’ widget', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_incoming_links" value="1" <?php if ( isset( $config['wpps_dash_incoming_links'] ) ) checked( $config['wpps_dash_incoming_links'], 1 ); ?>>
										<span><?php _e('Remove ‘Incoming Links’ widget', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_plugins" value="1" <?php if ( isset( $config['wpps_dash_plugins'] ) ) checked( $config['wpps_dash_plugins'], 1 ); ?>>
										<span><?php _e('Remove ‘Plugins’ widget', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_quick_press" value="1" <?php if ( isset( $config['wpps_dash_quick_press'] ) ) checked( $config['wpps_dash_quick_press'], 1 ); ?>>
										<span><?php _e('Remove ‘Quick Press’ widget', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_recent_drafts" value="1" <?php if ( isset( $config['wpps_dash_recent_drafts'] ) ) checked( $config['wpps_dash_recent_drafts'], 1 ); ?>>
										<span><?php _e('Remove ‘Recent Drafts’ widget', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_recent_comments" value="1" <?php if ( isset( $config['wpps_dash_recent_comments'] ) ) checked( $config['wpps_dash_recent_comments'], 1 ); ?>>
										<span><?php _e('Remove ‘Recent Comments’ widget', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Menu items', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_menu_wp" value="1" <?php if ( isset( $config['wpps_menu_wp'] ) ) checked( $config['wpps_menu_wp'], 1 ); ?>>
										<span><?php _e('Remove WordPress menu', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<p class="description"><?php _e('Remove the ‘WordPress’ menu from the top left of the Admin section or select individual options to remove.', 'wp-performance-security'); ?></p>
								<br>

								<div class="wpps_menu_wp_sub">
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_about" value="1" <?php if ( isset( $config['wpps_menu_about'] ) ) checked( $config['wpps_menu_about'], 1 ); ?>>
											<span><?php _e('About', 'wp-performance-security'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_wporg" value="1" <?php if ( isset( $config['wpps_menu_wporg'] ) ) checked( $config['wpps_menu_wporg'], 1 ); ?>>
											<span><?php _e('WordPress.org', 'wp-performance-security'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_documentation" value="1" <?php if ( isset( $config['wpps_menu_documentation'] ) ) checked( $config['wpps_menu_documentation'], 1 ); ?>>
											<span><?php _e('Documentation', 'wp-performance-security'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_forums" value="1" <?php if ( isset( $config['wpps_menu_forums'] ) ) checked( $config['wpps_menu_forums'], 1 ); ?>>
											<span><?php _e('Support Forums', 'wp-performance-security'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_feedback" value="1" <?php if ( isset( $config['wpps_menu_feedback'] ) ) checked( $config['wpps_menu_feedback'], 1 ); ?>>
											<span><?php _e('Feedback', 'wp-performance-security'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_site" value="1" <?php if ( isset( $config['wpps_menu_site'] ) ) checked( $config['wpps_menu_site'], 1 ); ?>>
											<span><?php _e('View Site', 'wp-performance-security'); ?></span>
										</label>
									</fieldset>
								</div>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Statistics', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="stats_admin_footer" value="1" <?php if ( isset( $config['stats_admin_footer'] ) ) checked( $config['stats_admin_footer'], 1 ); ?>>
										<span><?php _e('Show database statistics', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
								<p class="description"><?php _e('Display database queries, time spent and memory consumption in the footer of Admin pages.', 'wp-performance-security'); ?></p>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('“All Settings” menu', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_all_settings_link" value="1" <?php if ( isset( $config['wpps_all_settings_link'] ) ) checked( $config['wpps_all_settings_link'], 1 ); ?>>
										<span><?php _e('Add new Admin menu item “All Settings”', 'wp-performance-security'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

				</div>

				<div id="tabs-5" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="wpss_custom_login_logo"><?php _e('Login logo', 'wp-performance-security'); ?></label>
							</th>
							<td>
								<input type="text" class="regular-text code" name="wpss_custom_login_logo" value="<?php if ( isset( $config['wpss_custom_login_logo'] ) ) echo $config['wpss_custom_login_logo']; ?>">
								<span class="description"><?php _e('URL of custom image, 300px x 200px', 'wp-performance-security'); ?></span>
								<p class="description"><?php _e('Use a custom logo on the login page. Leave blank for default.', 'wp-performance-security'); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpps_custom_login_url"><?php _e('Login URL', 'wp-performance-security'); ?></label>
							</th>
							<td>
								<input type="text" class="regular-text code" name="wpps_custom_login_url" value="<?php if ( isset( $config['wpps_custom_login_url'] ) ) echo $config['wpps_custom_login_url']; ?>">
								<p class="description"><?php _e('Use a custom URL on the login page logo. Leave blank for default.', 'wp-performance-security'); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpps_custom_login_title"><?php _e('URL title attribute', 'wp-performance-security'); ?></label>
							</th>
							<td>
								<input type="text" name="wpps_custom_login_title" class="regular-text" value="<?php if ( isset( $config['wpps_custom_login_title'] ) ) echo $config['wpps_custom_login_title']; ?>">
								<p class="description"><?php _e('Custom login URL Title Attribute. Leave blank for default.', 'wp-performance-security'); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Errors', 'wp-performance-security'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="login_errors" value="1" <?php if ( isset( $config['login_errors'] ) ) checked( $config['login_errors'], 1 ); ?>>
										<span><?php _e('Hide detailed login form error messages', 'wp-performance-security'); ?></span>
									</label>
									<p class="description"><?php _e('By default WordPress shows detailed errors for failed login attempts. This can be a security risk.', 'wp-performance-security'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

				</div>

			</div>

			<p class="submit">
				<input type="submit" name="submit" class="button button-primary" value="<?php _e('Save All Changes', 'wp-performance-security'); ?>">
			</p>

		</form>

	</div>

	<?php
	}
