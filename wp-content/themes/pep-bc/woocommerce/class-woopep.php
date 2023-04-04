<?php
/*
 * WooCommerce functionality
 */
namespace PEP\WooCommerce;

class WooPEP extends WooSetup
{
	public function __construct()
    {
		if ( !class_exists( 'WooCommerce' ) ) return false;
		
		/* Remove actions */
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_single_product_summary','woocommerce_template_single_title',5);
		remove_action( 'woocommerce_after_single_product_summary','woocommerce_output_product_data_tabs',10);

		add_action( 'woocommerce_before_single_product_summary',array($this,'woocommerce_single_title'),0);
		add_action( 'woocommerce_archive_description',array($this,'woocommerce_render_archive_sidebar'),50);
		add_action( 'woocommerce_after_shop_loop_item_title', array($this,'excerpt_in_product_archives'), 40 );
		add_action( 'woocommerce_after_single_product_summary', array($this,'woo_template_product_description'), 10 );
		add_action( 'customize_register', array($this,'woo_customizer_register') );
		add_action( 'customize_controls_print_scripts', array($this,'add_customizer_scripts'), 30 );
	
		add_action( 'get_footer',array($this,'render_all_styles'),999);
		
		if(get_theme_mod('woocommerce_shop_page_show_price','yes')=='no') {
			remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price',10);
			
		}
	
		if(get_theme_mod('woocommerce_shop_page_display_images','yes')=='no') {
			remove_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_product_thumbnail',10);
		}
	
		if(get_theme_mod('woocommerce_single_product_show_sku','no')=='no') {
			add_filter( 'wc_product_sku_enabled', array($this,'remove_product_page_skus') );
		}
		if(get_theme_mod('woocommerce_checkout_order_note','no')=='no') {
			add_filter( 'woocommerce_checkout_fields' , array($this,'alter_woocommerce_checkout_fields') );
		}
	
		if(get_theme_mod('woocommerce_single_product_show_meta','no')=='no') {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		}
	
		if(get_theme_mod('woocommerce_single_product_show_relatedproducts','no')=='no') {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}
	
		if(get_theme_mod('woocommerce_single_product_show_about_tab')!="" || get_theme_mod('woocommerce_single_product_show_additional_info')=='summary') {
			add_action('woocommerce_single_product_summary','woocommerce_output_product_data_tabs',15);
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		}
		
		
		if(get_theme_mod('woocommerce_cart_show_thumbs','yes')=='no' && is_cart()) {
			add_filter( 'woocommerce_cart_item_thumbnail', '__return_empty_string' );
		}
		
		add_action( 'wp',array($this, 'template_hooks')); 
		
		genesis_register_sidebar( array(
			'id' => 'woo-archive',
			'name' => __('Before Woo archive','pep'),
			'description' => 'Adds a sidebar before WooCommerce products on archive page.',
			)
		);

		/* Filters */
		add_filter( 'woocommerce_package_rates', array($this,'hide_shipping_when_free_is_available'), 100 );
		add_filter( 'woocommerce_enqueue_styles', array($this,'woo_dequeue_styles') );
		add_filter( 'body_class', array($this,'woo_body_class') );
		add_filter( 'woocommerce_get_price_html', array($this,'price_free_zero_empty'), 100, 2 );
		add_filter( 'woocommerce_variable_sale_price_html', array($this,'variable_price_format'), 999, 2 );
		add_filter( 'woocommerce_variable_price_html', array($this,'variable_price_format'), 999, 2 );
		add_filter( 'woocommerce_coupons_enabled', array($this,'hide_coupon_field_on_cart') );
		add_filter( 'woocommerce_product_tabs', array($this,'woo_modify_product_tabs'), 98 );
		add_filter( 'wp_nav_menu_primary_items', array($this,'append_cart_icon'), 10, 2 );
		
		add_filter( 'woocommerce_product_description_heading', function() { return '';} );
		add_filter( 'woocommerce_product_additional_information_heading', function() { return '';} );
		
		add_filter(  'gettext',  array($this,'filter_translations')  );
		add_filter(  'ngettext',  array($this,'filter_translations')  );
		
		add_filter( 'woocommerce_loop_add_to_cart_link', array($this,'replacing_add_to_cart_button'), 10, 2 );
		
		add_action( 'pre_get_posts', array($this,'filter_search_results') );
	}
	
