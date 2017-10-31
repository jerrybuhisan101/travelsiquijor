<?php
    $data = c27()->merge_options([
            'facet' => '',
            'facetID' => uniqid() . '__facet',
            'options' => [
            	'count' => 8,
            	'multiselect' => false,
            	'hide_empty' => true,
            	'order_by' => 'count',
            ],
            'facet_data' => [
            	'choices' => [],
            ],
        ], $data);

    if (!$data['facet']) return;

    foreach((array) $data['facet']['options'] as $option) {
    	if ($option['name'] == 'count') $data['options']['count'] = $option['value'];
    	if ($option['name'] == 'multiselect') $data['options']['multiselect'] = $option['value'];
    	if ($option['name'] == 'hide_empty') $data['options']['hide_empty'] = $option['value'];
    	if ($option['name'] == 'order_by') $data['options']['order_by'] = $option['value'];
    }

    if ($data['facet']['show_field'] == 'job_category' || $data['facet']['show_field'] == 'job_tags') {
    	$taxonomy = ($data['facet']['show_field']) == 'job_category' ? 'job_listing_category' : 'case27_job_listing_tags';

    	$terms = get_terms([
    		'taxonomy' => $taxonomy,
    		'hide_empty' => $data['options']['hide_empty'],
    		'orderby' => $data['options']['order_by'],
    		'number' => $data['options']['count'],
            'order' => 'DESC',
    		]);

    	foreach ((array) $terms as $term) {
    		$data['facet_data']['choices'][] = [
    			'value' => $term->slug,
    			'label' => "{$term->name}",
    		];
    	}
    } else {
        if (!function_exists('c27_checkboxes_facet_query_group_by_filter')) {
            function c27_checkboxes_facet_query_group_by_filter($groupby) { global $wpdb;
                return $wpdb->postmeta . '.meta_value ';
            }
        }

        if (!function_exists('c27_checkboxes_facet_query_fields_filter')) {
            function c27_checkboxes_facet_query_fields_filter($fields) { global $wpdb;
                return "{$fields}, {$wpdb->postmeta}.meta_value, COUNT(*) as c27_field_count ";
            }
        }

        add_filter('posts_fields', 'c27_checkboxes_facet_query_fields_filter');
        add_filter('posts_groupby', 'c27_checkboxes_facet_query_group_by_filter');

    	$posts = query_posts([
    		'post_type' => 'job_listing',
    		'posts_per_page' => $data['options']['count'],
    		'meta_key'  => "_{$data['facet']['show_field']}",
    		'orderby'   => 'meta_value',
    		'order' => 'DESC',
    		]);

        remove_filter('posts_fields', 'c27_checkboxes_facet_query_fields_filter');
        remove_filter('posts_groupby', 'c27_checkboxes_facet_query_group_by_filter');

    	foreach ((array) $posts as $post) {
    		$data['facet_data']['choices'][] = [
    			'value' => $post->meta_value,
    			'label' => $post->meta_value,
    		];
    	}
    }

    $selected = isset($_GET[$data['facet']['show_field']]) ? (array) $_GET[$data['facet']['show_field']] : [];

    $GLOBALS['c27-facets-vue-object'][$data['listing_type']][$data['facet']['show_field']] = $selected;
?>

<div class="form-group form-group-tags">
	<label><?php echo esc_html( $data['facet']['label'] ) ?></label>
	<ul class="tags-nav">
		<?php foreach ((array) $data['facet_data']['choices'] as $choice): $choiceID = uniqid() . '__choiceid'; ?>
			<li>
				<div class="md-checkbox">
					<input id="<?php echo esc_attr( $choiceID ) ?>"
                           type="<?php echo $data['options']['multiselect'] ? 'checkbox' : 'radio' ?>"
                           value="<?php echo esc_attr( $choice['value'] ) ?>"
                           v-model="<?php echo esc_attr( "facets['{$data['listing_type']}']['{$data['facet']['show_field']}']" ) ?>"
                           @change="getListings"
                           >
					<label for="<?php echo esc_attr( $choiceID ) ?>" class=""><?php echo esc_attr( $choice['label'] ) ?></label>
				</div>
			</li>
		<?php endforeach ?>
	</ul>
</div>
