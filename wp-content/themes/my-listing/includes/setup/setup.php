<?php

class CASE27_Setup {

	protected static $_instance = null;
	protected $demos = [];
	protected $SITE_URL, $SITE_URL_ESCAPED, $SITE_DOMAIN;

	public function __construct()
	{
		$this->SITE_URL = untrailingslashit( get_site_url() );
		$this->SITE_URL_ESCAPED = str_replace(['"', '\''], '', json_encode( $this->SITE_URL ) );
		$this->SITE_DOMAIN = parse_url( $this->SITE_URL )['host'];

		$this->demos = [
			'mycity' => [
				'name' => 'MyCity',
				'slug' => 'mycity',
				'description' => 'Discover great spots and events near you. Your real time guide for bars, clubs and lounges in your city.',
				'xml-file-url' => CASE27_THEME_DIR . '/includes/setup/demos/mycity.xml',
				'background-url' => 'http://mylisting.27collective.net/my-city/wp-content/uploads/sites/4/2017/07/nightlife-1024x576.jpg',
				'replace_strings' => [
					['string' => 'http://mylisting.27collective.net/my-city',    'replace_with' => $this->SITE_URL],
					['string' => 'http://mylisting.27collective.net',            'replace_with' => $this->SITE_URL],
					['string' => 'http:\/\/mylisting.27collective.net\/my-city', 'replace_with' => $this->SITE_URL_ESCAPED],
					['string' => 'mylisting.27collective.net',                   'replace_with' => $this->SITE_DOMAIN],
				],
			],

			'mycar' => [
				'name' => 'MyCar',
				'slug' => 'mycar',
				'description' => 'Find a car to rent or buy.',
				'xml-file-url' => CASE27_THEME_DIR . '/includes/setup/demos/mycar.xml',
				'background-url' => 'http://mylisting.27collective.net/mycar/wp-content/uploads/sites/5/2017/08/636282002612902910LL-1024x576.jpg',
				'replace_strings' => [
					['string' => 'http://mylisting.27collective.net/mycar',      'replace_with' => $this->SITE_URL],
					['string' => 'http://mylisting.27collective.net',            'replace_with' => $this->SITE_URL],
					['string' => 'http:\/\/mylisting.27collective.net\/mycar',   'replace_with' => $this->SITE_URL_ESCAPED],
					['string' => 'mylisting.27collective.net',                   'replace_with' => $this->SITE_DOMAIN],
				],
			],

			'myhome' => [
				'name' => 'MyHome',
				'slug' => 'myhome',
				'description' => 'Find your next apartment or home.',
				'xml-file-url' => CASE27_THEME_DIR . '/includes/setup/demos/myhome.xml',
				'background-url' => 'http://mylisting.27collective.net/myhome/wp-content/uploads/sites/6/2017/08/22222222-3-1024x576.jpg',
				'replace_strings' => [
					['string' => 'http://mylisting.27collective.net/myhome',      'replace_with' => $this->SITE_URL],
					['string' => 'http://mylisting.27collective.net',             'replace_with' => $this->SITE_URL],
					['string' => 'http:\/\/mylisting.27collective.net\/myhome',   'replace_with' => $this->SITE_URL_ESCAPED],
					['string' => 'mylisting.27collective.net',                    'replace_with' => $this->SITE_DOMAIN],
				],
			],
		];

		add_action( 'admin_init', array( $this, 'register_importer' ) );
	}

	public static function instance()
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	public function get_demos()
	{
		return $this->demos;
	}

	public function get_demo($demo)
	{
		return isset($this->demos[$demo]) ? $this->demos[$demo] : '';
	}

	public function is_demo_imported($demo)
	{
		return in_array( $demo, get_option( 'case27_imported_demos', [] ) );
	}

	public function get_uploads_zip_url()
	{
		return 'http://27collective.net/files/mylisting/uploads.zip';
	}

	public function get_formatted_demo_file($demo)
	{
		global $wp_filesystem;

		$xml = $wp_filesystem->get_contents( $this->demos[$demo]['xml-file-url'] );

		foreach ((array) $this->demos[$demo]['replace_strings'] as $string) {
			$xml = str_replace($string['string'], $string['replace_with'], $xml);
		}

		$tmpDemoPath = trailingslashit( wp_upload_dir()['basedir'] ) . 'tmp_demo_file.xml';

		$wp_filesystem->put_contents( $tmpDemoPath, $xml );

		return $tmpDemoPath;
	}

