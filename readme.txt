=== Custom Admin Footer Text ===
Tags: footer, admin, custom, text, dev, client
Requires at least: 4.0
Tested up to: 4.1
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display custom text (or links) in the admin footer.

== Description ==

Custom Admin Footer Text lets you customize the left and right admin footer text. Overwrite the default text or links with anything of your choosing. Great for developers working on client sites who want to include support links, bylines or credits in the footer.

Currently the left footer says "Thank you for creating with WordPress" and the right footer shows the current WordPress version. For WordPress developers, this is a lost opportunity. Use that space to put links to your website, a copyright statement, whatever you want.

Use it for:

<ul>
<li>Copyright statements/credits</li>
<li>Link to support forum</li>
<li>Link to your other products</li>
</ul>

Or maybe you are just a regular guy who doesn't like the admin footer. Put a link to your site or whatever you want to see.

<h3>If you need help with this plugin</h3>

If this plugin breaks your site or just flat out does not work, create a thread in the <a href="http://wordpress.org/support/plugin/custom-admin-footer-text">Support</a> forum with a description of the issue. Make sure you are using the latest version of WordPress and the plugin before reporting issues, to be sure that the issue is with the current version and not with an older version where the issue may have already been fixed.

<strong>Please do not use the <a href="http://wordpress.org/support/view/plugin-reviews/custom-admin-footer-text">Reviews</a> section to report issues or request new features.</strong>

= Features =

<ul>
<li>Overwrite existing admin footer text with your own</li>
<li>Disable the plugin and the original text returns</li>
<li>Unclosed HTML tags are automatically fixed</li>
</ul>

== Installation ==

1. Upload plugin file through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings &raquo; Custom Admin Footer Text, configure plugin.
4. Reload the page to see the footer.

== Frequently Asked Questions ==

= How do I use the plugin? =

Go to Settings &raquo; Custom Admin Footer Text and enter the text you want to see in the left and right admin footers. Make sure the "enabled" checkbox is checked. Refresh the page and check the footer.

= I entered some text but don't see anything on the page. =

Are you using another plugin that is also trying to overwrite the admin footer?

Are you using any CSS that is hiding the footer?

Are you caching your admin pages?

= How can I style the output? =

This is the footer HTML output:

`<div id="wpfooter">
<p id="footer-left" class="alignleft"><!-- here is the left footer --></p>
<p id="footer-upgrade" class="alignright"><!-- here is the right footer --></p>
</div>`

Use these classes in your local stylesheet to style the output how you want.

= I don't want the admin CSS. How do I remove it? =

Add this to your functions.php:

`remove_action('admin_head', 'insert_caft_admin_css');`

== Screenshots ==

1. Plugin settings page where you can see the text entered and how it appears in the footer.

== Changelog ==

= 0.1.1 =
- confirmed compatibility with WordPress 4.1

= 0.1.0 =
- updated .pot file and readme

= 0.0.9 =
- fixed validation issue

= 0.0.8 =
- minor code update

= 0.0.7 =
- permanent fix for Undefined Index issue
- admin CSS and page updates

= 0.0.6 =
- code fix

= 0.0.5 = 
- code fix
- updated support tab

= 0.0.4 =
- code optimizations
- plugin settings page is now tabbed

= 0.0.3 =
- fix 2 for wp_kses

= 0.0.2 =
- fix for wp_kses

= 0.0.1 =
- created
- verified compatibility with 3.9

== Upgrade Notice ==

= 0.1.1 =
- confirmed compatibility with WordPress 4.1

= 0.1.0 =
- updated .pot file and readme

= 0.0.9 =
- fixed validation issue

= 0.0.8 =
- minor code update

= 0.0.7 =
- permanent fix for Undefined Index issue; admin CSS and page updates

= 0.0.6 =
- code fix

= 0.0.5 = 
- code fix; updated support tab

= 0.0.4 =
- code optimizations; plugin settings page is now tabbed

= 0.0.3 =
- fix 2 for wp_kses

= 0.0.2 =
- fix for wp_kses

= 0.0.1 =
created, verified compatibility with 3.9