<?php

if ( ! defined( 'CASE27_THEME_DIR' ) ) {
	define( 'CASE27_THEME_DIR', get_template_directory() );
}

if ( ! defined( 'CASE27_INTEGRATIONS_DIR' ) ) {
	define( 'CASE27_INTEGRATIONS_DIR', CASE27_THEME_DIR . '/includes/integrations' );
}

if ( ! defined( 'CASE27_ASSETS_DIR' ) ) {
	define( 'CASE27_ASSETS_DIR', CASE27_THEME_DIR . '/assets' );
}

if ( ! defined( 'CASE27_ENV' ) ) {
	define( 'CASE27_ENV', 'production' );
}

if ( ! defined( 'CASE27_THEME_VERSION' ) ) {
	if (CASE27_ENV == 'dev') {
		define( 'CASE27_THEME_VERSION', rand(1, 99999) );
	} else {
		$theme = wp_get_theme();
		define( 'CASE27_THEME_VERSION', $theme->get('Version') );
	}
}

if ( ! defined( 'ELEMENTOR_PARTNER_ID' ) ) {
	define( 'ELEMENTOR_PARTNER_ID', 2124 );
}

// Load classes.
require_once CASE27_THEME_DIR . '/includes/autoload.php';