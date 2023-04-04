<?php
/*
 * Customizer functionality - Layout
 */
namespace PEP\Customizer;

class Layout {

     public function __construct()
     {
        add_action('customize_register', array($this, 'add_customizer_layout'));
		add_action('wp_enqueue_scripts', array($this,'enqueue_customizer_css'));
		add_filter('body_class', array($this,'body_class'));
     }
	 
	 public function get_layouts() {
		 $default_image_sizes = get_intermediate_image_sizes();
		 $image_sizes=array();
		 $image_sizes['no_thumb']=__('Hide thumbnail','pep');
		 foreach($default_image_sizes as $image_size) {
			 $image_sizes[$image_size]=$image_size;
		 }
		 
		 $default_content='<p><span class="large">Wij bouwen aan deze website</span></p><p>Nieuwsgierig? Neem dan even <a href="https://pepbc.nl/contact/">contact met ons</a> op.</p>';
		 
		 $layouts=array(
			'layout_general'=>array(
				'title'=>__('General','pep'),
				'controls'=>array(
					'container_width'=>array(
						'label'=>__('Container Max-Width (px)','pep'),
						'type'=>'number',
						'default'=>1140,
						'elements'=>'body .wp-block-group__inner-container,body .site-inner,body #genesis-footer-widgets .wrap,body #prefooter .wrap{max-width: %spx;}
						.entry-content .wp-block-media-text .wp-block-media-text__content {max-width:calc(%spx / 2);}',
					),
					'container_padding'=>array(
						'label'=>__('Container Padding (px)','pep'),
						'type'=>'number',
						'default'=>0,
						'elements'=>'body .wp-block-group__inner-container,body .site-inner {padding-left: %spx;padding-right: %spx;}
						.entry-content .wp-block-media-text .wp-block-media-text__content {padding-right:%spx;}
						.entry-content .wp-block-media-text.has-media-on-the-right .wp-block-media-text__content {padding-left:%spx;}',
					),
				),
			),
			'layout_notification'=>array(
				'title'=>__('Notification area','pep'),
				'controls'=>array(
					'notify_width'=>array(
						'label'=>__('Container Max-Width (px)','pep'),
						'type'=>'number',
						'default'=>1140,
						'elements'=>'body #notification .wrap{max-width: %spx;}',
					),
					'notify_padding'=>array(
						'label'=>__('Container Padding (Left / Right) (em)','pep'),
						'type'=>'number',
						'default'=>0,
						'elements'=>'#notification .wrap {padding-left: %sem;padding-right: %sem;}',
					),
					'notify_padding_top'=>array(
						'label'=>__('Container Padding (Top / Bottom) (em)','pep'),
						'type'=>'number',
						'default'=>0,
						'elements'=>'#notification .wrap {padding-top: %sem;padding-bottom: %sem;}',
					),
				),
			),
			'layout_header'=>array(
				'title'=>__('Header','pep'),
				'controls'=>array(
					'header_width'=>array(
						'label'=>__('Container Max-Width (px)','pep'),
						'description'=>__('Leave empty to make full width','pep'),
						'type'=>'number',
						'default'=>1140,
						'elements'=>'body .site-header .wrap,body .topbar .wrap{max-width: %spx;}',
					),
					'header_padding'=>array(
						'label'=>__('Container Padding (px)','pep'),
						'type'=>'number',
						'default'=>30,
						'elements'=>'body .site-header .wrap,body #topbar .wrap {padding-left: %spx;padding-right: %spx;}',
					),
					'primary_menu_position'=>array(
						'label'=>__('Primary navigation position','pep'),
						'type'=>'select',
						'default'=>'right',
						'choices'=>array(
							'right'=>__('Next to logo','pep'),
							'below'=>__('Below logo','pep'),
						),
						'elements'=>
							array(
								'right'=>'',
								'below'=>'',
							),
					),
					'show_search'=>array(
						'label'=>__('Show search icon / form','pep'),
						'type'=>'select',
						'default'=>'no',
						'choices'=>array(
							'no'=>__('No','pep'),
							'yes'=>__('Yes','pep'),
						),
						'elements'=>
							array(
								'no'=>'',
								'yes'=>'',
							),
					),
				),
			),
			'layout_footer'=>array(
				'title'=>__('Footer','pep'),
				'controls'=>array(
					'footer_width'=>array(
						'label'=>__('Container Max-Width (px)','pep'),
						'description'=>__('Leave empty to make full width','pep'),
						'type'=>'number',
						'default'=>1140,
						'elements'=>'body .site-footer .wrap{max-width: %spx;}',
					),
					'footer_padding'=>array(
						'label'=>__('Container Padding (px)','pep'),
						'type'=>'number',
						'default'=>30,
						'elements'=>'body .site-footer .wrap,body #genesis-footer-widgets .footer-widget-area,#prefooter .wrap {padding-left: %spx;padding-right: %spx;}',
					),
					'footer_align'=>array(
						'label'=>__('Footer text align','pep'),
						'type'=>'select',
						'default'=>'left',
						'choices'=>array(
							'left'=>__('Left','pep'),
							'center'=>__('Center','pep'),
							'right'=>__('Right','pep'),
						),
						'elements'=>array(
							'left'=>'body .site-footer .wrap {text-align:%s;}body .site-footer .wrap .pep-copyright {float:right;}',
							'center'=>'body .site-footer .wrap {text-align:%s;}body .site-footer .wrap .pep-copyright {display:block;margin:10px 0;float:none;clear:both;}',
							'right'=>'body .site-footer .wrap {text-align:%s;}body .site-footer .wrap .pep-copyright {float:none;}body .site-footer .wrap .pep-copyright:before {content:"\00b7";display:inline-block;margin:0 10px 0 0;}',
						),
					),
					'footer_widgets'=>array(
						'label'=>__('Number of footer widgets','pep'),
						'type'=>'number',
						'default'=>'4',
					),
					'footer_widgets_top'=>array(
						'label'=>__('Footer widgets top padding','pep'),
						'type'=>'number',
						'default'=>'60',
						'elements'=>'body #genesis-footer-widgets {padding-top:%spx;}',
					),					
					'footer_widgets_bottom'=>array(
						'label'=>__('Footer widgets bottom padding','pep'),
						'type'=>'number',
						'default'=>'60',
						'elements'=>'body #genesis-footer-widgets {padding-bottom:%spx;}',
					),
				),
			),
			'ab_container'=>array(
				'title'=>__('Containers','pep'),
				'controls'=>array(
					'ab_container_width'=>array(
						'label'=>__('Container Max-Width (px)','pep'),
						'type'=>'number',
						'default'=>1140,
						'elements'=>'body .wp-block-genesis-blocks-gb-container.gb-block-container .gb-container-inside .gb-container-content[style*="max-width:1600px"],div[class*="__inner"]{max-width: %spx !important;}'
					),
					'ab_container_padding'=>array(
						'label'=>__('Container Padding Left / Right(px)','pep'),
						'type'=>'number',
						'default'=>30,
						'elements'=>'body .wp-block-genesis-blocks-gb-container.gb-block-container .gb-container-inside .gb-container-content,div[class*="__inner"] {padding-left: %spx;padding-right: %spx;}',
					),
					'ab_container_padding_top'=>array(
						'label'=>__('Container Padding Top (px)','pep'),
						'type'=>'number',
						'default'=>60,
						'elements'=>'body .wp-block-genesis-blocks-gb-container.gb-block-container .gb-container-inside .gb-container-content,div[class*="__inner"] {padding-top: %spx;}',
					),
					'ab_container_padding_bottom'=>array(
						'label'=>__('Container Padding Bottom (px)','pep'),
						'type'=>'number',
						'default'=>60,
						'elements'=>'body .wp-block-genesis-blocks-gb-container.gb-block-container .gb-container-inside .gb-container-content,div[class*="__inner"] {padding-bottom: %spx;}',
					),
					'ab_container_margin'=>array(
						'label'=>__('Container Margin Top / Bottom (px)','pep'),
						'type'=>'number',
						'default'=>60,
						'elements'=>'body .wp-block-genesis-blocks-gb-container.gb-block-container {margin-top: %spx;margin-bottom: %spx;}
						body .wp-block-genesis-blocks-gb-container.gb-block-container + .wp-block-genesis-blocks-gb-container.gb-block-container {margin-top:-%spx;}',
					)
				),
			),
			'archives'=>array(
				'title'=>__('Post archives','pep'),
				'controls'=>array(
					'archive_header_description'=>array(
						'label'=>__('Description position','pep'),
						'type'=>'select',
						'default'=>'inner',
						'choices'=>array(
							'inner'=>__('Inside archive header','pep'),
							'below'=>__('Below archive header','pep'),
						),
					),
					'archive_header_width'=>array(
						'label'=>__('Archive header width','pep'),
						'type'=>'select',
						'default'=>'alignfull',
						'choices'=>array(
							'alignfull'=>__('Align full','pep'),
							'alignwide'=>__('Align wide','pep'),
							'normal'=>__('Normal (inside content box)','pep'),
						),
					),
					'archive_thumbnail'=>array(
						'label'=>__('Thumbnail size','pep'),
						'type'=>'select',
						'default'=>'thumbnail',
						'choices'=>$image_sizes,
					),
					'show_excerpt'=>array(
						'label'=>__('Show Excerpt','pep'),
						'type'=>'select',
						'default'=>1,
						'choices'=>array(
							0=>__('Hide','pep'),
							1=>__('Show','pep'),
						),
					),
					'readmore'=>array(
						'label'=>__('Read more text','pep'),
						'description'=>__('Leave empty to hide CTA link','pep'),
						'type'=>'text',
						'default'=>__('Read more','pep'),
					),
				),
			),
			'maintenance'=>array(
				'title'=>__('Maintenance mode','pep'),
				'controls'=>array(
					'maintenance_mode'=>array(
						'label'=>__('Maintenance mode on or off','pep'),
						'type'=>'select',
						'default'=>'wp_debug',
						'choices'=>array(
							'wp_debug'=>__('Depending on WP-DEBUG setting','pep'),
							'on'=>__('On','pep'),
							'off'=>__('Off','pep'),
						),
					),
					'maintenance_style'=>array(
						'label'=>__('Style','pep'),
						'type'=>'select',
						'default'=>'pep',
						'choices'=>array(
							'pep'=>__('Default PEP style','pep'),
							'custom'=>__('Custom style','pep'),
						),
					),
					'maintenance_logo'=>array(
						'label'=>__('Logo','pep'),
						'type'=>'image',
						'description'=>__('Leave empty to hide logo','pep'),
						'default'=>'',
					),
					'maintenance_content'=>array(
						'label'=>__('Content','pep'),
						'type'=>'editor',
						'default'=>$default_content,
					),
				),
			),
		);
		return $layouts;
	 }
	 