	/* Use with if conditionals */
	public function template_hooks() {
		if(is_cart() || is_checkout()) {
			add_action('genesis_entry_content',array($this,'render_wc_steps'),0);
		}
	}
	
	
	// Remove each style one by one
	public function woo_dequeue_styles( $enqueue_styles ) {
		if(get_theme_mod('woocommerce_unset_css','no')=='yes') {
		
			unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
			unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
			unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
		
		}
		return $enqueue_styles;
	}
	
	/* Add body class to theme customisation */
	public function woo_body_class($classes) {
		if(is_woocommerce()) {
			$classes[]=get_theme_mod('woocommerce_shop_page_button_class','button-default');
		}
	
		if(get_theme_mod('woocommerce_cart_show_thumbs','yes')=='no' && is_cart()) {
			$classes[]='hide-cart-thumb';
		}
		
		if(is_checkout() && get_theme_mod('woocommerce_checkout_two_column','no')=='yes') {
			$classes[]='checkout-two-columns';
		}
		
		if(is_checkout() && get_theme_mod('woocommerce_checkout_boxed','boxed')=='boxed') {
			$classes[]='checkout-boxed';
		}

		return $classes;
	}
	
	/* add Genesis entry-header to WooCommerce single product */
	public function woocommerce_single_title() {
		if(!is_product()) return false;
		echo '<header class="entry-header">';
		echo '<h1 class="entry-title product_title">'.get_the_title().'</h1>';
		echo '</header>';
	}
	
	/* Change price format from range to "From:" */
	public function variable_price_format( $price, $product ) {
		global $current_user;
		$is_wholesale = get_user_meta( $current_user->ID, 'wcs_wholesale_customer', true );
		
		if(is_single() && is_product() && !is_front_page() && is_user_logged_in()) {
	
			$prefix = sprintf('%s: ', __('From', 'pep'));
			
			$min_price_regular = $product->get_variation_regular_price( 'min', true );
			$min_price_sale    = $product->get_variation_sale_price( 'min', true );
			$max_price = $product->get_variation_price( 'max', true );
			$min_price = $product->get_variation_price( 'min', true );
			
			if(function_exists('wcs_apply_wholesale_pricing') && $is_wholesale == '1') {
				$wholesale_min_price = wcs_apply_wholesale_pricing( $min_price, $product );
				$wholesale_max_price = wcs_apply_wholesale_pricing( $max_price, $product );
				
				if(is_array($wholesale_min_price)) {
					$wholesale_min_price = array_values($wholesale_min_price);
					$wholesale_min_price=array_filter($wholesale_min_price);
					
					if(!empty($wholesale_min_price)) {
						$wholesale_min_price=min( $wholesale_min_price);
					}
				}
			$price = '<span class="wholesale-price">'.__('Your price:','pep').' '.wc_price( $wholesale_min_price ).'</span>';
			$price .= '<span class="retail-price">'.__('Retail price:','pep').' '.wc_price( $min_price_regular ).'</span>';
				
			} else {
	
				$price = ( $min_price_sale == $min_price_regular ) ?
				wc_price( $min_price_regular ) :
				'<del>' . wc_price( $min_price_regular ) . '</del>' . '<ins>' . wc_price( $min_price_sale ) . '</ins>';
			
			}
		
			return ( $min_price == $max_price ) ?
				$price :
				sprintf('%s%s', $prefix, $price);
		} else {
			return $price;
		}

	}
	
	/* Display "Free" if price is zero */
	public function price_free_zero_empty( $price, $product ){
	
		if ( '' === $product->get_price() || 0 == $product->get_price() ) {
			$price = '<span class="woocommerce-Price-amount amount product-free">'.__('FREE','pep').'</span>';
		}
	
		return $price;
	}
	
