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

class Cars_Highskill{

	function __construct() {

		add_action('admin_enqueue_scripts', array($this, 'ch_add_admin_scripts'));
		add_action('wp_enqueue_scripts', array($this, 'ch_add_scripts'));
		add_action('init', array($this, 'ch_register_cars'));
		add_action('init', array($this, 'ch_custom_taxonomy'));
		add_action('add_meta_boxes', array($this, 'ch_add_fields_metabox'));
		add_action('save_post', array($this, 'ch_save'));
		add_shortcode( 'car_form', array($this, 'add_car_form'));
		add_shortcode( 'show_cars', array($this, 'show_cars_list'));
		add_action('init', array($this, 'add_custom_post'));
		add_action( 'widgets_init', 'register_cars_models');
		// Add widget Class
		require_once 'includes/cars-highskill-class.php';



	}


	function ch_add_admin_scripts() {
		wp_enqueue_style('ch-admin-style', plugins_url(). '/cars-highskill/css/style-admin.css');

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


		register_post_type('cars', $args);

	}


	function ch_custom_taxonomy() {

		$labels = array(
			'name' 						=> _x( 'Models', 'taxonomy general name' ),
			'singular_name' 			=> _x( 'Model', 'taxonomy singular name' ),
			'search_items' 				=> __( 'Search Models' ),
			'all_items' 				=> __( 'All Models' ),
			'parent_item' 				=> __( 'Parent Model' ),
			'parent_item_colon' 		=> __( 'Parent Model:' ),
			'edit_item' 				=> __( 'Edit Model' ), 
			'update_item' 				=> __( 'Update Model' ),
			'add_new_item' 				=> __( 'Add New Model' ),
			'new_item_name'				=> __( 'New Model Name' ),
			'menu_name' 				=> __( 'Models' ),
		); 	

		register_taxonomy(
			'models',
			'cars',
			array(
				'hierarchical' 	=> true,
				'labels' 		=> $labels,

			));
	}

	function ch_add_fields_metabox() {
		add_meta_box(
			'ch_fields',
			__('Cars Models Fields'), 
			'ch_fields_callback', 
			'cars', 
			'normal',
			'default'
		);
	}



	function ch_fields_callback($post) {
		wp_nonce_field(basename(__FILE__), 'wp_cars_nonce');
		$ch_stored_meta = get_post_meta($post->ID);
		?>
		<div class="wrap cars-form">

			<div class="form-group">
				<label for="description"><?php esc_html_e('Description','ch_domain'); ?></label>
				<?php 
				$content = get_post_meta($post->ID, 'description', true);
				$editor = 'description';
				$settings = array(
					'textarea_rows' => 5,
					'media_buttons' => true,
				);

				wp_editor($content, $editor, $settings);
				?>
			</div>
		</div>
		<?php
	}

	function ch_save($post_id) {
		$is_autosave = wp_is_post_autosave($post_id);
		$is_revision = wp_is_post_revision($post_id);
		$is_valid_nonce = (isset($_POST['wp_cars_nonce']) && wp_verify_nonce($_POST['wp_cars_nonce'], basename(__FILE__))) ? 'true' : 'false';

		if ($is_autosave || $is_revision || !$is_valid_nonce) {
			return;
		} 

		if (isset($_POST['taxonomy'])) {
			update_post_meta($post_id, 'taxonomy', sanitize_text_field($_POST['taxonomy']));
		}

		if (isset($_POST['description'])) {
			update_post_meta($post_id, 'description', sanitize_text_field($_POST['description']));
		}
	}

		// Creating a Car Adding Shortcode
	function add_car_form($atts, $content = null) {
		global $post;

		$atts = shortcode_atts(array(
			'title' => 'Add a Car',
			'category' => 'all',
		), $atts);

		ob_start(); ?> 
		<h3><?php _e('Shortcode Form') ?></h3>
		<form action="" method="POST" class="form-group">
			<label for="title"><?php _e('Title') ?></label>
			<input type="text" id="title" name="title" class="title"><br>
			<label for="category"><?php _e('Model') ?></label>

			<select name="category" id="category" class="category">
				<?php 
				$args = array('post_type'=>'cars',
					'order'=>'DESC',
				);

				$getPost = new wp_query($args);
				global $post;
				if ($getPost->have_posts()): ?>
					<ul>

						<?php while ( $getPost->have_posts()):$getPost->the_post(); ?>

							<p><?php $post->post_title ?></p>
							<?php $terms = get_the_terms($post->ID, 'models' );?>
							<?php foreach ($terms as $term) : ?>
								<li><?php echo '<option>' . $term_name = $term->name . '</option>' ?></li>
							<?php endforeach; endwhile; ?>
						</ul>
					<?php endif; ?>
				</select><br><br>

				<label for="description"><?php _e('Description') ?></label>
				<textarea rows="10" cols="35" name="description" id="description" class="description"></textarea><br>
				<?php wp_nonce_field('cpt_form', 'cpt_nonce_field');?>
				<input type="submit" value="Send" name="submit">
			</form>
			<?php 
			$output = ob_get_clean();
			wp_reset_postdata();
			return $output;

		}

		function add_custom_post() {
				// Form validation
			if(isset($_POST['cpt_nonce_field']) && wp_verify_nonce($_POST['cpt_nonce_field'], 'cpt_form')) {
				$post_information = array(
					'post_title' 		=> wp_strip_all_tags( $_POST['title'] ),
					'post_content' 		=> $_POST['description'],
					'post_category' 	=> array($_POST['category']),
					'post_type' 		=> 'cars',
					'post_status' 		=> 'pending'
				);

				$post_id = wp_insert_post($post_information);
			}
		}


			// Creating a Car Displaying Shortcode
		function show_cars_list($atts, $content = null) {
			$atts = shortcode_atts(array(
				'title' => 'Show a Car',
				'category' => 'category',
			), $atts);

			$args = array('post_type'=>'cars',
				'order'=>'desc',
			);

			$getPost = new wp_query($args);
			global $post;
			ob_start();  ?>
			<h3><?php _e('Cars List Shortcode') ?></h3>
			<table> 
				<tr> 
					<th><?php _e('Title') ?></th> 
					<th><?php _e('Date') ?></th> 
					<th><?php _e('Status') ?></th> 
				</tr> 

				<?php 

				$getPost = get_posts(array(

					'order'       => 'DESC',
					'post_type'   => 'cars',
				));

				global $post;
				foreach($getPost as $post) {
					echo "<tr><td>".$post->post_title."</td><td>".$post->post_date."</td><td>".$post->post_status."</td><tr>";
				}	
				?>
			</table>

			<?php

			$output = ob_get_clean();
			return $output;
			?>
			<?php

		}
	}
	new Cars_Highskill();