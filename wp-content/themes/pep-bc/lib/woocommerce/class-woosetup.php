<?php
/*
 * WooCommerce functionality
 */
namespace PEP\WooCommerce;

class WooSetup 
{
	public function __construct($customizer)
    {
		$this->customizer=$customizer;
		
		if ( !class_exists( 'WooCommerce' ) ) return false;

		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		add_theme_support( 'wc-product-gallery-zoom' );
			
		add_action( 'after_switch_theme', array($this,'woocommerce_image_dimensions_after_theme_setup'), 1 );
		add_action( 'activated_plugin', array($this,'woocommerce_image_dimensions_after_woo_activation'), 10, 2 );

		add_filter( 'woocommerce_get_image_size_gallery_thumbnail', array($this,'gallery_image_thumbnail') );
		add_filter( 'woocommerce_style_smallscreen_breakpoint', array($this,'woocommerce_breakpoint') );
		add_filter( 'genesiswooc_products_per_page', array($this,'default_products_per_page') );
		add_filter( 'woocommerce_pagination_args', array($this,'woocommerce_pagination') );
		
		add_action( 'wp_enqueue_scripts', array($this,'woocommerce_css') );

	}
	
	
	/**
	* Prints an inline script to the footer to keep products the same height.
	*
	* @since 2.3.0
	*/
	public function products_match_height() {

		// If Woocommerce is not activated, or a product page isn't showing, exit early.
		if ( ! class_exists( 'WooCommerce' ) || ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}
	
		wp_enqueue_script(
			'genesis-sample-match-height',
			get_stylesheet_directory_uri() . '/js/jquery.matchHeight.min.js',
			array( 'jquery' ),
			1,
			true
		);
		wp_add_inline_script(
			'genesis-sample-match-height',
			"jQuery(document).ready( function() { jQuery( '.product .woocommerce-LoopProduct-link').matchHeight(); });"
		);

	}
	
	/**
	* Modifies the WooCommerce breakpoints.
	*
	* @since 2.3.0
	*
	* @return string Pixel width of the theme's breakpoint.
	*/
	public function woocommerce_breakpoint() {

		$current = genesis_site_layout();
		$layouts = array(
			'one-sidebar' => array(
				'content-sidebar',
				'sidebar-content',
			),
		);
	
		if ( in_array( $current, $layouts['one-sidebar'], true ) ) {
			return '1200px';
		}
	
		return '860px';

	}
	
	/**
	* Sets the default products per page.
	*
	* @since 2.3.0
	*
	* @return int Number of products to show per page.
	*/
	public function default_products_per_page() {
		return 8;
	}
	
	/**
	* Updates the next and previous arrows to the default Genesis style.
	*
	* @param array $args The previous and next text arguments.
	* @since 2.3.0
	*
	* @return array New next and previous text arguments.
	*/
	public function woocommerce_pagination( $args ) {
	
		$args['prev_text'] = sprintf( '&laquo; %s', __( 'Previous Page', 'genesis-sample' ) );
		$args['next_text'] = sprintf( '%s &raquo;', __( 'Next Page', 'genesis-sample' ) );
	
		return $args;

	}
	
	/**
	* Defines WooCommerce image sizes on theme activation.
	*
	* @since 2.3.0
	*/
	public function woocommerce_image_dimensions_after_theme_setup() {

		global $pagenow;
	
		// Checks conditionally to see if we're activating the current theme and that WooCommerce is installed.
		if ( ! isset( $_GET['activated'] ) || 'themes.php' !== $pagenow || ! class_exists( 'WooCommerce' ) ) { // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- low risk, follows official snippet at https://goo.gl/nnHHQa.
			return;
		}
	
		$this->update_woocommerce_image_dimensions();

	}
	
	/**
	* Defines the WooCommerce image sizes on WooCommerce activation.
	*
	* @since 2.3.0
	*
	* @param string $plugin The path of the plugin being activated.
	*/
	public function woocommerce_image_dimensions_after_woo_activation( $plugin ) {

		// Checks to see if WooCommerce is being activated.
		if ( 'woocommerce/woocommerce.php' !== $plugin ) {
			return;
		}
	
		$this->update_woocommerce_image_dimensions();

	}
	
	/**
	* Filters the WooCommerce gallery image dimensions.
	*
	* @since 2.6.0
	*
	* @param array $size The gallery image size and crop arguments.
	* @return array The modified gallery image size and crop arguments.
	*/
	public function gallery_image_thumbnail( $size ) {

		$size = array(
			'width'  => 180,
			'height' => 180,
			'crop'   => 1,
		);

		return $size;

	}
	
	/**
	* Updates WooCommerce image dimensions.
	*
	* @since 2.3.0
	*/
	private function update_woocommerce_image_dimensions() {

		// Updates image size options.
		update_option( 'woocommerce_single_image_width', 655 );    // Single product image.
		update_option( 'woocommerce_thumbnail_image_width', 500 ); // Catalog image.
	
		// Updates image cropping option.
		update_option( 'woocommerce_thumbnail_cropping', '1:1' );
	
	}
	
