<?php

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

add_action('add_meta_boxes', 'ch_add_fields_metabox');

// Display Fields Metabox Content
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

add_action('save_post', 'ch_save');