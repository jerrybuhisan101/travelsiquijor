<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'title' => '',
			'wrapper_class' => 'grid-item reveal',
			'ref' => '',
			'categories' => [],
			'categories_item_interface' => 'WP_CATEGORY_OBJECT',
		], $data);
?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element categories-block">
		<div class="pf-head">
			<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
				<?php if ($data['icon_style'] != 3): ?>
					<?php echo c27()->get_icon_markup($data['icon']) ?>
				<?php endif ?>
				<h5><?php echo esc_html( $data['title'] ) ?></h5>
			</div>
		</div>
		<div class="pf-body">
			<div class="listing-details">
				<?php if ($data['categories'] && !is_wp_error($data['categories'])): ?>
					<ul>
						<?php foreach ((array) $data['categories'] as $category):
							if (is_wp_error($category) || !is_object($category)) continue;

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
				<?php endif ?>
			</div>
		</div>
	</div>
</div>