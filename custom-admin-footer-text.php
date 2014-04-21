<?php
/*
Plugin Name: Custom Admin Footer Text
Plugin URI: http://www.jimmyscode.com/wordpress/custom-admin-footer-text/
Description: Change the admin footer to your own custom text.
Version: 0.0.3
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/

define('CAFT_PLUGIN_NAME', 'Custom Admin Footer Text');
	// plugin constants
	define('CAFT_VERSION', '0.0.3');
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
	
	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', CAFT_LOCAL));
	}

	// delete option when plugin is uninstalled
	register_uninstall_hook(__FILE__, 'uninstall_caft_plugin');
	function uninstall_caft_plugin() {
		delete_option(CAFT_OPTION);
	}
	// localization to allow for translations
	add_action('init', 'caft_translation_file');
	function caft_translation_file() {
		$plugin_path = plugin_basename(dirname(__FILE__) . '/translations');
		load_plugin_textdomain(CAFT_LOCAL, '', $plugin_path);
	}
	// tell WP that we are going to use new options
	// also, register the admin CSS file for later inclusion
	add_action('admin_init', 'caft_options_init');
	function caft_options_init() {
		register_setting(CAFT_OPTIONS_NAME, CAFT_OPTION, 'caft_validation');
		register_caft_admin_style();
	}
	// validation function
	function caft_validation($input) {
		// sanitize textarea
		// how?????
		$input[CAFT_DEFAULT_FOOTERTEXT_NAME] = wp_kses_post(force_balance_tags($input[CAFT_DEFAULT_FOOTERTEXT_NAME]));
		return $input;
	} 

	// add Settings sub-menu
	add_action('admin_menu', 'caft_plugin_menu');
	function caft_plugin_menu() {
		add_options_page(CAFT_PLUGIN_NAME, CAFT_PLUGIN_NAME, CAFT_PERMISSIONS_LEVEL, CAFT_SLUG, 'caft_page');
	}
	// plugin settings page
	// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	function caft_page() {
		// check perms
		if (!current_user_can(CAFT_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', CAFT_LOCAL));
		}
		?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/bottom.png')) ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo CAFT_PLUGIN_NAME; _e(' by ', CAFT_LOCAL); ?><a href="http://www.jimmyscode.com/">Jimmy Pe&ntilde;a</a></h2>
			<div><?php _e('You are running plugin version', CAFT_LOCAL); ?> <strong><?php echo CAFT_VERSION; ?></strong>.</div>
			<form method="post" action="options.php">
			<?php settings_fields(CAFT_OPTIONS_NAME); ?>
			<?php $options = caft_getpluginoptions(); ?>
			<?php update_option(CAFT_OPTION, $options); ?>
			<h3 id="settings"><img src="<?php echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/settings.png')) ?>" title="" alt="" height="61" width="64" align="absmiddle" /> <?php _e('Plugin Settings', CAFT_LOCAL); ?></h3>
				<?php submit_button(); ?>
				<table class="form-table" id="theme-options-wrap">
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', CAFT_LOCAL); ?>" for="<?php echo CAFT_OPTION; ?>[<?php echo CAFT_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', CAFT_LOCAL); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo CAFT_OPTION; ?>[<?php echo CAFT_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo CAFT_OPTION; ?>[<?php echo CAFT_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', $options[CAFT_DEFAULT_ENABLED_NAME]); ?> /></td>
					</tr>
					<tr valign="top"><td colspan="2"><?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', CAFT_LOCAL); ?></td></tr>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter custom left footer text', CAFT_LOCAL); ?>" for="<?php echo CAFT_OPTION; ?>[<?php echo CAFT_DEFAULT_FOOTERTEXT_NAME; ?>]"><?php _e('Enter custom left footer text', CAFT_LOCAL); ?></label></strong></th>
						<td><textarea rows="12" cols="75" id="<?php echo CAFT_OPTION; ?>[<?php echo CAFT_DEFAULT_FOOTERTEXT_NAME; ?>]" name="<?php echo CAFT_OPTION; ?>[<?php echo CAFT_DEFAULT_FOOTERTEXT_NAME; ?>]"><?php echo $options[CAFT_DEFAULT_FOOTERTEXT_NAME]; ?></textarea></td>
					</tr>
					<tr valign="top"><td colspan="2"><?php _e('Type the custom footer text you want to appear in the lower <strong>left</strong> corner of admin menu pages. HTML allowed.', CAFT_LOCAL); ?></td></tr>

					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter custom right footer text', CAFT_LOCAL); ?>" for="<?php echo CAFT_OPTION; ?>[<?php echo CAFT_DEFAULT_RIGHTFOOTER_NAME; ?>]"><?php _e('Enter custom right footer text', CAFT_LOCAL); ?></label></strong></th>
						<td><textarea rows="12" cols="75" id="<?php echo CAFT_OPTION; ?>[<?php echo CAFT_DEFAULT_RIGHTFOOTER_NAME; ?>]" name="<?php echo CAFT_OPTION; ?>[<?php echo CAFT_DEFAULT_RIGHTFOOTER_NAME; ?>]"><?php echo $options[CAFT_DEFAULT_RIGHTFOOTER_NAME]; ?></textarea></td>
					</tr>
					<tr valign="top"><td colspan="2"><?php _e('Type the custom footer text you want to appear in the lower <strong>right</strong> corner of admin menu pages. HTML allowed.', CAFT_LOCAL); ?></td></tr>					

				</table>
				<?php submit_button(); ?>
			</form>
			<hr />
			<h3 id="support"><img src="<?php echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/support.png')) ?>" title="" alt="" height="64" width="64" align="absmiddle" /> Support</h3>
				<div class="support">
				<?php echo '<a href="http://wordpress.org/extend/plugins/' . CAFT_SLUG . '/">' . __('Documentation', CAFT_LOCAL) . '</a> | ';
					echo '<a href="http://wordpress.org/plugins/' . CAFT_SLUG . '/faq/">' . __('FAQ', CAFT_LOCAL) . '</a><br />';
					_e('If you like this plugin, please ', CAFT_LOCAL);
					echo '<a href="http://wordpress.org/support/view/plugin-reviews/' . CAFT_SLUG . '/">';
					_e('rate it on WordPress.org', CAFT_LOCAL);
					echo '</a> ';
					_e('and click the ', CAFT_LOCAL);
					echo '<a href="http://wordpress.org/plugins/' . CAFT_SLUG .  '/#compatibility">';
					_e('Works', CAFT_LOCAL);
					echo '</a> ';
					_e('button. For support please visit the ', CAFT_LOCAL);
					echo '<a href="http://wordpress.org/support/plugin/' . CAFT_SLUG . '">';
					_e('forums', CAFT_LOCAL);
					echo '</a>.';
				?>
				<br /><br />
				<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Donate with PayPal" width="92" height="26" /></a>
				</div>
		</div>
		<?php }

	// main function and filter
	add_filter('admin_footer_text', 'caft_remove_footer_admin', 1);
	add_filter('update_footer', 'caft_change_right_footer',999);
	function caft_remove_footer_admin($default) {
		$options = caft_getpluginoptions();
		$enabled = $options[CAFT_DEFAULT_ENABLED_NAME];
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
		$enabled = $options[CAFT_DEFAULT_ENABLED_NAME];
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
				if ($_GET['page'] == CAFT_SLUG) { // we are on this plugin's settings page
					$options = caft_getpluginoptions();
					if ($options != false) {
						$enabled = $options[CAFT_DEFAULT_ENABLED_NAME];
						if (!$enabled) {
							echo '<div id="message" class="error">' . CAFT_PLUGIN_NAME . ' ' . __('is currently disabled.', CAFT_LOCAL) . '</div>';
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
				if ($_GET['page'] == CAFT_SLUG) { // we are on this plugin's settings page
					caft_admin_styles();
				}
			}
		}
	}
	// add settings link on plugin page
	// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'caft_plugin_settings_link');
	function caft_plugin_settings_link($links) {
		$settings_link = '<a href="options-general.php?page=' . CAFT_SLUG . '">' . __('Settings', CAFT_LOCAL) . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}
	// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
	add_filter('plugin_row_meta', 'caft_meta_links', 10, 2);
	function caft_meta_links($links, $file) {
		$plugin = plugin_basename(__FILE__);
		// create link
		if ($file == $plugin) {
			$links = array_merge($links,
				array(
					'<a href="http://wordpress.org/support/plugin/' . CAFT_SLUG . '">' . __('Support', CAFT_LOCAL) . '</a>',
					'<a href="http://wordpress.org/extend/plugins/' . CAFT_SLUG . '/">' . __('Documentation', CAFT_LOCAL) . '</a>',
					'<a href="http://wordpress.org/plugins/' . CAFT_SLUG . '/faq/">' . __('FAQ', CAFT_LOCAL) . '</a>'
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
			plugins_url(CAFT_PATH . '/css/admin.css'),
			array(),
			CAFT_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all');
	}
	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'caft_activate');
	function caft_activate() {
		$options = caft_getpluginoptions();
		update_option(CAFT_OPTION, $options);
	}
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function caft_getpluginoptions() {
		return get_option(CAFT_OPTION, 
			array(
				CAFT_DEFAULT_ENABLED_NAME => CAFT_DEFAULT_ENABLED, 
				CAFT_DEFAULT_FOOTERTEXT_NAME => CAFT_DEFAULT_FOOTERTEXT, 
				CAFT_DEFAULT_RIGHTFOOTER_NAME => CAFT_DEFAULT_RIGHTFOOTER
			));
	}
?>