	/* Add summary to product archive */
	public function excerpt_in_product_archives() {
		if(get_theme_mod('woocommerce_shop_page_excerpts','no')=='yes') {
			echo '<div class="woo-product-excerpt">';
			the_excerpt();
			echo '</div>';
		}
	}
	
	public function render_all_styles() {
		if(is_checkout() || is_cart()) {
			wp_enqueue_style( 'cart', THEME_DIR.'/assets/css/checkout-cart.css');
		}
	}
	
	/* Add description */
	public function woo_template_product_description() {
		echo '<div class="entry-content">';
			echo '<div class="description">';
			woocommerce_get_template( 'single-product/tabs/description.php' );
			echo '</div>';
	
			if(get_theme_mod('woocommerce_single_product_show_additional_info')=='description') {
				echo '<div class="additional-info">';
				do_action('pep_before_single_product_additional_info');
				woocommerce_get_template( 'single-product/tabs/additional-information.php' );
				do_action('pep_after_single_product_additional_info');
				echo '</div>';
			}
		echo '</div>';
	}
	
	/* Add customizer options for WooCommerce */
	public function woo_customizer_register( $wp_customize ) {

		$wp_customize->add_setting(
			'woocommerce_checkout_two_column',
			array(
				'default'           => 'no',
			)
		);
	
		$wp_customize->add_control(
			'woocommerce_checkout_two_column',
			array(
				'label'       => __( 'Checkout columns', 'pep' ),
				'description' => __( 'Show checkout page in one or two columns.', 'pep' ),
				'priority'    => 9,
				'section'     => 'woocommerce_checkout',
				'settings'    => 'woocommerce_checkout_two_column',
				'type'        => 'select',
				'choices'=>array(
					'no'=>__('1 column','pep'),
					'yes'=>__('2 columns','pep')
				)
			)
		);
	
	
		$wp_customize->add_setting(
			'woocommerce_checkout_boxed',
			array(
				'default'           => 'boxed',
			)
		);
	
		$wp_customize->add_control(
			'woocommerce_checkout_boxed',
			array(
				'label'       => __( 'Box elements', 'pep' ),
				'description' => __( 'Add boxes around elements on checkout page.', 'pep' ),
				'priority'    => 9,
				'section'     => 'woocommerce_checkout',
				'settings'    => 'woocommerce_checkout_boxed',
				'type'        => 'select',
				'choices'=>array(
					'no'=>__('Default style','pep'),
					'boxed'=>__('Boxed style','pep')
				)
			)
		);
	
		$wp_customize->add_setting(
			'woocommerce_checkout_order_note',
			array(
				'default'           => 'no',
			)
		);
	
		// Add a control for showing excerpts.
		$wp_customize->add_control(
			'woocommerce_checkout_order_note',
			array(
				'label'       => __( 'Order note', 'pep' ),
				'priority'    => 9,
				'section'     => 'woocommerce_checkout',
				'settings'    => 'woocommerce_checkout_order_note',
				'type'        => 'select',
				'choices'=>array(
					'no'=>__('Hidden','pep'),
					'yes'=>__('Optional','pep')
				)
			)
		);
	
		$wp_customize->add_setting(
			'woocommerce_shop_page_excerpts',
			array(
				'default'           => 'no',
			)
		);
	
		// Add a control for showing excerpts.
		$wp_customize->add_control(
			'woocommerce_shop_page_excerpts',
			array(
				'label'       => __( 'Show excerpts', 'pep' ),
				'description' => __( 'Show excerpts on archive pages.', 'pep' ),
				'priority'    => 9,
				'section'     => 'woocommerce_product_catalog',
				'settings'    => 'woocommerce_shop_page_excerpts',
				'type'        => 'select',
				'choices'=>array(
					'no'=>__('Hide excerpts','pep'),
					'yes'=>__('Show excerpts','pep')
				)
			)
		);
		
		
		$wp_customize->add_setting(
			'woocommerce_shop_page_show_button_price',
			array(
				'default'           => 'no',
			)
		);
	
		// Add a control for show price in button.
		$wp_customize->add_control(
			'woocommerce_shop_page_show_button_price',
			array(
				'label'       => __( 'Show price in button', 'pep' ),
				'description' => __( 'Display price in cart-button.', 'pep' ),
				'priority'    => 9,
				'section'     => 'woocommerce_product_catalog',
				'settings'    => 'woocommerce_shop_page_show_button_price',
				'type'        => 'select',
				'choices'=>array(
					'no'=>__('No','pep'),
					'yes'=>__('Yes','pep')
				)
			)
		);
	
		$wp_customize->add_setting(
			'woocommerce_shop_page_show_price',
			array(
				'default'           => 'yes',
			)
		);
		
		// Add a control for show price below product name
		$wp_customize->add_control(
			'woocommerce_shop_page_show_price',
			array(
				'label'       => __( 'Show price below name', 'pep' ),
				'description' => __( 'Display product price', 'pep' ),
				'priority'    => 9,
				'section'     => 'woocommerce_product_catalog',
				'settings'    => 'woocommerce_shop_page_show_price',
				'type'        => 'select',
				'choices'=>array(
					'no'=>__('No','pep'),
					'yes'=>__('Yes','pep')
				)
			)
		);
	
		$wp_customize->add_setting(
			'woocommerce_shop_page_button_class',
			array(
				'default'           => 'button-default',
			)
		);
	
		// Add a control for showing excerpts.
		$wp_customize->add_control(
			'woocommerce_shop_page_button_class',
			array(
				'label'       => __( 'Button class', 'pep' ),
				'description' => __( 'Add class to body to design buttons on shop page.', 'pep' ),
				'priority'    => 10,
				'section'     => 'woocommerce_product_catalog',
				'settings'    => 'woocommerce_shop_page_button_class',
				'type'        => 'select',
				'choices'=>array(
					'button-default'=>__('Default','pep'),
					'button-alt'=>__('Alternate','pep'),
					'button-ghost'=>__('Ghost button','pep'),
				)
			)
		);
	
		$wp_customize->add_setting(
			'woocommerce_shop_page_display_images',
			array(
				'default'           => 'yes',
			)
		);
	
		// Add a control for showing excerpts.
		$wp_customize->add_control(
			'woocommerce_shop_page_display_images',
			array(
				'label'       => __( 'Show images', 'pep' ),
				'description' => __( 'Show product images on archive page.', 'pep' ),
				'priority'    => 10,
				'section'     => 'woocommerce_product_catalog',
				'settings'    => 'woocommerce_shop_page_display_images',
				'type'        => 'select',
				'choices'=>array(
					'yes'=>__('Show images','pep'),
					'no'=>__('Hide images','pep'),
				)
			)
		);
	
	
		/* Add new Woo section for single product */
		$wp_customize->add_section( 'pep_single_product' , array(
			'title'      => __('Product detail page','pep'),
			'priority'   => 30,
			'panel'=>'woocommerce',
		) );
	
		$wp_customize->add_setting(
			'woocommerce_single_product_show_sku',
			array(
				'default'           => 'no',
			)
		);
		$wp_customize->add_control(
			'woocommerce_single_product_show_sku',
			array(
				'label'       => __( 'Show SKU', 'pep' ),
				'description' => __( 'Show SKU below add to cart button.', 'pep' ),
				'priority'    => 10,
				'section'     => 'pep_single_product',
				'settings'    => 'woocommerce_single_product_show_sku',
				'type'        => 'select',
				'choices'=>array(
					'yes'=>__('Show','pep'),
					'no'=>__('Hide','pep'),
				)
			)
		);
	
		$wp_customize->add_setting(
			'woocommerce_single_product_show_meta',
			array(
				'default'           => 'no',
			)
		);
		$wp_customize->add_control(
			'woocommerce_single_product_show_meta',
			array(
				'label'       => __( 'Show categories & tags', 'pep' ),
				'description' => __( 'Show categories & tags below add to cart button.', 'pep' ),
				'priority'    => 20,
				'section'     => 'pep_single_product',
				'settings'    => 'woocommerce_single_product_show_meta',
				'type'        => 'select',
				'choices'=>array(
					'yes'=>__('Show','pep'),
					'no'=>__('Hide','pep'),
				)
			)
		);
	
		$wp_customize->add_setting(
			'woocommerce_single_product_show_relatedproducts',
			array(
				'default'           => 'no',
			)
		);
		$wp_customize->add_control(
			'woocommerce_single_product_show_relatedproducts',
			array(
				'label'       => __( 'Show related products', 'pep' ),
				'description' => __( 'Show related products on single product page.', 'pep' ),
				'priority'    => 20,
				'section'     => 'pep_single_product',
				'settings'    => 'woocommerce_single_product_show_relatedproducts',
				'type'        => 'select',
				'choices'=>array(
					'yes'=>__('Show','pep'),
					'no'=>__('Hide','pep'),
				)
			)
		);
	
		$wp_customize->add_setting(
			'woocommerce_single_product_show_additional_info',
			array(
				'default'           => 'no',
			)
		);
		$wp_customize->add_control(
			'woocommerce_single_product_show_additional_info',
			array(
				'label'       => __( 'Show additional info', 'pep' ),
				'description' => __( 'Choose where to show (or hide) additional product info.', 'pep' ),
				'priority'    => 20,
				'section'     => 'pep_single_product',
				'settings'    => 'woocommerce_single_product_show_additional_info',
				'type'        => 'select',
				'choices'=>array(
					'no'=>__('Hide','pep'),
					'description'=>__('Below product description','pep'),
					'summary'=>__('In summary tabs','pep'),
				)
			)
		);
	
		$wp_customize->add_setting(
			'woocommerce_single_product_show_about_tab_title',
			array(
				'default'           => get_bloginfo('name'),
			)
		);
	
		$wp_customize->add_control(
			'woocommerce_single_product_show_about_tab_title',
			array(
				'label'       => __( 'About tab title', 'pep' ),
				'description' => __( 'Title of about tab.', 'pep' ),
				'priority'    => 20,
				'section'     => 'pep_single_product',
				'settings'    => 'woocommerce_single_product_show_about_tab_title',
				'type'        => 'text',
			)
		);
	
	
	
		$wp_customize->add_setting( 'woocommerce_single_product_show_about_tab', array(
			'transport' => 'postMessage',
			'default' => '',
		) );
	
		$wp_customize->add_control( new \WP_Customize_Editor_Control( $wp_customize, 'woocommerce_single_product_show_about_tab', array(
			'label' => __( 'About tab content', 'pep' ),
			'description' => __( 'Leave empty to hide about tab.', 'pep' ),
			'section' => 'pep_single_product',
			'priority'    => 25,
			'editor_settings' => array(
				'quicktags' => true,
				'tinymce'   => true,
			),
		) ) );
	
		$wp_customize->add_section( 'pep_cart_page' , array(
			'title'      => __('Cart page','pep'),
			'priority'   => 40,
			'panel'=>'woocommerce',
		) );
	
		$wp_customize->add_setting(
			'woocommerce_cart_show_thumbs',
			array(
				'default' => 'no',
			)
		);
		$wp_customize->add_control(
			'woocommerce_cart_show_thumbs',
			array(
				'label'       => __( 'Show thumbnails', 'pep' ),
				'description' => __( 'Show product thumbnails in cart.', 'pep' ),
				'priority'    => 10,
				'section'     => 'pep_cart_page',
				'settings'    => 'woocommerce_cart_show_thumbs',
				'type'        => 'select',
				'choices'=>array(
					'yes'=>__('Show','pep'),
					'no'=>__('Hide','pep'),
				)
			)
		);
		
		/* Add new Woo section for general settings */
		$wp_customize->add_section( 'pep_woo_general' , array(
			'title'      => __('General settings','pep'),
			'priority'   => 0,
			'panel'=>'woocommerce',
		) );
		
		$wp_customize->add_setting(
			'woocommerce_unset_css',
			array(
				'default' => 'no',
			)
		);
		$wp_customize->add_control(
			'woocommerce_unset_css',
			array(
				'label'       => __( 'Unset all CSS-files', 'pep' ),
				'description' => __( 'Do you want to use default CSS-styling or not?', 'pep' ),
				'priority'    => 10,
				'section'     => 'pep_woo_general',
				'settings'    => 'woocommerce_unset_css',
				'type'        => 'select',
				'choices'=>array(
					'yes'=>__('Yes','pep'),
					'no'=>__('No','pep'),
				)
			)
		);
	
	}
	
