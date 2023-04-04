<?php
/*
 * Customizer functionality - Fonts
 */
namespace PEP\Customizer;

class Fonts {
	
	private $default_font='Arial, Helvetica';
	private $web_safe_font_list = array(
		''=>'nofont',
		'Georgia'=>'serif',
		'"Palatino Linotype", "Book Antiqua", Palatino'=>'serif',
		'"Times New Roman", Times'=>'serif',
		'Helvetica'=>'sans-serif',
		'Arial, Helvetica'=>'sans-serif',
		'"Arial Black", Gadget'=>'sans-serif',
		'"Comic Sans MS"'=>'cursive, sans-serif',
		'Impact, Charcoal'=>'sans-serif',
		'"Lucida Sans Unicode", "Lucida Grande"'=>'sans-serif',
		'Tahoma, Geneva'=>'sans-serif',
		'"Trebuchet MS", Helvetica'=>'sans-serif',
		'Verdana, Geneva'=>'sans-serif',
		'"Courier New", Courier'=>'monospace',
		'"Lucida Console", Monaco'=>'monospace',
	);	
	

    public function __construct()
    {
		add_action('customize_register', array($this, 'add_customizer_fonts'));
		add_action( 'wp_enqueue_scripts', array($this,'enqueue_customizer_css') );
    }
	 
	 public function add_customizer_fonts($wp_customize) {
		$wp_customize->add_panel('fonts',array(
			'title'=>__('Typography','pep'),
			'description'=>__('Define theme fonts','pep'),
			'priority'=>40,
		));
		
		$typography=$this->get_typography();
		
		
		
		$i=0;
		foreach($typography as $section => $options) {
			$wp_customize->add_section($section,array(
				'title'=>$options['title'],
				'priority'=>$i*10,
				'panel'=>'fonts',
				'description'=>$options['description'],
			));
			
			foreach($options['options'] as $id => $option) {
				$wp_customize->add_setting(
					$id,
					array(
						'default' => $option['default'],
					)
				);
					
				$args=array(
					'type' => $option['type'],
					'section' => $section,
					'label' => $option['label'],
				);
				
				if(isset($option['choices'])) {
					$args['choices']=$option['choices'];
				}
				
				if(isset($option['input_attrs'])) {
					$args['input_attrs']=$option['input_attrs'];
				}
				
				$wp_customize->add_control( $id, $args );
			}
			
			$i++;
		}
		
	 }
	 
	 private function get_fonts($select=false) {
		 $fonts=array();
		 foreach($this->web_safe_font_list as $font=>$category) {
			 $fonts[$font]=array('family'=>$font,'category'=>$category,'type'=>'websafe');
		 }
		 //delete_transient('google_fonts');
		 $googlefonts=get_transient('google_fonts');
		 if($googlefonts=="") {
			 
			 $api_keys=array(
				'AIzaSyDd8KIkubn_CFWVglXN0KRL2Lez4n_N7q4',
				'AIzaSyC1V84GIS--nZ-EJHNxxPX262QE4z56Vpc',
				'AIzaSyBWTHNB5Y3m9fSkUqtvyNVmZ3ZN4_F5Wkk',
			);
			$rand_api_key=array_rand($api_keys, 1);
			$api_key=$api_keys[$rand_api_key];
			$googlefonts=file_get_contents('https://www.googleapis.com/webfonts/v1/webfonts?key='.$api_key.'&sort=popularity');

			 $googlefonts=json_decode($googlefonts);
			 $googlefonts=$googlefonts->items;
			 
		 	 
			 $saveGoogleFonts=array();
			 foreach($googlefonts as $font) {
				
				foreach($font->variants as $key=>$variant){
					if (strpos($variant, 'italic') !== false) {
						unset($font->variants[$key]);
					}
					if (strpos($variant, 'regular') !== false) {
						$font->variants[$key]=400;
					}
					$font->variants[$key]=400;
				}
				
				$variants=implode(',',$font->variants);
				 
				$saveGoogleFonts['"'.$font->family.'"']=array('family'=>$font->family,'category'=>$font->category,'type'=>'google','variants'=>$variants);
			 }
			 
			 set_transient('google_fonts',$saveGoogleFonts,2 * WEEK_IN_SECONDS);
			 
			 $googlefonts=$saveGoogleFonts;
		 }
		 
		 foreach($googlefonts as $font => $option) {
			$fonts[$font]=array('family'=>$option['family'],'category'=>$option['category'],'type'=>$option['type'],'variants'=>$option['variants']);
		 }
		 
		 if($select==true) {
			$output=array();
			foreach($fonts as $font=>$option) {
				$output[$font]=$option['family'];
			}
			return $output;
		 } else {
			return $fonts;
		 }
		 
	 }
	
	public function enqueue_customizer_css() {
		$css = '';

		$fonts=$this->get_fonts();
		$typography=$this->get_typography();
		
		$enqueue_fonts=array();
		
		foreach($typography as $section => $options) {
						
			foreach($options['options'] as $id=>$option) {
				
				$current_alt=$current=get_theme_mod( $id, $option['default']);
				if($current!=$option['default'] && $option['elements']!="" && $current!="") {
					
					if($option['label']==__('Font Family','pep') && isset($fonts[$current]['type']) && $fonts[$current]['type']!='websafe') {
						$current_alt=$fonts[$current]['category'];
						
						$family=str_replace(' ','+',$current);
						$family=str_replace('"','',$family);
					
						$enqueue_fonts[$family]=$family;
					}
					
					if($option['label']==__('Font Weight','pep')) {
						
						$enqueue_weights[$family][$current]=$current;
					}
					
					$css.=sprintf($option['elements'],$current,$current_alt);
				}
			}
		}
		
		if(!empty($enqueue_fonts)) {
			$new_enqueue=array();
			
			foreach($enqueue_fonts as $font) {
				$new_enqueue[$font]=$font;
				if(isset($enqueue_weights[$font])) {
					
					$weights=$enqueue_weights[$font];
					$weights[]=400;
					$weights[]=700;
					$weights=implode(',',$weights);
				
					$new_enqueue[$font].=':'.$weights;
				} 
				
			}
			
			$enqueue_fonts=implode('|',$new_enqueue);
			
			wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family='.$enqueue_fonts.'&display=swap', array(), THEME_VERSION );
		}

		if ( $css ) {
			wp_add_inline_style( 'pep-bc', $css );
		}
	}
	