	 public function add_customizer_layout($wp_customize) {
		$wp_customize->add_panel('layout',array(
			'title'=>__('Layout','pep'),
			'priority'=>50,
		));
		
		$layouts=$this->get_layouts();
		 
		$i=0;
		foreach($layouts as $section => $options) {
			$wp_customize->add_section($section,array(
				'title'=>$options['title'],
				'priority'=>$i*10,
				'panel'=>'layout',
			));	

			foreach($options['controls'] as $id => $control) {
			
				$description='';
				if(isset($control['description']))	$description=$control['description'];
				
				$wp_customize->add_setting(
					$id,
					array(
						'default'           => $control['default'],
					)
				);
				
				$options=array(
					'type' => $control['type'],
					'section' => $section, // Add a default or your own section
					'label' => $control['label'],
					'description' => $description,
				);
				
				if(isset($control['choices'])) {
					$options['choices']=$control['choices'];
				}
			
				if($options['type']=='image') {
					$wp_customize->add_control( new \WP_Customize_Image_Control( $wp_customize, $id, array(
						'label' => $options['label'],
						'section' => $options['section'],
						'description'=>$description,
						'settings' => $id,    
					)));
				} elseif($options['type']=='editor' && class_exists('WP_Customize_Editor_Control')) {
					$wp_customize->add_control( new \WP_Customize_Editor_Control( $wp_customize, $id, array(
						'label' => $options['label'],
						'description' => $description,
						'section' => $options['section'],
						'editor_settings' => array(
							'quicktags' => true,
							'tinymce'   => true,
						),
					) ) );
				} elseif($options['type']=='editor') {
					$options['type']='textarea';
					$wp_customize->add_control( $id, $options );
				} else {
					$wp_customize->add_control( $id, $options );
				}
			}
			
			$i++;
			
		}	


		$wp_customize->add_setting(
			'logo_width',
			array(
				'default'           => 180,
			)
		);
				
		$options=array(
			'type' => 'number',
			'section' => 'title_tagline', // Add a default or your own section
			'label' => __('Max logo width','pep'),
		);	
			
		$wp_customize->add_control( 'logo_width', $options );		
		
	 }
	 
