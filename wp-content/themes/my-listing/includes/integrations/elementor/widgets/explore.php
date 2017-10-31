<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_Explore extends Widget_Base {

	public function get_name() {
		return 'case27-explore-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Explore Listings', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-post';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		$this->start_controls_section(
			'section_content_block',
			[
				'label' => esc_html__( 'Content', 'my-listing' ),
			]
		);

		$this->add_control(
			'27_title',
			[
				'label' => __( 'Title', 'my-listing' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'What are you looking for?', 'my-listing' ),
			]
		);

		$this->add_control(
			'27_subtitle',
			[
				'label' => __( 'Subtitle', 'my-listing' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Search or select categories', 'my-listing' ),
			]
		);


		$this->add_control(
			'27_active_tab',
			[
				'label' => __( 'Active Tab', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'default' => 'search-form',
				'options' => [
					'search-form' => __( 'Search', 'my-listing' ),
					'categories' => __( 'Categories', 'my-listing' ),
				],
			]
		);

		$this->add_control(
			'27_template',
			[
				'label' => __( 'Template', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'default' => 'explore-1',
				'options' => [
					'explore-1' => __( 'Template 1', 'my-listing' ),
					'explore-2' => __( 'Template 2', 'my-listing' ),
					'explore-no-map' => __( 'Template 3', 'my-listing' ),
				],
			]
		);

		$this->add_control(
			'27_finder_columns',
			[
				'label' => __( 'Columns by default', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'default' => 'finder-one-columns',
				'options' => [
					'finder-one-columns' => __( 'One', 'my-listing' ),
					'finder-two-columns' => __( 'Two', 'my-listing' ),
					'finder-three-columns' => __( 'Three', 'my-listing' ),
				],
				'condition' => ['27_template' => ['explore-1', 'explore-2']],
			]
		);

		$this->add_control(
			'27_map_skin',
			[
				'label' => __( 'Map Skin', 'my-listing' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'skin1',
				'options' => c27()->get_map_skins(),
			]
		);

		$this->add_control(
			'27_listing_types',
			[
				'label' => __( 'Listing Types', 'my-listing' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'type',
						'label' => __( 'Select Listing Type', 'my-listing' ),
						'type' => Controls_Manager::SELECT2,
						'options' => c27()->get_posts_dropdown_array([
							'post_type' => 'case27_listing_type',
							'posts_per_page' => -1,
							], 'post_name'),
					],
				],
				'title_field' => '{{{ type.toUpperCase() }}}',
			]
		);

		$this->add_control(
			'27_categories',
			[
				'label' => __( 'Categories', 'my-listing' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'category',
						'label' => __( 'Select Category', 'my-listing' ),
						'type' => Controls_Manager::SELECT2,
						'options' => c27()->get_terms_dropdown_array([
							'taxonomy' => 'job_listing_category',
							'hide_empty' => false,
							]),
						'multiple' => false,
					],
				],
				'title_field' => '{{{ category }}}',
			]
		);

		$traits->choose_overlay(__( 'Set an overlay for categories.', 'my-listing' ), '27_categories_overlay');


		$this->end_controls_section();
	}


	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('explore', [
			'title' => $this->get_settings('27_title'),
			'subtitle' => $this->get_settings('27_subtitle'),
			'active_tab' => $this->get_settings('27_active_tab'),
			'tabs' => $this->get_settings('27_tabs'),
			'listing_types' => $this->get_settings('27_listing_types'),
			'categories' => $this->get_settings('27_categories'),
			'map_skin' => $this->get_settings('27_map_skin'),
			'template' => $this->get_settings('27_template'),
			'finder_columns' => $this->get_settings('27_finder_columns'),
			'is_edit_mode' => Plugin::$instance->editor->is_edit_mode(),
			'categories_overlay' => [
				'type' => $this->get_settings('27_categories_overlay'),
				'gradient' => $this->get_settings('27_categories_overlay__gradient'),
				'solid_color' => $this->get_settings('27_categories_overlay__solid_color'),
			],
			]);
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_Explore() );
