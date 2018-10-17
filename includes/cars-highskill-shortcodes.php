<?php

// Creating a Car Adding Shortcode
function add_car_form($atts, $content = null) {
	global $post;
	// Create attributes and defaults
	$atts = shortcode_atts(array(
		'title' => 'Add a Car',
		'category' => 'all',
	), $atts);
	// Check Category
	if ($atts['category'] == 'all') {
		$terms = '';
	} else {
		$terms = array(
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $atts['category']

			));
	}

	// Query Args
	$args = array(
		'post_type' 	=> 'cars',
		'post_status' 	=> 'publish',
		'orderby' 		=> 'created',
		'order' 		=> 'ASC',
		'post_per_page' => $atts['count'],
		'tax_query' 	=> $terms
	);

	// Fetch Posts
	$car_posts = new WP_Query($args);

	$output = '';
	$output .= '<form class="form-group">';
	$output .= '<h3>Title</h3>';
	$output .= '<input type="text" id="title" name="title">';
	$output .= '<h3>Categories</h3>';
	
	$output 	.= get_the_term_list( $post->ID, 'category', '<select name="category"><option>', '</option><option>','</option></select>');
	
	$output .= '<h3>Description</h3>';
	$output .= '<textarea rows="10" cols="45" name="description"></textarea>';
	$output .= '<input type="submit" value="Send" name="submit">';
	$output .= '</form>';


	wp_reset_postdata();
	return $output;
}


// Creating a Car Displaying Shortcode
function show_cars_list($atts, $content = null) {
	global $post;
	// Create attributes and defaults
	$atts = shortcode_atts(array(
		'title' => 'Show a Car',
		'category' => 'category',
	), $atts);
	// Check Category
	if ($atts['category'] == 'all') {
		$terms = '';
	} else {
		$terms = array(
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $atts['category']

			));
	}

	// Query Args
	$args = array(
		'post_type' 	=> 'cars',
		'post_status' 	=> 'publish',
		'orderby' 		=> 'created',
		'order' 		=> 'ASC',
		'post_per_page' => $atts['count'],
		'tax_query' 	=> $terms
	);

	// Fetch Posts
	$car_posts = new WP_Query($args);

	$output = '';
	$output .= '<h3>Categories</h3>';

	$output .= get_the_term_list($post->ID, 'category', '<ul><li>', '</li><li>', '</li></ul>');
	wp_reset_postdata();
	return $output;  

}

add_shortcode( 'car_form', 'add_car_form' );
add_shortcode( 'show_cars', 'show_cars_list' );
