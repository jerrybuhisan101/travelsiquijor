<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'title' => '',
			'hours' => [],
			'wrapper_class' => 'grid-item reveal',
			'ref' => '',
		], $data);

	$today = isset($data['hours'][date('l')]) ? $data['hours'][date('l')] : false;
	$open_status = c27()->get_open_message_from_work_hours( $data['hours'] )
?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element work-hours-block">
		<div class="pf-head" data-toggle="collapse" data-target="#open-hours">
			<div class="title-style-1">
				<?php echo c27()->get_icon_markup($data['icon']) ?>
				<h5><span class="<?php echo esc_attr( $open_status['status'] ) ?> work-hours-status"><?php echo esc_html( $open_status['message'] ) ?></span></h5>
				<?php if ($today && ! empty( $today['from'] ) && ! empty( $today['to'] ) ): ?>
					<p class="timing-today"><?php _e( 'Open hours today:', 'my-listing' ) ?> <?php echo esc_html( $today['from'] . ' - ' . $today['to'] ) ?></p>
				<?php else: ?>
					<p class="timing-today"><?php _e( 'Closed today', 'my-listing' ) ?></p>
				<?php endif ?>
			</div>
		</div>
		<div id="open-hours" class="pf-body collapse">
			<ul class="extra-details">
				<?php foreach ((array) $data['hours'] as $day => $hours):
					if ( $day == 'timezone' ) continue; ?>
					<li>
						<p class="item-attr"><?php echo esc_html( $day ) ?></p>
						<p class="item-property">
							<?php echo ( ! empty( $hours['from'] ) && ! empty( $hours['to'] ) ) ? esc_html( $hours['from'] . ' - ' . $hours['to'] ) : '<em>' . __( 'Closed', 'my-listing' ) . '</em>' ?>
						</p>
					</li>
				<?php endforeach ?>

				<?php if ( ! empty( $data['hours']['timezone'] ) ):
					$localTime = new DateTime( 'now', new DateTimeZone( $data['hours']['timezone'] ) );
					?>
					<p class="work-hours-timezone">
						<?php printf( __( '(%s) %s local time', 'my-listing' ), $localTime->format( 'D' ), $localTime->format( 'H:i' ) ) ?>
					</p>
				<?php endif ?>
			</ul>
		</div>
	</div>
</div>
