<?php 

// Create Custom Post Type
function ch_register_cars() {
	$singular_name = apply_filters('ch_label_single', 'Car');
	$plural_name = apply_filters('ch_label_plural', 'Cars');

	$labels = array(
		'name' 					=> $plural_name,
		'singular_name' 		=> $singular_name,
		'add_new' 				=> 'Add New',
		'add_new_item' 			=> 'Add New '. $singular_name,
		'edit' 					=> 'Edit',
		'edit_item' 			=> 'Edit '. $singular_name,
		'new_item' 				=> 'New '. $singular_name,
		'view' 					=> 'View '. $singular_name,
		'view_item' 			=> 'View '. $plural_name,
		'search_items' 			=> 'Search '. $plural_name,
		'not_found' 			=> 'No ' . $plural_name . ' found',
		'not_found_in_trash' 	=> 'No ' . $plural_name. ' found',
		'menu_name'				=> $plural_name,


	);

	$args = apply_filters('ch_args', array(
		'labels' 			=> $labels,
		'description' 		=> 'Cars by models',
		'taxonomies' 		=> array('models'),
		'public' 			=> true,
		'show_in_menu' 		=> true,
		'menu_position' 	=> 5,
		'menu_icon' 		=> 'dashicons-performance',
		'show_in_nav_menus' => true,
		'query_var' 		=> true,
		'can_export' 		=> true,
		'has_archive'   	=> true,
		'rewrite' 			=> true,
		'capability_type' 	=> 'post',
		'supports' 			=> array(
			'title'
		),
	));

	// Register Post Type
	register_post_type('cars', $args);

}

add_action('init', 'ch_register_cars');

// Create Custom Taxonomies
function ch_custom_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Models', 'taxonomy general name' ),
    'singular_name' => _x( 'Model', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Models' ),
    'all_items' => __( 'All Models' ),
    'parent_item' => __( 'Parent Model' ),
    'parent_item_colon' => __( 'Parent Model:' ),
    'edit_item' => __( 'Edit Model' ), 
    'update_item' => __( 'Update Model' ),
    'add_new_item' => __( 'Add New Model' ),
    'new_item_name' => __( 'New Model Name' ),
    'menu_name' => __( 'Models' ),
  ); 	
 
  register_taxonomy('models',array('cars'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'models' ),
  ));
}
add_action( 'init', 'ch_custom_taxonomy');