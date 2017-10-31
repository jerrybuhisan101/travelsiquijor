<?php
	if (!class_exists('WP_Job_Manager')) return;

	$data = c27()->merge_options([
			'title' => '',
			'subtitle' => '',
			'tabs' => [],
			'template' => 'explore-default', // explore-default or explore-no-map
			'finder_columns' => 'finder-one-columns',
            'map_skin' => 'skin1',
            'listing_types' => [],
            'categories' => [],
            'is_edit_mode' => false,
			'categories_overlay' => [
				'type' => 'gradient',
				'gradient' => 'gradient1',
				'solid_color' => 'rgba(0, 0, 0, .1)',
			],
			'active_tab' => 'search-form',
		], $data);

	if ( $data['template'] == 'explore-2' && $data['finder_columns'] == 'finder-three-columns' ) {
		$data['finder_columns'] = 'finder-two-columns';
	}

	$data['categories'] = array_filter( (array) $data['categories'] );

	$GLOBALS['c27-facets-vue-object'] = [];
	$GLOBALS['listing-wrap'] = '';
	$GLOBALS['active-category'] = isset($_GET['category_id']) && $_GET['category_id'] ? absint((int) $_GET['category_id']) : null;
	$GLOBALS['active-tab'] = ( isset($_GET['active_tab']) ? sanitize_text_field($_GET['active_tab']) : ( $data['active_tab'] ? : 'search-form' ) );

	if ( ! $GLOBALS['active-category'] && ! empty( $data['categories'] ) ) {
		$GLOBALS['active-category'] = absint( $data['categories'][0]['category'] );
	}

	$active_category_key = array_search($GLOBALS['active-category'], array_column($data['categories'], 'category'));

	// Move active category to the top of the array.
	if ($active_category_key !== false) {
		$active_ctg = $data['categories'][$active_category_key];
		unset($data['categories'][$active_category_key]);
		array_unshift($data['categories'], $active_ctg);
	} else {
		// Or prepend it if it isn't part of the existing array at all.
		array_unshift($data['categories'], [
			'category' => $GLOBALS['active-category'],
			'_id' => uniqid(),
			]);
	}
?>

<?php if (!$data['template'] || $data['template'] == 'explore-1' || $data['template'] == 'explore-2'): ?>
	<?php $GLOBALS['listing-wrap'] = 'col-md-12'; ?>
	<div class="finder-container fc-type-1 <?php echo esc_attr( $data['finder_columns'] ) ?>" id="c27-explore-listings">
		<div class="mobile-explore-head">
			<a type="button" class="toggle-mobile-search" data-toggle="collapse" data-target="#finderSearch"><i class="material-icons sm-icon">sort</i><?php _e( 'Search Filters', 'my-listing' ) ?></a>
		</div>

		<div class="<?php echo $data['template'] == 'explore-2' ? 'fc-one-column' : '' ?>">
			<div class="finder-search collapse" id="finderSearch">
				<div class="finder-title col-md-12">
					<h1 class="case27-primary-text"><?php echo esc_html( $data['title'] ) ?></h1>
					<p><?php echo esc_html( $data['subtitle'] ) ?></p>
				</div>
				<div class="finder-tabs-wrapper">
					<?php c27()->get_partial('advanced-search-form', [
						'tabs' => $data['tabs'],
						'listing_types' => $data['listing_types'],
						'categories' => $data['categories'],
						'categories_overlay' => $data['categories_overlay'],
						]) ?>
				</div>
			</div>

			<div class="finder-listings">
				<div class="fl-head">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<span href="#" class="fl-results-no"><i class="material-icons sm-icon">visibility</i><span></span></span>
					</div>

					<?php if ( $data['finder_columns'] != 'finder-three-columns' ): ?>
						<div class="col-md-6 col-sm-6 col-xs-6 map-toggle-button">
							<a href="#" class=""><?php _e( 'Map view', 'my-listing' ) ?><i class="material-icons sm-icon">map</i></a>
						</div>

						<div class="col-md-6 column-switch">
							<a href="#" class="col-switch switch-one <?php echo $data['finder_columns'] == 'finder-one-columns' ? 'active' : '' ?>" data-no="finder-one-columns">
								<i class="material-icons">view_stream</i>
							</a>
							<a href="#" class="col-switch switch-two <?php echo $data['finder_columns'] == 'finder-two-columns' ? 'active' : '' ?>" data-no="finder-two-columns">
								<i class="material-icons">view_module</i>
							</a>
							<a href="#" class="col-switch switch-three <?php echo $data['finder_columns'] == 'finder-three-columns' ? 'active' : '' ?>" data-no="finder-three-columns">
								<i class="material-icons">view_comfy</i>
							</a>
						</div>
					<?php endif ?>

					<!-- <div class="btn btn-primary" @click="getListings">Get Listings</div> -->
					<!-- <div class="btn btn-primary" @click="updateMap">Update Map</div> -->
					<!-- <div class="btn btn-primary" @click="clearMapMarkers">Clear Markers</div> -->
				</div>
				<!-- <pre class="text-left">{{ facets[ state.activeListingType ] }}</pre> -->
				<!-- <pre>{{ state }}</pre> -->
				<!-- <pre>{{ facets }}</pre> -->
				<div class="results-view" v-show="!state.loading"></div>
				<div class="loader-bg" v-show="state.loading">
					<?php c27()->get_partial('spinner', [
						'color' => '#777',
						'classes' => 'center-vh',
						'size' => 28,
						'width' => 3,
						]); ?>
				</div>
				<div class="col-md-12 center-button pagination c27-explore-pagination" v-show="!state.loading"></div>
			</div>
		</div>

		<?php if ( $data['finder_columns'] != 'finder-three-columns' ): ?>
			<div class="finder-map">
				<div class="card-view">
					<a href="#" class="buttons button-1"><i class="material-icons sm-icon">view_module</i><?php _e( 'Card view', 'my-listing' ) ?></a>
				</div>
				<div class="map c27-map" id="<?php echo esc_attr( 'map__' . uniqid() ) ?>" data-options="<?php echo htmlspecialchars(json_encode([
					'skin' => $data['map_skin'],
					'zoom' => 10,
				]), ENT_QUOTES, 'UTF-8'); ?>">
				</div>
			</div>
		<?php endif ?>
	</div>
