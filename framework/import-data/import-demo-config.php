<?php
if ( ! class_exists( 'FamiSampleDataSettings' ) ) {
	class FamiSampleDataSettings {
		
		public function __construct() {
			
			// Filter Sample Data Menu
			add_filter( 'import_sample_data_menu_args', array( $this, 'import_sample_data_menu_args' ) );
			add_filter( 'import_sample_data_packages', array( $this, 'import_sample_data_packages' ) );
			add_filter( 'import_sample_data_required_plugins', array( $this, 'import_sample_data_required_plugins' ) );
			add_filter( 'import_sample_data_theme_option_key', array( $this, 'import_sample_data_theme_option_key' ) );
			add_action( 'import_sample_data_after_install_sample_data', array(
				$this,
				'import_sample_data_after_install_sample_data'
			), 10, 1 );
		}
		
		public function import_sample_data_theme_option_key( $theme_option_key ) {
			return '_cs_options';
		}
		
		public function import_sample_data_required_plugins( $plugins ) {
			$theme_plugins_uri = get_template_directory_uri() . '/framework/plugins/';
			$plugins           = array(
				array(
					'name'        => 'Casano Toolkit',
					'slug'        => 'casano-toolkit',
					'source'      => $theme_plugins_uri . 'casano-toolkit.zip',
					'source_type' => 'external',
					'file_path'   => 'casano-toolkit/casano-toolkit.php',
				),
				array(
					'name'        => 'WPBakery Page Builder',
					'slug'        => 'js_composer',
					'source'      => $theme_plugins_uri . 'js_composer.zip',
					'source_type' => 'js_composer',
					'file_path'   => 'js_composer/js_composer.php',
				),
				array(
					'name'        => 'WooCommerce',
					'slug'        => 'woocommerce',
					'required'    => true,
					'file_path'   => 'woocommerce/woocommerce.php',
					'source_type' => 'repo', // Plugins On wordpress.org
				),
				array(
					'name'        => 'Fami Responsive Visual Composer',
					'slug'        => 'fami-responsive-js-composer',
					'source'      => $theme_plugins_uri . 'fami-responsive-js-composer.zip',
					'source_type' => 'external',
					'file_path'   => 'fami-responsive-js-composer/init.php',
				),
				array(
					'name'        => 'Fami Sales Popup',
					'slug'        => 'fami-sales-popup',
					'source'      => $theme_plugins_uri . 'fami-sales-popup.zip',
					'source_type' => 'external',
					'file_path'   => 'fami-sales-popup/fami-sales-popup.php',
				),
				array(
					'name'        => 'Fami Buy Together',
					'slug'        => 'fami-buy-together',
					'source'      => $theme_plugins_uri . 'fami-buy-together.zip',
					'source_type' => 'external',
					'file_path'   => 'fami-buy-together/fami-buy-together.php',
				),
				array(
					'name'        => 'Fami WooCommerce Compare',
					'slug'        => 'fami-wc-compare',
					'required'    => true,
					'file_path'   => 'fami-wc-compare/fami-wc-compare.php',
					'source_type' => 'repo', // Plugins On wordpress.org
				),
				array(
					'name'        => 'Ziss - WooCommerce Product Pinner',
					'slug'        => 'ziss',
					'source'      => $theme_plugins_uri . 'ziss.zip',
					'source_type' => 'external',
					'file_path'   => 'ziss/zanisshop.php',
				),
				array(
					'name'        => 'WooCommerce Product Filter',
					'slug'        => 'plugin_slug',
					'source'      => $theme_plugins_uri . 'prdctfltr.zip',
					'source_type' => 'external',
					'file_path'   => 'prdctfltr/prdctfltr.php',
				),
				array(
					'name'        => 'YITH WooCommerce Wishlist', // The plugin name
					'slug'        => 'yith-woocommerce-wishlist', // The plugin slug (typically the folder name)
					'required'    => false, // If false, the plugin is only 'recommended' instead of required
					'source_type' => 'repo', // Plugins On wordpress.org
					'file_path'   => 'yith-woocommerce-wishlist/init.php',
				),
				array(
					'name'        => 'YITH WooCommerce Quick View', // The plugin name
					'slug'        => 'yith-woocommerce-quick-view', // The plugin slug (typically the folder name)
					'required'    => false, // If false, the plugin is only 'recommended' instead of required
					'source_type' => 'repo', // Plugins On wordpress.org
					'file_path'   => 'yith-woocommerce-quick-view/init.php',
				),
				array(
					'name'        => 'Slider Revolution',
					'slug'        => 'revslider',
					'source'      => $theme_plugins_uri . 'revslider.zip',
					'source_type' => 'external',
					'file_path'   => 'revslider/revslider.php',
				),
			);
			
			return $plugins;
		}
		
		/**
		 * Change Menu Sample dataß.
		 *
		 * @param   array $uri Remote URI for fetching content.
		 *
		 * @return  array
		 */
		public function import_sample_data_menu_args( $args ) {
			
			$args = array(
				'parent_slug' => 'casano_menu',
				'page_title'  => esc_html__( 'Import Sample Data', 'casano' ),
				'menu_title'  => esc_html__( 'Import Sample Data', 'casano' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'sample-data',
				'function'    => 'FamiImport_Sample_Data_Dashboard::dashboard'
			);
			
			return $args;
		}
		
		public function import_sample_data_packages( $packages ) {
			$previews_uri = get_template_directory_uri() . '/framework/import-data/previews/';
			
			return array(
				'casano' => array(
					'id'          => 'casano',
					'name'        => 'Casano',
					'thumbnail'   => get_template_directory_uri() . '/screenshot.jpg',
					'demo'        => 'https://casano.famithemes.com',
					'download'    => get_template_directory_uri() . '/framework/import-data/data/casano-data.zip',
					'tags'        => array( 'all', 'simple' ),
					'main'        => true,
					'sample-page' => array(
						array(
							'name'      => 'Home-Modern',
							'slug'      => 'home-modern',
							'thumbnail' => $previews_uri . 'home-modern.jpg',
						),
						array(
							'name'      => 'Home Classic',
							'slug'      => 'home-classic',
							'thumbnail' => $previews_uri . 'home-classic.jpg',
						),
						array(
							'name'      => 'Home Block',
							'slug'      => 'home-block',
							'thumbnail' => $previews_uri . 'home-block.jpg',
						),
						array(
							'name'      => 'Home-Minimal',
							'slug'      => 'home-minimal',
							'thumbnail' => $previews_uri . 'home-minimal.jpg',
						),
						array(
							'name'      => 'Home Fullscreen',
							'slug'      => 'home-fullscreen',
							'thumbnail' => $previews_uri . 'home-fullscreen.jpg',
						),
						array(
							'name'      => 'Home Instagram',
							'slug'      => 'home-instagram',
							'thumbnail' => $previews_uri . 'home-instagram.jpg',
						),
					)
				),
			
			);
		}
		
		public function import_sample_data_after_install_sample_data( $package ) {
			global $wpdb;
			update_option( 'yith-wcqv-enable-lightbox', 0 );
			// Fix nav menu item links
			$org_domain      = 'casano.famithemes.com';
			$cur_site_url    = get_site_url();
			$parse           = parse_url( $cur_site_url );
			$cur_site_domain = $parse['host'];
			
			$sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}postmeta SET meta_value = REPLACE(meta_value, %s, %s) WHERE (meta_key = '_menu_item_url' OR meta_key = '_menu_item_megamenu_mega_menu_url')", $org_domain, $cur_site_domain );
			$wpdb->query( $sql );
		}
	}
}

new FamiSampleDataSettings();