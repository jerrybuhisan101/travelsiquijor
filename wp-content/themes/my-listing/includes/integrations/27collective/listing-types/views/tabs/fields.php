<div class="tab-content">
	<input type="hidden" v-model="fields_json_string" name="case27_listing_type_fields">

	<h3 class="section-title">
		Select or create fields for this listing type
		<span class="subtitle">Need help? Read the <a href="https://27collective.net/files/mylisting/docs/#listing-types" target="_blank">documentation</a> or open a ticket in our <a href="https://helpdesk.27collective.net/" target="_blank">helpdesk</a>.</span>
	</h3>

	<div class="fields-wrapper">
		<div class="fields-column left">
			<h4>Used fields <span>Click on a field to edit. Drag & Drop to reorder.</span></h4>

			<draggable v-model="fields.used" :options="{group: 'listing-fields', animation: 100, handle: 'h5'}" :move="onFieldMove" @start="drag=true" @end="drag=false" class="fields-draggable used-fields" :class="	drag ? 'active' : ''">
				<div v-for="field in fields.used" :class="'field-wrapper-type-' + field.type">
					<div class="field-wrapper" :class="'field--' + field.slug" v-if="field">
						<div class="field" :class="'field-type-' + field.type">
							<h5>
								<span class="prefix">+</span>
								{{field.label}}
								<small v-show="!field.is_custom">({{field.default_label}})</small>
								<small v-show="field.is_custom">({{capitalize( field.type )}})</small>
								<span class="actions">
									<span title="Delete this field" @click.prevent="fieldsTab().deleteField(field)"><i class="mi delete"></i></span>
								</span>
							</h5>
							<div class="edit">
							<div class="form-group">
								<label>Label</label>
								<input type="text" v-model="field.label" @input="fieldsTab().setKey(field, field.label)">

								<?php c27()->get_partial('admin/input-language', ['object' => 'field.label_l10n']) ?>
							</div>
							<div class="form-group" v-if="field.is_custom">
								<label>Key</label>
								<input type="text" v-model="field.slug" @input="fieldsTab().setKey(field, field.slug)">
							</div>
							<div class="form-group" v-if="typeof field.placeholder !== 'undefined' && ! field.is_ui">
								<label>Placeholder</label>
								<input type="text" v-model="field.placeholder">

								<?php c27()->get_partial('admin/input-language', ['object' => 'field.placeholder_l10n']) ?>
							</div>
							<div class="form-group" v-if="typeof field.description !== 'undefined' && ! field.is_ui">
								<label>Description</label>
								<input type="text" v-model="field.description">

								<?php c27()->get_partial('admin/input-language', ['object' => 'field.description_l10n']) ?>
							</div>
							<div class="form-group" v-if="typeof field.icon !== 'undefined'">
								<label>Icon</label>
								<iconpicker v-model="field.icon"></iconpicker>
							</div>
							<div class="form-group full-width" v-if="typeof field.required !== 'undefined' && ! field.is_ui">
								<label><input type="checkbox" v-model="field.required"> Required field</label>
							</div>
							<div class="form-group full-width" v-if="typeof field.show_in_submit_form !== 'undefined' && ! field.is_ui">
								<label><input type="checkbox" v-model="field.show_in_submit_form"> Show in submit form</label>
							</div>
							<div class="form-group full-width" v-if="typeof field.show_in_admin !== 'undefined' && ! field.is_ui">
								<label><input type="checkbox" v-model="field.show_in_admin"> Show in admin edit page</label>
							</div>
							<div class="form-group full-width options-field" v-if="['select', 'multiselect', 'radio'].indexOf(field.type) > -1">
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

							<div class="form-group full-width" v-if="field.is_custom && ['term-checklist', 'term-multiselect', 'term-select'].indexOf(field.type) > -1">
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

							<div class="form-group full-width" v-if="field.is_custom && field.type == 'file'">
								<label>Allowed file types</label>
								<select multiple="multiple" v-model="field.allowed_mime_types_arr" @change="fieldsTab().editFieldMimeTypes($event, field)">
									<?php foreach ((array) get_allowed_mime_types() as $extension => $mime): ?>
										<option value="<?php echo "{$extension} => {$mime}" ?>"><?php echo $mime ?></option>
									<?php endforeach ?>
								</select>
								<br><br>
								<label><input type="checkbox" v-model="field.multiple"> Allow multiple files?</label>
							</div>

							<div class="form-group full-width" v-if="field.type == 'related-listing'">
								<label>Related Listing Type</label>
								<div class="select-wrapper">
									<select v-model="field.listing_type">
										<?php foreach ($data['listing_types'] as $listing_type): ?>
											<option value="<?php echo $listing_type->post_name ?>"><?php echo $listing_type->post_title ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>

							<div class="form-group full-width" v-if="field.type == 'date'">
								<label>Format</label>
								<div class="select-wrapper">
									<select v-model="field.format">
										<option value="date">Date</option>
										<option value="datetime">Date + Time</option>
									</select>
								</div>
							</div>

							<div class="form-group-wrapper full-width" v-if="field.type == 'number'">
								<div class="form-group">
									<label>Minimum value</label>
									<input type="number" v-model="field.min">
								</div>

								<div class="form-group">
									<label>Maximum value</label>
									<input type="number" v-model="field.max">
								</div>

								<div class="form-group">
									<label>Step size</label>
									<input type="number" v-model="field.step">
								</div>
							</div>
							</div>
						</div>
					</div>
				</div>
			</draggable>
			<div class="placeholder-fields">
				<div class="field-wrapper">
					<div class="field"><h5>Placeholder field</h5></div>
					<div class="field"><h5>Placeholder field</h5></div>
					<div class="field"><h5>Placeholder field</h5></div>
					<div class="field"><h5>Placeholder field</h5></div>
				</div>
			</div>
		</div>

		<div class="fields-column right">
			<h4>All available fields</h4>

			<draggable v-model="fields.available" :options="{group: 'listing-fields', animation: 100}" @start="drag=true" @end="drag=false" class="fields-draggable available-fields" :class="drag ? 'active' : ''"	>
				<div v-for="field in fields.available">
					<div class="field" :class="'field-type-' + field.type">
						<h5><span title="Drag to add" class="drag-to-add" @click.prevent="fieldsTab().useField(field)"><i class="mi compare_arrows"></i></span>{{field.label}}</h5>
					</div>
				</div>
			</draggable>

			<div class="form-group field add-new-field">
				<label>Create a custom field</label>
				<div class="select-wrapper">
					<select v-model="state.fields.new_field_type">
						<optgroup label="Direct Input">
							<option value="text">Text</option>
							<option value="textarea">Text Area</option>
							<option value="wp-editor">WP Editor</option>
							<option value="password">Password</option>
							<option value="date">Date</option>
							<option value="number">Number</option>
							<option value="url">URL</option>
							<option value="email">Email</option>
						</optgroup>
						<optgroup label="Choices">
							<option value="select">Select</option>
							<option value="multiselect">Multi Select</option>
							<option value="checkbox">Checkbox</option>
							<option value="radio">Radio Buttons</option>
							<option value="related-listing">Related Listing Select</option>
							<option value="select-products">Products Select</option>
						</optgroup>
						<optgroup label="Terms">
							<option value="term-select">Term Select</option>
							<option value="term-multiselect">Term Multi Select</option>
							<option value="term-checklist">Term Checklist</option>
						</optgroup>
						<optgroup label="Form UI">
							<option value="form-heading">Heading</option>
						</optgroup>
						<optgroup label="Others">
							<option value="file">File</option>
						</optgroup>
					</select>
				</div>

				<button class="btn btn-primary pull-right" @click.prevent="fieldsTab().addField()">Create</button>
			</div>
		</div>
	</div>
</div>

<!-- <pre>{{ fields.used }}</pre> -->
