<?php
/*
 * Customizer output functionality
 * This file adds the required CSS to the front end to the PEP Theme.
 */
namespace PEP;

class Output extends Customizer
{
   
    public function __construct()
    {
		add_action( 'wp_enqueue_scripts', array($this,'enqueue_customizer_css') );
	}
	
	/**
	* Checks the settings for the link color, and accent color.
	* If any of these value are set the appropriate CSS is output.
	*
	* @since 2.2.3
	*/
	public function enqueue_customizer_css() {

		$handle = defined( 'THEME_NAME' ) && THEME_NAME ? sanitize_title_with_dashes( THEME_NAME ) : 'PEP';
	
		$color_link   = get_theme_mod( 'pep_link_color', $this->customizer_get_default_link_color() );
		$color_accent = get_theme_mod( 'pep_accent_color', $this->customizer_get_default_accent_color() );
		$bgcolor_footerwidgets = get_theme_mod( 'pep_footerwidget_bgcolor', $this->customizer_get_default_accent_color() );
		$color_footerwidgets = get_theme_mod( 'pep_footerwidget_accent_color', $this->customizer_get_default_accent_color() );
		$bgcolor_footer = get_theme_mod( 'pep_footer_bgcolor', '#ffffff' );
		$color_accent_footer = get_theme_mod( 'pep_footer_accent_color', $this->customizer_get_default_accent_color() );
		$button_color = get_theme_mod( 'pep_button_color', $this->customizer_get_default_accent_color() );
		$pep_slider_controls = get_theme_mod( 'pep_slider_controls', $this->customizer_get_default_accent_color() );
		
		
		$logo         = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
	
		if ( $logo ) {
			$logo_height           = absint( $logo[2] );
			$logo_max_width        = get_theme_mod( 'pep_logo_width', 350 );
			$logo_width            = absint( $logo[1] );
			$logo_ratio            = $logo_width / max( $logo_height, 1 );
			$logo_effective_height = min( $logo_width, $logo_max_width ) / max( $logo_ratio, 1 );
			$logo_padding          = max( 0, ( 60 - $logo_effective_height ) / 2 );
		}
	
		$css = '';
	
		
		
		$css .= ( $this->customizer_get_default_link_color() !== $button_color ) ? sprintf(
			'
	
			.entry-content .wp-block-button .wp-block-button__link {
				background-color: %s;
				color:%s;
			}
	
			',
			$button_color,
			$this->color_contrast( $button_color )
		) : '';
	
		$css .= ( $this->customizer_get_default_accent_color() !== $color_accent ) ? sprintf(
			'
	
			button:focus,
			button:hover,
			input[type="button"]:focus,
			input[type="button"]:hover,
			input[type="reset"]:focus,
			input[type="reset"]:hover,
			input[type="submit"]:focus,
			input[type="submit"]:hover,
			input[type="reset"]:focus,
			input[type="reset"]:hover,
			input[type="submit"]:focus,
			input[type="submit"]:hover,
			.button:focus,
			.button:hover {
				background-color: %1$s;
				color: %2$s;
			}
	
			@media only screen and (min-width: 960px) {
				.genesis-nav-menu > .menu-highlight > a:hover,
				.genesis-nav-menu > .menu-highlight > a:focus,
				.genesis-nav-menu > .menu-highlight.current-menu-item > a {
					background-color: %1$s;
					color: %2$s;
				}
			}
			',
			$color_accent,
			$this->color_contrast( $color_accent )
		) : '';
		
		$css .= ( $this->customizer_get_default_accent_color() !== $pep_slider_controls ) ? sprintf(
			'
			a[class*=carrousel__control__list__link]{
				border-color: %1$s;
			}
			
			a[class*=carrousel__control__list__link]:focus, 
			a[class*=carrousel__control__list__link]:hover, 
			a[class*=carrousel__control__list__link]:active, 
			a[class*=carrousel__control__list__link][aria-selected=true] {
				background-color: %1$s;
			}
			',
			$pep_slider_controls
		) : '';
	
		$css .= ( $this->customizer_get_default_accent_color() !== $bgcolor_footerwidgets ) ? sprintf(
			'
			#genesis-footer-widgets {
				background-color: %1$s;
				color: %2$s;
			}

			#genesis-footer-widgets a,
			#genesis-footer-widgets h2,
			#genesis-footer-widgets h3,
			#genesis-footer-widgets h4 {color: %2$s;}
	
			#genesis-footer-widgets .footer-widget-area:not(:first-child),
			body #genesis-footer-widgets ul li {border-color: %2$s;}
	
			body #genesis-footer-widgets a:hover,
			body #genesis-footer-widgets a:focus {color: %3$s;text-decoration:underline;}
			',
			$bgcolor_footerwidgets,
			$this->color_contrast( $bgcolor_footerwidgets ),
			$color_footerwidgets
		) : '';
		
		$css .= ( $this->customizer_get_default_accent_color() !== $bgcolor_footerwidgets ) ? sprintf(
			'
			#genesis-footer-widgets {
				background-color: %1$s;
				color: %2$s;
			}

			#genesis-footer-widgets a,
			#genesis-footer-widgets h2,
			#genesis-footer-widgets h3,
			#genesis-footer-widgets h4 {color: %2$s !important;}
	
			#genesis-footer-widgets .footer-widget-area:not(:first-child),
			body #genesis-footer-widgets ul li {border-color: %2$s !important;}
	
			body #genesis-footer-widgets a:hover,
			body #genesis-footer-widgets a:focus {color: %3$s !important;text-decoration:underline;}
			',
			$bgcolor_footerwidgets,
			$this->color_contrast( $bgcolor_footerwidgets ),
			$color_footerwidgets
		) : '';
	
		
	
		$css .= ( has_custom_logo() && ( 200 <= $logo_effective_height ) ) ?
			'
			.site-header {
				position: static;
			}
			'
		: '';
	
		$css .= ( has_custom_logo() && ( 350 !== $logo_max_width ) ) ? sprintf(
			'
			.wp-custom-logo .site-container .title-area {
				max-width: %spx;
			}
			',
			$logo_max_width
		) : '';
	
		// Place menu below logo and center logo once it gets big.
		$css .= ( has_custom_logo() && ( 600 <= $logo_max_width ) ) ?
			'
			.wp-custom-logo .title-area,
			.wp-custom-logo .menu-toggle,
			.wp-custom-logo .nav-primary {
				float: none;
			}
	
			.wp-custom-logo .title-area {
				margin: 0 auto;
				text-align: center;
			}
	
			@media only screen and (min-width: 960px) {
				.wp-custom-logo .nav-primary {
					text-align: center;
				}
	
				.wp-custom-logo .nav-primary .sub-menu {
					text-align: left;
				}
			}
			'
		: '';
	
		$css .= ( has_custom_logo() && $logo_padding && ( 1 < $logo_effective_height ) ) ? sprintf(
			'
			.wp-custom-logo .title-area {
				padding-top: %spx;
			}
			',
			$logo_padding + 5
		) : '';
	
		if ( $css ) {
			wp_add_inline_style( 'pep-theme', $css );
		}
	
	}
	
}	