	public function create_demo_menus()
	{
		$menuname = 'Demo Menu';
		$menu_exists = wp_get_nav_menu_object( $menuname );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menuname );

			$itemID = wp_update_nav_menu_item($menu_id, 0, [
				'menu-item-title' => __( 'Pages', 'my-listing' ),
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
				]);

			foreach ((array) get_pages() as $page) {
				wp_update_nav_menu_item($menu_id, 0, [
					'menu-item-title' => $page->post_title,
					'menu-item-url' => esc_url( get_permalink($page->ID) ),
					'menu-item-status' => 'publish',
					'menu-item-parent-id' => $itemID,
					]);
			}

			$locations = get_theme_mod('nav_menu_locations');
			$locations['primary'] = $menu_id;
			$locations['footer'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	public function register_importer()
	{
		if ( defined( 'WP_LOAD_IMPORTERS' ) && class_exists( 'WP_IMPORT' ) && isset($_GET['demo']) && ($demo = CASE27_Setup::get_demo($_GET['demo'])) ) {
			register_importer( 'case27_demo_import',  __( '<strong>27 > </strong> Import Demo', 'my-listing' ),
				__( 'Choose a <strong>demo</strong> from a list of available ones to use with this theme.', 'my-listing' ),
				function() use ($demo) {
					if (CASE27_ENV === 'dev') {
						wp_redirect( admin_url( 'admin.php?page=case27-tools-setup&msg=done' ) );
						die;
					}

					// Large time limit, since the time of the import process is unpredictable.
					set_time_limit( DAY_IN_SECONDS );

					// Initalize the WP FileSystem API.
					WP_Filesystem();

					// Don't fetch attachments since the uploads folder will be downloaded and added using a zip file.
					add_filter('import_allow_create_users', '__return_false');
					add_filter('import_allow_fetch_attachments', '__return_false');

					// Replace the urls in the demo .xml file with the ones of the current site.
					// And temporarily save the file in the uploads directory.
					$demoFile = $this->get_formatted_demo_file( $demo['slug'] );

					// Import the xml file generated usign the WordPress exporter.
					$importer = new WP_Import;
					$importer->import( $demoFile );
					unlink($demoFile);

					// Generate the uploads folder.
					$this->add_uploads();

					// Create demo menus containing all pages.
					$this->create_demo_menus();

					// Save an option with the name of the imported demo(s).
					$importedDemos = get_option( 'case27_imported_demos', [] );
					update_option( 'case27_imported_demos', array_merge( [$demo['slug']], $importedDemos ), false );

					// Redirect back to the import demo page.
					wp_redirect( admin_url( 'admin.php?page=case27-tools-setup&msg=done' ) );
					die;
				});
		}
	}

	public function add_uploads()
	{
		$new_dir = wp_upload_dir()['basedir'];

		$files = require CASE27_THEME_DIR . '/includes/setup/files.php';

		foreach ($files as $file) {
			$dirname = pathinfo( $file, PATHINFO_DIRNAME );
			$filename = pathinfo( $file, PATHINFO_BASENAME );
			$extension = pathinfo( $file, PATHINFO_EXTENSION );
			wp_mkdir_p( trailingslashit( $new_dir ) . $dirname );

			if ( in_array( $extension, ['jpg', 'jpeg'] ) ) {
				copy(CASE27_THEME_DIR . '/includes/setup/placeholders/' . rand(1, 3) . '.jpg', trailingslashit( $new_dir ) . trailingslashit( $dirname ) . $filename );
			} elseif ( in_array( $extension, ['png'] ) ) {
				copy(CASE27_THEME_DIR . '/includes/setup/placeholders/' . rand(1, 3) . '.png', trailingslashit( $new_dir ) . trailingslashit( $dirname ) . $filename );
			} else {
				touch( trailingslashit( $new_dir ) . trailingslashit( $dirname ) . $filename );
			}
		}
	}
}

CASE27_Setup::instance();
