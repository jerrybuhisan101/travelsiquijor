<?php
    $data = c27()->merge_options([
            'facet' => '',
            'facetID' => uniqid() . '__facet',
        ], $data);

    if (!$data['facet']) return;

    $value = isset($_GET['search_keywords']) ? $_GET['search_keywords' ] : '';

    $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['search_keywords'] = $value;
?>

<div class="form-group">
    <label for="<?php echo esc_attr( $data['facetID'] ) ?>"><?php echo esc_html( $data['facet']['label'] ) ?></label>
    <input type="text" v-model="facets['<?php echo esc_attr( $data['listing_type'] ) ?>']['search_keywords']" id="<?php echo esc_attr( $data['facetID'] ) ?>" name="search_keywords"
           placeholder="<?php echo isset($data['facet']['placeholder']) ? esc_attr( $data['facet']['placeholder'] ) : __( 'What are you looking for?', 'my-listing' ) ?>" @keyup="getListings">
</div>