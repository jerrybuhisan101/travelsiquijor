<?php
    $data = c27()->merge_options([
            'facet' => '',
            'facetID' => uniqid() . '__facet',
            'listing_type' => '',
            'options' => [
            	'units' => 'km',
            	'max' => 500,
            	'default' => 10,
            	'active' => true,
            ],
            'is_vue_template' => true,
        ], $data);

    if (!$data['facet']) return;

    foreach((array) $data['facet']['options'] as $option) {
    	if ($option['name'] == 'units') {
    		if ($option['value'] == 'metric') $data['options']['units'] = 'km';
    		if ($option['value'] == 'imperial') $data['options']['units'] = 'mi';
    	}

    	if ($option['name'] == 'max') $data['options']['max'] = $option['value'];
    	if ($option['name'] == 'default') $data['options']['default'] = $option['value'];
    	if ($option['name'] == 'active') $data['options']['active'] = $option['value'];
    }

    $value = isset($_GET['proximity']) && is_numeric($_GET['proximity']) ? $_GET['proximity'] : $data['options']['default'];

    $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['proximity'] = $value;
    $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['proximity_active'] = ($data['options']['active'] || (isset($_GET['proximity']) && is_numeric($_GET['proximity'])));
    $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['proximity_units'] = isset($_GET['proximity_units']) && $_GET['proximity_units'] ? $_GET['proximity_units'] : $data['options']['units'];
?>

<div class="form-group radius radius1 proximity-slider">
	<label><?php echo esc_html( $data['facet']['label'] ) ?></label>
    <?php if ($data['is_vue_template']): ?>
        <div class="md-checkbox pull-right no-margin">
            <input id="<?php echo esc_attr( $data['facetID'] ) ?>" type="checkbox" v-model="<?php echo esc_attr( "facets['{$data['listing_type']}'].proximity_active" ) ?>" @change="getListings">
            <label for="<?php echo esc_attr( $data['facetID'] ) ?>"></label>
        </div>
        <input type="hidden" v-model="<?php echo esc_attr( "facets['{$data['listing_type']}'].proximity_units" ) ?>">
        <range-slider v-model="<?php echo esc_attr( "facets['{$data['listing_type']}']['proximity']" ) ?>"
                      type="simple"
                      suffix="<?php echo esc_attr( $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['proximity_units'] ) ?>"
                      min="0"
                      max="<?php echo esc_attr( $data['options']['max'] ) ?>"
                      start="<?php echo esc_attr( $value ) ?>"
                      @input="getListings"
                      ></range-slider>
    <?php else: ?>
       <input type="text" class="amount" readonly id="<?php echo esc_attr( $data['facetID'] . '__display' ) ?>">
       <input type="hidden" name="proximity" id="<?php echo esc_attr( $data['facetID'] ) ?>">
       <input type="hidden" name="proximity_units" value="<?php echo esc_attr( $data['options']['units'] ) ?>">
       <div class="slider-range basic-form-slider-range"
            data-type="simple"
            data-suffix="<?php echo esc_attr( $data['options']['units'] ) ?>"
            data-min="0"
            data-max="<?php echo esc_attr( $data['options']['max'] ) ?>"
            data-start="<?php echo esc_attr( $value ) ?>"
            data-input-id="<?php echo esc_attr( $data['facetID'] ) ?>"
            ></div>
    <?php endif ?>
</div>