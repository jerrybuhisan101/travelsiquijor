<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'gallery_items' => [],
			'gallery_item_interface' => 'WP_IMAGE_OBJECT',
			'items_per_row' => 3,
			'items_per_row_mobile' => 2,
			'gallery_type' => 'carousel',
			'wrapper_class' => 'block-element',
			'ref' => '',
            'is_edit_mode' => false,
		], $data);


	// Since images won't always be available as WP Image Objects, we need to add support for only
	// providing the image url's, as is the case with WP Job Manager gallery images. To make multiple methods work,
	// in this case by passing image objects and image urls, we need to specify a key with the method used, and then format
	// the items to one final format which will then be used in the HTML template.
	// The key is 'gallery_item_interface' and currently it accepts the values 'WP_IMAGE_OBJECT', and 'CASE27_JOB_MANAGER_ARRAY'.
	$gallery_items = [];

	if ($data['gallery_item_interface'] == 'WP_IMAGE_OBJECT') {
		foreach ($data['gallery_items'] as $item) {
			$image_quality = ($data['gallery_type'] == 'carousel-with-preview') ? 'large' : 'medium';

			$image = wp_get_attachment_image_src($item['item']['id'], $image_quality);
			$large_image = wp_get_attachment_image_src($item['item']['id'], 'full');
			$gallery_items[] = [
				'image' => [
					'url' => $image ? $image[0] : false,
					'width' => $image[1],
					'height' => $image[2],
				],
				'large_image' => [
					'url' => $large_image ? $large_image[0] : false,
					'width' => $large_image[1],
					'height' => $large_image[2],
				],
			];
		}
	}

	if ($data['gallery_item_interface'] == 'CASE27_JOB_MANAGER_ARRAY') {
		foreach ($data['gallery_items'] as $item) {
			$image_quality = ($data['gallery_type'] == 'carousel-with-preview') ? 'large' : 'medium';

			$image = job_manager_get_resized_image($item, $image_quality);

			if (!$image) continue;

			list($image_width, $image_height) = getimagesize($image);

			$large_image = job_manager_get_resized_image($item, 'full');
			list($large_image_width, $large_image_height) = getimagesize($large_image);

			$gallery_items[] = [
				'image' => [
					'url' => $image,
					'width' => $image_width,
					'height' => $image_height,
				],
				'large_image' => [
					'url' => $large_image,
					'width' => $large_image_width,
					'height' => $large_image_height,
				],
			];
		}
	}

?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<?php if (!$data['gallery_type'] || $data['gallery_type'] == 'carousel'): ?>
		<div class="element gallery-carousel-block">
			<div class="pf-head">
				<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
					<?php if ($data['icon_style'] != 3): ?>
						<?php echo c27()->get_icon_markup($data['icon']) ?>
					<?php endif ?>
					<h5><?php echo esc_html( $data['title'] ) ?></h5>
				</div>

				<div class="gallery-nav">
					<ul>
						<li>
							<a href="#" class="gallery-prev-btn">
								<i class="material-icons">keyboard_arrow_left</i>
							</a>
						</li>
						<li>
							<a href="#" class="gallery-next-btn">
								<i class="material-icons">keyboard_arrow_right</i>
							</a>
						</li>
					</ul>
				</div>
			</div>

			<div class="pf-body">
				<div class="gallery-carousel owl-carousel" data-items="<?php echo esc_attr( $data['items_per_row'] ) ?>" data-items-mobile="<?php echo esc_attr( $data['items_per_row_mobile'] ) ?>">

					<?php foreach ((array) $gallery_items as $item): ?>
						<?php if ($item['image']['url']): ?>
							<a class="item"
							   href="<?php echo esc_url( $item['large_image'] ? $item['large_image']['url'] : $item['image']['url'] ) ?>"
							   style="background-image: url('<?php echo esc_url( $item['image']['url'] ) ?>')"
							   data-width="<?php echo esc_attr( $item['large_image'] ? $item['large_image']['width'] : $item['image']['width'] ) ?>"
							   data-height="<?php echo esc_attr( $item['large_image'] ? $item['large_image']['height'] : $item['image']['height'] ) ?>">
							</a>
						<?php endif ?>
					<?php endforeach ?>

				</div>
			</div>
		</div>
	<?php endif ?>

	<?php if ($data['gallery_type'] == 'carousel-with-preview'): ?>
		<div class="element slider-padding gallery-block">
			<div class="pf-body">
				<div class="gallerySlider car-slider">
					<div class="owl-carousel galleryPreview">
						<?php foreach ((array) $gallery_items as $item): ?>
							<?php if ($item['image']['url']): ?>
								<a class="item"
								   href="<?php echo esc_url( $item['large_image'] ? $item['large_image']['url'] : $item['image']['url'] ) ?>"
								   style="background-image: url('<?php echo esc_url( $item['image']['url'] ) ?>')"
								   data-width="<?php echo esc_attr( $item['large_image'] ? $item['large_image']['width'] : $item['image']['width'] ) ?>"
								   data-height="<?php echo esc_attr( $item['large_image'] ? $item['large_image']['height'] : $item['image']['height'] ) ?>">
								</a>
							<?php endif ?>
						<?php endforeach ?>
					</div>
					<div class="gallery-thumb owl-carousel" id="customDots" data-items="<?php echo esc_attr( $data['items_per_row'] ) ?>" data-items-mobile="<?php echo esc_attr( $data['items_per_row_mobile'] ) 	?>">
						<?php $i = 0; ?>
						<?php foreach ((array) $gallery_items as $item): ?>
							<?php if ($item['image']['url']): ?>
								<a class="item slide-thumb"
								   data-slide-no="<?php echo esc_attr( $i ) ?>"
								   href="<?php echo esc_url( $item['image']['url'] ) ?>"
								   style="background-image: url('<?php echo esc_url( $item['image']['url'] ) ?>')">
								</a>
							<?php $i++; endif; ?>
						<?php endforeach ?>
					</div>
					<div class="gallery-nav">
						<ul>
							<li>
								<a href="#" class="gallery-prev-btn">
									<i class="material-icons">keyboard_arrow_left</i>
								</a>
							</li>
							<li>
								<a href="#" class="gallery-next-btn">
									<i class="material-icons">keyboard_arrow_right</i>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>
</div>

<?php if ($data['is_edit_mode']): ?>
    <script type="text/javascript">case27_ready_script(jQuery);</script>
<?php endif ?>