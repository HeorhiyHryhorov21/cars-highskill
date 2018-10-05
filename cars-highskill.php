<?php 
/*
Plugin Name: Cars Highskill Plugin
Description: Simple Cars Widget Plugin
Version: 1.0
Author: George
*/

//Exit if Accessed Directly
if (!defined("ABSPATH")) {
	exit();
}

//Load Scripts
require_once(plugin_dir_path(__FILE__).'/includes/cars-highskill-scripts.php');

// Load Shortcodes
require_once(plugin_dir_path(__FILE__).'/includes/cars-highskill-shortcodes.php');

//Load Class
require_once(plugin_dir_path(__FILE__).'includes/cars-highskill-class.php');

// Check if admin
if (is_admin()) {
	// Load Custom Post Type
	require_once(plugin_dir_path(__FILE__).'/includes/cars-highskill-cpt.php');

	// Load Custom Fields
	require_once(plugin_dir_path(__FILE__).'/includes/cars-highskill-fields.php');
}
