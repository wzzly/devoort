<?php
/*
 * WooCommerce functionality
 */
namespace PEP;

class WooOutput extends Template
{
	public function __construct()
    {
		
		//add_filter( 'woocommerce_enqueue_styles', array($this,'woocommerce_styles') );
		
		add_action( 'wp_enqueue_scripts', array($this,'woocommerce_css') );
		
	}

	/**
	* Enqueues the theme's custom WooCommerce styles to the WooCommerce plugin.
	*
	* @param array $enqueue_styles The WooCommerce styles to enqueue.
	* @since 2.3.0
	*
	* @return array Modified WooCommerce styles to enqueue.
	*/
	public function woocommerce_styles( $enqueue_styles ) {

		$enqueue_styles['woocommerce-styles'] = array(
			'src'     => get_stylesheet_directory_uri() . '/assets/css/woocommerce.css',
			'deps'    => '',
			'version' => '1',
			'media'   => 'screen',
		);

		return $enqueue_styles;

	}

	

	/**
	* Adds the themes's custom CSS to the WooCommerce stylesheet.
	*
	* @since 2.3.0
	*
	* @return string CSS to be outputted after the theme's custom WooCommerce stylesheet.
	*/
	public function woocommerce_css() {
	
		$color_link   = get_theme_mod( 'pep_link_color', $this->customizer_get_default_link_color() );
		$color_accent = get_theme_mod( 'pep_accent_color', $this->customizer_get_default_accent_color() );
		$color_button   = get_theme_mod( 'pep_button_color', $this->customizer_get_default_button_color() );
		$color_button_accent = get_theme_mod( 'pep_button_accent_color', $this->customizer_get_default_button_accent_color() );
	
		$woo_css = '';
	
		$woo_css .= ( $this->customizer_get_default_link_color() !== $color_link ) ? sprintf(
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
		
		$woo_css .= ( $this->customizer_get_default_accent_color() !== $color_accent ) ? sprintf(
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
			$this->color_contrast( $color_accent )
		) : '';
		
		$woo_css.='body.woocommerce-checkout #customer_details .woocommerce-shipping-fields #ship-to-different-address:before{content:"'.__('Shipping','woocommerce').'";}
		.woocommerce form .woocommerce-account-fields:before{content:"'.__('Create account','woocommerce').'";}
		.woocommerce-checkout #payment ul.wc_payment_methods:before {content:"'.__('Payment methods','woocommerce').'";}
		';
		
		
		$woo_css .= ( $this->customizer_get_default_button_color() !== $color_button ) ? sprintf(
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
			$this->color_contrast( $color_button )
		) : '';
		
		$woo_css .= ( $this->customizer_get_default_button_accent_color() !== $color_button_accent ) ? sprintf(
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
			$this->color_contrast( $color_button_accent )
		) : '';
		
		/*
		.woocommerce a.button, .woocommerce a.button.alt, .woocommerce button.button, .woocommerce button.button.alt, .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button[type="submit"], .woocommerce #respond input#submit, .woocommerce #respond input#submit.alt
		*/
		
		if ( $woo_css ) {
			wp_add_inline_style( 'woocommerce-styles', $woo_css );
		}
		
	
	}
			
}