<?php endif ?>

<?php if ($data['template'] == 'explore-no-map'): ?>
	<?php $GLOBALS['listing-wrap'] = 'col-md-4 col-sm-6 reveal'; ?>
	<div id="c27-explore-listings">
		<div class="finder-container fc-type-2">
			<div class="mobile-explore-head">
				<a type="button" class="toggle-mobile-search" data-toggle="collapse" data-target="#finderSearch"><i class="material-icons sm-icon">sort</i><?php _e( 'Search Filters', 'my-listing' ) ?></a>
			</div>
			<div class="finder-search collapse" id="finderSearch">
				<div class="finder-title col-md-12">
					<h3 class="case27-primary-text"><?php echo esc_html( $data['title'] ) ?></h3>
					<p><?php echo esc_html( $data['subtitle'] ) ?></p>
				</div>
				<div class="finder-tabs-wrapper">
					<?php c27()->get_partial('advanced-search-form', [
							'tabs' => $data['tabs'],
							'listing_types' => $data['listing_types'],
							'categories' => $data['categories'],
							'categories_overlay' => $data['categories_overlay'],
						]) ?>
				</div>
			</div>
			<div class="finder-overlay"></div>
		</div>

		<section class="i-section explore-type-2">
			<div class="container">
				<div class="fl-head row reveal">
					<div class="col-md-6 col-sm-6 col-xs-6 toggle-search-type-2">
						<a href="#" class=""><?php _e( 'Search Filters', 'my-listing' ) ?><i class="material-icons sm-icon">sort</i></a>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<span href="#" class="fl-results-no pull-right"><i class="material-icons sm-icon">visibility</i><span></span></span>
					</div>
				</div>
				<!-- <pre>{{ state }}</pre> -->
				<div class="row results-view fc-type-2-results" v-show="!state.loading"></div>
				<div class="loader-bg" v-show="state.loading">
					<?php c27()->get_partial('spinner', [
						'color' => '#777',
						'classes' => 'center-vh',
						'size' => 28,
						'width' => 3,
						]); ?>
				</div>
				<div class="row center-button pagination c27-explore-pagination" v-show="!state.loading"></div>
			</div>
		</section>
	</div>
<?php endif ?>

<script type="text/javascript">
	var CASE27_Explore_Listings__Facets = <?php echo json_encode($GLOBALS['c27-facets-vue-object']) ?>;
	var CASE27_Explore_Listings__Listing_Wrap = <?php echo json_encode($GLOBALS['listing-wrap']) ?>;
	var CASE27_Explore_Listings__Active_Category = <?php echo json_encode($GLOBALS['active-category']) ?>;
	var CASE27_Explore_Listings__Active_Tab = <?php echo json_encode($GLOBALS['active-tab']) ?>;
</script>


<?php if ($data['is_edit_mode']): ?>
    <script type="text/javascript">case27_ready_script(jQuery); CASE27_Explore_Listings__func(); case27_initialize_maps();</script>
<?php endif ?>