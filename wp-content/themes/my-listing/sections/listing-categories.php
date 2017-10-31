<?php
	if (!class_exists('WP_Job_Manager')) return;

	$data = c27()->merge_options([
			'categories' => [],
			'template' => 'template_1',
            'overlay_type' => 'gradient',
            'overlay_gradient' => 'gradient1',
            'overlay_solid_color' => 'rgba(0, 0, 0, .5)',
            'columns' => ['lg' => 3, 'md' => 3, 'sm' => 2, 'xs' => 1],
            'container' => 'container-fluid',
		], $data);

	$category_ids = array_column((array) $data['categories'], 'category_id');

	$categories = (array) get_terms([
		'taxonomy' => 'job_listing_category',
		'hide_empty' => false,
		'include' => array_filter($category_ids) ? : [-1],
		'orderby' => 'include',
		]);

	$itemSize = sprintf( 'col-lg-%1$d col-md-%2$d col-sm-%3$d col-xs-%4$d reveal',
						  12 / absint( $data['columns']['lg'] ), 12 / absint( $data['columns']['md'] ),
						  12 / absint( $data['columns']['sm'] ), 12 / absint( $data['columns']['xs'] ) );
?>

<?php if (!$data['template'] || $data['template'] == 'template_1'): ?>
	<section class="i-section discover-places">
		<div class="<?php echo esc_attr( $data['container'] ) ?>">
			<?php if (!is_wp_error($categories)): ?>
				<div class="row section-body">
					<?php foreach ($categories as $category): ?>
						<?php
						$icon = get_field('icon', 'job_listing_category_' . $category->term_id) ? : c27()->defaults()['category']['icon'];
						$image = get_field('image', 'job_listing_category_' . $category->term_id);
						?>
						<div class="<?php echo esc_attr( $itemSize ) ?> reveal">
							<div class="listing-cat" >
								<a href="<?php echo esc_url( get_term_link($category) ) ?>">
									<div class="overlay <?php echo $data['overlay_type'] == 'gradient' ? esc_attr( $data['overlay_gradient'] ) : '' ?>"
                             			 style="<?php echo $data['overlay_type'] == 'solid_color' ? 'background-color: ' . esc_attr( $data['overlay_solid_color'] ) . '; ' : '' ?>"></div>
									<div class="lc-background" style="<?php echo $image && is_array($image) ? "background-image: url('" . esc_url( $image['sizes']['large'] ) . "');" : ''; ?>">
									</div>
									<div class="lc-info">
										<h4 class="case27-secondary-text"><?php echo esc_html( $category->name ) ?></h4>
										<h6>
											<?php echo $category->count ? esc_html( number_format_i18n( $category->count ) ) : __( 'No', 'my-listing' ) ?>
											<?php echo _n( 'listing', 'listings', $category->count, 'my-listing' ) ?>
										</h6>
									</div>
									<div class="lc-icon">
										<i class="<?php echo esc_attr( $icon ) ?>"></i>
									</div>
								</a>
							</div>
						</div>
					<?php endforeach ?>
				</div>
			<?php endif ?>
		</div>
	</section>
<?php endif ?>

<?php if ($data['template'] == 'template_2'): ?>
	<section class="i-section all-categories">
		<div class="<?php echo esc_attr( $data['container'] ) ?>">
			<?php if (!is_wp_error($categories)): ?>
				<div class="row section-body">
					<?php foreach ($categories as $category): ?>
						<?php
						$icon = get_field('icon', 'job_listing_category_' . $category->term_id) ? : c27()->defaults()['category']['icon'];
						$color = get_field('color', 'job_listing_category_' . $category->term_id) ? : c27()->defaults()['category']['color'];
						$text_color = get_field('text_color', 'job_listing_category_' . $category->term_id) ? : c27()->defaults()['category']['text_color'];
						?>
						<div class="<?php echo esc_attr( $itemSize ) ?> ac-category reveal">
							<div class="cat-card" >
								<a href="<?php echo esc_url( get_term_link($category) ) ?>">
									<div class="ac-front-side face">
										<div class="hovering-c">
											<span class="cat-icon" style="background-color: <?php echo esc_attr( $color ) ?>;">
												<i class="<?php echo esc_attr( $icon ) ?>" style="color: <?php echo esc_attr( $text_color ) ?>;"></i>
											</span>
											<span class="category-name"><?php echo esc_html( $category->name ) ?></span>
										</div>
									</div>
									<div class="ac-back-side face" style="background-color: <?php echo esc_attr( $color ) ?>;">
										<div class="hovering-c">
											<p style="color: <?php echo esc_attr( $text_color ) ?>;">
												<?php echo $category->count ? esc_html( number_format_i18n( $category->count ) ) : __( 'No', 'my-listing' ) ?>
												<?php echo _n( 'listing', 'listings', $category->count, 'my-listing' ) ?>
											</p>
										</div>
									</div>
								</a>
							</div>
						</div>
					<?php endforeach ?>
				</div>
			<?php endif ?>
		</div>
	</section>
<?php endif ?>

<?php if ($data['template'] == 'template_3'): ?>
	<section class="i-section">
		<div class="<?php echo esc_attr( $data['container'] ) ?>">
			<?php if (!is_wp_error($categories)): ?>
				<div class="row">
					<?php foreach ($categories as $category): ?>
						<?php $image = get_field('image', 'job_listing_category_' . $category->term_id); ?>
						<div class="<?php echo esc_attr( $itemSize ) ?> car-item reveal">
							<a href="<?php echo esc_url( get_term_link($category) ) ?>">
								<div class="car-item-container">
									<div class="car-item-img" style="<?php echo $image && is_array($image) ? "background-image: url('" . esc_url( $image['sizes']['large'] ) . "');" : ''; ?>">
									</div>
									<div class="car-item-details">
										<h3><?php echo esc_html( $category->name ) ?></h3>
										<p>
											<?php echo $category->count ? esc_html( number_format_i18n( $category->count ) ) : __( 'No', 'my-listing' ) ?>
											<?php echo _n( 'listing', 'listings', $category->count, 'my-listing' ) ?>
										</p>
									</div>
								</div>
							</a>
						</div>
					<?php endforeach ?>
				</div>
			<?php endif ?>
		</div>
	</section>
<?php endif ?>