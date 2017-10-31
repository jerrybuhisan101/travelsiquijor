<?php
	$data = c27()->merge_options([
			'tabs' => [],
			'listing_types' => [],
			'categories' => [],
			'categories_overlay' => [
				'type' => 'gradient',
				'gradient' => 'gradient1',
				'solid_color' => 'rgba(0, 0, 0, .1)',
			],
		], $data);

	$data['listing_types'] = array_filter(array_map(function($listing_type) {
		$post_obj = get_posts([
			'name' => $listing_type,
			'post_type' => 'case27_listing_type', 'post_status' => 'publish', 'numberposts' => 1,
			]);

		if (!$post_obj) return false;

		$post_obj[0]->c27_meta = c27()->get_listing_type_options($post_obj[0]->post_name, ['search', 'settings']);

		return $post_obj[0];
	}, array_column((array) $data['listing_types'], 'type')));

	$data['categories'] = get_terms([
		'taxonomy' => 'job_listing_category',
		'include' => array_column((array) $data['categories'], 'category'),
		'orderby' => 'include',
		]);

	$tabs = array_filter( array_map(function($tab) {
		if ($tab && $tab['27_tab_type'] == 'listing_type') {
			$listing_type = get_posts([
				'name'        => $tab['27_listing_type'],
				'post_type'   => 'case27_listing_type', 'post_status' => 'publish', 'numberposts' => 1,
				]);

			if (!$listing_type) return false;

			$tab['listing_type_obj'] = $listing_type[0];
			$tab['listing_type_obj']->c27_meta = c27()->get_listing_type_options($listing_type[0]->post_name, ['search', 'settings']);
		}

		return $tab;
	}, (array) $data['tabs']) );
?>

<div class="finder-tabs col-md-12">
	<ul class="nav nav-tabs tabs-menu" role="tablist">
		<li :class="state.activeTab == 'search-form' ? 'active' : ''">
			<a href="#search-form" role="tab" class="tab-switch" @click="state.activeTab = 'search-form'; getListings();">
				<i class="material-icons">search</i> <?php _e( 'Search', 'my-listing' ) ?>
			</a>
		</li>

		<li :class="state.activeTab == 'categories' ? 'active' : ''">
			<a href="#categories" role="tab" class="tab-switch" @click="state.activeTab = 'categories'">
				<i class="material-icons">bookmark_border</i> <?php _e( 'Categories', 'my-listing' ) ?>
			</a>
		</li>

		<?php  ?>
	</ul>

	<?php
	$active_listing_type = isset($_GET['active_listing_type']) ? sanitize_text_field($_GET['active_listing_type']) : false;
	if (!$active_listing_type && $data['listing_types']) {
		$active_listing_type = $data['listing_types'][0]->post_name;
	}

	$listing_types_dropdown = array_map(function($ltype) {
		return [
			'label' => ($ltype->c27_meta['settings']['plural_name'] ? : $ltype->post_title),
			'value' => $ltype->post_name,
		];
	}, (array) $data['listing_types']);

	?>
	<div class="tab-content">
		<div id="search-form" class="listing-type-filters search-tab tab-pane fade" :class="state.activeTab == 'search-form' ? 'in active' : ''">
			<div class="form-group">
				<label><?php _e( 'Type', 'my-listing' ) ?></label>
					<select2 v-model="state.activeListingType" placeholder="Select an option..."
						:choices="<?php echo htmlspecialchars(json_encode($listing_types_dropdown), ENT_QUOTES, 'UTF-8'); ?>"
						:selected="<?php echo htmlspecialchars(json_encode($active_listing_type), ENT_QUOTES, 'UTF-8'); ?>"
						required="required"
						@input="_getListings"></select2>
			</div>

			<?php foreach ($data['listing_types'] as $listing_type): ?>
				<?php $GLOBALS['c27-facets-vue-object'][$listing_type->post_name] = ['page' => 0]; ?>
				<?php $facets = $listing_type->c27_meta['search']['advanced']['facets'] ?>

				<div v-show="state.activeListingType == '<?php echo esc_attr( $listing_type->post_name ) ?>'">
					<form class="light-forms filter-wrapper">
						<?php foreach ((array) $facets as $facet): ?>
							<?php c27()->get_partial("facets/{$facet['type']}", [
								'facet' => $facet,
								'listing_type' => $listing_type->post_name,
								]) ?>
						<?php endforeach ?>
					</form>
					<div class="form-group fc-search">
						<a href="#" class="buttons button-2 full-width button-animated c27-explore-search-button" @click.prevent="getListings">
							<?php _e( 'Search', 'my-listing' ) ?><i class="material-icons">keyboard_arrow_right</i>
						</a>
					</div>
				</div>

			<?php endforeach ?>
		</div>

		<div id="categories" class="listing-cat-tab tab-pane fade c27-explore-categories" :class="state.activeTab == 'categories' ? 'in active' : ''">
			<?php foreach ((array) $data['categories'] as $term):
				$icon = get_field('icon', 'job_listing_category_' . $term->term_id) ? : c27()->defaults()['category']['icon'];
				$image = get_field('image', 'job_listing_category_' . $term->term_id);
				?>
				<div class="listing-cat" :class="<?php echo esc_attr( $term->term_id ) ?> == state.activeCategory ? 'active' : ''">
					<a @click.prevent="state.activeCategory = '<?php echo esc_attr( $term->term_id ) ?>'; state.activeCategoryPage = 0;">
						<div class="overlay <?php echo $data['categories_overlay']['type'] == 'gradient' ? esc_attr( $data['categories_overlay']['gradient'] ) : '' ?>"
							 style="<?php echo $data['categories_overlay']['type'] == 'solid_color' ? 'background-color: ' . esc_attr( $data['categories_overlay']['solid_color'] ) . '; ' : '' ?>"></div>
						<div class="lc-background" style="<?php echo is_array($image) && !empty($image) ? "background-image: url('" . esc_url( $image['sizes']['large'] ) . "');" : ''; ?>">
						</div>
						<div class="lc-info">
							<h4 class="case27-secondary-text"><?php echo esc_html( $term->name ) ?></h4>
							<h6>
								<?php echo $term->count ? esc_html( number_format_i18n( $term->count ) ) : __( 'No', 'my-listing' ) ?>
								<?php echo _n( 'listing', 'listings', $term->count, 'my-listing' ) ?>
							</h6>
						</div>
						<div class="lc-icon">
							<i class="<?php echo esc_attr( $icon ) ?>"></i>
						</div>
					</a>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</div>
