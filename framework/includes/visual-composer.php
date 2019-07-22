<?php
if ( ! class_exists( 'Casano_Visual_Composer' ) ) {
	class Casano_Visual_Composer {
		public function __construct() {
			$this->define_constants();
			add_filter( 'vc_google_fonts_get_fonts_filter', array( $this, 'vc_fonts' ) );
			$this->params();
			$this->autocomplete();
			/* Custom font Icon*/
			add_filter( 'vc_iconpicker-type-casanocustomfonts', array( &$this, 'iconpicker_type_casano_customfonts' ) );
			$this->map_shortcode();
		}

		/**
		 * Define  Constants.
		 */
		private function define_constants() {
			$this->define( 'CASANO_SHORTCODE_PREVIEW', get_theme_file_uri( '/framework/assets/images/shortcode-previews/' ) );
			$this->define( 'CASANO_SHORTCODES_ICONS_URI', get_theme_file_uri( '/framework/assets/images/vc-shortcodes-icons/' ) );
			$this->define( 'CASANO_PRODUCT_STYLE_PREVIEW', get_theme_file_uri( '/woocommerce/product-styles/' ) );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string      $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		function params() {
			if ( function_exists( 'casano_toolkit_vc_param' ) ) {
				casano_toolkit_vc_param( 'taxonomy', array( $this, 'taxonomy_field' ) );
				casano_toolkit_vc_param( 'uniqid', array( $this, 'uniqid_field' ) );
				casano_toolkit_vc_param( 'select_preview', array( $this, 'select_preview_field' ) );
				casano_toolkit_vc_param( 'number', array( $this, 'number_field' ) );
			}
		}

		/**
		 * load param autocomplete render
		 * */
		public function autocomplete() {
			add_filter( 'vc_autocomplete_casano_products_ids_callback', array(
				$this,
				'productIdAutocompleteSuggester'
			), 10, 1 );
			add_filter( 'vc_autocomplete_casano_products_ids_render', array(
				$this,
				'productIdAutocompleteRender'
			), 10, 1 );
			add_filter( 'vc_autocomplete_casano_pinmap_ids_callback', array(
				$this,
				'pinmapIdAutocompleteSuggester'
			), 10, 1 );
			add_filter( 'vc_autocomplete_casano_pinmap_ids_render', array(
				$this,
				'pinmapIdAutocompleteRender'
			), 10, 1 );

		}

		/*
         * taxonomy_field
         * */
		public function taxonomy_field( $settings, $value ) {
			$dependency = '';
			$value_arr  = $value;
			if ( ! is_array( $value_arr ) ) {
				$value_arr = array_map( 'trim', explode( ',', $value_arr ) );
			}
			$output = '';
			if ( isset( $settings['hide_empty'] ) && $settings['hide_empty'] ) {
				$settings['hide_empty'] = 1;
			} else {
				$settings['hide_empty'] = 0;
			}
			if ( ! empty( $settings['taxonomy'] ) ) {
				$terms_fields = array();
				if ( isset( $settings['placeholder'] ) && $settings['placeholder'] ) {
					$terms_fields[] = "<option value=''>" . $settings['placeholder'] . "</option>";
				}
				$terms = get_terms( $settings['taxonomy'], array(
					'parent'     => $settings['parent'],
					'hide_empty' => $settings['hide_empty']
				) );
				if ( $terms && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$selected       = ( in_array( $term->slug, $value_arr ) ) ? ' selected="selected"' : '';
						$terms_fields[] = "<option value='{$term->slug}' {$selected}>{$term->name}</option>";
					}
				}
				$size     = ( ! empty( $settings['size'] ) ) ? 'size="' . $settings['size'] . '"' : '';
				$multiple = ( ! empty( $settings['multiple'] ) ) ? 'multiple="multiple"' : '';
				$uniqeID  = uniqid();
				$output   = '<select style="width:100%;" id="vc_taxonomy-' . $uniqeID . '" ' . $multiple . ' ' . $size . ' name="' . $settings['param_name'] . '" class="casano_vc_taxonomy wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" ' . $dependency . '>'
				            . implode( $terms_fields )
				            . '</select>';
			}

			return $output;
		}

		public function uniqid_field( $settings, $value ) {
			if ( ! $value ) {
				$value = uniqid( hash( 'crc32', $settings['param_name'] ) . '-' );
			}
			$output = '<input type="text" class="wpb_vc_param_value textfield" name="' . $settings['param_name'] . '" value="' . esc_attr( $value ) . '" />';

			return $output;
		}

		public function number_field( $settings, $value ) {
			$dependency = '';
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type       = isset( $settings['type '] ) ? $settings['type'] : '';
			$min        = isset( $settings['min'] ) ? $settings['min'] : '';
			$max        = isset( $settings['max'] ) ? $settings['max'] : '';
			$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';
			if ( ! $value && isset( $settings['std'] ) ) {
				$value = $settings['std'];
			}
			$output = '<input type="number" min="' . esc_attr( $min ) . '" max="' . esc_attr( $max ) . '" class="wpb_vc_param_value textfield ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . esc_attr( $value ) . '" ' . $dependency . ' style="max-width:100px; margin-right: 10px;" />' . $suffix;

			return $output;
		}

		public function select_preview_field( $settings, $value ) {
			ob_start();
			// Get menus list
			$options = $settings['value'];
			$default = $settings['default'];
			if ( is_array( $options ) && count( $options ) > 0 ) {
				$uniqeID = uniqid();
				$i       = 0;
				?>
                <div class="container-select_preview">
                    <select id="casano_select_preview-<?php echo esc_attr( $uniqeID ); ?>"
                            name="<?php echo esc_attr( $settings['param_name'] ); ?>"
                            class="casano_select_preview vc_select_image wpb_vc_param_value wpb-input wpb-select <?php echo esc_attr( $settings['param_name'] ); ?> <?php echo esc_attr( $settings['type'] ); ?>_field">
						<?php foreach ( $options as $k => $option ): ?>
							<?php
							if ( $i == 0 ) {
								$first_value = $k;
							}
							$i ++;
							?>
							<?php $selected = ( $k == $value ) ? ' selected="selected"' : ''; ?>
                            <option data-img="<?php echo esc_url( $option['img'] ); ?>"
                                    value='<?php echo esc_attr( $k ) ?>' <?php echo esc_attr( $selected ) ?>><?php echo esc_attr( $option['alt'] ) ?></option>
						<?php endforeach; ?>
                    </select>
                    <div class="image-preview">
						<?php if ( isset( $options[ $value ] ) && $options[ $value ] && ( isset( $options[ $value ]['img'] ) ) ): ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[ $value ]['img'] ); ?>">
						<?php else: ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[ $default ]['img'] ); ?>">
						<?php endif; ?>
                    </div>
                </div>
				<?php
			}

			return ob_get_clean();
		}

		/**
		 * Suggester for autocomplete by id/name/title/sku
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 * @return array - id's from products with title/sku.
		 */
		public function productIdAutocompleteSuggester( $query ) {
			global $wpdb;
			$product_id      = (int) $query;
			$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title, b.meta_value AS sku
    					FROM {$wpdb->posts} AS a
    					LEFT JOIN ( SELECT meta_value, post_id  FROM {$wpdb->postmeta} WHERE `meta_key` = '_sku' ) AS b ON b.post_id = a.ID
    					WHERE a.post_type = 'product' AND ( a.ID = '%d' OR b.meta_value LIKE '%%%s%%' OR a.post_title LIKE '%%%s%%' )", $product_id > 0 ? $product_id : - 1, stripslashes( $query ), stripslashes( $query )
			), ARRAY_A
			);
			$results         = array();
			if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
				foreach ( $post_meta_infos as $value ) {
					$data          = array();
					$data['value'] = $value['id'];
					$data['label'] = esc_html__( 'Id', 'casano' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'casano' ) . ': ' . $value['title'] : '' ) . ( ( strlen( $value['sku'] ) > 0 ) ? ' - ' . esc_html__( 'Sku', 'casano' ) . ': ' . $value['sku'] : '' );
					$results[]     = $data;
				}
			}

			return $results;
		}

		/**
		 * Find product by id
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 *
		 * @return bool|array
		 */
		public function productIdAutocompleteRender( $query ) {
			$query = trim( $query['value'] ); // get value from requested
			if ( ! empty( $query ) ) {
				// get product
				$product_object = wc_get_product( (int) $query );
				if ( is_object( $product_object ) ) {
					$product_sku         = $product_object->get_sku();
					$product_title       = $product_object->get_title();
					$product_id          = $product_object->get_id();
					$product_sku_display = '';
					if ( ! empty( $product_sku ) ) {
						$product_sku_display = ' - ' . esc_html__( 'Sku', 'casano' ) . ': ' . $product_sku;
					}
					$product_title_display = '';
					if ( ! empty( $product_title ) ) {
						$product_title_display = ' - ' . esc_html__( 'Title', 'casano' ) . ': ' . $product_title;
					}
					$product_id_display = esc_html__( 'Id', 'casano' ) . ': ' . $product_id;
					$data               = array();
					$data['value']      = $product_id;
					$data['label']      = $product_id_display . $product_title_display . $product_sku_display;

					return ! empty( $data ) ? $data : false;
				}

				return false;
			}

			return false;
		}

		/**
		 * Suggester for autocomplete by id/name/title
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 * @return array - id's from post_types with title/.
		 */
		public function pinmapIdAutocompleteSuggester( $query ) {
			global $wpdb;
			$post_type_id    = (int) $query;
			$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title 
    					FROM {$wpdb->posts} AS a 
    					WHERE a.post_type = 'casano_mapper' AND ( a.ID = '%d' OR a.post_title LIKE '%%%s%%' )", $post_type_id > 0 ? $post_type_id : - 1, stripslashes( $query ), stripslashes( $query )
			), ARRAY_A
			);
			$results         = array();
			if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
				foreach ( $post_meta_infos as $value ) {
					$data          = array();
					$data['value'] = $value['id'];
					$data['label'] = esc_html__( 'Id', 'casano' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'casano' ) . ': ' . $value['title'] : '' );
					$results[]     = $data;
				}
			}

			return $results;
		}

		/**
		 * Find product by id
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 *
		 * @return bool|array
		 */
		public function pinmapIdAutocompleteRender( $query ) {
			$query = trim( $query['value'] ); // get value from requested
			if ( ! empty( $query ) ) {
				// get post_type
				$post_type_object = wc_get_post_type( (int) $query );
				if ( is_object( $post_type_object ) ) {
					$post_type_title = $post_type_object->get_title();
					$post_type_id    = $post_type_object->get_id();

					$post_type_title_display = '';
					if ( ! empty( $post_type_title ) ) {
						$post_type_title_display = ' - ' . esc_html__( 'Title', 'casano' ) . ': ' . $post_type_title;
					}
					$post_type_id_display = esc_html__( 'Id', 'casano' ) . ': ' . $post_type_id;
					$data                 = array();
					$data['value']        = $post_type_id;
					$data['label']        = $post_type_id_display . $post_type_title_display;

					return ! empty( $data ) ? $data : false;
				}

				return false;
			}

			return false;
		}

		public function vc_fonts( $fonts_list ) {
			/* Gotham */
			$Gotham              = new stdClass();
			$Gotham->font_family = "Gotham";
			$Gotham->font_styles = "100,300,400,600,700";
			$Gotham->font_types  = "300 Light:300:light,400 Normal:400:normal";

			$fonts = array( $Gotham );

			return array_merge( $fonts_list, $fonts );
		}

		/* Custom Font icon*/
		function iconpicker_type_casano_customfonts( $icons ) {
			$icons['Flaticon'] = array(
				array('flaticon-magnifying-glass'=>'Flaticon magnifying glass'),
				array('flaticon-profile'=>'Flaticon profile'),
				array('flaticon-bag'=>'Flaticon bag'),
				array('flaticon-right-arrow'=>'Flaticon right arrow'),
				array('flaticon-left-arrow'=>'Flaticon left arrow'),
				array('flaticon-right-arrow-1'=>'Flaticon right arrow 1'),
				array('flaticon-left-arrow-1'=>'Flaticon left arrow 1'),
				array('flaticon-mail'=>'Flaticon mail'),
				array('flaticon-flame'=>'Flaticon flame'),
				array('flaticon-clock'=>'Flaticon clock'),
				array('flaticon-comment'=>'Flaticon comment'),
				array('flaticon-chat'=>'Flaticon chat'),
				array('flaticon-heart'=>'Flaticon heart'),
				array('flaticon-valentines-heart'=>'Flaticon valentines heart'),
				array('flaticon-filter'=>'Flaticon filter'),
				array('flaticon-loading'=>'Flaticon loading'),
				array('flaticon-checked'=>'Flaticon checked'),
				array('flaticon-tick'=>'Flaticon tick'),
				array('flaticon-close'=>'Flaticon close'),
				array('flaticon-circular-check-button'=>'Flaticon circular check button'),
				array('flaticon-check'=>'Flaticon check'),
				array('flaticon-play-button'=>'Flaticon play button'),
				array('flaticon-360-degrees'=>'Flaticon 360 degrees'),
				array('flaticon-login'=>'Flaticon login'),
				array('flaticon-menu'=>'Flaticon menu'),
				array('flaticon-menu-1'=>'Flaticon menu 1'),
				array('flaticon-placeholder'=>'Flaticon placeholder'),
				array('flaticon-metre'=>'Flaticon metre'),
				array('flaticon-share'=>'Flaticon share'),
				array('flaticon-shuffle'=>'Flaticon shuffle'),
				array('flaticon-running'=>'Flaticon running'),
				array('flaticon-recycle'=>'Flaticon recycle'),
				array('flaticon-instagram'=>'Flaticon instagram'),
				array('flaticon-delivery-truck'=>'Flaticon delivery truck'),
				array('flaticon-closed-lock'=>'Flaticon closed lock'),
				array('flaticon-support'=>'Flaticon support'),
				array('flaticon-diamond'=>'Flaticon diamond'),
				array('flaticon-high-heels'=>'Flaticon high heels'),
				array('flaticon-shirt'=>'Flaticon shirt'),
				array('flaticon-dress'=>'Flaticon dress'),
				array('flaticon-shirt-1'=>'Flaticon shirt 1'),
				array('flaticon-glasses'=>'Flaticon glasses'),
				array('flaticon-shopping-bag'=>'Flaticon shopping bag'),
				array('flaticon-trousers'=>'Flaticon trousers'),
				array('flaticon-user'=>'Flaticon user'),
				array('flaticon-magnifying-glass-1'=>'Flaticon magnifying glass 1'),
				array('flaticon-shopping-bag-1'=>'Flaticon shopping bag 1'),
				array('flaticon-envelope'=>'Flaticon envelope'),
				array('flaticon-instagram-1'=>'Flaticon instagram 1'),
				array('flaticon-rocket-ship'=>'Flaticon rocket ship'),
				array('flaticon-refresh'=>'Flaticon refresh'),
				array('flaticon-return'=>'Flaticon return'),
				array('flaticon-padlock'=>'Flaticon padlock'),
				array('flaticon-random'=>'Flaticon random'),
				array('flaticon-shopping-cart'=>'Flaticon shopping cart'),
				array('flaticon-cart'=>'Flaticon cart'),
				array('flaticon-filter-1'=>'Flaticon filter 1'),
				array('flaticon-startup'=>'Flaticon startup'),
				array('flaticon-return-1'=>'Flaticon return 1'),
				array('flaticon-letter'=>'Flaticon letter'),
				array('flaticon-diamond-1'=>'Flaticon diamond 1'),
			);

			return $icons;
		}

		public function animation_on_scroll() {
		    return array(
			    esc_html__( 'None', 'casano' )      => '',
			    esc_html__( 'Smooth Up', 'casano' ) => 'casano-wow fadeInUp',
			    esc_html__( 'Smooth Down', 'casano' ) => 'casano-wow fadeInDown',
			    esc_html__( 'Smooth Left', 'casano' ) => 'casano-wow fadeInLeft',
			    esc_html__( 'Smooth Right', 'casano' ) => 'casano-wow fadeInRight',
		    );
        }

		public function map_shortcode() {
			/* Map New Banner */
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Banner', 'casano' ),
					'base'        => 'casano_banner', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display a Banner list.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'banner.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-02.jpg',
								),
                                'style-03' => array(
									'alt' => 'Style 03', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-03.jpg',
								),
								'style-04' => array(
									'alt' => 'Style 04', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-04.jpg',
								),
                                'style-05' => array(
									'alt' => 'Style 05', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-05.jpg',
								),
                                'style-06' => array(
									'alt' => 'Style 06', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-06.jpg',
								),
								'style-07' => array(
									'alt' => 'Style 07', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-07.jpg',
								),
								'style-08' => array(
									'alt' => 'Style 08', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-08.jpg',
								),
								'style-09' => array(
									'alt' => 'Style 09', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-09.jpg',
								),
                                'style-10' => array(
									'alt' => 'Style 10', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-10.jpg',
								),
								'style-11' => array(
									'alt' => 'Style 11', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-11.jpg',
								),
								'style-12' => array(
									'alt' => 'Style 12', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-12.jpg',
								),
								'style-13' => array(
									'alt' => 'Style 13', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'banner/style-13.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'casano' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'casano' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array(
                                    'style-11',
                                    'style-12',
                                    'style-13',
								),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Big Title', 'casano' ),
							'param_name'  => 'bigtitle',
							'description' => esc_html__( 'The big title of shortcode', 'casano' ),
                            'admin_label' => true,
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Description', 'casano' ),
							'param_name' => 'desc',
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => array(
                                    'style-01',
                                    'style-02',
                                    'style-03',
                                    'style-05',
                                    'style-08',
                                ),
                            ),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'Banner Link', 'casano' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add banner link.', 'casano' ),
						),
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Image", "casano" ),
							"param_name"  => "image",
							"admin_label" => false,
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'banner_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New blog */
			$categories_array = array(
				esc_html__( 'All', 'casano' ) => '',
			);
			$args             = array();
			$categories       = get_categories( $args );
			foreach ( $categories as $category ) {
				$categories_array[ $category->name ] = $category->slug;
			}

			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Blog', 'casano' ),
					'base'        => 'casano_blog', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display a blog list.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'blog.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'blog/style-01.jpg',
								),
                                'style-02' => array(
									'alt' => 'Style 02',
									'img' => CASANO_SHORTCODE_PREVIEW . 'blog/style-02.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type' => 'loop',
							'heading' => esc_html__('Option Query', 'casano'),
							'param_name' => 'loop_query',
							'save_always' => true,
							'value' => 'post_type:post|size:10|order_by:date',
							'settings' => array(
								'size' => array(
									'hidden' => false,
									'value' => 6,
								),
								'order_by' => array('value' => 'date'),
							),
							'description' => esc_html__('Create WordPress loop, to populate content from your site.', 'casano'),
						),
						/* Owl */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'casano' ) => 'true',
								esc_html__( 'No', 'casano' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'casano' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'casano' )  => 'false',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'casano' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'casano' )  => '',
								esc_html__( 'Light', 'casano' ) => 'nav-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Navigation color', 'casano' ),
							'param_name'  => 'nav_color',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'navigation',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'casano' )  => 'false',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Enable Dots', 'casano' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots.", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Default', 'casano' )  => '',
								esc_html__( 'Light', 'casano' ) => 'dots-light',
								esc_html__( 'Dark', 'casano' ) => 'dots-dark',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Dots color', 'casano' ),
							'param_name'  => 'dots_color',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'dots',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'casano' ) => 'true',
								esc_html__( 'No', 'casano' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'casano' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'casano' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'casano' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Auto Responsive Margin', 'casano' ),
							'param_name' => 'autoresponsive',
							'group'      => esc_html__( 'Carousel settings', 'casano' ),
							'value'      => array(
								esc_html__( 'No', 'casano' )  => '',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'        => '',
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'casano' ),
							"param_name"  => "ls_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px < 1500px )", 'casano' ),
							"param_name"  => "lg_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'casano' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'casano' ),
							"param_name"  => "sm_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'casano' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'casano' ),
							"param_name"  => "ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'blog_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Section Button*/
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Button', 'casano' ),
					'base'        => 'casano_button', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display Button.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'testimonial.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'button/style-01.jpg'
								),
                                'style-02' => array(
									'alt' => 'Style 02',
									'img' => CASANO_SHORTCODE_PREVIEW . 'button/style-02.jpg'
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'Button Link', 'casano' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add button link.', 'casano' ),
                            'admin_label' => true,
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Choose use icon', 'casano' ),
							'value'      => array(
								esc_html__( 'No', 'casano' ) => '',
								esc_html__( 'Yes', 'casano' )   => 'icontype',
							),
							'param_name' => 'iconimage',
							'std'        => '',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'casano' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'casano' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'casano' ) => 'fontflaticon',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'description' => esc_html__( 'Select icon library.', 'casano' ),
							'std'         => 'fontawesome',
							'dependency'  => array(
								'element' => 'iconimage',
								'value'   => 'icontype',
							),
						),
						array(
							'param_name'  => 'icon_casanocustomfonts',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'casanocustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							'settings'    => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 4000,
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'button_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			/* Map New Category */
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Category', 'casano' ),
					'base'        => 'casano_categories', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display Category.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'cat.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'categories/style-01.jpg',
								),
                                'style-02' => array(
									'alt' => 'Style 02',
									'img' => CASANO_SHORTCODE_PREVIEW . 'categories/style-02.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							"type"        => "taxonomy",
							"taxonomy"    => "product_cat",
							"class"       => "",
							"heading"     => esc_html__( "Product Category", 'casano' ),
							"param_name"  => "taxonomy",
							"value"       => '',
							'parent'      => '',
							'multiple'    => false,
							'hide_empty'  => false,
							'placeholder' => esc_html__( 'Choose category', 'casano' ),
							"description" => esc_html__( "Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'casano' ),
							'std'         => '',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'categories_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			vc_map(
				array(
					'base'        => 'casano_contact',
					'name'        => esc_html__( 'Casano: Contact', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'cat.png',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display Custom Contact', 'casano' ),
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'contact/style-01.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'casano' ),
							'param_name'  => 'title',
							'admin_label' => true,
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Description', 'casano' ),
							'param_name'  => 'desc',
							'admin_label' => true,
						),
						array(
							'type'       => 'param_group',
							'heading'    => esc_html__( 'Contact Items', 'casano' ),
							'param_name' => 'contact_item',
							'params'     => array(
								array(
									'type'        => 'textfield',
									'heading'     => esc_html__( 'Title Item', 'casano' ),
									'param_name'  => 'title_item',
									'admin_label' => true,
								),
								array(
									'type'       => 'vc_link',
									'heading'    => esc_html__( 'Link', 'casano' ),
									'param_name' => 'link_item',
								),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'contact_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Map New Custom menu*/
			$all_menu = array();
			$menus    = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			if ( $menus && count( $menus ) > 0 ) {
				foreach ( $menus as $m ) {
					$all_menu[ $m->name ] = $m->slug;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Custom Menu', 'casano' ),
					'base'        => 'casano_custommenu', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display a custom menu.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'custom-menu.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'custommenu/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => CASANO_SHORTCODE_PREVIEW . 'custommenu/style-02.jpg',
								),
								'style-03' => array(
									'alt' => 'Style 03',
									'img' => CASANO_SHORTCODE_PREVIEW . 'custommenu/style-03.jpg',
								),
                                'style-04' => array(
									'alt' => 'Style 04',
									'img' => CASANO_SHORTCODE_PREVIEW . 'custommenu/style-04.jpg',
								),
                                'style-05' => array(
									'alt' => 'Style 05',
									'img' => CASANO_SHORTCODE_PREVIEW . 'custommenu/style-05.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'casano' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'casano' ),
							'admin_label' => true,
							'std'         => '',
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => array( 'style-01','style-02'),
                            ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Menu', 'casano' ),
							'param_name'  => 'menu',
							'value'       => $all_menu,
							'description' => esc_html__( 'Select menu to display.', 'casano' ),
                            'admin_label' => true,
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'custommenu_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Demo', 'casano' ),
					'base'        => 'casano_demo', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display a demo list.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'banner.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'demo/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'demo/style-02.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Image", "casano" ),
							"param_name"  => "image",
							"admin_label" => false,
						),
						array(
							'type'        => 'number',
							'heading'     => esc_html__( 'Number', 'casano' ),
							'param_name'  => 'post_id',
							'description' => esc_html__( 'The Post, page id.', 'casano' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array('style-02'),
							),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'Demo Link', 'casano' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add demo link.', 'casano' ),
							'admin_label' => true,
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array('style-01'),
							),
						),
                        array(
                            'type'       => 'dropdown',
                            'heading'    => esc_html__( 'Comming soon mode', 'casano' ),
                            'value'      => array(
                                esc_html__( 'Off', 'casano' )  => '',
                                esc_html__( 'On', 'casano' ) => 'comming-mode',
                            ),
                            'param_name' => 'comming',
                            'std'        => '',
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => array( 'style-01'),
                            ),
                        ),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'demo_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Section IconBox*/
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Icon Box', 'casano' ),
					'base'        => 'casano_iconbox', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display Iconbox.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'iconbox.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'iconbox/style-01.jpg'
								),
                                'style-02' => array(
									'alt' => 'Style 02',
									'img' => CASANO_SHORTCODE_PREVIEW . 'iconbox/style-02.jpg'
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Choose icon or image', 'casano' ),
							'value'      => array(
								esc_html__( 'Icon', 'casano' )  => '',
								esc_html__( 'Image', 'casano' ) => 'imagetype',
							),
							'param_name' => 'iconimage',
							'std'        => '',
						),
						array(
							"type"       => "attach_image",
							"heading"    => esc_html__( "Image custom", "casano" ),
							"param_name" => "image",
							'dependency' => array(
								'element' => 'iconimage',
								'value'   => 'imagetype',
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'casano' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'casano' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'casano' ) => 'fontflaticon',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'description' => esc_html__( 'Select icon library.', 'casano' ),
							'std'         => 'fontawesome',
							'dependency'  => array(
								'element' => 'iconimage',
								'value'   => array( '' ),
							),
						),
						array(
							'param_name'  => 'icon_casanocustomfonts',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'casanocustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							'settings'    => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 4000,
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'casano' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The Title of IconBox.', 'casano' ),
							'admin_label' => true,
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Description', 'casano' ),
							'param_name'  => 'des',
							'description' => esc_html__( 'The Description of IconBox.', 'casano' ),
							'admin_label' => true,
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", 'casano' ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'iconbox_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),

					)
				)
			);
			/* Map New Section tabs */
			vc_map(
				array(
					'name'                      => esc_html__( 'Section', 'casano' ),
					'base'                      => 'vc_tta_section',
					'icon'                      => 'icon-wpb-ui-tta-section',
					'allowed_container_element' => 'vc_row',
					'is_container'              => true,
					'show_settings_on_create'   => false,
					'as_child'                  => array(
						'only' => 'vc_tta_tour,vc_tta_tabs,vc_tta_accordion',
					),
					'category'                  => esc_html__( 'Content', 'casano' ),
					'description'               => esc_html__( 'Section for Tabs, Tours, Accordions.', 'casano' ),
					'params'                    => array(
                        array(
                            'type'        => 'textfield',
                            'param_name'  => 'title',
                            'heading'     => esc_html__( 'Title', 'casano' ),
                            'description' => esc_html__( 'Enter section title (Note: you can leave it empty).', 'casano' ),
                        ),
                        array(
                            'type'        => 'attach_image',
                            'param_name'  => 'image',
                            'heading'     => esc_html__( 'Image', 'casano' ),
                            'description' => esc_html__( 'Enter section image.', 'casano' ),
                        ),
						array(
							'type'        => 'el_id',
							'param_name'  => 'tab_id',
							'settings'    => array(
								'auto_generate' => true,
							),
							'heading'     => esc_html__( 'Section ID', 'casano' ),
							'description' => esc_html__( 'Enter section ID (Note: make sure it is unique and valid according to w3c specification.', 'casano' )
						),
						array(
							'type'        => 'checkbox',
							'param_name'  => 'add_icon',
							'heading'     => esc_html__( 'Add icon?', 'casano' ),
							'description' => esc_html__( 'Add icon next to section title.', 'casano' ),
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'i_position',
							'value'       => array(
								esc_html__( 'Before title', 'casano' ) => 'left',
								esc_html__( 'After title', 'casano' )  => 'right',
							),
							'dependency'  => array(
								'element' => 'add_icon',
								'value'   => 'true',
							),
							'heading'     => esc_html__( 'Icon position', 'casano' ),
							'description' => esc_html__( 'Select icon position.', 'casano' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'casano' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'casano' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'casano' ) => 'fontflaticon',
							),
							'dependency'  => array(
								'element' => 'add_icon',
								'value'   => 'true',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'std'         => 'fontawesome',
							'description' => esc_html__( 'Select icon library.', 'casano' ),
						),
						array(
							'param_name'  => 'icon_casanocustomfonts',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'casanocustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							// default value to backend editor admin_label
							'settings'    => array(
								'emptyIcon'    => false,
								// default true, display an "EMPTY" icon?
								'iconsPerPage' => 4000,
								// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'casano' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'casano' ),
						),
					),
					'js_view'                   => 'VcBackendTtaSectionView',
					'custom_markup'             => '
                    <div class="vc_tta-panel-heading">
                        <h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left"><a href="javascript:;" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-accordion data-vc-container=".vc_tta-container"><span class="vc_tta-title-text">{{ section_title }}</span><i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i></a></h4>
                    </div>
                    <div class="vc_tta-panel-body">
                        {{ editor_controls }}
                        <div class="{{ container-class }}">
                        {{ content }}
                        </div>
                    </div>',
					'default_content'           => '',
				)
			);

			/*Map New section title */
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Section Title', 'casano' ),
					'base'        => 'casano_title',
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display a custom title.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'section-title.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'title/style-01.jpg',
								),
                                'style-02' => array(
									'alt' => 'Style 02', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'title/style-02.jpg',
								),
                                'style-03' => array(
									'alt' => 'Style 03', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'title/style-03.jpg',
								),
								'style-04' => array(
									'alt' => 'Style 04', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'title/style-04.jpg',
								),
                                'style-05' => array(
									'alt' => 'Style 05', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'title/style-05.jpg',
								),
                                'style-06' => array(
									'alt' => 'Style 06', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'title/style-06.jpg',
								),
								'style-07' => array(
									'alt' => 'Style 07', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'title/style-07.jpg',
								),
                                'style-08' => array(
									'alt' => 'Style 08', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'title/style-08.jpg',
								),
                                'style-09' => array(
									'alt' => 'Style 09', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'title/style-09.jpg',
								),
                                'style-10' => array(
									'alt' => 'Style 10', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'title/style-10.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'casano' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'casano' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'casano' ) => 'fontflaticon',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'description' => esc_html__( 'Select icon library.', 'casano' ),
							'std'         => 'fontawesome',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array('style-03'),
							),
						),
						array(
							'type'        => 'attach_image',
							'param_name'  => 'image',
							'heading'     => esc_html__( 'Background Image', 'casano' ),
							'description' => esc_html__( 'Enter section image.', 'casano' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-09'),
							),
						),
						array(
							'param_name'  => 'icon_casanocustomfonts',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'casanocustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							'settings'    => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 4000,
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'casano' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'casano' ),
							'admin_label' => true,
							'std'         => '',
						),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__( 'Description', 'casano' ),
                            'param_name'  => 'desc',
                            'description' => esc_html__( 'The description of shortcode', 'casano' ),
                            'std'         => '',
                            'dependency'  => array(
	                            'element' => 'style',
	                            'value'   => array('style-06','style-07','style-09'),
                            ),
                        ),
						array(
							'type'       => 'vc_link',
							'heading'    => esc_html__( 'Link', 'casano' ),
							'param_name' => 'link',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array('style-01','style-03','style-07'),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'title_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			// Map new Tabs element.
			vc_map(
				array(
					'name'                    => esc_html__( 'Casano: Tabs', 'casano' ),
					'base'                    => 'casano_tabs',
					'icon'                    => CASANO_SHORTCODES_ICONS_URI . 'tabs.png',
					'is_container'            => true,
					'show_settings_on_create' => false,
					'as_parent'               => array(
						'only' => 'vc_tta_section',
					),
					'category'                => esc_html__( 'Casano Elements', 'casano' ),
					'description'             => esc_html__( 'Tabs content', 'casano' ),
					'params'                  => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01', //CASANO_SHORTCODE_PREVIEW
									'img' => CASANO_SHORTCODE_PREVIEW . 'tabs/style-01.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__( 'Title', 'casano' ),
                            'param_name'  => 'title_tabs',
                            'description' => esc_html__( 'The title of shortcode', 'casano' ),
                            'admin_label' => true,
                            'std'         => '',
                        ),
                        array(
                            'type'       => 'vc_link',
                            'heading'    => esc_html__( 'Link', 'casano' ),
                            'param_name' => 'link',
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => array('style-03','style-04'),
                            ),
                        ),
						vc_map_add_css_animation(),
						array(
							'param_name' => 'ajax_check',
							'heading'    => esc_html__( 'Using Ajax Tabs', 'casano' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Yes', 'casano' ) => '1',
								esc_html__( 'No', 'casano' )  => '0',
							),
							'std'        => '0',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Active Section', 'casano' ),
							'param_name' => 'active_section',
							'std'        => '1',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'casano' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'casano' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'tabs_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'collapsible_all',
							'heading'          => esc_html__( 'Allow collapse all?', 'casano' ),
							'description'      => esc_html__( 'Allow collapse all accordion sections.', 'casano' ),
							'edit_field_class' => 'hidden',
						),
					),
					'js_view'                 => 'VcBackendTtaTabsView',
					'custom_markup'           => '
                    <div class="vc_tta-container" data-vc-action="collapse">
                        <div class="vc_general vc_tta vc_tta-tabs vc_tta-color-backend-tabs-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
                            <div class="vc_tta-tabs-container">'
					                             . '<ul class="vc_tta-tabs-list">'
					                             . '<li class="vc_tta-tab" data-vc-tab data-vc-target-model-id="{{ model_id }}" data-element_type="vc_tta_section"><a href="javascript:;" data-vc-tabs data-vc-container=".vc_tta" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-target-model-id="{{ model_id }}"><span class="vc_tta-title-text">{{ section_title }}</span></a></li>'
					                             . '</ul>
                            </div>
                            <div class="vc_tta-panels vc_clearfix {{container-class}}">
                              {{ content }}
                            </div>
                        </div>
                    </div>',
					'default_content'         => '
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'casano' ), 1 ) . '"][/vc_tta_section]
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'casano' ), 2 ) . '"][/vc_tta_section]
                    ',
					'admin_enqueue_js'        => array(
						vc_asset_url( 'lib/vc_tabs/vc-tabs.min.js' ),
					),
				)
			);
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Video', 'casano' ),
					'base'        => 'casano_video', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display shortcode.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'testimonial.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'video/style-01.jpg'
								),
                                'style-02' => array(
									'alt' => 'Style 02',
									'img' => CASANO_SHORTCODE_PREVIEW . 'video/style-02.jpg'
								),
                                'style-03' => array(
									'alt' => 'Style 03',
									'img' => CASANO_SHORTCODE_PREVIEW . 'video/style-03.jpg'
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Video Type', 'casano' ),
							'value'       => array(
								esc_html__( 'Video html5', 'casano' )  => 'html5',
								esc_html__( 'Vimeo', 'casano' )        => 'vimeo',
								esc_html__( 'Youtube', 'casano' )      => 'youtbe',
							),
							'param_name'  => 'type',
							'std'         => 'html5',
							'description' => esc_html__( 'Select video type.', 'casano' ),
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Video ID', 'casano' ),
							'param_name'  => 'video',
							'description' => esc_html__( 'Add video id.', 'casano' ),
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Video Loop', 'casano' ),
							'value'       => array(
								esc_html__( 'Yes', 'casano' )  => 'yes',
								esc_html__( 'No', 'casano' )   => 'no',
							),
							'param_name'  => 'loop',
							'std'         => 'yes',
							'description' => esc_html__( 'Select video loop.', 'casano' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Video Autoplay', 'casano' ),
							'value'       => array(
								esc_html__( 'Yes', 'casano' )  => 'yes',
								esc_html__( 'No', 'casano' )   => 'no',
							),
							'param_name'  => 'autoplay',
							'std'         => 'yes',
							'description' => esc_html__( 'Select video autoplay.', 'casano' ),
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'        => 'attach_image',
							'param_name'  => 'image',
							'heading'     => esc_html__( 'Background Image', 'casano' ),
							'description' => esc_html__( 'Enter section image.', 'casano' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-02','style-03'),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'casano' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The Title', 'casano' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-03'),
							),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'Button Link', 'casano' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add button link.', 'casano' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-02','style-03'),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'video_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			// Map new Products
			// CUSTOM PRODUCT SIZE
			$product_size_width_list = array();
			$width                   = 330;
			$height                  = 348;
			$crop                    = 1;
            if ( function_exists( 'wc_get_image_size' ) ) {
	            $size      = array(
		            "width"  => 330,
		            "height" => 348,
		            "crop"   => 1,
	            );
                $width  = isset( $size['width'] ) && is_numeric($size['width']) ? $size['width'] : $width;
                $height = isset( $size['height']) && is_numeric($size['height']) ? $size['height'] : $height;
                $crop   = isset( $size['crop'] ) ? $size['crop'] : $crop;
            }
			for ( $i = 100; $i < $width; $i = $i + 10 ) {
				array_push( $product_size_width_list, $i );
			}
			$product_size_list                           = array();
			$product_size_list[ $width . 'x' . $height ] = $width . 'x' . $height;
			foreach ( $product_size_width_list as $k => $w ) {
			    if(is_numeric($w)) {
                    $w = intval( $w );
                    if ( isset( $width ) && $width > 0 ) {
                        $h = round( $height * $w / $width );
                    } else {
                        $h = $w;
                    }
                    $product_size_list[ $w . 'x' . $h ] = $w . 'x' . $h;
                }
			}
			$product_size_list['Custom'] = 'custom';
			$attributes_tax              = array();
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				$attributes_tax = wc_get_attribute_taxonomies();
			}

			$attributes = array();
			if ( is_array( $attributes_tax ) && count( $attributes_tax ) > 0 ) {
				foreach ( $attributes_tax as $attribute ) {
					$attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Products', 'casano' ),
					'base'        => 'casano_products', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display a product list or grid.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'product.png',
					'params'      => array(
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Product List style', 'casano' ),
							'param_name'  => 'productsliststyle',
							'value'       => array(
								esc_html__( 'Grid Bootstrap', 'casano' ) => 'grid',
								esc_html__( 'Owl Carousel', 'casano' )   => 'owl',
							),
							'description' => esc_html__( 'Select a style for list', 'casano' ),
							'admin_label' => true,
							'std'         => 'grid',
						),
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Product style', 'casano' ),
							'value'       => array(
								'1' => array(
									'alt' => esc_html__( 'Style 01', 'casano' ),
									'img' => CASANO_PRODUCT_STYLE_PREVIEW . 'content-product-style-1.jpg',
								),
                                '2' => array(
									'alt' => esc_html__( 'Style 02', 'casano' ),
									'img' => CASANO_PRODUCT_STYLE_PREVIEW . 'content-product-style-2.jpg',
								),
                                '3' => array(
									'alt' => esc_html__( 'Style 03', 'casano' ),
									'img' => CASANO_PRODUCT_STYLE_PREVIEW . 'content-product-style-3.jpg',
								),
							),
							'default'     => '1',
							'admin_label' => true,
							'param_name'  => 'product_style',
							'description' => esc_html__( 'Select a style for product item', 'casano' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Text Color ', 'casano' ),
							'param_name'  => 'text_color',
							'value'       => array(
								esc_html__( 'Dark', 'casano' ) => '',
								esc_html__( 'Light', 'casano' )   => 'text-light',
							),
							'description' => esc_html__( 'choose text color', 'casano' ),
							'std'         => '',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Show Star Rating', 'casano' ),
							'param_name'  => 'show_star',
							'value'       => array(
								esc_html__( 'Show Rating', 'casano' ) => '',
								esc_html__( 'None Rating', 'casano' )   => 'no-star',
							),
							'description' => esc_html__( 'Select show star rating', 'casano' ),
							'std'         => '',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Image size', 'casano' ),
							'param_name'  => 'product_image_size',
							'value'       => $product_size_list,
							'description' => esc_html__( 'Select a size for product', 'casano' ),
							'std'         => '320x387',
							'admin_label' => true,
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Width", 'casano' ),
							"param_name" => "product_custom_thumb_width",
							"value"      => $width,
							"suffix"     => esc_html__( "px", 'casano' ),
							"dependency" => array( "element" => "product_image_size", "value" => array( 'custom' ) ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Height", 'casano' ),
							"param_name" => "product_custom_thumb_height",
							"value"      => $height,
							"suffix"     => esc_html__( "px", 'casano' ),
							"dependency" => array( "element" => "product_image_size", "value" => array( 'custom' ) ),
						),
						/*Products */
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Target', 'casano' ),
							'param_name'  => 'target',
							'value'       => array(
								esc_html__( 'Best Selling Products', 'casano' ) => 'best-selling',
								esc_html__( 'Top Rated Products', 'casano' )    => 'top-rated',
								esc_html__( 'Recent Products', 'casano' )       => 'recent-product',
								esc_html__( 'Product Category', 'casano' )      => 'product-category',
								esc_html__( 'Products', 'casano' )              => 'products',
								esc_html__( 'Featured Products', 'casano' )     => 'featured_products',
								esc_html__( 'On Sale', 'casano' )               => 'on_sale',
								esc_html__( 'On New', 'casano' )                => 'on_new',
							),
							'description' => esc_html__( 'Choose the target to filter products', 'casano' ),
							'std'         => 'recent-product',
							'group'       => esc_html__( 'Products options', 'casano' ),
						),
                        array(
                            "type"        => "taxonomy",
                            "taxonomy"    => "product_cat",
                            "class"       => "",
                            "heading"     => esc_html__( "Product Category", 'casano' ),
                            "param_name"  => "taxonomy",
                            "value"       => '',
                            'parent'      => '',
                            'multiple'    => true,
                            'hide_empty'  => false,
                            'placeholder' => esc_html__( 'Choose category', 'casano' ),
                            "description" => esc_html__( "Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'casano' ),
                            'std'         => '',
                            'group'       => esc_html__( 'Products options', 'casano' ),
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => esc_html__( 'Total items', 'casano' ),
                            'param_name' => 'per_page',
                            'value'      => 10,
                            "dependency" => array(
                                "element" => "target",
                                "value"   => array(
                                    'best-selling',
                                    'top-rated',
                                    'recent-product',
                                    'product-category',
                                    'featured_products',
                                    'product_attribute',
                                    'on_sale',
                                    'on_new'
                                )
                            ),
                            'group'       => esc_html__( 'Products options', 'casano' ),
                        ),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order by", 'casano' ),
							"param_name"  => "orderby",
							"value"       => array(
								'',
								esc_html__( 'Date', 'casano' )          => 'date',
								esc_html__( 'ID', 'casano' )            => 'ID',
								esc_html__( 'Author', 'casano' )        => 'author',
								esc_html__( 'Title', 'casano' )         => 'title',
								esc_html__( 'Modified', 'casano' )      => 'modified',
								esc_html__( 'Random', 'casano' )        => 'rand',
								esc_html__( 'Comment count', 'casano' ) => 'comment_count',
								esc_html__( 'Menu order', 'casano' )    => 'menu_order',
								esc_html__( 'Sale price', 'casano' )    => '_sale_price',
							),
							'std'         => 'date',
							"description" => esc_html__( "Select how to sort.", 'casano' ),
							"dependency"  => array(
								"element" => "target",
								"value"   => array(
									'top-rated',
									'recent-product',
									'product-category',
									'featured_products',
									'on_sale',
									'on_new',
									'product_attribute'
								)
							),
							'group'       => esc_html__( 'Products options', 'casano' ),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order", 'casano' ),
							"param_name"  => "order",
							"value"       => array(
								esc_html__( 'ASC', 'casano' )  => 'ASC',
								esc_html__( 'DESC', 'casano' ) => 'DESC',
							),
							'std'         => 'DESC',
							"description" => esc_html__( "Designates the ascending or descending order.", 'casano' ),
							"dependency"  => array(
								"element" => "target",
								"value"   => array(
									'top-rated',
									'recent-product',
									'product-category',
									'featured_products',
									'on_sale',
									'on_new',
									'product_attribute'
								)
							),
							'group'       => esc_html__( 'Products options', 'casano' ),
						),
						array(
							'type'        => 'autocomplete',
							'heading'     => esc_html__( 'Products', 'casano' ),
							'param_name'  => 'ids',
							'settings'    => array(
								'multiple'      => true,
								'sortable'      => true,
								'unique_values' => true,
							),
							'save_always' => true,
							'description' => esc_html__( 'Enter List of Products', 'casano' ),
							"dependency"  => array( "element" => "target", "value" => array( 'products' ) ),
							'group'       => esc_html__( 'Products options', 'casano' ),
						),
						/* OWL Settings */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( '1 Row', 'casano' )  => '1',
								esc_html__( '2 Rows', 'casano' ) => '2',
								esc_html__( '3 Rows', 'casano' ) => '3',
								esc_html__( '4 Rows', 'casano' ) => '4',
								esc_html__( '5 Rows', 'casano' ) => '5',
							),
							'std'         => '1',
							'heading'     => esc_html__( 'The number of rows which are shown on block', 'casano' ),
							'param_name'  => 'owl_number_row',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'casano' ),
							'param_name' => 'owl_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'casano' ) => 'rows-space-0',
								esc_html__( '10px', 'casano' )    => 'rows-space-10',
								esc_html__( '20px', 'casano' )    => 'rows-space-20',
								esc_html__( '30px', 'casano' )    => 'rows-space-30',
								esc_html__( '40px', 'casano' )    => 'rows-space-40',
								esc_html__( '50px', 'casano' )    => 'rows-space-50',
								esc_html__( '60px', 'casano' )    => 'rows-space-60',
								esc_html__( '70px', 'casano' )    => 'rows-space-70',
								esc_html__( '80px', 'casano' )    => 'rows-space-80',
								esc_html__( '90px', 'casano' )    => 'rows-space-90',
								esc_html__( '100px', 'casano' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Carousel settings', 'casano' ),
							"dependency" => array(
								"element" => "owl_number_row",
								"value"   => array( '2', '3', '4', '5' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'casano' ) => 'true',
								esc_html__( 'No', 'casano' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'casano' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'casano' )  => 'false',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Navigation', 'casano' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Normal', 'casano' )  => '',
								esc_html__( 'Right', 'casano' ) => 'nav-right',
								esc_html__( 'Center', 'casano' ) => 'nav-center',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Navigation position', 'casano' ),
							'param_name'  => 'nav_position',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'navigation',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'casano' )  => '',
								esc_html__( 'Light', 'casano' ) => 'nav-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Navigation color', 'casano' ),
							'param_name'  => 'nav_color',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'navigation',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'casano' )  => 'false',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Enable Dots', 'casano' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Default', 'casano' )  => '',
								esc_html__( 'Light', 'casano' ) => 'dots-light',
								esc_html__( 'Dark', 'casano' ) => 'dots-dark',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Dots color', 'casano' ),
							'param_name'  => 'dots_color',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'dots',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
                                esc_html__( 'No', 'casano' )  => 'false',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Loop', 'casano' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'casano' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"suffix"      => esc_html__( "milliseconds", 'casano' ),
							"description" => esc_html__( 'Slide speed in milliseconds', 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'casano' ),
							"param_name"  => "margin",
							"value"       => "0",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Auto Responsive Margin', 'casano' ),
							'param_name' => 'autoresponsive',
							'group'      => esc_html__( 'Carousel settings', 'casano' ),
							'value'      => array(
								esc_html__( 'No', 'casano' )  => '',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'        => '',
							"dependency" => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'casano' ),
							"param_name"  => "ls_items",
							"value"       => "5",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px and < 1500px )", 'casano' ),
							"param_name"  => "lg_items",
							"value"       => "4",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'casano' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'casano' ),
							"param_name"  => "sm_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'casano' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'casano' ),
							"param_name"  => "ts_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						/* Bostrap setting */
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'casano' ),
							'param_name' => 'boostrap_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'casano' ) => 'rows-space-0',
								esc_html__( '10px', 'casano' )    => 'rows-space-10',
								esc_html__( '20px', 'casano' )    => 'rows-space-20',
								esc_html__( '30px', 'casano' )    => 'rows-space-30',
								esc_html__( '40px', 'casano' )    => 'rows-space-40',
								esc_html__( '50px', 'casano' )    => 'rows-space-50',
								esc_html__( '60px', 'casano' )    => 'rows-space-60',
								esc_html__( '70px', 'casano' )    => 'rows-space-70',
								esc_html__( '80px', 'casano' )    => 'rows-space-80',
								esc_html__( '90px', 'casano' )    => 'rows-space-90',
								esc_html__( '100px', 'casano' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Boostrap settings', 'casano' ),
							"dependency" => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Desktop', 'casano' ),
							'param_name'  => 'boostrap_bg_items',
							'value'       => array(
								esc_html__( '1 item', 'casano' )  => '12',
								esc_html__( '2 items', 'casano' ) => '6',
								esc_html__( '3 items', 'casano' ) => '4',
								esc_html__( '4 items', 'casano' ) => '3',
								esc_html__( '5 items', 'casano' ) => '15',
								esc_html__( '6 items', 'casano' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >= 1500px )', 'casano' ),
							'group'       => esc_html__( 'Boostrap settings', 'casano' ),
							'std'         => '15',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Desktop', 'casano' ),
							'param_name'  => 'boostrap_lg_items',
							'value'       => array(
								esc_html__( '1 item', 'casano' )  => '12',
								esc_html__( '2 items', 'casano' ) => '6',
								esc_html__( '3 items', 'casano' ) => '4',
								esc_html__( '4 items', 'casano' ) => '3',
								esc_html__( '5 items', 'casano' ) => '15',
								esc_html__( '6 items', 'casano' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >= 1200px and < 1500px )', 'casano' ),
							'group'       => esc_html__( 'Boostrap settings', 'casano' ),
							'std'         => '3',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on landscape tablet', 'casano' ),
							'param_name'  => 'boostrap_md_items',
							'value'       => array(
								esc_html__( '1 item', 'casano' )  => '12',
								esc_html__( '2 items', 'casano' ) => '6',
								esc_html__( '3 items', 'casano' ) => '4',
								esc_html__( '4 items', 'casano' ) => '3',
								esc_html__( '5 items', 'casano' ) => '15',
								esc_html__( '6 items', 'casano' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=992px and < 1200px )', 'casano' ),
							'group'       => esc_html__( 'Boostrap settings', 'casano' ),
							'std'         => '3',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on portrait tablet', 'casano' ),
							'param_name'  => 'boostrap_sm_items',
							'value'       => array(
								esc_html__( '1 item', 'casano' )  => '12',
								esc_html__( '2 items', 'casano' ) => '6',
								esc_html__( '3 items', 'casano' ) => '4',
								esc_html__( '4 items', 'casano' ) => '3',
								esc_html__( '5 items', 'casano' ) => '15',
								esc_html__( '6 items', 'casano' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=768px and < 992px )', 'casano' ),
							'group'       => esc_html__( 'Boostrap settings', 'casano' ),
							'std'         => '4',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Mobile', 'casano' ),
							'param_name'  => 'boostrap_xs_items',
							'value'       => array(
								esc_html__( '1 item', 'casano' )  => '12',
								esc_html__( '2 items', 'casano' ) => '6',
								esc_html__( '3 items', 'casano' ) => '4',
								esc_html__( '4 items', 'casano' ) => '3',
								esc_html__( '5 items', 'casano' ) => '15',
								esc_html__( '6 items', 'casano' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=480  add < 768px )', 'casano' ),
							'group'       => esc_html__( 'Boostrap settings', 'casano' ),
							'std'         => '6',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Mobile', 'casano' ),
							'param_name'  => 'boostrap_ts_items',
							'value'       => array(
								esc_html__( '1 item', 'casano' )  => '12',
								esc_html__( '2 items', 'casano' ) => '6',
								esc_html__( '3 items', 'casano' ) => '4',
								esc_html__( '4 items', 'casano' ) => '3',
								esc_html__( '5 items', 'casano' ) => '15',
								esc_html__( '6 items', 'casano' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device < 480px)', 'casano' ),
							'group'       => esc_html__( 'Boostrap settings', 'casano' ),
							'std'         => '12',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'products_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Instagram */
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Instagram Feed', 'casano' ),
					'base'        => 'casano_instagram', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display a instagram photo list.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'instagram.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'instagram/style-01.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Choose icon or image', 'casano' ),
							'value'      => array(
								esc_html__( 'No use', 'casano' ) => '',
								esc_html__( 'Icon', 'casano' )   => 'icontype',
								esc_html__( 'Image', 'casano' )  => 'imagetype',
							),
							'param_name' => 'iconimage',
							'std'        => '',
						),
						array(
							"type"       => "attach_image",
							"heading"    => esc_html__( "Image custom", "casano" ),
							"param_name" => "image",
							'dependency' => array(
								'element' => 'iconimage',
								'value'   => 'imagetype',
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'casano' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'casano' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'casano' ) => 'fontflaticon',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'description' => esc_html__( 'Select icon library.', 'casano' ),
							'std'         => 'fontawesome',
							'dependency'  => array(
								'element' => 'iconimage',
								'value'   => 'icontype',
							),
						),
						array(
							'param_name'  => 'icon_casanocustomfonts',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'casanocustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							'settings'    => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 4000,
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Images limit', 'casano' ),
							'param_name'  => 'limit',
							'std'         => '6',
							'admin_label' => true,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Instagram user ID', 'casano' ),
							'param_name'  => 'id',
							'admin_label' => true,
							'description' => esc_html__( 'Your Instagram ID. Ex: 2267639447. ', 'casano' ) . '<a href="http://instagram.pixelunion.net/" target="_blank">' . esc_html__( 'How to find?', 'casano' ) . '</a>',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Access token', 'casano' ),
							'param_name'  => 'token',
							'description' => esc_html__( 'Your Instagram token. Ex: 2267639447.1677ed0.eade9f2bbe8245ea8bdedab984f3b4c3. ', 'casano' ) . '<a href="http://instagram.pixelunion.net/" target="_blank">' . esc_html__( 'How to find?', 'casano' ) . '</a>',
							'admin_label' => true,
						),
						/* Owl */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( '1 Row', 'casano' )  => '1',
								esc_html__( '2 Rows', 'casano' ) => '2',
								esc_html__( '3 Rows', 'casano' ) => '3',
								esc_html__( '4 Rows', 'casano' ) => '4',
								esc_html__( '5 Rows', 'casano' ) => '5',
							),
							'std'         => '1',
							'heading'     => esc_html__( 'The number of rows which are shown on block', 'casano' ),
							'param_name'  => 'owl_number_row',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'casano' ),
							'param_name' => 'owl_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'casano' ) => 'rows-space-0',
								esc_html__( '10px', 'casano' )    => 'rows-space-10',
								esc_html__( '20px', 'casano' )    => 'rows-space-20',
								esc_html__( '30px', 'casano' )    => 'rows-space-30',
								esc_html__( '40px', 'casano' )    => 'rows-space-40',
								esc_html__( '50px', 'casano' )    => 'rows-space-50',
								esc_html__( '60px', 'casano' )    => 'rows-space-60',
								esc_html__( '70px', 'casano' )    => 'rows-space-70',
								esc_html__( '80px', 'casano' )    => 'rows-space-80',
								esc_html__( '90px', 'casano' )    => 'rows-space-90',
								esc_html__( '100px', 'casano' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Carousel settings', 'casano' ),
							"dependency" => array(
								"element" => "owl_number_row",
								"value"   => array( '2', '3', '4', '5' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'casano' ) => 'true',
								esc_html__( 'No', 'casano' )  => 'false'
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'casano' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'casano' )  => 'false',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'casano' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'casano' )  => 'false',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Enable Dots', 'casano' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots.", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'casano' ) => 'true',
								esc_html__( 'No', 'casano' )  => 'false'
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'casano' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'casano' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'casano' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Auto Responsive Margin', 'casano' ),
							'param_name' => 'autoresponsive',
							'group'      => esc_html__( 'Carousel settings', 'casano' ),
							'value'      => array(
								esc_html__( 'No', 'casano' )  => '',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'        => '',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'casano' ),
							"param_name"  => "ls_items",
							"value"       => "5",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px )", 'casano' ),
							"param_name"  => "lg_items",
							"value"       => "4",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'casano' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'casano' ),
							"param_name"  => "sm_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'casano' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'casano' ),
							"param_name"  => "ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'instagram_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			/*Map New Instagram Shop Wrap*/
			vc_map(
				array(
					'name'                    => esc_html__( 'Casano: Instagram Shop Wrap', 'casano' ),
					'base'                    => 'casano_instagramshopwrap',
					'category'                => esc_html__( 'Casano Elements', 'casano' ),
					'description'             => esc_html__( 'Display a custom instagram shop wrap.', 'casano' ),
					'as_parent'               => array( 'only' => 'ziss' ),
					'content_element'         => true,
					'show_settings_on_create' => true,
					'js_view'                 => 'VcColumnView',
					'icon'                    => CASANO_SHORTCODES_ICONS_URI . 'container.png',
					'params'                  => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'instagramshopwrap/style-01.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Choose icon or image', 'casano' ),
							'value'      => array(
								esc_html__( 'Icon', 'casano' )  => 'icontype',
								esc_html__( 'Image', 'casano' ) => 'imagetype',
							),
							'param_name' => 'iconimage',
							'std'        => 'icontype',
						),
						array(
							"type"       => "attach_image",
							"heading"    => esc_html__( "Image custom", "casano" ),
							"param_name" => "image",
							'dependency' => array(
								'element' => 'iconimage',
								'value'   => 'imagetype',
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'casano' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'casano' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'casano' ) => 'fontflaticon',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'description' => esc_html__( 'Select icon library.', 'casano' ),
							'std'         => 'fontawesome',
							'dependency'  => array(
								'element' => 'iconimage',
								'value'   => 'icontype',
							),
						),
						array(
							'param_name'  => 'icon_casanocustomfonts',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'casanocustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							'settings'    => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 4000,
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'casano' ),
							'param_name'  => 'title',
							'admin_label' => true,
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							'heading'     => esc_html__( 'Extra Class Name', 'casano' ),
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'casano' ),
							'type'        => 'textfield',
							'param_name'  => 'el_class',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'instagramshopwrap_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Map new Container */
			vc_map(
				array(
					'name'                    => esc_html__( 'Casano: Container', 'casano' ),
					'base'                    => 'casano_container',
					'category'                => esc_html__( 'Casano Elements', 'casano' ),
					'content_element'         => true,
					'show_settings_on_create' => true,
					'is_container'            => true,
					'js_view'                 => 'VcColumnView',
					'icon'                    => CASANO_SHORTCODES_ICONS_URI . 'container.png',
					'params'                  => array(
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Container Large', 'casano' ) => '',
								esc_html__( 'Container Normal', 'casano' )  => 'normal',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Select Container', 'casano' ),
							'param_name'  => 'container_type',
							'group'       => esc_html__( 'Container settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'param_name'       => 'container_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);

			/*Map New Newsletter*/
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Newsletter', 'casano' ),
					'base'        => 'casano_newsletter', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display a newsletter box.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'newllter.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'newsletter/style-01.jpg',
								),
                                'style-02' => array(
									'alt' => 'Style 02',
									'img' => CASANO_SHORTCODE_PREVIEW . 'newsletter/style-02.jpg',
								),
                                'style-03' => array(
									'alt' => 'Style 03',
									'img' => CASANO_SHORTCODE_PREVIEW . 'newsletter/style-03.jpg',
								),
                                'style-04' => array(
									'alt' => 'Style 04',
									'img' => CASANO_SHORTCODE_PREVIEW . 'newsletter/style-04.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'casano' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'casano' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'casano' ) => 'fontflaticon',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'description' => esc_html__( 'Select icon library.', 'casano' ),
							'std'         => 'fontawesome',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-02','style-03'),
							),
						),
						array(
							'param_name'  => 'icon_casanocustomfonts',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'casanocustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'casano' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							'settings'    => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 4000,
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'casano' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'casano' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'casano' ),
							'admin_label' => true,
							'std'         => '',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array('style-04'),
							),
						),
						array(
							'type'       => 'textarea',
							'heading'    => esc_html__( 'Description', 'casano' ),
							'param_name' => 'description',
							'std'        => '',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Placeholder text", 'casano' ),
							"param_name"  => "placeholder_text",
							"admin_label" => false,
							'std'         => 'Email address',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Placeholder text", 'casano' ),
							"param_name"  => "button_text",
							"admin_label" => false,
							'std'         => 'SUBSCRIBE',
                            'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-03'),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'newsletter_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Map New Slider*/
			vc_map(
				array(
					'name'                    => esc_html__( 'Casano: Slider', 'casano' ),
					'base'                    => 'casano_slider',
					'category'                => esc_html__( 'Casano Elements', 'casano' ),
					'description'             => esc_html__( 'Display a custom slide.', 'casano' ),
					'as_parent'               => array( 'only' => 'vc_single_image,casano_banner,casano_iconbox,casano_testimonials,casano_categories,casano_title' ),
					'content_element'         => true,
					'show_settings_on_create' => true,
					'js_view'                 => 'VcColumnView',
					'icon'                    => CASANO_SHORTCODES_ICONS_URI . 'slide.png',
					'params'                  => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => esc_html__( 'Title', 'casano' ),
                            'param_name'  => 'title',
                            'admin_label' => true,
                        ),
						/* Owl */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'casano' ) => 'true',
								esc_html__( 'No', 'casano' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'casano' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'casano' )  => 'false',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'casano' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'casano' )  => '',
								esc_html__( 'Light', 'casano' ) => 'nav-light',
								esc_html__( 'Arrow Dark', 'casano' ) => 'nav-arrow-dark',
								esc_html__( 'Circle Dark', 'casano' ) => 'circle-dark',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Navigation color', 'casano' ),
							'param_name'  => 'nav_color',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'navigation',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'casano' )  => 'false',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Enable Dots', 'casano' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots.", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Default', 'casano' )  => '',
								esc_html__( 'Light', 'casano' ) => 'dots-light',
								esc_html__( 'Dark', 'casano' ) => 'dots-dark',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Dots color', 'casano' ),
							'param_name'  => 'dots_color',
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'dots',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'casano' ) => 'true',
								esc_html__( 'No', 'casano' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'casano' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'casano' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'casano' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'casano' ),
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Auto Responsive Margin', 'casano' ),
							'param_name' => 'autoresponsive',
							'group'      => esc_html__( 'Carousel settings', 'casano' ),
							'value'      => array(
								esc_html__( 'No', 'casano' )  => '',
								esc_html__( 'Yes', 'casano' ) => 'true',
							),
							'std'        => '',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'casano' ),
							"param_name"  => "ls_items",
							"value"       => "5",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px < 1500px )", 'casano' ),
							"param_name"  => "lg_items",
							"value"       => "4",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'casano' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'casano' ),
							"param_name"  => "sm_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'casano' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'casano' ),
							"param_name"  => "ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'casano' ),
							'admin_label' => false,
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							'heading'     => esc_html__( 'Extra Class Name', 'casano' ),
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'casano' ),
							'type'        => 'textfield',
							'param_name'  => 'el_class',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'slider_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Section Testimonial*/
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Testimonial', 'casano' ),
					'base'        => 'casano_testimonials', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display testimonial info.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'testimonial.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'testimonial/style-01.jpg'
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => CASANO_SHORTCODE_PREVIEW . 'testimonial/style-02.jpg'
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'attach_image',
							'heading'    => esc_html__( 'Image', 'casano' ),
							'param_name' => 'image',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Star Rating', 'casano' ),
							'param_name' => 'rating',
							'value'      => array(
								esc_html__( '1 Star', 'casano' )  => 'rating-1',
								esc_html__( '2 Stars', 'casano' ) => 'rating-2',
								esc_html__( '3 Stars', 'casano' ) => 'rating-3',
								esc_html__( '4 Stars', 'casano' ) => 'rating-4',
								esc_html__( '5 Stars', 'casano' ) => 'rating-5',
							),
							'std'        => 'rating-5',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-01'),
							),
						),
						array(
							'type'       => 'textarea',
							'heading'    => esc_html__( 'Content', 'casano' ),
							'param_name' => 'desc',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Name', 'casano' ),
							'param_name'  => 'name',
							'description' => esc_html__( 'Name', 'casano' ),
							'admin_label' => true,
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Position', 'casano' ),
							'param_name'  => 'position',
							'description' => esc_html__( 'Position', 'casano' ),
							'admin_label' => true,
						),
						array(
							'type'       => 'vc_link',
							'heading'    => esc_html__( 'Link', 'casano' ),
							'param_name' => 'link',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'testimonials_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			/*Section Team*/
			require_once vc_path_dir( 'CONFIG_DIR', 'content/vc-icon-element.php' );
			$icon_params = array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Link Social', 'casano' ),
					'param_name'  => 'link_social',
					'admin_label' => true,
					'description' => esc_html__( 'shortcode title.', 'casano' ),
				),
			);
			$icon_params = array_merge( $icon_params, (array) vc_map_integrate_shortcode(
				vc_icon_element_params(), 'i_', '',
				array(
					// we need only type, icon_fontawesome, icon_.., NOT color and etc
					'include_only_regex' => '/^(type|icon_\w*)/',
				)
			)
			);
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Team', 'casano' ),
					'base'        => 'casano_team', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display team info.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'testimonial.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'team/style-01.jpg'
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'attach_image',
							'heading'    => esc_html__( 'Image', 'casano' ),
							'param_name' => 'image',
						),
						array(
							'type'       => 'param_group',
							'heading'    => esc_html__( 'Social', 'casano' ),
							'param_name' => 'social_team',
							'params'     => $icon_params,
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Name', 'casano' ),
							'param_name'  => 'name',
							'description' => esc_html__( 'Name', 'casano' ),
							'admin_label' => true,
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Position', 'casano' ),
							'param_name'  => 'position',
							'description' => esc_html__( 'Position', 'casano' ),
							'admin_label' => true,
						),
						array(
							'type'       => 'vc_link',
							'heading'    => esc_html__( 'Link', 'casano' ),
							'param_name' => 'link',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'team_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			/* Map Google Map */
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Google Map', 'casano' ),
					'base'        => 'casano_googlemap', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display a google map.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'gmap.png',
					'params'      => array(
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Pin", "casano" ),
							"param_name"  => "pin_icon",
							"admin_label" => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Title", 'casano' ),
							"param_name"  => "title",
							'admin_label' => true,
							"description" => esc_html__( "title.", 'casano' ),
							'std'         => 'Fami themes',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Phone", 'casano' ),
							"param_name"  => "phone",
							'admin_label' => true,
							"description" => esc_html__( "phone.", 'casano' ),
							'std'         => '088-465 9965 02',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Email", 'casano' ),
							"param_name"  => "email",
							'admin_label' => true,
							"description" => esc_html__( "email.", 'casano' ),
							'std'         => 'famithemes@gmail.com',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Map Height", 'casano' ),
							"param_name"  => "map_height",
							'admin_label' => true,
							'std'         => '400',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Maps type', 'casano' ),
							'param_name' => 'map_type',
							'value'      => array(
								esc_html__( 'ROADMAP', 'casano' )   => 'ROADMAP',
								esc_html__( 'SATELLITE', 'casano' ) => 'SATELLITE',
								esc_html__( 'HYBRID', 'casano' )    => 'HYBRID',
								esc_html__( 'TERRAIN', 'casano' )   => 'TERRAIN',
							),
							'std'        => 'ROADMAP',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Show info content?', 'casano' ),
							'param_name' => 'info_content',
							'value'      => array(
								esc_html__( 'Yes', 'casano' ) => '1',
								esc_html__( 'No', 'casano' )  => '2',
							),
							'std'        => '1',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Address", 'casano' ),
							"param_name"  => "address",
							'admin_label' => true,
							"description" => esc_html__( "address.", 'casano' ),
							'std'         => 'New York City, NY, USA',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Longitude", 'casano' ),
							"param_name"  => "longitude",
							'admin_label' => true,
							"description" => esc_html__( "longitude.", 'casano' ),
							'std'         => '-73.935242',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Latitude", 'casano' ),
							"param_name"  => "latitude",
							'admin_label' => true,
							"description" => esc_html__( "latitude.", 'casano' ),
							'std'         => '40.730610',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Zoom", 'casano' ),
							"param_name"  => "zoom",
							'admin_label' => true,
							"description" => esc_html__( "zoom.", 'casano' ),
							'std'         => '14',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", 'casano' ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'googlemap_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);

			/* Map New Social */
			$socials     = array();
			$all_socials = casano_get_option( 'user_all_social' );
			$i           = 1;
			if ( $all_socials ) {
				foreach ( $all_socials as $social ) {
					$socials[ $social['title_social'] ] = $i ++;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Casano: Socials', 'casano' ),
					'base'        => 'casano_socials', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Casano Elements', 'casano' ),
					'description' => esc_html__( 'Display a social list.', 'casano' ),
					'icon'        => CASANO_SHORTCODES_ICONS_URI . 'socials.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'socials/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => CASANO_SHORTCODE_PREVIEW . 'socials/style-02.jpg',
								),
                                'style-03' => array(
									'alt' => 'Style 03',
									'img' => CASANO_SHORTCODE_PREVIEW . 'socials/style-03.jpg',
								),
                                'style-04' => array(
									'alt' => 'Style 04',
									'img' => CASANO_SHORTCODE_PREVIEW . 'socials/style-04.jpg',
								),
								'style-05' => array(
									'alt' => 'Style 05',
									'img' => CASANO_SHORTCODE_PREVIEW . 'socials/style-05.jpg',
								),
                                'style-06' => array(
									'alt' => 'Style 06',
									'img' => CASANO_SHORTCODE_PREVIEW . 'socials/style-06.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__( 'Display on', 'casano' ),
							'param_name' => 'use_socials',
							'class'      => 'checkbox-display-block',
							'value'      => $socials,
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "casano" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "casano" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),
						array(
							'param_name'       => 'socials_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);

			/* Pin Mapper */
			$all_pin_mappers      = get_posts(
				array(
					'post_type'      => 'casano_mapper',
					'posts_per_page' => '-1'
				)
			);
			$all_pin_mappers_args = array(
				esc_html__( ' ---- Choose a pin mapper ---- ', 'casano' ) => '0',
			);
			if ( ! empty( $all_pin_mappers ) ) {
				foreach ( $all_pin_mappers as $pin_mapper ) {
					$all_pin_mappers_args[ $pin_mapper->post_title ] = $pin_mapper->ID;
				}
			} else {
				$all_pin_mappers_args = array(
					esc_html__( ' ---- No pin mapper to choose ---- ', 'casano' ) => '0',
				);
			}
			vc_map(
				array(
					'name'     => esc_html__( 'Casano: Pin Mapper', 'casano' ),
					'base'     => 'casano_pinmap',
					'category' => esc_html__( 'Casano Elements', 'casano' ),
					'icon'     => CASANO_SHORTCODES_ICONS_URI . 'pinmapper.png',
					'params'   => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'casano' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => CASANO_SHORTCODE_PREVIEW . 'pinmapper/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => CASANO_SHORTCODE_PREVIEW . 'pinmapper/style-02.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'textarea',
							'heading'    => esc_html__( 'Title', 'casano' ),
							'param_name' => 'title',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array(
									'style-02',
								),
							),
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Short Description', 'casano' ),
							'param_name'  => 'short_desc',
							'description' => esc_html__( 'Short description display under the title', 'casano' ),
							'admin_label' => true,
							'std'         => '',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array(
									'style-02',
								),
							),
						),
						array(
							'type'       => 'vc_link',
							'heading'    => esc_html__( 'Button Link', 'casano' ),
							'param_name' => 'link',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array(
									'style-02',
								),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Choose Pin Mapper', 'casano' ),
							'param_name' => 'ids',
							'value'      => $all_pin_mappers_args
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'casano' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'casano' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'casano' ),
						),
						array(
							'param_name'       => 'custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'casano' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'casano' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'casano' ),
						),

					),
				)
			);

		}
	}

	new Casano_Visual_Composer();
}

