<?php
/*
 * Customizer functionality
 */
namespace PEP;

class Customizer extends Template
{
   
    public function __construct()
    {
		//add_action( 'customize_register', array($this,'customizer_register') );
		//add_action( 'customize_register', array($this,'footer_widgets') );
	}
	
	/**
	* Registers settings and controls with the Customizer.
	*
	* @since 2.2.3
	*
	* @param WP_Customize_Manager $wp_customize Customizer object.
	*/
	public function customizer_register( $wp_customize ) {
	
		/* Link colors */
		$wp_customize->add_setting(
			'pep_link_color',
			array(
				'default'           => $this->customizer_get_default_link_color(),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
	
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'pep_link_color',
				array(
					'description' => __( 'Change the color of post info links, hover color of linked titles, hover color of menu items, and more.', 'pep' ),
					'label'       => __( 'Link Color', 'pep' ),
					'section'     => 'colors',
					'settings'    => 'pep_link_color',
				)
			)
		);
	
		$wp_customize->add_setting(
			'pep_accent_color',
			array(
				'default'           => $this->customizer_get_default_accent_color(),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
	
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'pep_accent_color',
				array(
					'description' => __( 'Change the default hover color for button links, the menu button, and submit buttons. This setting does not apply to buttons created with the Buttons block.', 'pep' ),
					'label'       => __( 'Accent Color', 'pep' ),
					'section'     => 'colors',
					'settings'    => 'pep_accent_color',
				)
			)
		);
		
		$wp_customize->add_setting(
			'pep_slider_controls',
			array(
				'default'           => $this->customizer_get_default_accent_color(),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
	
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'pep_slider_controls',
				array(
					'description' => __( 'Change the color of slider controls.', 'pep' ),
					'label'       => __( 'Slider controls colors', 'pep' ),
					'section'     => 'colors',
					'settings'    => 'pep_slider_controls',
				)
			)
		);
		
		
		/*Button colors */
		$wp_customize->add_setting(
			'pep_button_color',
			array(
				'default'           => $this->customizer_get_default_button_color(),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
	
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'pep_button_color',
				array(
					'description' => __( 'Change the color of buttons.', 'pep' ),
					'label'       => __( 'Button Color', 'pep' ),
					'section'     => 'colors',
					'settings'    => 'pep_button_color',
				)
			)
		);
	
		$wp_customize->add_setting(
			'pep_button_accent_color',
			array(
				'default'           => $this->customizer_get_default_button_accent_color(),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
	
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'pep_button_accent_color',
				array(
					'description' => __( 'Change the default hover color for buttons.', 'pep' ),
					'label'       => __( 'Button Accent Color', 'pep' ),
					'section'     => 'colors',
					'settings'    => 'pep_button_accent_color',
				)
			)
		);		
		
	
		$wp_customize->add_setting(
			'pep_footerwidget_bgcolor',
			array(
				'default'           => $this->customizer_get_default_accent_color(),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
	
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'pep_footerwidget_bgcolor',
				array(
					'description' => __( 'Change the default background-color for footer widgets area.', 'pep' ),
					'label'       => __( 'Footer widgets background-color', 'pep' ),
					'section'     => 'colors',
					'settings'    => 'pep_footerwidget_bgcolor',
				)
			)
		);
	
		$wp_customize->add_setting(
			'pep_footerwidget_accent_color',
			array(
				'default'           => $this->customizer_get_default_accent_color(),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
	
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'pep_footerwidget_accent_color',
				array(
					'description' => __( 'Change the accent text color for footer widgets area.', 'pep' ),
					'label'       => __( 'Footer widgets accent color', 'pep' ),
					'section'     => 'colors',
					'settings'    => 'pep_footerwidget_accent_color',
				)
			)
		);

		$wp_customize->add_setting(
			'pep_footer_bgcolor',
			array(
				'default'           => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
	
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'pep_footer_bgcolor',
				array(
					'description' => __( 'Change the background color for footer area.', 'pep' ),
					'label'       => __( 'Footer background-color', 'pep' ),
					'section'     => 'colors',
					'settings'    => 'pep_footer_bgcolor',
				)
			)
		);
	
		$wp_customize->add_setting(
			'pep_footer_accent_color',
			array(
				'default'           => $this->customizer_get_default_accent_color(),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
	
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'pep_footer_accent_color',
				array(
					'description' => __( 'Change the accent text color for footer area.', 'pep' ),
					'label'       => __( 'Footer accent color', 'pep' ),
					'section'     => 'colors',
					'settings'    => 'pep_footer_accent_color',
				)
			)
		);
	
		$wp_customize->add_setting(
			'pep_logo_width',
			array(
				'default'           => 350,
				'sanitize_callback' => 'absint',
			)
		);
	
		// Add a control for the logo size.
		$wp_customize->add_control(
			'pep_logo_width',
			array(
				'label'       => __( 'Logo Width', 'pep' ),
				'description' => __( 'The maximum width of the logo in pixels.', 'pep' ),
				'priority'    => 9,
				'section'     => 'title_tagline',
				'settings'    => 'pep_logo_width',
				'type'        => 'number',
				'input_attrs' => array(
					'min' => 100,
				),
	
			)
		);
	
	}
	
	public function footer_widgets( $wp_customize ) {
		
		$wp_customize->add_setting(
			'pep_footer_widgets',
			array(
				'default'           => 1,
				'sanitize_callback' => 'absint',
			)
		);
	
		// Add a control for the logo size.
		$wp_customize->add_control(
			'pep_footer_widgets',
			array(
				'label'       => __( 'Show last footer widgets area below', 'pep' ),
				'description' => __( 'Show the last footer widget area 100% width below other footer widgets (screen-width > 960px).', 'pep' ),
				'priority'    => 100,
				'section'     => 'genesis_layout',
				'settings'    => 'pep_footer_widgets',
				'type'        => 'checkbox',
			)
		);
		
		$wp_customize->add_setting(
			'pep_footer_widgets_default',
			array(
				'default'           => 1,
				'sanitize_callback' => 'absint',
			)
		);
	
		// Add a control for the logo size.
		$wp_customize->add_control(
			'pep_footer_widgets_default',
			array(
				'label'       => __( 'Footer widgets default styling', 'pep' ),
				'description' => __( 'Use default PEP styling for footer widget area', 'pep' ),
				'priority'    => 110,
				'section'     => 'genesis_layout',
				'settings'    => 'pep_footer_widgets_default',
				'type'        => 'checkbox',
			)
		);
	}
}	