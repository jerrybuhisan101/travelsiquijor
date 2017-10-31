<?php
// Listing field types.
$GLOBALS['case27_listing_fields'] = array(
	'job' => array(
		'job_title' => array(
			'slug' => 'job_title',
			'label' => __( 'Title', 'my-listing' ),
			'type' => 'text',
			'placeholder' => '',
			'required' => true,
			'priority' => 1,
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_tagline' => array(
			'slug' => 'job_tagline',
			'label' => __( 'Tagline', 'my-listing' ),
			'type' => 'text',
			'placeholder' => '',
			'required' => false,
			'priority' => 1,
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_location' => array(
			'slug' => 'job_location',
			'type' => 'location',
			'required' => false,
			'label' => __( 'Location', 'my-listing' ),
			'placeholder' => __( 'e.g. "London"', 'my-listing' ),
			'priority' => 2,
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_type' => array(
			'slug' => 'job_type',
			'label' => __( 'Job Type', 'my-listing' ),
			'required' => false,
			'priority' => 3,
			'placeholder' => '',
			'show_in_admin' => true,
			'show_in_submit_form' => false,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_category' => array(
			'slug' => 'job_category',
			'label' => __( 'Category', 'my-listing' ),
			'priority' => 4,
			'required' => false,
			'placeholder' => '',
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'type' => 'term-multiselect',
			'taxonomy' => 'job_listing_category',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_tags' => array(
			'slug' => 'job_tags',
			'label' => __( 'Tags', 'my-listing' ),
			'priority' => 4.5,
			'required' => false,
			'placeholder' => '',
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'type' => 'term-multiselect',
			'taxonomy' => 'case27_job_listing_tags',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_description' => array(
			'slug' => 'job_description',
			'type' => 'textarea',
			'label' => __( 'Description', 'my-listing' ),
			'priority' => 5,
			'required' => true,
			'placeholder' => '',
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		// 'application' => array(
		// 	'slug' => 'application',
		// 	'label' => 'Application email/URL',
		// 	'type' => 'text',
		// 	'required' => true,
		// 	'placeholder' => 'Enter an email address or website URL',
		// 	'priority' => 6,
		// 	'show_in_admin' => true,
		// 	'show_in_submit_form' => false,
		// 	'description' => '',
		// ),

		'job_email' => array(
			'slug' => 'job_email',
			'label' => __( 'Contact Email', 'my-listing' ),
			'type' => 'email',
			'required' => false,
			'placeholder' => '',
			'priority' => 10,
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_logo' => array(
			'slug' => 'job_logo',
			'label' => __( 'Logo', 'my-listing' ),
			'type' => 'file',
			'required' => true,
			'priority' => 11,
			'placeholder' => '',
			'ajax' => true,
			'multiple' => false,
			'allowed_mime_types' => array(
				'jpg' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'gif' => 'image/gif',
				'png' => 'image/png',
			),
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_cover' => array(
			'slug' => 'job_cover',
			'label' => __( 'Cover Image', 'my-listing' ),
			'type' => 'file',
			'required' => false,
			'priority' => 12,
			'ajax' => true,
			'placeholder' => '',
			'multiple' => false,
			'allowed_mime_types' => array(
				'jpg' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'gif' => 'image/gif',
				'png' => 'image/png',
			),
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_gallery' => array(
			'slug' => 'job_gallery',
			'label' => __( 'Gallery Images', 'my-listing' ),
			'type' => 'file',
			'required' => false,
			'priority' => 12,
			'ajax' => true,
			'placeholder' => '',
			'multiple' => true,
			'allowed_mime_types' => array(
				'jpg' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'gif' => 'image/gif',
				'png' => 'image/png',
			),
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_website' => array(
			'slug' => 'job_website',
			'label' => __( 'Website', 'my-listing' ),
			'type' => 'url',
			'required' => false,
			'placeholder' => '',
			'priority' => 13,
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_phone' => array(
			'slug' => 'job_phone',
			'label' => __( 'Phone Number', 'my-listing' ),
			'type' => 'text',
			'required' => false,
			'placeholder' => '',
			'priority' => 14,
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_video_url' => array(
			'slug' => 'job_video_url',
			'label' => __( 'Video URL', 'my-listing' ),
			'type' => 'url',
			'required' => false,
			'placeholder' => '',
			'priority' => 15,
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'job_date' => array(
			'slug' => 'job_date',
			'label' => __( 'Date', 'my-listing' ),
			'type' => 'date',
			'required' => false,
			'placeholder' => '',
			'priority' => 15.5,
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'format' => 'date',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'related_listing' => array(
			'slug' => 'related_listing',
			'label' => __( 'Related Listing', 'my-listing' ),
			'priority' => 16,
			'required' => false,
			'placeholder' => '',
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'type' => 'related-listing',
			'listing_type' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'work_hours' => array(
			'slug' => 'work_hours',
			'label' => __( 'Work Hours', 'my-listing' ),
			'priority' => 16.5,
			'required' => false,
			'placeholder' => '',
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'type' => 'work-hours',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'select_products' => array(
			'slug' => 'select_products',
			'label' => __( 'Products', 'my-listing' ),
			'priority' => 17,
			'required' => false,
			'placeholder' => '',
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'type' => 'select-products',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'links' => array(
			'slug' => 'links',
			'label' => __( 'Social Networks', 'my-listing' ),
			'priority' => 17.5,
			'required' => false,
			'placeholder' => '',
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'type' => 'links',
			'listing_type' => '',
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'price_range' => array(
			'slug' => 'price_range',
			'label' => __( 'Price Range', 'my-listing' ),
			'type' => 'select',
			'required' => false,
			'placeholder' => '',
			'priority' => 18,
			'show_in_admin' => true,
			'show_in_submit_form' => true,
			'description' => '',
			'options' => ['$' => '$', '$$' => '$$', '$$$' => '$$$'],
			'label_l10n' => ['locale' => 'en_US'],
			'placeholder_l10n' => ['locale' => 'en_US'],
			'description_l10n' => ['locale' => 'en_US'],
		),

		'form_heading' => array(
			'slug' => 'form_heading',
			'label' => __( 'Form Heading', 'my-listing' ),
			'type' => 'form-heading',
			'priority' => 20,
			'show_in_submit_form' => true,
			'icon' => 'icon-pencil-2',
			'required' => false,
			'label_l10n' => ['locale' => 'en_US'],
		),
	),
);