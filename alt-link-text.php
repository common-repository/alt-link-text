<?php
/*
Plugin Name: Alt Link Text
Plugin URI: http://www.technokinetics.com/plugins/alt-link-text
Description: Alt Link Text adds "Alternative Link Text" and "Alternative Title Attribute" fields to the Write Page and Manage Page screens. These can be used to specify alternative link text and title attributes to be used in place of the page title in page lists generated using the wp_list_pages() function.
Version: 1.1
Author: Tim Holt
Author URI: http://www.technokinetics.com/

    Copyright 2008 Tim Holt (tim@technokinetics.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// HOOKS

register_activation_hook(__FILE__,'wp_alt_link_text_install');
register_deactivation_hook( __FILE__, 'wp_alt_link_text_uninstall' );
add_action('admin_menu', 'wp_alt_link_text_add_field');
add_action('save_post', 'save_alt_link_text');
add_filter('wp_list_pages_excludes', 'page_exclusions');
add_filter('wp_list_pages', 'wp_alt_link_text');



// ACTIVATION

function wp_alt_link_text_install() {
	global $wpdb;
	$posts_table = $wpdb->prefix . 'posts';
	mysql_query("ALTER TABLE " . $posts_table . " ADD show_in_menu TINYINT(1) DEFAULT 1 NOT NULL AFTER post_title");
	mysql_query("ALTER TABLE " . $posts_table . " ADD alt_link_text VARCHAR(60) AFTER show_in_menu");
	mysql_query("ALTER TABLE " . $posts_table . " ADD alt_title_attribute VARCHAR(100) AFTER alt_link_text");
}



// DEACTIVATION

function wp_alt_link_text_uninstall() {
	global $wpdb;
	$posts_table = $wpdb->prefix . 'posts';
	// If the following lines are uncommented, then deactivating the plugin will remove any trace of it
	// mysql_query("ALTER TABLE " . $posts_table . " DROP show_in_menu");
	// mysql_query("ALTER TABLE " . $posts_table . " DROP alt_link_text");
	// mysql_query("ALTER TABLE " . $posts_table . " DROP alt_title_attribute");
}



// ADD FIELD

function wp_alt_link_text_add_field() {
	if (function_exists('add_meta_box')) {
		add_meta_box('alt_link_text_box', 'Alt-Link-Text', 'alt_link_text_inner', 'page', 'normal', 'low');
	}
}

function alt_link_text_inner() {
	global $post, $show_in_menu, $alt_link_text, $alt_title_attribute; ?>
	<p><input type="checkbox" id="show_in_menu" name="show_in_menu" <?php if (!isset($post->show_in_menu) || $post->show_in_menu == 1) { echo 'checked="checked" '; } ?>/> If this box is checked, then this Page will appear in page lists generated using wp_list_pages().</p>
	<p><input type="text" id="alt_link_text" name="alt_link_text" value="<?php echo $post->alt_link_text; ?>" /> This link text will be used in page lists generated using wp_list_pages().</p>
	<p><input type="text" id="alt_title_attribute" name="alt_title_attribute" value="<?php echo $post->alt_title_attribute; ?>" /> This title attribute will be used in page lists generated using wp_list_pages().</p><?php
}

// SAVE ALT ANCHOR

function save_alt_link_text() {

	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} else {
		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	}
	
	global $wpdb;
	$posts_table = $wpdb->prefix . 'posts';

	if ($_POST[show_in_menu]) {
		mysql_query("UPDATE " . $posts_table . " SET show_in_menu = '1' WHERE ID = $_POST[ID]");
	} else {
		mysql_query("UPDATE " . $posts_table . " SET show_in_menu = '0' WHERE ID = $_POST[ID]");
	}
	
	if ($_POST[alt_link_text] == "") {
		mysql_query("UPDATE " . $posts_table . " SET alt_link_text = null WHERE ID = $_POST[ID]");
	} else {
		mysql_query("UPDATE " . $posts_table . " SET alt_link_text = '" . $_POST[alt_link_text] . "' WHERE ID = $_POST[ID]");
	}
	
	if ($_POST[alt_title_attribute] == "") {
		mysql_query("UPDATE " . $posts_table . " SET alt_title_attribute = null WHERE ID = $_POST[ID]");
	} else {
		mysql_query("UPDATE " . $posts_table . " SET alt_title_attribute = '" . $_POST[alt_title_attribute] . "' WHERE ID = $_POST[ID]");
	}
}


// EXCLUDE PAGES FROM PAGE LIST

function page_exclusions($page_exclusions) {
	global $wpdb;
	$posts_table = $wpdb->prefix . 'posts';
	$page_exlusions_data = mysql_query("SELECT ID FROM " . $posts_table . " WHERE show_in_menu = '0' AND post_status = 'publish'");
	while ($row = mysql_fetch_assoc($page_exlusions_data)) {
		extract($row);
		$page_exclusions[] = $ID;
	}
	return $page_exclusions;
}



// REPLACE TITLES IN WP-LIST-PAGES RESULTS

function wp_alt_link_text($output) {	
	global $wpdb;
	$posts_table = $wpdb->prefix . 'posts';
	$alt_link_text_data = mysql_query("SELECT post_title, alt_link_text FROM " . $posts_table . " WHERE alt_link_text IS NOT NULL AND post_status = 'publish'");
	while ($row = mysql_fetch_assoc($alt_link_text_data)) {
		extract($row);
		$post_title = wptexturize($post_title);
		$output = str_replace('>' . $post_title, '>' . $alt_link_text, $output);
	}
	$alt_title_attribute_data = mysql_query("SELECT post_title, alt_title_attribute FROM " . $posts_table . " WHERE alt_title_attribute IS NOT NULL AND post_status = 'publish'");
	while ($row = mysql_fetch_assoc($alt_title_attribute_data)) {
		extract($row);
		$post_title = wptexturize($post_title);		
		$output = str_replace('title="' . $post_title, 'title="' . $alt_title_attribute, $output);
	}
	return $output;
}

?>