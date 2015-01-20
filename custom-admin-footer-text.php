<?php
/*
Plugin Name: Custom Admin Footer Text
Plugin URI: http://www.jimmyscode.com/wordpress/custom-admin-footer-text/
Description: Change the admin footer to your own custom text.
Version: 0.1.1
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/

if (!defined('CAFT_PLUGIN_NAME')) {
	// plugin constants
	define('CAFT_PLUGIN_NAME', 'Custom Admin Footer Text');
	define('CAFT_VERSION', '0.1.1');
	define('CAFT_SLUG', 'custom-admin-footer-text');
	define('CAFT_LOCAL', 'caft');
	define('CAFT_OPTION', 'caft');
	define('CAFT_OPTIONS_NAME', 'caft_options');
	define('CAFT_PERMISSIONS_LEVEL', 'manage_options');
	define('CAFT_PATH', plugin_basename(dirname(__FILE__)));
	/* default values */
	define('CAFT_DEFAULT_ENABLED', true);
	define('CAFT_DEFAULT_FOOTERTEXT', '');
	define('CAFT_DEFAULT_RIGHTFOOTER', '');
	/* option array member names */
	define('CAFT_DEFAULT_ENABLED_NAME', 'enabled');
	define('CAFT_DEFAULT_FOOTERTEXT_NAME', 'footertext');
	define('CAFT_DEFAULT_RIGHTFOOTER_NAME', 'rightfooter');
}
	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', caft_get_local()));
	}

	// localization to allow for translations
	add_action('init', 'caft_translation_file');
	function caft_translation_file() {
		$plugin_path = caft_get_path() . '/translations';
		load_plugin_textdomain(caft_get_local(), '', $plugin_path);
	}
	// tell WP that we are going to use new options
	// also, register the admin CSS file for later inclusion
	add_action('admin_init', 'caft_options_init');
	function caft_options_init() {
		register_setting(CAFT_OPTIONS_NAME, caft_get_option(), 'caft_validation');
		register_caft_admin_style();
	}
	// validation function
	function caft_validation($input) {
		// validate all form fields
		if (!empty($input)) {
			$input[CAFT_DEFAULT_ENABLED_NAME] = (bool)$input[CAFT_DEFAULT_ENABLED_NAME];
			$input[CAFT_DEFAULT_RIGHTFOOTER_NAME] = wp_kses_post(force_balance_tags($input[CAFT_DEFAULT_RIGHTFOOTER_NAME]));
			$input[CAFT_DEFAULT_FOOTERTEXT_NAME] = wp_kses_post(force_balance_tags($input[CAFT_DEFAULT_FOOTERTEXT_NAME]));
		}
		return $input;
	} 

	// add Settings sub-menu
	add_action('admin_menu', 'caft_plugin_menu');
	function caft_plugin_menu() {
		add_options_page(CAFT_PLUGIN_NAME, CAFT_PLUGIN_NAME, CAFT_PERMISSIONS_LEVEL, caft_get_slug(), 'caft_page');
	}
	// plugin settings page
	// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	function caft_page() {
		// check perms
		if (!current_user_can(CAFT_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', caft_get_local()));
		}
		?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo caft_getimagefilename('bottom.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo CAFT_PLUGIN_NAME; _e(' by ', caft_get_local()); ?><a href="http://www.jimmyscode.com/">Jimmy Pe&ntilde;a</a></h2>
			<div><?php _e('You are running plugin version', caft_get_local()); ?> <strong><?php echo CAFT_VERSION; ?></strong>.</div>
			
			<?php /* http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-5-tabbed-navigation-for-your-settings-page--wp-24971 */ ?>
			<?php $active_tab = (isset($_GET['tab']) ? $_GET['tab'] : 'settings'); ?>
			<h2 class="nav-tab-wrapper">
			  <a href="?page=<?php echo caft_get_slug(); ?>&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', caft_get_local()); ?></a>
				<a href="?page=<?php echo caft_get_slug(); ?>&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', caft_get_local()); ?></a>
			</h2>
			
			<form method="post" action="options.php">
				<?php settings_fields(CAFT_OPTIONS_NAME); ?>
				<?php $options = caft_getpluginoptions(); ?>
				<?php update_option(caft_get_option(), $options); ?>
				<?php if ($active_tab == 'settings') { ?>
					<h3 id="settings"><img src="<?php echo caft_getimagefilename('settings.png'); ?>" title="" alt="" height="61" width="64" align="absmiddle" /> <?php _e('Plugin Settings', caft_get_local()); ?></h3>
					<table class="form-table" id="theme-options-wrap">
						<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', caft_get_local()); ?>" for="<?php echo caft_get_option(); ?>[<?php echo CAFT_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', caft_get_local()); ?></label></strong></th>
							<td><input type="checkbox" id="<?php echo caft_get_option(); ?>[<?php echo CAFT_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo caft_get_option(); ?>[<?php echo CAFT_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', caft_checkifset(CAFT_DEFAULT_ENABLED_NAME, CAFT_DEFAULT_ENABLED, $options)); ?> /></td>
						</tr>
						<?php caft_explanationrow(__('Is plugin enabled? Uncheck this to turn it off temporarily.', caft_get_local())); ?>
						<?php caft_getlinebreak(); ?>
						<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter custom left footer text', caft_get_local()); ?>" for="<?php echo caft_get_option(); ?>[<?php echo CAFT_DEFAULT_FOOTERTEXT_NAME; ?>]"><?php _e('Enter custom left footer text', caft_get_local()); ?></label></strong></th>
							<td><textarea rows="12" cols="75" id="<?php echo caft_get_option(); ?>[<?php echo CAFT_DEFAULT_FOOTERTEXT_NAME; ?>]" name="<?php echo caft_get_option(); ?>[<?php echo CAFT_DEFAULT_FOOTERTEXT_NAME; ?>]"><?php echo caft_checkifset(CAFT_DEFAULT_FOOTERTEXT_NAME, CAFT_DEFAULT_FOOTERTEXT, $options); ?></textarea></td>
						</tr>
						<?php caft_explanationrow(__('Type the custom footer text you want to appear in the lower <strong>left</strong> corner of admin menu pages. HTML allowed.', caft_get_local())); ?>
						<?php caft_getlinebreak(); ?>
						<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter custom right footer text', caft_get_local()); ?>" for="<?php echo caft_get_option(); ?>[<?php echo CAFT_DEFAULT_RIGHTFOOTER_NAME; ?>]"><?php _e('Enter custom right footer text', caft_get_local()); ?></label></strong></th>
							<td><textarea rows="12" cols="75" id="<?php echo caft_get_option(); ?>[<?php echo CAFT_DEFAULT_RIGHTFOOTER_NAME; ?>]" name="<?php echo caft_get_option(); ?>[<?php echo CAFT_DEFAULT_RIGHTFOOTER_NAME; ?>]"><?php echo caft_checkifset(CAFT_DEFAULT_RIGHTFOOTER_NAME, CAFT_DEFAULT_RIGHTFOOTER, $options); ?></textarea></td>
						</tr>
						<?php caft_explanationrow(__('Type the custom footer text you want to appear in the lower <strong>right</strong> corner of admin menu pages. HTML allowed.', caft_get_local())); ?>
						</table>
						<?php submit_button(); ?>
				<?php } else { ?>
					<h3 id="support"><img src="<?php echo caft_getimagefilename('support.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Support', caft_get_local()); ?></h3>
					<div class="support">
						<?php echo caft_getsupportinfo(caft_get_slug(), caft_get_local()); ?>
					</div>
				<?php } ?>
			</form>
		</div>
		<?php }

	// main function and filter
	add_filter('admin_footer_text', 'caft_remove_footer_admin', 1);
	add_filter('update_footer', 'caft_change_right_footer',999);
	function caft_remove_footer_admin($default) {
		$options = caft_getpluginoptions();
		if (!empty($options)) {
			$enabled = (bool)$options[CAFT_DEFAULT_ENABLED_NAME];
		} else {
			$enabled = CAFT_DEFAULT_ENABLED;
		}
		if ($enabled) {
			$ft = $options[CAFT_DEFAULT_FOOTERTEXT_NAME];
			if ($ft !== CAFT_DEFAULT_FOOTERTEXT) {
				return $ft;
			} else { // plugin enabled but nothing entered?
				return $default;
			}
		} else { // plugin disabled, show whatever was there before
			return $default;
		}
	}
	function caft_change_right_footer($default) {
		$options = caft_getpluginoptions();
		if (!empty($options)) {
			$enabled = (bool)$options[CAFT_DEFAULT_ENABLED_NAME];
		} else {
			$enabled = CAFT_DEFAULT_ENABLED;
		}
		if ($enabled) {
			$rft = $options[CAFT_DEFAULT_RIGHTFOOTER_NAME];
			if ($rft !== CAFT_DEFAULT_RIGHTFOOTER) {
				return $rft;
			} else { // plugin enabled but nothing entered?
				return $default;
			}
		} else { // plugin disabled, show whatever was there before
			return $default;
		}
	}
	
	// show admin messages to plugin user
	add_action('admin_notices', 'caft_showAdminMessages');
	function caft_showAdminMessages() {
		// http://wptheming.com/2011/08/admin-notices-in-wordpress/
		global $pagenow;
		if (current_user_can(CAFT_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if (isset($_GET['page'])) {
					if ($_GET['page'] == caft_get_slug()) { // we are on this plugin's settings page
						$options = caft_getpluginoptions();
						if (!empty($options)) {
							$enabled = (bool)$options[CAFT_DEFAULT_ENABLED_NAME];
							if (!$enabled) {
								echo '<div id="message" class="error">' . CAFT_PLUGIN_NAME . ' ' . __('is currently disabled.', caft_get_local()) . '</div>';
							}
						}
					}
				}
			} // end page check
		} // end privilege check
	} // end admin msgs function
	// enqueue admin CSS if we are on the plugin options page
	add_action('admin_head', 'insert_caft_admin_css');
	function insert_caft_admin_css() {
		global $pagenow;
		if (current_user_can(CAFT_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if (isset($_GET['page'])) {
					if ($_GET['page'] == caft_get_slug()) { // we are on this plugin's settings page
						caft_admin_styles();
					}
				}
			}
		}
	}
	// add helpful links to plugin page next to plugin name
	// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
	// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'caft_plugin_settings_link');
	add_filter('plugin_row_meta', 'caft_meta_links', 10, 2);
	
	function caft_plugin_settings_link($links) {
		return caft_settingslink($links, caft_get_slug(), caft_get_local());
	}
	function caft_meta_links($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$links = array_merge($links,
			array(
				sprintf(__('<a href="http://wordpress.org/support/plugin/%s">Support</a>', caft_get_local()), caft_get_slug()),
				sprintf(__('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', caft_get_local()), caft_get_slug()),
				sprintf(__('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a>', caft_get_local()), caft_get_slug())
			));
		}
		return $links;	
	}
	// enqueue/register the admin CSS file
	function caft_admin_styles() {
		wp_enqueue_style('caft_admin_style');
	}
	function register_caft_admin_style() {
		wp_register_style('caft_admin_style',
			plugins_url(caft_get_path() . '/css/admin.css'),
			array(),
			CAFT_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all');
	}
	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'caft_activate');
	function caft_activate() {
		$options = caft_getpluginoptions();
		update_option(caft_get_option(), $options);
		
		// delete option when plugin is uninstalled
		register_uninstall_hook(__FILE__, 'uninstall_caft_plugin');
	}
	function uninstall_caft_plugin() {
		delete_option(caft_get_option());
	}
		
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function caft_getpluginoptions() {
		return get_option(caft_get_option(), 
			array(
				CAFT_DEFAULT_ENABLED_NAME => CAFT_DEFAULT_ENABLED, 
				CAFT_DEFAULT_FOOTERTEXT_NAME => CAFT_DEFAULT_FOOTERTEXT, 
				CAFT_DEFAULT_RIGHTFOOTER_NAME => CAFT_DEFAULT_RIGHTFOOTER
			));
	}
	
	// encapsulate these and call them throughout the plugin instead of hardcoding the constants everywhere
	function caft_get_slug() { return CAFT_SLUG; }
	function caft_get_local() { return CAFT_LOCAL; }
	function caft_get_option() { return CAFT_OPTION; }
	function caft_get_path() { return CAFT_PATH; }
	
	function caft_settingslink($linklist, $slugname = '', $localname = '') {
		$settings_link = sprintf( __('<a href="options-general.php?page=%s">Settings</a>', $localname), $slugname);
		array_unshift($linklist, $settings_link);
		return $linklist;
	}
		function caft_getsupportinfo($slugname = '', $localname = '') {
		$output = __('Do you need help with this plugin? Check out the following resources:', $localname);
		$output .= '<ol>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/support/plugin/%s">Support Forum</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://www.jimmyscode.com/wordpress/%s">Plugin Homepage / Demo</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/developers/">Development</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/changelog/">Changelog</a><br />', $localname), $slugname) . '</li>';
		$output .= '</ol>';
		
		$output .= sprintf( __('If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/%s/">rate it on WordPress.org</a>', $localname), $slugname);
		$output .= sprintf( __(' and click the <a href="http://wordpress.org/plugins/%s/#compatibility">Works</a> button. ', $localname), $slugname);
		$output .= '<br /><br /><br />';
		$output .= __('Your donations encourage further development and support. ', $localname);
		$output .= '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Support this plugin" width="92" height="26" /></a>';
		$output .= '<br /><br />';
		return $output;
	}
	function caft_checkifset($optionname, $optiondefault, $optionsarr) {
		return (isset($optionsarr[$optionname]) ? $optionsarr[$optionname] : $optiondefault);
	}
	function caft_getlinebreak() {
	  echo '<tr valign="top"><td colspan="2"></td></tr>';
	}
	function caft_explanationrow($msg = '') {
		echo '<tr valign="top"><td></td><td><em>' . $msg . '</em></td></tr>';
	}
	function caft_getimagefilename($fname = '') {
		return plugins_url(caft_get_path() . '/images/' . $fname);
	}
?>