	/* Customizer scripts */
	public function add_customizer_scripts() {
		?>
		<script type="text/javascript">

			jQuery( document ).ready( function( $ ) {
				wp.customize.section( 'pep_cart_page', function( section ) {
					section.expanded.bind( function( isExpanded ) {
						if ( isExpanded ) {
							wp.customize.previewer.previewUrl.set( '<?php echo esc_js( wc_get_page_permalink( 'cart' ) ); ?>' );
						}
					} );
				} );

			});
		</script>
		<?php
	}
	
	/* Remove SKU from product detail page */
	public function remove_product_page_skus($enabled) {
		if ( ! is_admin() && is_product() ) {
			return false;
		}
	
		return $enabled;
	}
	
	/* Remove order comments / customer note */
	public function alter_woocommerce_checkout_fields( $fields ) {
		unset($fields['order']['order_comments']);
		return $fields;
	}
	
	// Render steps on checkout and cart 
	public function render_wc_steps() {
		
		if(is_cart()) {
			$progress=6;
			$current=__('Current page: Cart','pep');
		} elseif(is_checkout()) {
			$progress=50;
			$current=__('Current page: Checkout','pep');
		} else {
			$progress=100;
			$current=__('Current page: Confirmation','pep');
		}
		include(THEME_VIEWS_PATH.'/woocommerce/steps.php');
	}
	
