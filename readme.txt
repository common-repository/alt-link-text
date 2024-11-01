=== Alt-Link-Text ===
Contributors: Technokinetics
Donate link: http://www.technokinetics.com/donations/
Tags: navigation, menu, page list, link text, title attribute, page title, wp_list_pages
Requires at least: 2.5
Tested up to: 2.6.1
Stable tag: 1.1

Alt Link Text can be used to modify the link text and title attributes of links in page lists, and to remove pages from page lists completely.

== Description ==

Alt Link Text can be used to modify the link text and title attributes of links in page lists, and to remove pages from page lists completely. By default, links in page lists generated using wp_list_pages use the page title as both the link text and the title attribute. This plugin adds "Alternative Link Text" and "Alternative Title Attribute" fields to the Write Page and Manage Page screens which can be used to specify alternative link text and title attributes. It also adds a checkbox that can be unchecked to remove a page from these lists.

This can be useful if your page titles are too long for your navigation menu (e.g. "Terms & Conditions" could be abbreviated to "T&C" to save space), or if you just want them to be different (e.g. you might want your home page to have "Welcome" as its title but to be linked to as "Home" in your navigation menu).

Alt Link Text has now evolved into <a href="http://wordpress.org/extend/plugins/page-lists-plus/">Page Lists Plus</a>.

== Installation ==

1. Download the plugin's zip file, extract the contents, and upload them to your wp-content/plugins folder.
2. Login to your WordPress dashboard, click ”Plugins”, and activate Alt Link Text.
3. Set the alternative link text and title attribute that you’d like to use through the Write Page or Manage Page screens. You will need to save the page for the changes to take effect.

== Frequently Asked Questions ==

= Why won't Alt-Link-Text work with older versions of WordPress? =

Alt-Link-Text uses the add_meta_box() function to create new fields on the Write Page and Manage Page screens. This function was introduced in WordPress 2.5.

= Will Alt-Link-Text work with WordPress MU? =

No. At the moment, Alt-Link-Text is not WPMU compatible.

= If I deactivate Alt-Link-Text, will my alternative link text and title attributes be deleted? =

No. If you want to remove all trace of the plugin from your database, then you need to uncomment lines 56-58 of your alt-link-text.php file and then deactivate the plugin.

== Screenshots ==