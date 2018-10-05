<?php

/**
 * new WordPress Widget format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class Cars_Highskill_Widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	function __construct() {
		parent::__construct(
			'cars_highskill', // Base ID
			esc_html__( 'Cars Highskill', 'ch_domain' ), // Name
			array( 'description' => esc_html__( 'Cars and Models Show', 'ch_domain' ), ) // Args
		);
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array  An array of standard parameters for widgets in this theme
	 * @param array  An array of settings for this widget instance
	 * @return void Echoes it's output
	 */

	function widget( $args, $instance ) {
		echo $args['before_widget'];

		echo $args['before_title'];
		if (!empty($instance['title'])) {
			echo '<h1>'.$instance['title'].'</h1>';
		}
		echo $args['after_title'];
		?>

		<?php
		if ($instance['show_cposts'] == true) {
			// Querying Custom Post Type
			$args = [
				'post_type'      	=> 'cars',
				'post_status' 	=> 'publish',
				'order' 		=> 'ASC',
				'posts_per_page' 	=> 10,
			];
			$loop = new WP_Query($args);
			while ($loop->have_posts()) {
				$loop->the_post();
				?>


				<div class="show-custom-posts">
					<ul>
						<li><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></li>

					</ul>
				</div>
				<?php
			}
		}	
		wp_reset_query();
		?>
		
		<?php
		if ($instance['show_taxonomy'] == true) {
			// Getting posts taxonomies
			?>
			<h3>Categories</h3>
			
			
			<?php echo get_the_term_list( $post->ID, 'category', '<ul><li>', '</li><li>', '</li></ul>' );  ?>
			
			<?php
			
		}

		echo $args['after_widget'];
	}

	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 *
	 * @param array  An array of new settings as submitted by the admin
	 * @param array  An array of the previous settings
	 * @return array The validated and (if necessary) amended settings
	 */
	function update( $new_instance, $old_instance ) {

		$instance = array();
		
		$instance['title']  = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

		$instance['show_taxonomy'] = (!empty($new_instance['show_taxonomy'])) ? strip_tags($new_instance['show_taxonomy']) : '';

		$instance['show_cposts'] = (!empty($new_instance['show_cposts'])) ? strip_tags($new_instance['show_cposts']) : '';

		
		return $instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array  An array of the current settings for this widget
	 * @return void Echoes it's output
	 */
	function form( $instance ) {
		$show_taxonomy = $instance['show_taxonomy'];
		$show_cposts = $instance['show_cposts'];

		$title = !empty($instance['title']) ? $instance['title'] : __('Cars and Models', 'ch_domain');
		
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title: '); ?></label></p>
			<p>
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>">
			</p>

			
			<p>
				<input class="checkbox" type="checkbox" <?php checked($instance['show_cposts'], "on");?> id="<?php echo $this->get_field_id('show_cposts'); ?>" name="<?php echo $this->get_field_name('show_cposts'); ?>">
				<label for="<?php echo $this->get_field_id('show_cposts'); ?>">Show Custom Posts</label>

			</p>



			<p>
				<input class="checkbox" type="checkbox" <?php checked($instance['show_taxonomy'], "on");?> id="<?php echo $this->get_field_id('show_taxonomy'); ?>" name="<?php echo $this->get_field_name('show_taxonomy'); ?>">
				<label for="<?php echo $this->get_field_id('show_taxonomy'); ?>">Show Taxonomy</label>

			</p>
			<?php 
		}
	}

			//Register Widget
	function register_cars_models() {
		register_widget('Cars_Highskill_Widget');
	}
	add_action( 'widgets_init', 'register_cars_models' );