<?php
/*
 * Gutenberg blocks functionality
 */
namespace PEP\Gutenberg;

class Blocks extends Gutenberg
{
	public function __construct()
    {
		include(THEME_PATH.'/lib/geocoder/AbstractGeocoder.php');
        include(THEME_PATH.'/lib/geocoder/Geocoder.php');

        //ToDo make key available from settings
        $key = 'd770c19fcb5849f9929a349394038a71';
        $this->geocoder = new \OpenCage\Geocoder\Geocoder($key);
		
		add_action( 'enqueue_block_editor_assets', array($this,'block_assets_backend') );
		add_action('wp_enqueue_scripts', array($this,'register_openlayers'));
		register_block_type( 'pep/richcontact', array('render_callback' => array($this,'block_contact_render')) );
		register_block_type( 'pep/breadcrumbs', array('render_callback' => array($this,'block_breadcrumbs_render')) );
		register_block_type( 'pep/slickslider', array('render_callback' => array($this,'block_slickslider')) );
	}
	
	public function block_assets_backend() {
		// Scripts.
		wp_enqueue_script(
			'pep-gutenberg-blocks', // Handle.
			THEME_DIR .'/assets/js/blocks.build.js' , // Block.build.js: We register the block here. Built with Webpack.
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-data', 'wp-editor','underscore') // Dependencies, defined above.
			// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ) // Version: File modification time.
		);
	
		// Styles.
		wp_enqueue_style(
			'pep-gutenberg-blocks-editor', // Handle.
			THEME_DIR .'/assets/css/blocks.editor.build.css' , // Block editor CSS.
			array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
			// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
		);
	}
	
	
	/* richcontact:: Render frontend output */
	public function block_contact_render( $attributes, $content ) {
		
		//echo get_the_ID();
		//update_post_meta($attributes['save_post_id'],'latitude',$geodata['lat']);
		//update_post_meta($attributes['save_post_id'],'longitude',$geodata['lng']);
		
		$geodata=get_post_meta(get_the_ID(),'geo_data',true);
		if(empty($geodata)) $geodata=array();
		
		$attributes = shortcode_atts(array(
			'company_name' => get_theme_mod('company_name',get_bloginfo('name')),
			'subline'=>'',
			'street'=>get_theme_mod('company_address'),
			'postcode'=>get_theme_mod('company_postal'),
			'city'=>get_theme_mod('company_city'),
			'country'=>get_theme_mod('company_country','NL'),
			'phone'=>get_theme_mod('company_phone'),
			'fax'=>'',
			'description'=>'',
			'price_range'=>'',
			'schema_org'=>'LocalBusiness',
			'company_vat'=>'',
			'company_kvk'=>'',
			'alt_url'=>'',
			'company_menu'=>'',
			'company_reservations'=>'',
			'email'=>get_theme_mod('company_email', get_bloginfo('admin_email')),
			'show_country' => false,
			'show_name' => true,
			'show_subline' => false,
			'show_phone' => true,
			'show_fax' => false,
			'show_email' => true,
			'show_hours' => false,
			'show_kvk' => false,
			'show_vat' => false,
			'show_menu' => false,
			'show_reservations' => false,
			'show_map' => false,
			'full_days' => false,
			'class' => '',
			'opening_Mo_open'=>'',
			'opening_Mo_close'=>'',
			'opening_Tu_open'=>'',
			'opening_Tu_close'=>'',
			'opening_We_open'=>'',
			'opening_We_close'=>'',
			'opening_Th_open'=>'',
			'opening_Th_close'=>'',
			'opening_Fr_open'=>'',
			'opening_Fr_close'=>'',
			'opening_Sa_open'=>'',
			'opening_Sa_close'=>'',
			'opening_Su_open'=>'',
			'opening_Su_close'=>'',
			'block_id'=>'',
			'hide_all_map'=>false,
        ), $attributes, 'contact' );
		
		$attributes['currentDay']=substr(date('D'),0,-1);
		
		$hours=array();
		$days=array('Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su');
		foreach($days as $day) {
			if($attributes['opening_'.$day.'_open'] && $attributes['opening_'.$day.'_close']) {
				$hours[$day]['open']=$attributes['opening_'.$day.'_open'];
				$hours[$day]['closed']=$attributes['opening_'.$day.'_close'];
			}
		}
		
		if($attributes['full_days']) {
			$day_name=array(
				'Mo'=>__('Monday'),
				'Tu'=>__('Tuesday'),
				'We'=>__('Wednesday'),
				'Th'=>__('Thursday'),
				'Fr'=>__('Friday'),
				'Sa'=>__('Saturday'),
				'Su'=>__('Sunday'),
			);
		} else {
			$day_name=array(
				'Mo'=>__('Mon'),
				'Tu'=>__('Tue'),
				'We'=>__('Wed'),
				'Th'=>__('Thu'),
				'Fr'=>__('Fri'),
				'Sa'=>__('Sat'),
				'Su'=>__('Sun'),
			);
		}
		
		$attributes['day_name']=$day_name;
		$attributes['hours']=$hours;
		
		/* Start Geocoding */
		
		if($attributes['show_map']==true && !isset($geodata['block_'.$attributes['block_id']])) {
		
			$query = trim($attributes['street'].', '.$attributes['postcode'].' '.$attributes['city'].', '.$attributes['country']);
			$options=array('language'=>'nl');
			
			$geocode = $this->geocoder->geocode($query,$options);
	
			$temp_geodata['block_'.$attributes['block_id']]=$geocode['results'][0]['geometry'];
			
			$geodata=array_merge($geodata,$temp_geodata);
				
			update_post_meta(get_the_ID(),'geo_data',$geodata);
		}
		
		$attributes['geodata']=$geodata;
		
		if(isset($geodata['block_'.$attributes['block_id']]) && $attributes['show_map']==true) {
			$lat=$geodata['block_'.$attributes['block_id']]['lat'];
			$lng=$geodata['block_'.$attributes['block_id']]['lng'];
			
			
			$address_formatted=' '.$attributes['company_name'].' br '.$attributes['street'].' br '.$attributes['postcode'].' '.$attributes['city'];
				if($attributes['country']!="Nederland" && $attributes['country']!="NL" && $attributes['country']!="The Netherlands" && $attributes['country']!="Netherlands") {
					$address_formatted.=' br '.$attributes['country'];
				}
			
			$geo=array(
				
					'target'=>$attributes['block_id'],
					'lat'=>$lat,
					'lng'=>$lng,
					'address'=>$address_formatted,
					'marker'=>THEME_DIR.'/assets/images/icon-marker.png',
					'zoom'=>15
				
			);
			$attributes['geodata']=$geo;
			wp_enqueue_style('openlayers');
				
			wp_enqueue_script('openlayers');
			//wp_localize_script( 'openlayers-init', 'geodata', $geo );
			
			
			wp_enqueue_script('openlayers-init');
		}
		/* End Geocoding */
		
		if(is_admin()) return true;
		
		return $this->render_block('contact', $attributes);
		
	}
	
