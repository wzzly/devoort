<?php
/*
 * Customizer functionality - Colors
 */
namespace PEP\Customizer;

class Colors {

     public function __construct()
     {
        add_action('customize_register', array($this, 'add_customizer_colors'));
		add_action( 'wp_enqueue_scripts', array($this,'enqueue_customizer_css') );
		
		add_theme_support( 'editor-color-palette', $this->gutenberg_colors() );
     }

	 private function get_colors() {
		$colors=array(
		'notificationbar'=>array(
			'notifiy_bar_background'=>array(
				'default' => '',
				'label'       => __( 'Notification Bar Background Color', 'pep' ),
                'elements' => 'body #notification {background-color:%s;}',
			),
			'notifiy_bar_bottom_border'=>array(
				'default' => '',
				'label'       => __( 'Notification Bar Bottom Border Color', 'pep' ),
                'elements' => 'body #notification {border-bottom-color:%s;}',
			),
			'notifiy_bar_text'=>array(
				'default' => '',
				'label'       => __( 'Notification Bar Text Color', 'pep' ),
                'elements' => 'body #notification {color:%s;}',
			),
			'notifiy_bar_link'=>array(
				'default' => '',
				'label'       => __( 'Notification Bar Link Color', 'pep' ),
                'elements' => 'body #notification a{color:%s;}',
			),
			'notifiy_bar_link_hover'=>array(
				'default' => '',
				'label'       => __( 'Notification Bar Link Hover Color', 'pep' ),
                'elements' => 'body #notification a:hover,body #notification a:focus{color:%s;}',
			),
		),
		'topbar'=>array(
			'top_bar_background'=>array(
				'default' => '#333333',
				'label'       => __( 'Top Bar Background Color', 'pep' ),
                'elements' => 'body .topbar {background-color:%s;}',
			),
			'top_bar_bottom_border'=>array(
				'default' => '#111111',
				'label'       => __( 'Top Bar Bottom Border Color', 'pep' ),
                'elements' => 'body .topbar {border-bottom-color:%s;}',
			),
			'top_bar_text'=>array(
				'default' => '#ffffff',
				'label'       => __( 'Top Bar Text Color', 'pep' ),
                'elements' => 'body .topbar {color:%s;}',
			),
			'top_bar_link'=>array(
				'default' => '#0055AE',
				'label'       => __( 'Top Bar Link Color', 'pep' ),
                'elements' => 'body .topbar a{color:%s;}',
			),
			'top_bar_link_hover'=>array(
				'default' => '#111111',
				'label'       => __( 'Top Bar Link Hover Color', 'pep' ),
                'elements' => 'body .topbar a:hover,body .topbar a:focus{color:%s;}',
			),
		),
		'header'=>array(
			'header_background'=>array(
				'default' => '#ffffff',
				'label'       => __( 'Header Background Color', 'pep' ),
                'elements' => 'body .site-header{background-color:%s;}',
			),
			'header_bottom_border'=>array(
				'default' => '#ffffff',
				'label'       => __( 'Header Bottom Border Color', 'pep' ),
                'elements' => 'body .site-header{border-bottom-color:%s;}',
			),
			'breadcrumbs_text'=>array(
				'default' => '#666666',
				'label'       => __( 'Breadcrumbs Text Color', 'pep' ),
                'elements' => '',
			),
			'breadcrumbs_text_current'=>array(
				'default' => '#333333',
				'label'       => __( 'Breadcrumbs Text Active Color', 'pep' ),
                'elements' => '',
			),
		),
		'mainmenu'=>array(
			'navigation_bar_background'=>array(
				'default' => '#ffffff',
				'label'       => __( 'Navigation Bar Background Color', 'pep' ),
				'description' => __('Header bar style','pep'),
                'elements' => 'body .nav-primary{background-color:%s;}',
			),
			'navigation_bar_top_border'=>array(
				'default' => '#ccc',
				'label'       => __( 'Navigation Bar Top Border Color', 'pep' ),
				'description' => __('Header bar style','pep'),
                'elements' => 'body .nav-primary{border-color:%s;}',
			),
			'main_menu_text'=>array(
				'default' => '#111111',
				'label'       => __( 'Main Menu Link Color', 'pep' ),
                'elements' => 'body .nav-primary .genesis-nav-menu > li > a{color:%s;}',
			),
			'main_menu_text_hover'=>array(
				'default' => '#0055AE',
				'label'       => __( 'Main Menu Link Hover Color', 'pep' ),
                'elements' => 'body .nav-primary .genesis-nav-menu a:focus,body .nav-primary .genesis-nav-menu a:hover,body .nav-primary .genesis-nav-menu .current-menu-item > a,body .nav-primary .genesis-nav-menu .current-page-ancestor > a,body .nav-primary .genesis-nav-menu .current-menu-ancestor > a,body .nav-primary .genesis-nav-menu .current-menu-parent > a,body .nav-primary .genesis-nav-menu .sub-menu .current-menu-item > a:focus,body .nav-primary .genesis-nav-menu .sub-menu .current-menu-item > a:hover{color:%s;}',
			),
			'sub_menu_link'=>array(
				'default' => '#0055AE',
				'label'       => __( 'Sub Menu Link Color', 'pep' ),
                'elements' => 'body .nav-primary .genesis-nav-menu .sub-menu a {color:%s;}',
			),
			'sub_menu_link_hover'=>array(
				'default' => '#ffe',
				'label'       => __( 'Sub Menu Link Hover Color', 'pep' ),
                'elements' => 'body .nav-primary .genesis-nav-menu .sub-menu .current-menu-item > a,
    			body .nav-primary .genesis-nav-menu .sub-menu .current_page_item > a,
				body .nav-primary .genesis-nav-menu  .sub-menu .current-page-ancestor > a,
    			body .nav-primary .genesis-nav-menu  .sub-menu a:hover,
    			body .nav-primary .genesis-nav-menu  .sub-menu a:focus {color:%s;}',
			),
			'sub_menu_link_background'=>array(
				'default' => '#fff',
				'label'       => __( 'Sub Menu Link Background Color', 'pep' ),
                'elements' => 'body .nav-primary .genesis-nav-menu > li > .sub-menu .sub-menu a,body .nav-primary .genesis-nav-menu > li > .sub-menu > a {background-color:%s;}',
			),
			'sub_menu_link_background_hover'=>array(
				'default' => '#111',
				'label'       => __( 'Sub Menu Link Hover Background', 'pep' ),
                'elements' => '
    			body .nav-primary .genesis-nav-menu .sub-menu .current-menu-item a,
    			body .nav-primary .genesis-nav-menu .sub-menu .current_page_item a,
    			body .nav-primary .genesis-nav-menu .sub-menu a:hover,
    			body .nav-primary .genesis-nav-menu .sub-menu a:focus {background-color:%s;}',
			),
		),
		'body'=>array(
			'body_background'=>array(
				'default' => '#ffffff',
				'label'       => __( 'Body Background Color', 'pep' ),
                'elements' => 'body .site-container {background-color:%s;}',
			),
			'h1_color'=>array(
				'default' => '#111111',
				'label'       => __( 'H1 Page Title Color', 'pep' ),
                'elements' => 'body .site-inner h1 {color:%s}',
			),
			'h2_color'=>array(
				'default' => '#111111',
				'label'       => __( 'H2 Subtitle Color', 'pep' ),
                'elements' => 'body .site-inner h2 {color:%s}',
			),
			'h3_color'=>array(
				'default' => '#333333',
				'label'       => __( 'H3 Subtitle Color', 'pep' ),
                'elements' => 'body .site-inner h3,.woocommerce-checkout #payment ul.wc_payment_methods:before {color:%s}',
			),
            'h4_color'=>array(
				'default' => '#555555',
				'label'       => __( 'H4 Subtitle Color', 'pep' ),
                'elements' => 'body .site-inner h4 {color:%s}',
			),
			'text_color'=>array(
				'default' => '#252525',
				'label'       => __( 'Text Color', 'pep' ),
                'elements' => 'body .site-inner {color:%s}',
			),
			'body_link'=>array(
				'default' => '#0055AE',
				'label'       => __( 'Link Color', 'pep' ),
                'elements' => 'body .site-inner a{color:%s;}',
			),
			'body_link_hover'=>array(
				'default' => '#111111',
				'label'       => __( 'Link Hover Color', 'pep' ),
                'elements' => 'body .site-inner a:hover{color:%s;}body .site-inner a:focus{background-color:%s;color:#fff;}',
			),
			'selection_color'=>array(
				'default' => '#fff',
				'label'       => __( 'Text selection color', 'pep' ),
                'elements' => '::-moz-selection{ color: %s; }::selection{color: %s;}',
			),
			'selection_background'=>array(
				'default' => '#0073e5',
				'label'       => __( 'Text selection background color', 'pep' ),
                'elements' => '::-moz-selection{background-color: %s;} ::selection{background-color: %s;}',
			),
		),
		'forms'=>array(
			'form_input'=>array(
				'default' => '#ffffff',
				'label'       => __( 'Input Box Background Color', 'pep' ),
                'elements' => 'body .content input,body .content select,body .content textarea{background-color:%s;}',
			),
			'form_input_focus'=>array(
				'default' => '#eeeeee',
				'label'       => __( 'Input Box Background Focus Color', 'pep' ),
                'elements' => 'body .content input:hover,body .content select:hover,body .content textarea:hover,body .content input:focus,body .content select:focus,body .content textarea:focus{background-color:%s;}',
			),
			'form_input_border'=>array(
				'default' => '#666666',
				'label'       => __( 'Input Box Border Color', 'pep' ),
                'elements' => 'body .content input,body .content select,body .content textarea{border-color:%s;}',
			),
			'form_input_border_hover'=>array(
				'default' => '#111111',
				'label'       => __( 'Input Box Border Hover Color', 'pep' ),
                'elements' => 'body .content input:hover,body .content select:hover,body .content textarea:hover,body .content input:focus,body .content select:focus,body .content textarea:focus{border-color:%s;}',
			),
			'form_input_text'=>array(
				'default' => '#666666',
				'label'       => __( 'Input Box Text Color', 'pep' ),
                'elements' => 'body .content input,body .content select,body .content textarea{color:%s;}',
			),
			'form_input_text_focus'=>array(
				'default' => '#111111',
				'label'       => __( 'Input Box Text Focus Color', 'pep' ),
                'elements' => 'body .content input:hover,body .content select:hover,body .content textarea:hover,body .content input:focus,body .content select:focus,body .content textarea:focus{color:%s;}',
			),
			'form_label'=>array(
				'default' => '#111111',
				'label'       => __( 'Label Color', 'pep' ),
                'elements' => 'body .site-inner label{color:%s;}',
			),
			'form_label_required'=>array(
				'default' => '#cc0000',
				'label'       => __( 'Label Required (*) Color', 'pep' ),
                'elements' => 'body .site-inner label .required{color:%s;}',
			),
			'form_description'=>array(
				'default' => '#666666',
				'label'       => __( 'Field Description Color', 'pep' ),
                'elements' => 'body .gform_wrapper .field_description_below .gfield_description{color:%s;}',
			),
			'button_bck'=>array(
				'default' => '#666666',
				'label'       => __( 'Button Background Color', 'pep' ),
                'elements' => '.gform_wrapper .gform_footer input.button, .gform_wrapper .gform_footer input[type=submit]:not([class*="background-color"]), .gform_wrapper .gform_page_footer input.button, .gform_wrapper .gform_page_footer input[type=submit]:not([class*="background-color"]), .gb-cta-button .gb-button, .wp-block-button .wp-block-button__link:not([class*="background-color"]),.content button,.content input[type="button"],.content input[type="reset"],.content input[type="submit"]:not([class*="background-color"]),.content .button{background-color:%s;;border-color:%s}',
			),
			'button_bck_hover'=>array(
				'default' => '#666666',
				'label'       => __( 'Button Background Hover Color', 'pep' ),
                'elements' => '.gform_wrapper .gform_footer input.button:hover, .gform_wrapper .gform_footer input[type=submit]:not([class*="background-color"]):hover, .gform_wrapper .gform_page_footer input.button:hover, .gform_wrapper .gform_page_footer input[type=submit]:hover,.gb-cta-button .gb-button:hover,.wp-block-button .wp-block-button__link:not([class*="background-color"]):hover,.gform_wrapper .gform_footer input.button:focus, .gform_wrapper .gform_footer input[type=submit]:not([class*="background-color"]):focus, .gform_wrapper .gform_page_footer input.button:focus, .gform_wrapper .gform_page_footer input[type=submit]:focus,.gb-cta-button .gb-button:focus,.wp-block-button .wp-block-button__link:not([class*="background-color"]):focus,.content button:focus, button:hover,.content input[type="button"]:not([class*="background-color"]):focus,.content input[type="button"]:not([class*="background-color"]):hover,.content input[type="reset"]:focus,.content input[type="reset"]:hover,.content input[type="submit"]:not([class*="background-color"]):focus,.content input[type="submit"]:not([class*="background-color"]):hover,.content .button:focus,.content .button:hover{background-color:%s;border-color:%s}',
			),
			'button_text'=>array(
				'default' => '#666666',
				'label'       => __( 'Button Text Color', 'pep' ),
                'elements' => '.gform_wrapper .gform_footer input.button, .gform_wrapper .gform_footer input[type=submit]:not([class*="background-color"]), .gform_wrapper .gform_page_footer input.button, .gform_wrapper .gform_page_footer input[type=submit], .gb-cta-button .gb-button, .wp-block-button .wp-block-button__link:not([class*="background-color"]),.content button,.content input[type="button"],.content input[type="reset"],.content input[type="submit"]:not([class*="background-color"]),.content .button{color:%s;}',
			),
			'button_text_hover'=>array(
				'default' => '#666666',
				'label'       => __( 'Button Text Hover Color', 'pep' ),
                'elements' => '.gform_wrapper .gform_footer input.button:hover, .gform_wrapper .gform_footer input[type=submit]:not([class*="background-color"]):hover, .gform_wrapper .gform_page_footer input.button:hover, .gform_wrapper .gform_page_footer input[type=submit]:hover,.gb-cta-button .gb-button:hover,.wp-block-button .wp-block-button__link:not([class*="background-color"]):hover,.gform_wrapper .gform_footer input.button:focus, .gform_wrapper .gform_footer input[type=submit]:not([class*="background-color"]):focus, .gform_wrapper .gform_page_footer input.button:focus, .gform_wrapper .gform_page_footer input[type=submit]:focus,.gb-cta-button .gb-button:focus,.wp-block-button .wp-block-button__link:not([class*="background-color"]):focus,.content button:focus, button:hover,.content input[type="button"]:not([class*="background-color"]):focus,.content input[type="button"]:not([class*="background-color"]):hover,.content input[type="reset"]:focus,.content input[type="reset"]:hover,.content input[type="submit"]:not([class*="background-color"]):focus,.content input[type="submit"]:not([class*="background-color"]):hover,.content .button:focus,.content .button:hover{color:%s;}',
			),
			'placeholder'=>array(
				'default' => '#666',
				'label'       => __( 'Placeholder color', 'pep' ),
                'elements' => '::-ms-input-placeholder {color: %s;opacity: 1;}:-ms-input-placeholder {color: %s;opacity: 1;}::placeholder {color: %s;opacity: 1;}',
			),
		),
		'footer'=>array(
			'footer_background'=>array(
				'default' => '#dddddd',
				'label'       => __( 'Footer Background Color', 'pep' ),
                'elements' => 'body #genesis-footer-widgets {background-color:%s;}',
			),
			'footer_headings'=>array(
				'default' => '#111111',
				'label'       => __( 'Footer Heading (H3 / H4) Color', 'pep' ),
                'elements' => 'body #genesis-footer-widgets h2,body #genesis-footer-widgets h3,body #genesis-footer-widgets h4 {color:%s;}',
			),
			'footer_text'=>array(
				'default' => '#111111',
				'label'       => __( 'Footer Text Color', 'pep' ),
                'elements' => 'body #genesis-footer-widgets {color:%s;}',
			),
			'footer_link'=>array(
				'default' => '#111111',
				'label'       => __( 'Footer Link Color', 'pep' ),
                'elements' => 'body #genesis-footer-widgets .wrap a {color:%s;}',
			),
			'footer_link_hover'=>array(
				'default' => '#0055AE',
				'label'       => __( 'Footer Link Hover Color', 'pep' ),
                'elements' => 'body #genesis-footer-widgets .wrap a:hover,body #genesis-footer-widgets .wrap a:focus{color:%s;}',
			),
			'footer_divider'=>array(
				'default' => '#aaaaaa',
				'label'       => __( 'Footer Divider Color', 'pep' ),
                'elements' => 'body #genesis-footer-widgets .footer-widget-area:not(:first-child),body #genesis-footer-widgets ul li{border-color:%s;}',
			),
			'copyright_background'=>array(
				'default' => '#ffffff',
				'label'       => __( 'Copyright Background Color', 'pep' ),
                'elements' => 'body .site-footer {background-color:%s;}',
			),
			'copyright_text'=>array(
				'default' => '#666666',
				'label'       => __( 'Copyright Text Color', 'pep' ),
                'elements' => 'body .site-footer {color:%s;}',
			),
			'copyright_link'=>array(
				'default' => '#0055ae',
				'label'       => __( 'Copyright Link Color', 'pep' ),
                'elements' => 'body .site-footer a{color:%s;}',
			),
			'copyright_link_hover'=>array(
				'default' => '#444444',
				'label'       => __( 'Copyright Link Hover Color', 'pep' ),
                'elements' => '.genesis-nav-menu a:focus, .genesis-nav-menu a:hover, .genesis-nav-menu .current-menu-item > a, .genesis-nav-menu .sub-menu .current-menu-item > a:focus, .genesis-nav-menu .sub-menu .current-menu-item > a:hover,body .site-footer a:hover,body .site-footer a:focus{color:%s;}',
			),
		),
		'gutenberg'=>array(
			'gutenberg_1'=>array(
				'default' => '#ffffff',
				'label'       => __( 'Gutenberg Color #1', 'pep' ),
                'elements' => '.has-gutenberg-1-color{--color:%s;}
				:not(.is-style-outline) > .has-gutenberg-1-background-color{--bgcolor:%s;}
				.is-style-outline > .has-gutenberg-1-background-color{--bordercolor:%s;}',
			),
			'gutenberg_2'=>array(
				'default' => '#111111',
				'label'       => __( 'Gutenberg Color #2', 'pep' ),
                 'elements' => '.has-gutenberg-2-color{--color:%s;}
				:not(.is-style-outline) > .has-gutenberg-2-background-color{--bgcolor:%s;}
				.is-style-outline > .has-gutenberg-2-background-color{--bordercolor:%s;}',
			),
			'gutenberg_3'=>array(
				'default' => '#666666',
				'label'       => __( 'Gutenberg Color #3', 'pep' ),
                'elements' => '.has-gutenberg-3-color{--color:%s;}
				:not(.is-style-outline) > .has-gutenberg-3-background-color{--bgcolor:%s;}
				.is-style-outline > .has-gutenberg-3-background-color{--bordercolor:%s;}',
			),
			'gutenberg_4'=>array(
				'default' => '#eeeeee',
				'label'       => __( 'Gutenberg Color #4', 'pep' ),
                 'elements' => '.has-gutenberg-4-color{--color:%s;}
				:not(.is-style-outline) > .has-gutenberg-4-background-color{--bgcolor:%s;}
				.is-style-outline > .has-gutenberg-4-background-color{--bordercolor:%s;}',
			),
			'gutenberg_5'=>array(
				'default' => '#0055AE',
				'label'       => __( 'Gutenberg Color #5', 'pep' ),
                 'elements' => '.has-gutenberg-5-color{--color:%s;}
				:not(.is-style-outline) > .has-gutenberg-5-background-color{--bgcolor:%s;}
				.is-style-outline > .has-gutenberg-5-background-color{--bordercolor:%s;}',
			),
			'gutenberg_6'=>array(
				'default' => '#ffda01',
				'label'       => __( 'Gutenberg Color #6', 'pep' ),
                 'elements' => '.has-gutenberg-6-color{--color:%s;}
				:not(.is-style-outline) > .has-gutenberg-6-background-color{--bgcolor:%s;}
				.is-style-outline > .has-gutenberg-6-background-color{--bordercolor:%s;}',
			),
			'gutenberg_7'=>array(
				'default' => '#cc0000',
				'label'       => __( 'Gutenberg Color #7', 'pep' ),
                'elements' => '.has-gutenberg-7-color{--color:%s;}
				:not(.is-style-outline) > .has-gutenberg-7-background-color{--bgcolor:%s;}
				.is-style-outline > .has-gutenberg-7-background-color{--bordercolor:%s;}',
			),
			'gutenberg_8'=>array(
				'default' => '#fffffe',
				'label'       => __( 'Gutenberg Color #8', 'pep' ),
                'elements' => '.has-gutenberg-8-color{--color:%s;}
				:not(.is-style-outline) > .has-gutenberg-8-background-color{--bgcolor:%s;}
				.is-style-outline > .has-gutenberg-8-background-color{--bordercolor:%s;}',
			),
			'gutenberg_9'=>array(
				'default' => '#fffffe',
				'label'       => __( 'Gutenberg Color #9', 'pep' ),
                'elements' => '.has-gutenberg-9-color{--color:%s;}
				:not(.is-style-outline) > .has-gutenberg-9-background-color{--bgcolor:%s;}
				.is-style-outline > .has-gutenberg-9-background-color{--bordercolor:%s;}',
			),
			'gutenberg_10'=>array(
				'default' => '#fffffe',
				'label'       => __( 'Gutenberg Color #10', 'pep' ),
                'elements' => '.has-gutenberg-10-color{--color:%s;}
				:not(.is-style-outline) > .has-gutenberg-10-background-color{--bgcolor:%s;}
				.is-style-outline > .has-gutenberg-10-background-color{--bordercolor:%s;}',
			),
		)
		);
		if ( class_exists( 'WooCommerce' ) ) {
		$colors['woocommerce']=array(
				'wc_price'=>array(
					'default' => '#111',
					'label'       => __( 'WooCommerce Price Color', 'pep' ),
					'elements' =>'',
				),
				'wc_price_old'=>array(
					'default' => '#666',
					'label'       => __( 'WooCommerce Old Price Color', 'pep' ),
					'description' => 'Define color of old price (if sales price).',
					'elements' =>'',
				),
				'wc_button'=>array(
					'default' => '#ffda01',
					'label'       => __( 'WooCommerce Button Background', 'pep' ),
					'elements' => '.woocommerce a.button:not(.disabled), .woocommerce a.button.alt:not(.disabled), .woocommerce button.button:not([disabled]), .woocommerce button.button.alt:not(.disabled):not([disabled]), .woocommerce input.button:not(.disabled):not([disabled]), .woocommerce input.button.alt:not(.disabled):not([disabled]), .woocommerce input.button[type="submit"]:not(.disabled):not([disabled]), .woocommerce #respond input#submit:not(.disabled):not([disabled]), .woocommerce #respond input#submit.alt:not(.disabled):not([disabled]){background-color:%s !important;}',
				),
				'wc_button_hover'=>array(
					'default' => '#111',
					'label'       => __( 'WooCommerce Button Background Hover', 'pep' ),
					'elements' =>'.woocommerce a.button:not(.disabled):focus, .woocommerce a.button:not(.disabled):hover, .woocommerce a.button.alt:not(.disabled):focus, .woocommerce a.button.alt:not(.disabled):hover, .woocommerce button.button:not(.disabled):focus, .woocommerce button.button:not(.disabled):hover, .woocommerce button.button.alt:not(.disabled):focus, .woocommerce button.button.alt:not(.disabled):hover, .woocommerce input.button:not(.disabled):focus, .woocommerce input.button:not(.disabled):hover, .woocommerce input.button.alt:not(.disabled):focus, .woocommerce input.button.alt:not(.disabled):hover, .woocommerce input[type="submit"]:not(.disabled):focus, .woocommerce input[type="submit"]:not(.disabled):hover, .woocommerce #respond input#submit:not(.disabled):focus, .woocommerce #respond input#submit:not(.disabled):hover, .woocommerce #respond input#submit.alt:not(.disabled):focus, .woocommerce #respond input#submit.alt:not(.disabled):hover {background-color:%s !important;}',
				),
				'wc_button_text'=>array(
					'default' => '#111',
					'label'       => __( 'WooCommerce Button Text Color', 'pep' ),
					'elements' =>'.woocommerce a.button:not(.disabled), .woocommerce a.button.alt:not(.disabled), .woocommerce button.button:not(.disabled):not([disabled]), .woocommerce button.button.alt:not(.disabled):not([disabled]), .woocommerce input.button:not(.disabled):not([disabled]), .woocommerce input.button.alt:not(.disabled):not([disabled]), .woocommerce input.button[type="submit"]:not(.disabled):not([disabled]), .woocommerce #respond input#submit:not(.disabled):not([disabled]), .woocommerce #respond input#submit.alt:not(.disabled):not([disabled]) {color:%s !important;}',
				),
				'wc_button_text_hover'=>array(
					'default' => '#fff',
					'label'       => __( 'WooCommerce Button Text Hover Color', 'pep' ),
					'elements' =>'.woocommerce a.button:not(.disabled):focus, .woocommerce a.button:not(.disabled):hover, .woocommerce a.button.alt:not(.disabled):focus, .woocommerce a.button.alt:not(.disabled):hover, .woocommerce button.button:not(.disabled):focus, .woocommerce button.button:not(.disabled):hover, .woocommerce button.button.alt:not(.disabled):focus, .woocommerce button.button.alt:not(.disabled):hover, .woocommerce input.button:not(.disabled):focus, .woocommerce input.button:not(.disabled):hover, .woocommerce input.button.alt:not(.disabled):focus, .woocommerce input.button.alt:not(.disabled):hover, .woocommerce input[type="submit"]:not(.disabled):focus, .woocommerce input[type="submit"]:not(.disabled):hover, .woocommerce #respond input#submit:not(.disabled):focus, .woocommerce #respond input#submit:not(.disabled):hover, .woocommerce #respond input#submit.alt:not(.disabled):focus, .woocommerce #respond input#submit.alt:not(.disabled):hover {color:%s !important;}',
				),
				'wc_add_cart'=>array(
					'default' => '#ffda01',
					'label'       => __( 'WooCommerce Add to Cart Button Background', 'pep' ),
					'elements' =>'',
				),
				'wc_add_cart_hover'=>array(
					'default' => '#111',
					'label'       => __( 'WooCommerce Add to Cart Button Background Hover', 'pep' ),
					'elements' =>'',
				),
				'wc_add_cart_text'=>array(
					'default' => '#111',
					'label'       => __( 'WooCommerce Add to Cart Button Text Color', 'pep' ),
					'elements' =>'',
				),
				'wc_add_cart_text_hover'=>array(
					'default' => '#fff',
					'label'       => __( 'WooCommerce Add to Cart Button Text Hover Color', 'pep' ),
					'elements' =>'',
				),
				'wc_cart_icon'=>array(
					'default' => '#385163',
					'label'       => __( 'Cart Icon Color', 'pep' ),
					'description' => 'Cart Icon color in Main navigation',
					'elements' =>'',
				),
				'wc_cart_icon_hover'=>array(
					'default' => '#385163',
					'label'       => __( 'Cart Icon Hover Color', 'pep' ),
					'elements' =>'',
				),
				'wc_cart_icon_items'=>array(
					'default' => '#ffda01',
					'label'       => __( 'Cart Icon Counter Color', 'pep' ),
					'elements' =>'',
				),
				'wc_cart_icon_items_hover'=>array(
					'default' => '#111',
					'label'       => __( 'Cart Icon Counter Hover Color', 'pep' ),
					'elements' =>'',
				),

			);
		}
		
		return $colors;
	}