	public function enqueue_customizer_css() {
		$css = '';
 
		$layouts=$this->get_layouts();

		foreach($layouts as $layout) {
			
			foreach ($layout['controls'] as $id=>$options) {
				$current=get_theme_mod( $id, $options['default']);

                if(!empty($current) && $current!=$options['default'] && !empty($options['elements'])) {
					
					if(is_array($options['elements'])) {
						$options['elements']=$options['elements'][$current];
					}
					
                    $css.=sprintf($options['elements'],$current,$current,$current,$current,$current);

                }

			}
		}
		
		$logo_width=get_theme_mod( 'logo_width', 180);
		if($logo_width!=180) {
			$css.='body.wp-custom-logo .title-area {max-width:'.$logo_width.'px;}';
			$nav_position=get_theme_mod('primary_menu_position','right');
			if($nav_position=='right') {
				$css.='@media only screen and (min-width: 961px) {body.wp-custom-logo .site-header .nav-primary {max-width:calc(100% - '.((int)$logo_width+20).'px);}}';
			}
		}
		
		if ( $css ) {
			wp_add_inline_style( 'pep-bc', $css );
		}
	}
	
	public function body_class($classes) {
		$nav_position=get_theme_mod('primary_menu_position','right');
		if($nav_position=='right') {
			$classes[]='primary-nav-right';
		} else {
			$classes[]='primary-nav-below';
		}
		return $classes;
	}
}