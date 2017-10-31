<?php
	$data = c27()->merge_options([
			'placeholder' => 'Search...',
		], $data);

	$featured_categories = c27()->get_setting('header_search_form_featured_categories', []);
?>

<?php if (class_exists('WP_Job_Manager')): ?>

	<div id="c27-header-search-form">
		<form action="<?php echo esc_url( c27()->get_setting('general_explore_listings_page') ) ?>" method="GET">
			<div class="dark-forms header-search">
				<i class="material-icons">search</i>
				<input type="search" placeholder="<?php echo esc_attr($data['placeholder']) ?>" v-model="search_query" name="search_keywords" autocomplete="off">
				<div class="instant-results" v-if="search_query_valid">
					<ul class="instant-results-list ajax-results"></ul>
					<button type="submit" class="buttons full-width button-5 search view-all-results" v-show="has_found_posts && !loading">
						<i class="material-icons">search</i><?php _e( 'View all results', 'my-listing' ) ?>
					</button>
					<button type="submit" class="buttons full-width button-5 search view-all-results" v-show="!has_found_posts && !loading">
						<i class="material-icons">close</i><?php _e( 'No results', 'my-listing' ) ?>
					</button>
					<div class="loader-bg" v-show="loading">
						<?php c27()->get_partial('spinner', [
							'color' => '#777',
							'classes' => 'center-vh',
							'size' => 24,
							'width' => 2.5,
							]); ?>
					</div>
				</div>
				<?php if (!is_wp_error($featured_categories) && is_array($featured_categories)): ?>
					<div class="instant-results" v-if="!search_query_valid">
						<ul class="instant-results-list">
            				<li class="ir-cat"><?php _e( 'Featured', 'my-listing' ) ?></li>
							<?php foreach ($featured_categories as $category): ?>
								<?php
								$icon = get_field('icon', 'job_listing_category_' . $category->term_id) ? : c27()->defaults()['category']['icon'];
								$color = get_field('color', 'job_listing_category_' . $category->term_id) ? : c27()->defaults()['category']['color'];
								$text_color = get_field('text_color', 'job_listing_category_' . $category->term_id) ? : c27()->defaults()['category']['text_color'];
								?>
								<li>
									<a href="<?php echo esc_url( get_term_link($category) ) ?>">
										<span class="cat-icon" style="background-color: <?php echo esc_attr( $color ) ?>;">
											<i class="<?php echo esc_attr( $icon ) ?>" style="color: <?php echo esc_attr( $text_color ) ?>;"></i>
										</span>
										<span class="category-name"><?php echo esc_html( $category->name ) ?></span>
									</a>
								</li>
							<?php endforeach ?>
						</ul>
					</div>
				<?php endif ?>
			</div>
		</form>
	</div>

<?php else: ?>

	<div>
		<form action="<?php echo esc_url( home_url('/') ) ?>" method="GET">
			<div class="dark-forms header-search">
				<i class="material-icons">search</i>
				<input type="search" placeholder="<?php echo esc_attr( $data['placeholder'] ) ?>" value="<?php echo isset($_GET['s']) ? esc_attr( sanitize_text_field($_GET['s']) ) : '' ?>" name="s">
				<div class="instant-results"></div>
			</div>
		</form>
	</div>

<?php endif ?>