	// hide coupon field on cart page
	public function hide_coupon_field_on_cart( $enabled ) {
		if ( is_cart() ) {
			$enabled = false;
		}
		return $enabled;
	}
	
	/* Modify product tabs */
	public function woo_modify_product_tabs($tabs) {

		global $post;
		
		unset( $tabs['description'] ); // Remove the description tab
		unset( $tabs['reviews'] ); // Remove the reviews tab
	
		$temp_info=array();
		if(isset($tabs['additional_information'])) {
			$temp_info = $tabs['additional_information'];
			unset( $tabs['additional_information'] ); // Remove the additional information tab
		}
	
		if(get_theme_mod('woocommerce_single_product_show_about_tab','')!='') {
			$tabs['about'] = array(
				'title' 	=> get_theme_mod('woocommerce_single_product_show_about_tab_title',get_bloginfo('name')),
				'priority' 	=> 10,
				'callback' 	=> array($this,'woo_new_product_tab_about')
			);
		}
	
		$excerpt= get_the_excerpt();
		if($excerpt!="") {
			$tabs['summary'] = array(
				'title' 	=> __( 'Summary', 'pep' ),
				'priority' 	=> 20,
				'callback' 	=> array($this,'woo_new_product_tab_summary')
			);
		}
	
		if(get_theme_mod('woocommerce_single_product_show_additional_info')=='summary') {
			$tabs['aditional_information']=$temp_info;
		}
	
		return $tabs;
	}
	
