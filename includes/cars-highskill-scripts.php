<?php

// Check if admin
if(is_admin()) {
	// Add Scripts
	function ch_add_admin_scripts() {
		wp_enqueue_style('ch-admin-style', plugins_url(). '/cars-highskill/css/style-admin.css');
	}

	add_action('admin_init', 'ch_add_admin_scripts');
}

// Add Scripts

function ch_add_scripts() {
	wp_enqueue_style('ch-style', plugins_url().'/cars-highskill/css/style.css');
	wp_enqueue_style('jquery-ui-css',
                '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',
                false,
                false);
	wp_enqueue_script( 'ch-script', plugins_url().'/cars-highskill/js/main.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'jquery-ui-accordion' );
}

add_action('wp_enqueue_scripts', 'ch_add_scripts');