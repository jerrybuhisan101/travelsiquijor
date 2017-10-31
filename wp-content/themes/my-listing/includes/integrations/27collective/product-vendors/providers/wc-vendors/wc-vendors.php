<?php

namespace CASE27\Integrations\ProductVendors;

class WCVendorsProvider implements ProviderInterface {

	public function activate() {
		if ( ! class_exists( '\\WC_Vendors' ) ) {
			return false;
		}

		add_filter( 'woocommerce_locate_template', [ $this, 'locate_template' ], 20 );

		// CSV Export wrapper.
		add_action( 'case27_woocommerce_before_template_part_csv-export.php', function() { ?> <div class="c27-wc-vendors-export"> <?php }, 5 );
		add_action( 'case27_woocommerce_after_template_part_csv-export.php', function() { ?> </div> <?php });

		// Orders wrapper.
		add_action( 'case27_woocommerce_before_template_part_orders.php', function() { ?> <div class="c27-wc-vendors-orders"> <?php }, 5 );
		add_action( 'case27_woocommerce_after_template_part_orders.php', function() { ?> </div> <?php });

		// Denied wrapper.
		add_action( 'case27_woocommerce_before_template_part_denied.php', function() { ?> <div class="c27-wc-vendors-denied"> <?php }, 5 );
		add_action( 'case27_woocommerce_after_template_part_denied.php', function() { ?> </div> <?php });


		// User Dashboard Pages.
		$this->dashboard_pages();
	}

	public function dashboard_pages()
	{
		// My Store page.
		\CASE27\Classes\DashboardPages::instance()->add_page([
			'endpoint' => '27-store',
			'title' => __( 'My Store', 'my-listing' ),
			'template' => trailingslashit( CASE27_INTEGRATIONS_DIR ) . '27collective/product-vendors/providers/wc-vendors/views/my-store.php',
			'show_in_menu' => true,
			'order' => 5,
			]);
	}

	public function locate_template( $template )
	{
		$_template_name = explode('/templates/', $template);
		$template_name = array_pop($_template_name);
		$template_path = trailingslashit( CASE27_INTEGRATIONS_DIR ) . "27collective/product-vendors/providers/wc-vendors/views/{$template_name}";

		if ( locate_template("includes/integrations/27collective/product-vendors/providers/wc-vendors/views/{$template_name}") && file_exists($template_path) ) {
			return $template_path;
		}

		return $template;
	}
}

return new WCVendorsProvider;