	/* About tab */
	public function woo_new_product_tab_about() {

		echo get_theme_mod('woocommerce_single_product_show_about_tab');
	
	}
	
	/* Summary tab */
	public function woo_new_product_tab_summary() {
	
		woocommerce_get_template( 'single-product/short-description.php' );
	
	}
	
	// Append cart item (and cart count) to end of main menu.
	public function append_cart_icon( $items, $args ) {
			
		$cart_item_count = WC()->cart->get_cart_contents_count();
		$cart_link=$cart_count_span = '';
		if ( $cart_item_count ) {
			$cart_count_span = '<span class="count">'.$cart_item_count.'</span> <span class="screen-reader-text">'._n( 'item in cart', 'items in cart', $cart_item_count, 'pep' ).'</span>';
			$cart_link = '<li class="cart menu-item menu-item-type-post_type menu-item-object-page"><a href="' . get_permalink( wc_get_page_id( 'cart' ) ) . '"><i class="fa fa-shopping-cart"></i> <span class="mobile-only">'.__('Shopping cart','pep').'</span> '.$cart_count_span.'</a></li>';
		}
	
		// Add the cart link to the end of the menu.
		$items = $items . $cart_link;
	
		return $items;
	}



	public function filter_translations( $translated ) {
		$locale=get_locale();
		if($locale=='nl_NL') {
			$words = array(
				'Gerelateerde producten' => 'Ook interessant',  
				'Andere suggesties&hellip;' => 'Ook interessant',  
				'Properties' => 'Eigenschappen',  
				'Extra informatie' => 'Extra info',  
				'View product' => 'Meer info',
				'Bekijk product' => 'Meer info',
				'Next Page' => 'Volgende',
				'Previous Page' => 'Vorige',
			);
		} elseif($locale=='de_DE') {
			$words = array(
				'Gerelateerde producten' => 'Ook interessant',  
				'Andere suggesties&hellip;' => 'Ook interessant',  
				'Properties' => 'Eigenschaften',  
				'Extra informatie' => 'Extra info',  
				'View product' => 'Anzeigen',
				'Next Page' => 'Nächste Seite',
				'Previous Page' => 'Vorherige Seite',
				'Zusätzliche Information'=>'Mehr Infos'
			);
			
		}
		if(isset($words) && !empty($words)) {
			$translated = str_ireplace(  array_keys($words),  $words,  $translated );
		}
		return $translated;
	}
	
