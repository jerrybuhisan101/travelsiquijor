<?php
    $data = c27()->merge_options([
            'facet' => '',
            'facetID' => uniqid() . '__facet',
            'listing_type' => '',
        ], $data);

    if (!$data['facet']) return;

    $value = isset($_GET[$data['facet']['show_field']]) ? $_GET[$data['facet']['show_field'] ] : '';

    $GLOBALS['c27-facets-vue-object'][$data['listing_type']][$data['facet']['show_field']] = $value;
?>

<div class="form-group">
	<label for="<?php echo esc_attr( $data['facetID'] ) ?>"><?php echo esc_html( $data['facet']['label'] ) ?></label>
	<input type="text"
		   id="<?php echo esc_attr( $data['facetID'] ) ?>"
		   name="<?php echo esc_attr( $data['facet']['show_field'] ) ?>"
		   v-model="facets['<?php echo esc_attr( $data['listing_type'] ) ?>']['<?php echo esc_attr( $data['facet']['show_field'] ) ?>']"
		   placeholder="<?php echo isset($data['facet']['placeholder']) ? esc_attr( $data['facet']['placeholder'] ) : '' ?>"
		   @keyup="getListings"
		   >
</div>