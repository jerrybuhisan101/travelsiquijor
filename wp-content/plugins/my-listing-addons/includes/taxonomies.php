<?php

add_action( 'case27_register_taxonomies', function() {
	$labels = [
		'name'                       => _x( 'Tags', 'taxonomy general name', 'my-listing' ),
		'singular_name'              => _x( 'Tag', 'taxonomy singular name', 'my-listing' ),
		'search_items'               => __( 'Search Tags', 'my-listing' ),
		'popular_items'              => __( 'Popular Tags', 'my-listing' ),
		'all_items'                  => __( 'All Tags', 'my-listing' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Tag', 'my-listing' ),
		'update_item'                => __( 'Update Tag', 'my-listing' ),
		'add_new_item'               => __( 'Add New Tag', 'my-listing' ),
		'new_item_name'              => __( 'New Tag Name', 'my-listing' ),
		'separate_items_with_commas' => __( 'Separate Tags with commas', 'my-listing' ),
		'add_or_remove_items'        => __( 'Add or remove Tags', 'my-listing' ),
		'choose_from_most_used'      => __( 'Choose from the most used Tags', 'my-listing' ),
		'not_found'                  => __( 'No Tags found.', 'my-listing' ),
		'menu_name'                  => __( 'Tags', 'my-listing' ),
	];

	$args = [
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => [ 'slug' => 'writer' ],
	];

	register_taxonomy( 'case27_job_listing_tags', 'job_listing', $args );
});