<?php
/**
 * Listing.
 */

class CASE27_Listings {

	private $listing_type = false;

	public function __construct()
	{
		add_action('case27_add_listing_form_template_start', [$this, 'setup_listing_fields']);
		add_action('wp', [$this, 'setup_listing_fields'], 1);
		add_filter('job_manager_job_listing_data_fields', [$this, 'admin_fields']);

		$this->show_custom_fields_in_admin();
	}

	public function show_custom_fields_in_admin() {
		$types = [
			'location', 'date', 'email', 'hidden', 'links', 'location',
			'number', 'related-listing', 'select-products', 'term-multiselect',
			'url', 'work-hours',
		];

		foreach ($types as $fieldType) {
			if ( file_exists( trailingslashit( CASE27_INTEGRATIONS_DIR ) . "wp-job-manager/templates/form-fields/admin/{$fieldType}.php" ) ) {
				add_action( "job_manager_input_{$fieldType}", function($key, $field) use ( $fieldType ) {
					require_once trailingslashit( CASE27_INTEGRATIONS_DIR ) . "wp-job-manager/templates/form-fields/admin/{$fieldType}.php";
				}, 10, 2);
			}
		}
	}


	/*
     * Setup filters for displaying fields on
     * listing submit form, edit form, and admin edit form.
	 */
	public function setup_listing_fields($listing_type)
	{
		if (isset($_POST['case27_listing_type']) && !empty($_POST['case27_listing_type'])) {
			$listing_type = $_POST['case27_listing_type'];
		}

		// If it's the edit form.
		if ( !empty($_REQUEST['job_id']) ) {
			$listing_type = get_post_meta(absint( $_REQUEST[ 'job_id' ] ), '_case27_listing_type', true);
		}

		if (!is_string($listing_type) || !$listing_type) {
			return;
		}

		$listing_type = get_posts([
			'name' => $listing_type,
			'post_type' => 'case27_listing_type',
			'post_status' => 'publish',
			]);

		if ($listing_type) {
			$this->listing_type = $listing_type[0];

			add_filter( 'submit_job_form_fields', array($this, 'submit_fields') );
		}
	}

	/*
     * Fields on Submit Listing Form.
	 */
	public function submit_fields($fields)
	{
		unset($fields['company']);
		$new_fields = $this->get_submit_form_fields();

		foreach ($fields['job'] as $key => $field) {
			if (!isset($new_fields[$key])) {
				continue;
			}

			$new_fields[$key] = array_merge($field, $new_fields[$key]);
		}

		$new_fields['case27_listing_type'] = [
			'label' => __( 'Listing Type', 'my-listing' ),
			'type' => 'hidden',
			'required' => false,
			'slug' => 'case27_listing_type',
			'value' => $this->listing_type->post_name,
			'priority' => 1,
		];

		return ['job' => $new_fields, 'company' => []];
	}

	/*
     * Fields on Admin Edit Listing Form.
	 */
	public function admin_fields($fields) {
		global $post;

		$listing_type_field = [
			'label' => __( 'Listing Type', 'my-listing' ),
			'type' => 'select',
			'required' => false,
			'slug' => 'case27_listing_type',
			'value' => '',
			'priority' => 1,
			'options' => array_merge( [ '' => '&mdash; Select Type &mdash;' ], c27()->get_posts_dropdown_array([
				'post_type' => 'case27_listing_type',
				'posts_per_page' => -1,
			], 'post_name') ),
		];

		if (!$post->_case27_listing_type) {
			$fields['_case27_listing_type'] = $listing_type_field;
			return $fields;
		}

		$listing_type = get_posts([
			'name' => $post->_case27_listing_type,
			'post_type' => 'case27_listing_type',
			'post_status' => 'publish',
			]);

		if (!$listing_type) {
			$fields['_case27_listing_type'] = $listing_type_field;
			return $fields;
		}

		$this->listing_type = $listing_type[0];

		$new_fields = $this->get_submit_form_fields();

		foreach ($new_fields as $key => $field) {
			if (substr($key, 0, 1) !== '_') {
				$new_fields["_{$key}"] = $field;
				unset($new_fields[$key]);
			}
		}

		foreach ($fields as $key => $field) {
			if (!isset($new_fields[$key])) {
				continue;
			}

			$new_fields[$key] = array_merge($field, $new_fields[$key]);
		}

		$new_fields['_case27_listing_type'] = $listing_type_field;
		$new_fields['_case27_listing_type']['value'] = $this->listing_type->post_name;

		// dd($new_fields);

		return $new_fields;
	}


	/*
     * Get Fields list for current listing.
	 */
	public function get_fields()
	{
		return (array) unserialize(get_post_meta($this->listing_type->ID, 'case27_listing_type_fields', true));
	}


	/*
     * Get Submit Form fields for current listing.
	 */
	public function get_submit_form_fields()
	{
		return array_filter($this->get_fields(), function($field) {
			return isset($field['show_in_submit_form']) && $field['show_in_submit_form'] == true;
		});
	}
}

$GLOBALS['case27_listings'] = new CASE27_Listings;