<?php

namespace CASE27\Integrations\ListingTypes\Fields\Presets;

use CASE27\Integrations\ListingTypes\Fields\Field as Field;

abstract class Preset extends Field {

	public $is_custom = false,
		   $default_label = false,
		   $priority = 5;

	public function renderOptions();
}