	private function get_typography(){
		$typography=array(
			'typo_heading_h1'=>array(
				'title'=>__('Heading H1','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('h1','html body h1',2),
			),
			'typo_heading_h2'=>array(
				'title'=>__('Heading H2','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('h2','html body h2',1.6),
			),
			'typo_heading_h3'=>array(
				'title'=>__('Heading H3','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('h3','html body .entry-content h3,body.woocommerce-checkout #customer_details .woocommerce-shipping-fields #ship-to-different-address:before,.woocommerce-checkout #payment ul.wc_payment_methods:before',1.3),
			),
			'typo_heading_h4'=>array(
				'title'=>__('Heading H4','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('h4','html body .entry-content h4',1.1),
			),
			'typo_content'=>array(
				'title'=>__('Content','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('content','html body'),
			),
			'typo_notificationbar'=>array(
				'title'=>__('Notification bar','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('notify_bar','body #notification',0.91),
			),
			'typo_topbar'=>array(
				'title'=>__('Topbar','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('top_bar','body .topbar',0.91),
			),
			'typo_main_menu'=>array(
				'title'=>__('Primary Navigation','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('main_menu','body .nav-primary .genesis-nav-menu,body .nav-primary .genesis-nav-menu > li a',1.1),
			),
			'typo_main_sub_menu'=>array(
				'title'=>__('Primary Navigation Sub Menu','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('sub_menu','body .nav-primary .genesis-nav-menu .sub-menu li a',0.91),
			),
			'typo_quotes'=>array(
				'title'=>__('Quotes','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('quotes','html body blockquote > *,html body .site-inner .wp-block-quote p,body .gb-testimonial-text'),
			),
			'typo_buttons'=>array(
				'title'=>__('Buttons','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('buttons','.wp-block-button a,.gb-cta-button a'),
			),
			'typo_forms'=>array(
				'title'=>__('Forms','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('forms','form input:not([type="submit"])'),
			),
			'typo_footer_widgets'=>array(
				'title'=>__('Footer widgets','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('ftr_widget','html body #genesis-footer-widgets .widget-wrap > div',1),
			),			
			'typo_footer_title'=>array(
				'title'=>__('Footer widget title','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('footer_widget_title','html body #genesis-footer-widgets .widgettitle',1.1),
			),
			'typo_footer'=>array(
				'title'=>__('Footer','pep'),
				'description'=>'',
				'options'=>$this->get_typo_options('footer','.site-footer'),
			)
		);
		return $typography;
	}
	
	private function get_typo_options($prefix,$elements,$default_size=1) {
	  $options=array(
		$prefix.'_font'=>array(
			'label'=>__('Font Family','pep'),
			'default'=> $this->default_font,
			'type'=>'select',
			'choices'=>$this->get_fonts(true),
			'elements'=>$elements.'{font-family:%s,%s !important;}',
		),
		$prefix.'_size'=>array(
			'label'=>__('Font Size','pep'),
			'default'=>$default_size,
			'type'=>'number',
			'input_attrs'=>array(
				'min'=>1,
				'max'=>10,
				'step'=>0.05,
			),
			'elements'=>$elements.'{font-size:%sem !important;}',
		),
		$prefix.'_weight'=>array(
			'label'=>__('Font Weight','pep'),
			'default'=>400,
			'type'=>'number',
			'input_attrs'=>array(
				'min'=>300,
				'max'=>900,
				'step'=>100,
			),
			'elements'=>$elements.'{font-weight:%s !important;}',
		),
		$prefix.'_text_transform'=>array(
			'label'=>__('Text Transform','pep'),
			'default'=>'none',
			'type'=>'select',
			'choices'=>array(
				'uppercase'=>'Uppercase',
				'lowercase'=>'Lowercase',
				'capitalize'=>'Capitalize',
				'none'=>__('none','pep'),
			),
			'elements'=>$elements.'{text-transform:%s !important;}',
		),
		$prefix.'_letter_spacing'=>array(
			'label'=>__('Letter Spacing (px)','pep'),
			'default'=>0,
			'type'=>'number',
			'input_attrs'=>array(
				'min'=>-10,
				'max'=>10,
				'step'=>0.05,
			),
			'elements'=>$elements.'{letter-spacing:%spx !important;}',
		),
		$prefix.'_line_height'=>array(
			'label'=>__('Line Height (em)','pep'),
			'default'=>1.65,
			'type'=>'number',
			'input_attrs'=>array(
				'min'=>0,
				'max'=>5,
				'step'=>0.05,
			),
			'elements'=>$elements.'{line-height:%s !important;}',
		),
		$prefix.'_align'=>array(
			'label'=>__('Text align','pep'),
			'default'=>'left',
			'type'=>'select',
			'choices'=>array(
				'left'=>'Left',
				'right'=>'Right',
				'center'=>'Center',
				'none'=>__('none','pep'),
			),
			'elements'=>$elements.'{text-align:%s !important;}',
		),
	  );
	  return $options;
	}
}