	 public function add_customizer_colors($wp_customize) {
		$wp_customize->add_panel('colors',array(
			'title'=>__('Colors','pep'),
			'description'=>__('Define theme colors','pep'),
			'priority'=>30,
		));
		
		$wp_customize->add_section('notificationbar',array(
			'title'=>__('Notification Bar','pep'),
			'priority'=>4,
			'panel'=>'colors',
			'description'=>__('Define notification colors','pep'),
		));

		
		$wp_customize->add_section('topbar',array(
			'title'=>__('Top bar','pep'),
			'priority'=>5,
			'panel'=>'colors',
			'description'=>__('Define topbar colors','pep'),
		));

		$wp_customize->add_section('header',array(
			'title'=>__('Header','pep'),
			'priority'=>10,
			'panel'=>'colors',
			'description'=>__('Define header colors','pep'),
		));

		$wp_customize->add_section('mainmenu',array(
			'title'=>__('Main Menu','pep'),
			'priority'=>20,
			'panel'=>'colors',
			'description'=>__('Define Main Menu colors','pep'),
		));

		$wp_customize->add_section('body',array(
			'title'=>__('Body','pep'),
			'priority'=>30,
			'panel'=>'colors',
			'description'=>__('Define Body colors','pep'),
		));

		$wp_customize->add_section('forms',array(
			'title'=>__('Forms','pep'),
			'priority'=>40,
			'panel'=>'colors',
			'description'=>__('Define Form colors','pep'),
		));

		$wp_customize->add_section('footer',array(
			'title'=>__('Footer','pep'),
			'priority'=>50,
			'panel'=>'colors',
			'description'=>__('Define Footer colors','pep'),
		));

		$wp_customize->add_section('gutenberg',array(
			'title'=>__('Gutenberg','pep'),
			'priority'=>900,
			'panel'=>'colors',
			'description'=>__('Define colors shown in Gutenberg Editor for background and texts.','pep'),
		));

		$colors=$this->get_colors();


		if ( class_exists( 'WooCommerce' ) ) {
			$wp_customize->add_section('woocommerce',array(
				'title'=>__('WooCommerce','pep'),
				'priority'=>990,
				'panel'=>'colors',
				'description'=>__('Define WooCommerce colors','pep'),
			));

			
		}

		foreach($colors as $section=>$color_settings) {
			foreach($color_settings as $id => $color) {

				$description='';
				if(isset($color['description']))	$description=$color['description'];

				$wp_customize->add_setting(
					$id,
					array(
						'default'           => $color['default'],
						'sanitize_callback' => 'sanitize_hex_color',
					)
				);

				$wp_customize->add_control(
					new \WP_Customize_Color_Control(
						$wp_customize,
						$id,
						array(
							'description' => $description,
							'label'       => $color['label'],
							'section'     => $section,
							'settings'    => $id,
						)
					)
				);
			}
		}

	 }

	public function enqueue_customizer_css() {
		$css = '';

		$colors=$this->get_colors();
		//echo '<pre>';

		foreach($colors as $color) {
			foreach ($color as $id=>$options) {
				$current=get_theme_mod( $id, $options['default']);

                if($current!=$options['default'] && $options['elements']!="") {
                    $css.=sprintf($options['elements'],$current,$current,$current);
                }

			}
		}

		if ( $css ) {
			wp_add_inline_style( 'pep-bc', $css );
		}
	}
	
	public function gutenberg_colors() {
		
		$colors=$this->get_colors();
		$colors=$colors['gutenberg'];
		
		$output=array();
		foreach($colors as $slug=>$color) {
			$output[]=array(
				'name'=>$color['label'],
				'slug'=>$slug,
				'color'=>get_theme_mod( $slug, $color['default']),
			);
		}		
		return $output;
	}
}