	public function replacing_add_to_cart_button( $button, $product  ) {
		$button_text = __("View product", "pep");
		$price=wc_price($product->get_price());
		
		if(get_theme_mod('woocommerce_shop_page_show_button_price','no')=='yes') {		
			$button = '<a class="button" href="' . $product->get_permalink() . '"><span class="button-text">' . $button_text . '</span> <span class="screen-reader-text">'.__('about','pep').' '.$product->get_name().'</span> <span class="button-price">'.$price.'</span></a>';
		} else {
			$button = '<a class="button" href="' . $product->get_permalink() . '"><span class="button-text">' . $button_text . '</span> <span class="screen-reader-text">'.__('about','pep').' '.$product->get_name().'</span></a>';
		}
		
		$button=apply_filters('pep_change_add_to_cart_button',$button,$button_text,$price,$product);
	
		return $button;
	}
	
	public function filter_search_results($query) {
		 if( ! is_admin() && is_search() && $query->is_main_query() ) {
			$query->set( 'post_type', 'product' );
		}
	}
	
	public function woocommerce_render_archive_sidebar() {
		
		global $woocommerce;
		
		if(is_archive() && is_woocommerce() || is_shop() || is_product_category()) {
			if ( is_active_sidebar( 'woo-archive' ) ) : ?>
				<ul id="woo-sidebar">
				<?php dynamic_sidebar( 'woo-archive' ); ?>
				</ul>
			<?php endif; 
		}
	}
	
	/**
	* Hide shipping rates when free shipping is available.
	* Updated to support WooCommerce 2.6 Shipping Zones.
	*
	* @param array $rates Array of rates found for the package.
	* @return array
	*/
	public function hide_shipping_when_free_is_available( $rates ) {
		$free = array();
		foreach ( $rates as $rate_id => $rate ) {
			if ( 'free_shipping' === $rate->method_id ) {
				$free[ $rate_id ] = $rate;
				break;
			}
		}
		return ! empty( $free ) ? $free : $rates;
	}
	
}