if ( class_exists( 'Vc_Manager' ) ) {
	function change_vc_row() {
		$args = array(
			array(
				"type"        => "checkbox",
				"group"       => "Additions",
				"holder"      => "div",
				"class"       => "custom-checkbox",
				"heading"     => esc_html__( 'Parallax effect: ', 'casano' ),
				"description" => esc_html__( 'Chosen for using Paralax scroll', 'casano' ),
				"param_name"  => "paralax_class",
				'admin_label' => true,
				"value"       => array(
					esc_html__( 'paralax-slide', 'casano' ) => "type_paralax",
				),
			),
			array(
				"type"        => "checkbox",
				"group"       => "Additions",
				"heading"     => esc_html__( 'Slide Class: ', 'casano' ),
				"description" => esc_html__( 'Chosen for using slide scroll', 'casano' ),
				"param_name"  => "section_class",
				'admin_label' => true,
				"value"       => array(
					esc_html__( 'section-slide', 'casano' ) => "section-slide",
				),
			),
		);
		foreach ( $args as $value ) {
			// vc_add_param( "vc_row", $value );
			vc_add_param( "vc_section", $value );
		}
	}

	change_vc_row();
	get_template_part( 'vc_templates/vc_row.php' );
	get_template_part( 'vc_templates/vc_section.php' );
}

VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Accordion' );

class WPBakeryShortCode_Casano_Tabs extends WPBakeryShortCode_VC_Tta_Accordion {
}

class WPBakeryShortCode_Casano_Accordions extends WPBakeryShortCode_VC_Tta_Accordion {
}

class WPBakeryShortCode_Casano_Container extends WPBakeryShortCodesContainer {
}

class WPBakeryShortCode_Casano_Slider extends WPBakeryShortCodesContainer {
}

class WPBakeryShortCode_casano_Instagramshopwrap extends WPBakeryShortCodesContainer {
}
