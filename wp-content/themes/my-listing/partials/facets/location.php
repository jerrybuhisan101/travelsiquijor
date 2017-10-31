<?php
    $data = c27()->merge_options([
            'facet' => [],
            'facetID' => uniqid() . '__facet',
        ], $data);

    if (!$data['facet']) return;

    $value = isset($_GET['search_location']) ? $_GET['search_location' ] : '';

    $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['search_location'] = $value;
    $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['search_location_lat'] = false;
    $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['search_location_lng'] = false;

?>

<div class="form-group location-wrapper">
    <label for="<?php echo esc_attr( $data['facetID'] ) ?>"><?php echo esc_html( $data['facet']['label'] ) ?></label>
    <input type="text"
    	   class="form-location-autocomplete"
    	   id="<?php echo esc_attr( $data['facetID'] ) ?>"
           name="search_location"
    	   placeholder="<?php echo isset($data['facet']['placeholder']) ? esc_attr( $data['facet']['placeholder'] ) : __( 'Enter Location...', 'my-listing' ) ?>"
           v-model="facets['<?php echo esc_attr( $data['listing_type'] ) ?>']['search_location']"
           @keyup="geocodeLocation"
    	   >
    <i class="material-icons geocode-location" @click="getUserLocation">my_location</i>
</div>