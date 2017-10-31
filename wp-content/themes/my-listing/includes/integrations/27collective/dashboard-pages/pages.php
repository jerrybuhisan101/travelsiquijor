<?php

class CASE27_Integrations_Dashboard_Pages {

	/**
	 * Plugin actions.
	 */
	public function __construct() {
		// My Listings page.
		\CASE27\Classes\DashboardPages::instance()->add_page([
			'endpoint' => '27-listings',
			'title' => __( 'My Listings', 'my-listing' ),
			'template' => trailingslashit( CASE27_INTEGRATIONS_DIR ) . '27collective/dashboard-pages/templates/my-listings.php',
			'show_in_menu' => true,
			'order' => 2,
			]);

		// My Bookmakrs page.
		\CASE27\Classes\DashboardPages::instance()->add_page([
			'endpoint' => '27-bookmarks',
			'title' => __( 'Bookmarks', 'my-listing' ),
			'template' => trailingslashit( CASE27_INTEGRATIONS_DIR ) . '27collective/dashboard-pages/templates/bookmarks.php',
			'show_in_menu' => true,
			'order' => 4,
			]);
	}
}

new CASE27_Integrations_Dashboard_Pages;
