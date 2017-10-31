<?php
    $data = c27()->merge_options([
            'facet' => '',
            'facetID' => uniqid() . '__facet',
            'options' => [
            	'count' => 8,
            	'multiselect' => false,
            	'hide_empty' => true,
                'order_by' => 'count',
            	'order' => 'DESC',
                'placeholder' => __( 'Select an option', 'my-listing' ),
            ],
            'facet_data' => [
            	'choices' => [],
            ],
            'is_vue_template' => true,
        ], $data);

    if (!$data['facet']) return;

    foreach((array) $data['facet']['options'] as $option) {
    	if ($option['name'] == 'count') $data['options']['count'] = $option['value'];
    	if ($option['name'] == 'multiselect') $data['options']['multiselect'] = $option['value'];
    	if ($option['name'] == 'hide_empty') $data['options']['hide_empty'] = $option['value'];
        if ($option['name'] == 'order_by') $data['options']['order_by'] = $option['value'];
        if ($option['name'] == 'order') $data['options']['order'] = $option['value'];
    	if ($option['name'] == 'placeholder') $data['options']['placeholder'] = $option['value'];
    }

    if ($data['facet']['show_field'] == 'job_category' || $data['facet']['show_field'] == 'job_tags') {
    	$taxonomy = ($data['facet']['show_field']) == 'job_category' ? 'job_listing_category' : 'case27_job_listing_tags';

    	$terms = get_terms([
    		'taxonomy' => $taxonomy,
    		'hide_empty' => $data['options']['hide_empty'],
    		'orderby' => $data['options']['order_by'],
    		'number' => $data['options']['count'],
            'order' => $data['options']['order'],
    		]);

        if (!is_wp_error($terms)) {
            foreach ((array) $terms as $term) {
                $data['facet_data']['choices'][] = [
                    'value' => $term->slug,
                    'label' => "{$term->name}",
                    'selected' => false,
                ];
            }
        }
    } else {
        if (!function_exists('c27_dropdown_facet_query_group_by_filter')) {
            function c27_dropdown_facet_query_group_by_filter($groupby) { global $wpdb;
                return $wpdb->postmeta . '.meta_value ';
            }
        }

        if (!function_exists('c27_dropdown_facet_query_fields_filter')) {
            function c27_dropdown_facet_query_fields_filter($fields) { global $wpdb;
                return "{$wpdb->postmeta}.meta_value, COUNT(*) as c27_field_count ";
            }
        }

        add_filter('posts_fields', 'c27_dropdown_facet_query_fields_filter');
        add_filter('posts_groupby', 'c27_dropdown_facet_query_group_by_filter');

    	$posts = query_posts([
    		'post_type' => 'job_listing',
    		'posts_per_page' => $data['options']['count'],
    		'meta_key'  => "_{$data['facet']['show_field']}",
            'orderby' => $data['options']['order_by'],
            'order' => $data['options']['order'],
    		]);

        remove_filter('posts_fields', 'c27_dropdown_facet_query_fields_filter');
        remove_filter('posts_groupby', 'c27_dropdown_facet_query_group_by_filter');

    	foreach ((array) $posts as $post) {
            if ( is_serialized( $post->meta_value ) ) {
                $post->meta_value = unserialize( $post->meta_value );
            }

            if ( is_array( $post->meta_value ) ) {
                foreach ($post->meta_value as $value) {
                    $data['facet_data']['choices'][] = [
                        'value' => $value,
                        'label' => "{$value}",
                        'selected' => false,
                    ];
                }
            } else {
        		$data['facet_data']['choices'][] = [
        			'value' => $post->meta_value,
                    'label' => "{$post->meta_value}",
        			'selected' => false,
        		];
            }
    	}
    }

    $selected = isset($_GET[$data['facet']['show_field']]) ? (array) $_GET[$data['facet']['show_field']] : [];

    $GLOBALS['c27-facets-vue-object'][$data['listing_type']][$data['facet']['show_field']] = $selected;
?>

<div class="form-group">
	<label><?php echo esc_html( $data['facet']['label'] ) ?></label>
    <?php if ($data['is_vue_template']): ?>
        <select2 v-model="<?php echo esc_attr( "facets['{$data['listing_type']}']['{$data['facet']['show_field']}']" ) ?>"
                 multiple="<?php echo esc_attr( $data['options']['multiselect'] ) ?>"
                 :choices="<?php echo str_replace('&amp;', '&', htmlspecialchars(json_encode($data['facet_data']['choices']), ENT_QUOTES, 'UTF-8')) ?>"
                 :selected="<?php echo str_replace('&amp;', '&', htmlspecialchars(json_encode($selected), ENT_QUOTES, 'UTF-8')) ?>"
                 placeholder="<?php echo esc_attr( $data['options']['placeholder'] ) ?>"
                 @input="getListings"></select2>
    <?php else: ?>
        <select name="<?php echo esc_attr( $data['facet']['show_field'] ) ?>[]"
                placeholder="<?php echo esc_attr( $data['options']['placeholder'] ) ?>" class="custom-select">
                <option></option>
            <?php foreach ($data['facet_data']['choices'] as $choice): ?>
                <option value="<?php echo esc_attr( $choice['value'] ) ?>"><?php echo esc_html( $choice['label'] ) ?></option>
            <?php endforeach ?>
        </select>
    <?php endif ?>
</div>