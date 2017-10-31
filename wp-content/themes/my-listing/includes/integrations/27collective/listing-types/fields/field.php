<?php

namespace CASE27\Integrations\ListingTypes\Fields;

abstract class Field {

	public $props = [
			   'type'                => 'text',
			   'slug'                => 'custom-field',
			   'label'               => 'Custom Field',
			   'default'             => '',
			   'required'            => false,
			   'is_custom'           => true,
			   'label_l10n'          => ['locale' => 'en_US'],
			   'description'         => '',
			   'placeholder'         => '',
			   'default_label'       => 'Custom Field',
			   'show_in_admin'       => true,
			   'description_l10n'    => ['locale' => 'en_US'],
			   'placeholder_l10n'    => ['locale' => 'en_US'];
			   'show_in_submit_form' => true,
		   ];

	public function render();

	public function renderField() {
		ob_start(); ?>
		<div class="field-settings-wrapper">
			<?php $this->render(); ?>
		</div>
		<?php return ob_get_clean();
	}

	public function getLabelField() {
		ob_start(); ?>
		<div class="form-group">
			<label>Label</label>
			<input type="text" v-model="field.label" @input="fieldsTab().setKey(field, field.label)">

			<?php c27()->get_partial('admin/input-language', ['object' => 'field.label_l10n']) ?>
		</div>
		<?php return ob_get_clean();
	}

	public function getKeyField() {
		ob_start(); ?>
		<div class="form-group">
			<label>Key</label>
			<input type="text" v-model="field.slug" @input="fieldsTab().setKey(field, field.slug)">
		</div>
		<?php return ob_get_clean();
	}

	public function getPlaceholderField() {
		ob_start(); ?>
		<div class="form-group">
			<label>Placeholder</label>
			<input type="text" v-model="field.placeholder">

			<?php c27()->get_partial('admin/input-language', ['object' => 'field.placeholder_l10n']) ?>
		</div>
		<?php return ob_get_clean();
	}

	public function getDescriptionField() {
		ob_start(); ?>
		<div class="form-group">
			<label>Description</label>
			<input type="text" v-model="field.description">

			<?php c27()->get_partial('admin/input-language', ['object' => 'field.description_l10n']) ?>
		</div>
		<?php return ob_get_clean();
	}

	public function getIconField() {
		ob_start(); ?>
		<div class="form-group">
			<label>Icon</label>
			<iconpicker v-model="field.icon"></iconpicker>
		</div>
		<?php return ob_get_clean();
	}

	public function getRequiredField() {
		ob_start(); ?>
		<div class="form-group full-width">
			<label><input type="checkbox" v-model="field.required"> Required field</label>
		</div>
		<?php return ob_get_clean();
	}

	public function getShowInSubmitFormField() {
		ob_start(); ?>
		<div class="form-group full-width">
			<label><input type="checkbox" v-model="field.show_in_submit_form"> Show in submit form</label>
		</div>
		<?php return ob_get_clean();
	}

	public function getShowInAdminField() {
		ob_start(); ?>
		<div class="form-group full-width">
			<label><input type="checkbox" v-model="field.show_in_admin"> Show in admin edit page</label>
		</div>
		<?php return ob_get_clean();
	}

	public function getOptionsField() {
		ob_start(); ?>
		<div class="form-group full-width options-field">
			<hr>
			<label>Options</label>

			<div class="form-group" v-for="(value, key, index) in field.options" v-show="!state.fields.editingOptions">
				<input type="text" v-model="field.options[key]" disabled="disabled">
			</div>

			<div v-show="!state.fields.editingOptions && !Object.keys(field.options).length">
				<small><em>No options added yet.</em></small>
			</div>

			<textarea
			id="custom_field_options"
			v-show="state.fields.editingOptions"
			placeholder="Add each option in a new line."
			@keyup="fieldsTab().editFieldOptions($event, field)"
			cols="50" rows="7">{{ Object.keys(field.options).map(function(el) { return field.options[el]; }).join('\n') }}</textarea>
			<small v-show="state.fields.editingOptions"><em>Put each option in a new line.</em></small>
			<br><br v-show="state.fields.editingOptions || Object.keys(field.options).length">
			<button @click.prevent="state.fields.editingOptions = !state.fields.editingOptions;" class="btn btn-primary">{{ state.fields.editingOptions ? 'Save Options' : 'Add/Edit Options' }}</button>
		</div>
		<?php return ob_get_clean();
	}

	public function getTaxonomyField() {
		ob_start(); ?>
		<div class="form-group full-width">
			<label>Taxonomy</label>
			<div class="select-wrapper">
				<select v-model="field.taxonomy">
					<?php
					foreach ( (array) get_taxonomies(null, 'objects') as $taxonomy ) {
						echo "<option value='{$taxonomy->name}'>{$taxonomy->label}</option>";
					}
					?>
				</select>
			</div>
		</div>
		<?php return ob_get_clean();
	}

	public function getAllowedMimeTypesField() {
		ob_start(); ?>
		<div class="form-group full-width">
			<label>Allowed file types</label>
			<select multiple="multiple" v-model="field.allowed_mime_types_arr" @change="fieldsTab().editFieldMimeTypes($event, field)">
				<?php foreach ((array) get_allowed_mime_types() as $extension => $mime): ?>
					<option value="<?php echo "{$extension} => {$mime}" ?>"><?php echo $mime ?></option>
				<?php endforeach ?>
			</select>
			<br><br>
			<label><input type="checkbox" v-model="field.multiple"> Allow multiple files?</label>
		</div>
		<?php return ob_get_clean();
	}

	public function getListingTypeField() {
		ob_start(); ?>
		<div class="form-group full-width">
			<label>Related Listing Type</label>
			<div class="select-wrapper">
				<select v-model="field.listing_type">
					<?php foreach ($data['listing_types'] as $listing_type): ?>
						<option value="<?php echo $listing_type->post_name ?>"><?php echo $listing_type->post_title ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<?php return ob_get_clean();
	}

	public function getAllowedMimeTypesField() {
		ob_start(); ?>
		<div class="form-group full-width" v-if="field.type == 'date'">
			<label>Format</label>
			<div class="select-wrapper">
				<select v-model="field.format">
					<option value="date">Date</option>
					<option value="datetime">Date + Time</option>
				</select>
			</div>
		</div>
		<?php return ob_get_clean();
	}

	public function getMinField() {
		ob_start(); ?>
		<div class="form-group">
			<label>Minimum value</label>
			<input type="number" v-model="field.min">
		</div>
		<?php return ob_get_clean();
	}

	public function getMaxField() {
		ob_start(); ?>
		<div class="form-group">
			<label>Maximum value</label>
			<input type="number" v-model="field.max">
		</div>
		<?php return ob_get_clean();
	}

	public function getStepField() {
		ob_start(); ?>
		<div class="form-group">
			<label>Step size</label>
			<input type="number" v-model="field.step">
		</div>
		<?php return ob_get_clean();
	}
}