	 private function render_block($template_name, $params = array())
    {

        global $post;

        extract($params); // Array is now available as variable (array key = variable)

        ob_start();
        include(THEME_VIEWS_PATH.'/gutenberg/'.$template_name.'.php');
        $return = ob_get_contents();
        ob_end_clean();

        return $return;

    }
	
	public function register_openlayers() {
		wp_register_style('openlayers', 'https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.0.0/css/ol.css', array(), '6.0.0', 'all' );
		wp_register_script('openlayers','https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.0.0/build/ol.js','6.0.0');
		wp_register_script('openlayers-init',THEME_DIR . '/assets/js/openlayers.init.js',array('openlayers','jquery'),'1.0.0');
	}
	
	public function block_breadcrumbs_render( $attributes, $content ) {
		if(is_admin()) return true;
		return $this->render_block('breadcrumbs', $attributes);
	}
	
	public function block_slickslider($attributes, $content) {
	
		if(is_admin()) return false;

		wp_enqueue_script('carousel');
		wp_enqueue_style('carousel');
		
		if(!isset($attributes['slides_to_scroll'])) $attributes['slides_to_scroll']=1;
		if(!isset($attributes['load_slides'])) $attributes['load_slides']=1;
		
		if($attributes['slides_to_scroll'] == 0) $attributes['slides_to_scroll']=1;
		if($attributes['load_slides'] == 0) $attributes['load_slides']=1;
		
		$class='';
		if(isset($attributes['align'])) $class.='align'.$attributes['align'];
		
		if(!isset($attributes['selectElement'])) $attributes['selectElement']='';
		if(!isset($attributes['speed'])) $attributes['speed']=500;
		if(!isset($attributes['auto_play_speed'])) $attributes['auto_play_speed']=5000;
		if(!isset($attributes['auto_play'])) $attributes['auto_play']="false";
		if(!isset($attributes['appendArrows'])) $attributes['appendArrows']="";
		if(!isset($attributes['appendDots'])) $attributes['appendDots']="";
		if(!isset($attributes['prevArrow'])) $attributes['prevArrow']=__('Previous');
		if(!isset($attributes['nextArrow'])) $attributes['nextArrow']=__('Next');
		
		$class.=' col-'.$attributes['load_slides'];
		
		if(isset($attributes['show_arrows']) && $attributes['show_arrows']) {
			$class.=' show-arrows';
			$attributes['show_arrows']="true";
		} else {
			$attributes['show_arrows']="false";
		}
			
		if(isset($attributes['show_controls']) && $attributes['show_controls']) {
			$class.=' show-controls';
			$attributes['show_controls']="true";
		} else {
			$attributes['show_controls']="false";
		}
		
		
		if(isset($attributes['slickStyling'])) {
			$class.=' slick-'.$attributes['slickStyling'];
		}
		
		if(isset($attributes['className'])) {
			$class.=' '.$attributes['className'];
		}
		
		$elID='slider_'.rand(0,9999).'_'.rand(0,9999);
			
		if(isset($attributes['infinite'])) {
			$attributes['infinite']="true";
		} else {
			$attributes['infinite']="false";
		}
		
		ob_start();
		?>
jQuery(document).ready(function($) {
	<?php if($attributes["selectElement"]=='ul') { ?>
	$.extend({
		replaceTag: function (currentElem, newTagObj, keepProps) {
			var $currentElem = $(currentElem);
			var i, $newTag = $(newTagObj).clone();
			if (keepProps) {//{{{
				newTag = $newTag[0];
				newTag.className = currentElem.className;
				$.extend(newTag.classList, currentElem.classList);
				$.extend(newTag.attributes, currentElem.attributes);
			}//}}}
			$currentElem.wrapAll($newTag);
			$currentElem.contents().unwrap();
			// return node; (Error spotted by Frank van Luijn)
			return this; // Suggested by ColeLawrence
		}
	});
	
	$.fn.extend({
		replaceTag: function (newTagObj, keepProps) {
			// "return" suggested by ColeLawrence
			return this.each(function() {
				jQuery.replaceTag(this, newTagObj, keepProps);
			});
		}
	});
	// On edge hit
	
	
	$('#<?php echo $elID;?> .wp-block-pep-slickslider ul').replaceTag('<div>',true);
	$('#<?php echo $elID;?> .wp-block-pep-slickslider <?php echo $attributes["selectElement"];?> li').each(function() {
		$(this).replaceTag('<div>', true);
	});
	<?php } 
	
	//print_r($attributes["selectElement"]);exit();
	?>
	$('#<?php echo $elID;?> .wp-block-pep-slickslider').slick({
		dots: <?php echo $attributes['show_controls'];?>,
		arrows:<?php echo $attributes['show_arrows'];?>,
		infinite: <?php echo $attributes['infinite'];?>,
		speed: <?php echo $attributes['speed'];?>,
		slidesToShow: <?php echo $attributes['load_slides'];?>,
		slidesToScroll: <?php echo $attributes['slides_to_scroll'];?>,
		autoplay:<?php echo $attributes['auto_play'];?>,
		autoplaySpeed:<?php echo $attributes['auto_play_speed'];?>,
		adaptiveHeight: false,
		arialabel: '<?php echo $attributes['arialabel'];?>',
		focusOnChange:true,
		focusOnSelect:true,
		<?php if(isset($attributes['customPaging']) && $attributes['customPaging']!="") { ?>
		customPaging: function(slider,i) {
			var customPaging='<?php echo $attributes['customPaging'];?>';
			customPaging=customPaging.split(",");
			var title = customPaging[i];
			return '<button type="button" role="tab" id="slick-slide'+slider.$slides[i]+'" aria-controls="slick-slide'+slider.$slides[i]+'" aria-label="'+title+'"> '+title+' </button>';
		},
		customPagingADALabel: function(slider, i, numDotGroups, slide) {
			
				var customPaging='<?php echo $attributes['customPaging'];?>';
			customPaging=customPaging.split(",");
			var title = customPaging[i-1];
                    return title;
        },
		<?php } ?>
<?php if($attributes['appendArrows']!="") { ?>appendArrows: '<?php echo $attributes['appendArrows'];?>',<?php } ?>
<?php if($attributes['appendDots']!="") { ?>appendDots: '<?php echo $attributes['appendDots'];?>',<?php } ?>
prevArrow: '<button type="button" class="slick-prev"><?php echo $attributes["prevArrow"];?></button>',
nextArrow: '<button type="button" class="slick-next"><?php echo $attributes["nextArrow"];?></button>',
		responsive: [
			{
				breakpoint: 960,
				settings: {
					slidesToShow: <?php if($attributes['load_slides'] < 3) { echo $attributes['load_slides']; } else { echo '3';} ?>,
					slidesToScroll: <?php if($attributes['slides_to_scroll'] < 3) { echo $attributes['slides_to_scroll']; } else { echo '3';} ?>,
				}
			},
			{
				breakpoint: 768,
				settings: {
					adaptiveHeight: true,
				}
			},
			{
				breakpoint: 600,
				settings: {
					adaptiveHeight: true,
					slidesToShow: <?php if($attributes['load_slides'] < 2) { echo $attributes['load_slides']; } else { echo '2';} ?>,
					slidesToScroll: <?php if($attributes['slides_to_scroll'] < 2) { echo $attributes['slides_to_scroll']; } else { echo '2';} ?>,
				}
			},
			{
				breakpoint: 480,
				settings: {
					adaptiveHeight: true,
					slidesToShow: <?php if($attributes['load_slides'] < 1) { echo $attributes['load_slides']; } else { echo '1';} ?>,
					slidesToScroll: <?php if($attributes['slides_to_scroll'] < 1) { echo $attributes['slides_to_scroll']; } else { echo '1';} ?>,
				}
			}
		],
	});

});
		<?php
		$script=ob_get_contents();
		ob_end_clean();
		wp_add_inline_script('carousel',$script);
	
		ob_start();
		include(THEME_VIEWS_PATH.'/gutenberg/slickslider.php');
		$output = ob_get_contents();
		ob_end_clean();
				
		return $output;
	}


}