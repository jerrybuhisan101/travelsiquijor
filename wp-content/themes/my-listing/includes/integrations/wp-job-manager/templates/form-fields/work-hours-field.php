<?php
$weekdays = [
	__( 'Monday', 'my-listing' ), __( 'Tuesday', 'my-listing' ), __( 'Wednesday', 'my-listing' ),
	__( 'Thursday', 'my-listing' ), __( 'Friday', 'my-listing' ), __( 'Saturday', 'my-listing' ), __( 'Sunday', 'my-listing' )
	];

$weekdays_english = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

$weekdays_short = [
	__( 'Mon', 'my-listing' ), __( 'Tue', 'my-listing' ), __( 'Wed', 'my-listing' ),
	__( 'Thu', 'my-listing' ), __( 'Fri', 'my-listing' ), __( 'Sat', 'my-listing' ), __( 'Sun', 'my-listing' )
];

?>

<div class="form-group double-input c27-work-hours">
	<ul class="days bl-tabs">
		<div class="bl-tabs-menu">
			<ul class="nav nav-tabs" role="tablist">
				<?php foreach ($weekdays as $i => $day): ?>
					<li role="presentation" <?php echo $i === 0 ? 'class="active"' : '' ?>>
						<a href="#day_<?php echo esc_attr( $i ) ?>"
						   aria-controls="day_<?php echo esc_attr( $i ) ?>"
						   role="tab" class="tab-switch">
						   <span class="visible-lg"><?php echo esc_html( $day ) ?></span>
						   <span class="hidden-lg"><?php echo esc_html( $weekdays_short[ $i ] ) ?></span>
						</a>
					</li>
				<?php endforeach ?>
			</ul>
		</div>
		<div class="tab-content">
			<?php foreach ($weekdays as $i => $day): $static_name = $weekdays_english[$i]; ?>
				<div role="tabpanel" class="tab-pane fade <?php echo $i === 0 ? 'active in' : '' ?>" id="day_<?php echo esc_attr( $i ) ?>">
					<li class="day">
						<select name="<?php echo esc_attr( (isset($field['name']) ? $field['name'] : $key) . "[$static_name][from]" ); ?>" placeholder="<?php esc_attr_e( 'From', 'my-listing' ) ?>" class="custom-select">
							<option value=""><?php esc_html_e( 'From', 'my-listing' ) ?></option>
							<?php foreach (range(0, 86399, 900) as $time):
								$formatted_time = gmdate('H:i', $time);
								$is_selected = isset($field['value']) && isset($field['value'][$static_name]) && isset($field['value'][$static_name]['from']) && $field['value'][$static_name]['from'] == $formatted_time;
								?>
								<option value="<?php echo esc_attr( $formatted_time ) ?>" <?php echo $is_selected ? 'selected="selected"' : '' ?>><?php echo esc_html( $formatted_time ) ?></option>
							<?php endforeach ?>
						</select>
						<select name="<?php echo esc_attr( (isset($field['name']) ? $field['name'] : $key) . "[$static_name][to]" ); ?>" placeholder="<?php esc_attr_e( 'To', 'my-listing' ) ?>" class="custom-select">
							<option value=""><?php esc_html_e( 'To', 'my-listing' ) ?></option>
							<?php foreach (range(0, 86399, 900) as $time):
								$formatted_time = gmdate('H:i', $time);
								$is_selected = isset($field['value']) && isset($field['value'][$static_name]) && isset($field['value'][$static_name]['to']) && $field['value'][$static_name]['to'] == $formatted_time;
								?>
								<option value="<?php echo esc_attr( $formatted_time ) ?>" <?php echo $is_selected ? 'selected="selected"' : '' ?>><?php echo esc_html( $formatted_time ) ?></option>
							<?php endforeach ?>
						</select>
					</li>
				</div>
			<?php endforeach ?>
		</div>
	</ul>
</div>

<div class="form-group">
	<?php
	$timezones = timezone_identifiers_list();
	$default_timezone = date_default_timezone_get();
	$wp_timezone = get_option('timezone_string');
	$listing_timezone = isset($field['value']) && isset($field['value']['timezone']) && in_array( $field['value']['timezone'], $timezones ) ? $field['value']['timezone'] : false;

	$current_timezone = ( $listing_timezone ?: ( $wp_timezone ?: $default_timezone ) );
	?>
	<label><?php _e( 'Timezone', 'my-listing' ) ?></label>
	<select name="<?php echo esc_attr( (isset($field['name']) ? $field['name'] : $key) . "[timezone]" ); ?>" placeholder="<?php esc_attr_e( 'Timezone', 'my-listing' ) ?>" class="custom-select">
		<?php foreach ($timezones as $timezone): ?>
			<option value="<?php echo esc_attr( $timezone ) ?>" <?php echo $timezone == $current_timezone ? 'selected="selected"' : '' ?>><?php echo esc_html( $timezone ) ?></option>
		<?php endforeach ?>
	</select>
</div>

<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>