	/**
	* Adds the themes's custom CSS to the WooCommerce stylesheet.
	*
	* @since 2.3.0
	*
	* @return string CSS to be outputted after the theme's custom WooCommerce stylesheet.
	*/
	public function woocommerce_css() {
		
		if(is_woocommerce()) {
			wp_enqueue_style('pep-woo',THEME_DIR.'/assets/css/woocommerce.css',array(),filemtime(__FILE__));
		}
	
		$color_link   = get_theme_mod( 'pep_link_color', $this->customizer->customizer_get_default_link_color() );
		$color_accent = get_theme_mod( 'pep_accent_color', $this->customizer->customizer_get_default_accent_color() );
		$color_button   = get_theme_mod( 'pep_button_color', $this->customizer->customizer_get_default_button_color() );
		$color_button_accent = get_theme_mod( 'pep_button_accent_color', $this->customizer->customizer_get_default_button_accent_color() );
	
		$woo_css = '';
	
		$woo_css .= ( $this->customizer->customizer_get_default_link_color() !== $color_link ) ? sprintf(
			'
	
			.woocommerce div.product p.price,
			.woocommerce div.product span.price,
			.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover,
			.woocommerce div.product .woocommerce-tabs ul.tabs li a:focus,
			.woocommerce ul.products li.product h3:hover,
			.woocommerce ul.products li.product .price,
			.woocommerce .woocommerce-breadcrumb a:hover,
			.woocommerce .woocommerce-breadcrumb a:focus,
			.woocommerce .widget_layered_nav ul li.chosen a::before,
			.woocommerce .widget_layered_nav_filters ul li a::before,
			.woocommerce .widget_rating_filter ul li.chosen a::before {
				color: %s;
			}
	
		',
			$color_link
		) : '';
		
		$woo_css .= ( $this->customizer->customizer_get_default_accent_color() !== $color_accent ) ? sprintf(
			'
			.woocommerce a.button:hover,
			.woocommerce a.button:focus,
			.woocommerce a.button.alt:hover,
			.woocommerce a.button.alt:focus,
			.woocommerce button.button:hover,
			.woocommerce button.button:focus,
			.woocommerce button.button.alt:hover,
			.woocommerce button.button.alt:focus,
			.woocommerce input.button:hover,
			.woocommerce input.button:focus,
			.woocommerce input.button.alt:hover,
			.woocommerce input.button.alt:focus,
			.woocommerce input[type="submit"]:hover,
			.woocommerce input[type="submit"]:focus,
			.woocommerce span.onsale,
			.woocommerce #respond input#submit:hover,
			.woocommerce #respond input#submit:focus,
			.woocommerce #respond input#submit.alt:hover,
			.woocommerce #respond input#submit.alt:focus,
			.woocommerce.widget_price_filter .ui-slider .ui-slider-handle,
			.woocommerce.widget_price_filter .ui-slider .ui-slider-range {
				background-color: %1$s;
				color: %2$s;
			}
	
			.woocommerce-info,
			.woocommerce-message {
				border-top-color: %1$s;
			}
	
			.woocommerce-info::before,
			.woocommerce-message::before {
				color: %1$s;
			}
	
		',
			$color_accent,
			$this->customizer->color_contrast( $color_accent )
		) : '';
		
		$woo_css.='body.woocommerce-checkout #customer_details .woocommerce-shipping-fields #ship-to-different-address:before{content:"'.__('Shipping','woocommerce').'";}
		.woocommerce form .woocommerce-account-fields:before{content:"'.__('Create account','woocommerce').'";}
		.woocommerce-checkout #payment ul.wc_payment_methods:before {content:"'.__('Payment methods','woocommerce').'";}
		';
		
		
		$woo_css .= ( $this->customizer->customizer_get_default_button_color() !== $color_button ) ? sprintf(
			'
			.woocommerce a.button, 
			.woocommerce a.button.alt, 
			.woocommerce button.button, 
			.woocommerce button.button.alt, 
			.woocommerce input.button, 
			.woocommerce input.button.alt, 
			.woocommerce input.button[type="submit"], 
			.woocommerce #respond input#submit, 
			.woocommerce #respond input#submit.alt {
				background-color: %1$s;
				color: %2$s;
			}
	
		',
			$color_button,
			$this->customizer->color_contrast( $color_button )
		) : '';
		
		$woo_css .= ( $this->customizer->customizer_get_default_button_accent_color() !== $color_button_accent ) ? sprintf(
			'
			.woocommerce a.button:hover, 
			.woocommerce a.button.alt:hover, 
			.woocommerce button.button:hover, 
			.woocommerce button.button.alt:hover, 
			.woocommerce input.button:hover, 
			.woocommerce input.button.alt:hover, 
			.woocommerce input.button[type="submit"]:hover, 
			.woocommerce #respond input#submit:hover, 
			.woocommerce #respond input#submit.alt:hover,
			.woocommerce a.button:focus, 
			.woocommerce a.button.alt:focus, 
			.woocommerce button.button:focus, 
			.woocommerce button.button.alt:focus, 
			.woocommerce input.button:focus, 
			.woocommerce input.button.alt:focus, 
			.woocommerce input.button[type="submit"]:focus, 
			.woocommerce #respond input#submit:focus, 
			.woocommerce #respond input#submit.alt:focus{
				background-color: %1$s !important;
				color: %2$s !important;
			}
		',
			$color_button_accent,
			$this->customizer->color_contrast( $color_button_accent )
		) : '';
		
		/*
		.woocommerce a.button, .woocommerce a.button.alt, .woocommerce button.button, .woocommerce button.button.alt, .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button[type="submit"], .woocommerce #respond input#submit, .woocommerce #respond input#submit.alt
		*/
		
		if ( $woo_css ) {
			wp_add_inline_style( 'woocommerce-styles', $woo_css );
		}
		
	
	}
}