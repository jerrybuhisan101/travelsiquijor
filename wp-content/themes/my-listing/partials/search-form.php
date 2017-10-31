<?php
	$data = c27()->merge_options([
			'layout' => 'wide',
			'align' => 'center',
			'listing_types' => '',
			'tabs_mode' => 'light',
			'box_shadow' => 'no',
			'search_page_id' => '',
		], $data);

	if (!$data['search_page_id']) {
		$data['search_page_id'] = c27()->get_setting( 'general_explore_listings_page' );
	}

	$queried_listing_types = [];
	foreach ((array) explode(',', (string) $data['listing_types']) as $listing_type) {
		$listing_type = trim($listing_type);

		$listing_type = get_posts([
			'name'        => $listing_type,
			'post_type'   => 'case27_listing_type',
			'post_status' => 'publish',
			'numberposts' => 1
			]);

		if ($listing_type) {
			$listing_type[0]->c27_meta = c27()->get_listing_type_options($listing_type[0]->post_name, ['search', 'settings']);
			$queried_listing_types[$listing_type[0]->post_name] = $listing_type[0];
		}
	}
?>
<div class="<?php echo esc_attr( 'text-' . $data['align'] ) ?> <?php echo esc_attr( $data['tabs_mode'] == 'dark' ? 'featured-light' : '' ) ?>">
	<div class="featured-search reveal <?php echo esc_attr( $data['layout'] ) ?>">
		<div class="fs-tabs">
			<ul class="nav nav-tabs" role="tablist">
				<?php
				$i = 0;
				foreach ($queried_listing_types as $ltype): $i++; ?>
					<li role="presentation" class="<?php echo $i === 1 ? 'active' : '' ?>">
						<a href="#search-form-tab-<?php echo esc_attr( $ltype->post_name ) ?>" role="tab" class="tab-switch">
							<i class="<?php echo esc_attr( $ltype->c27_meta['settings']['icon'] ) ?>"></i><?php echo esc_html( $ltype->c27_meta['settings']['plural_name'] ) ?>
						</a>
					</li>
				<?php endforeach ?>
			</ul>
			<div class="tab-content <?php echo $data['box_shadow'] == 'yes' ? 'add-box-shadow' : '' ?>">
				<?php
				$i = 0;
				foreach ($queried_listing_types as $ltype): $i++; ?>
					<?php $facets = $ltype->c27_meta['search']['basic']['facets'] ?>

					<div role="tabpanel" class="tab-pane fade in <?php echo $i === 1 ? 'active' : '' ?>" id="search-form-tab-<?php echo esc_attr( $ltype->post_name ) ?>">
						<form method="GET" action="<?php echo esc_url( is_numeric( $data['search_page_id'] ) ? get_permalink( absint( $data['search_page_id'] ) ) : $data['search_page_id'] ) ?>">
							<?php foreach ((array) $facets as $facet): ?>
								<?php c27()->get_partial("facets/{$facet['type']}", [
									'facet' => $facet,
									'listing_type' => $ltype->post_name,
	            					'is_vue_template' => false,
									]) ?>
								<?php endforeach ?>
							<div class="form-group">
								<input type="hidden" name="active_listing_type" value="<?php echo esc_attr( $ltype->post_name ) ?>">
								<button type="submit" class="buttons button-2 search"><i class="material-icons">search</i><?php _e( 'Search', 'my-listing' ) ?></button>
							</div>
						</form>
					</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>