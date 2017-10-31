<?php

namespace CASE27\Integrations\ListingTypes\Fields\Presets;

class Title extends Preset {

	public $type                = 'text',
		   $slug                = 'job_title',
		   $label               = __( 'Title', 'my-listing' ),
		   $required            = true,
		   $show_in_admin       = true,
		   $show_in_submit_form = true,
		   $default             = '',
		   $priority            = 1;

	public function renderOptions() {

	}
}