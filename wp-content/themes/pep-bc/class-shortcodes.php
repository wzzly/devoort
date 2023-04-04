<?php
/*
 * All theme related shortcodes
 */
namespace PEP;

class Shortcodes {

    /*
     * Initiation of class
     *
     */

    public function __construct()
    {
        add_shortcode('social-icons', array( $this, 'social_icons' ));
        add_shortcode('button', array( $this, 'button'));
		add_shortcode('contact', array( $this, 'contact'));
		add_shortcode('clients', array( $this, 'render_clients_sc' ));

		add_shortcode('contact-form-7', array($this, 'contact_form7_dummy'));
		add_shortcode('gutenberg-reusable',array($this,'render_gutenberg_reusable_block'));
    }
	
	
    /*
     * Social icons
     */
	public function social_icons($atts, $content)
    {

        $atts = shortcode_atts(array(
			'color' => 'black',
            'size' => 'default',
		), $atts, 'social-icons' );

		$socials=get_option('wpseo_social');
		
        return $this->render_shortcode_content('social-icons', array(
            'linkedin' => $socials['linkedin_url'],
            'facebook' => $socials['facebook_site'],
			'pinterest' => $socials['pinterest_url'],
            'twitter' => $socials['twitter_site'],
			'instagram' => $socials['instagram_url'],
            'youtube' => $socials['youtube_url'],
            'color' => $atts['color'],
            'size' => $atts['size']
        ));

    }

    /*
     * Contact information
     */
    public function contact($atts, $content)
    {
        $atts = shortcode_atts(array(
			'show_address' => 1,
			'show_subline' => 0,
			'show_tel' => 0,
			'show_fax' => 0,
			'show_email' => 0,
			'show_hours' => 0,
			'show_kvk' => 0,
			'show_vat' => 0,
			'show_menu' => 0,
			'show_reservations' => 0,
			'full_days' => 1,
			'class' => '',
        ), $atts, 'contact' );
		
		$hours=array();
		$days=array('Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su');
		foreach($days as $day) {
			if(get_theme_mod('opening_'.$day.'_open') && get_theme_mod('opening_'.$day.'_close')) {
				$hours[$day]['open']=get_theme_mod('opening_'.$day.'_open');
				$hours[$day]['closed']=get_theme_mod('opening_'.$day.'_close');
			}
		}
		
		if($atts['full_days']) {
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
		
		$currentDay=date('D');

		if($atts['show_address']) {
			return $this->render_shortcode_content('contact', array(
				'show_tel' => $atts['show_tel'],
				'show_fax' => $atts['show_fax'],
				'show_subline' => $atts['show_subline'],
				'show_email' => $atts['show_email'],
				'show_hours' => $atts['show_hours'],
				'show_kvk' => $atts['show_kvk'],
				'show_vat' => $atts['show_vat'],
				'show_menu' => $atts['show_menu'],
				'show_reservations' => $atts['show_reservations'],
				'day_name'=>$day_name,
				'hours'=>$hours,
				'class' => $atts['class'],
				'currentDay' => substr($currentDay,0,-1),
			));
		} else {
			return $this->render_shortcode_content('contact-without-address', array(
				'show_tel' => $atts['show_tel'],
				'show_fax' => $atts['show_fax'],
				'show_email' => $atts['show_email'],
				'show_hours' => $atts['show_hours'],
				'show_kvk' => $atts['show_kvk'],
				'show_vat' => $atts['show_vat'],
				'show_menu' => $atts['show_menu'],
				'show_reservations' => $atts['show_reservations'],
				'day_name'=>$day_name,
				'hours'=>$hours,
				'class' => $atts['class'],
			));
		}
    }
	


    /*
     * Button
     */

    public function button($atts, $content) {

       extract(shortcode_atts(array(

			'title' => 'Button',

			'url' => 'http://',

			'color' => 'default',

			'size' => '',

			'icon' => '',

			'icon_align' => 'right',

			'rotate' => ' ',

			'target' => '_self'

	    ), $atts));
		
		if($content) $title=$content;
		if(isset($atts['href'])) $url=$atts['href'];

	    if ($color == 'readmore'){

	    	$output = '<a target="'. $target .'" href="'. $url .'" class="btn wp-block-button__link read-more ' . $size . '">';

	    }elseif ($color == 'disabled'){

	    	$output = '<a target="'. $target .'" href="'. $url .'" class="btn wp-block-button__link disabled btn-' . $color . ' ' . $size . '">';

	    }else{

	    	$output = '<a target="'. $target .'" href="'. $url .'" class="btn wp-block-button__link btn-' . $color . ' ' . $size . '">';

	    }

	    if ($icon) {

	    	if ($icon_align == 'left') {

	    		$output = $output . '<i class="left fa fa-' . $icon . ' ' . $rotate . '"></i>' . $title .'</a>';

	    	}else{

	    		$output = $output . $title . '<i class="right fa fa-' . $icon . ' ' . $rotate . '"></i>' . '</a>';

	    	}

	    }else{

	    	$output = $output . $title . '</a>';

	    }

	    return '<div class="wp-block-button">'.$output.'</div>';

    }

       
     /*
      * Puts output in buffering and return contents
      *
      * @param string $template_name The template filename to be used
      * @param array Variables passed to the template file
      * @return string Template contents
      */

    private function render_shortcode_content($template_name, $params = array())
    {

        global $post;

        extract($params); // Array is now available as variable (array key = variable)

        ob_start();
        include(THEME_VIEWS_PATH.'/shortcodes/'.$template_name.'.php');
        $return = ob_get_contents();
        ob_end_clean();

        return $return;

    }
	
	public function contact_form7_dummy() {
		return false;
	}
	
	public function render_clients_sc($atts=array(),$content='') {
		$atts=shortcode_atts(
			array(
				'type'=>'',
				'limit'=>-1,
				'image'=>false,
				'title'=>true,
				'order'=>'DESC',
				'orderby'=>'ID',
			),$atts,'clients');
			
		
		ob_start();
		
		$args=array(
			'post_type'=>'client',
			'posts_per_page'=>$atts['limit'],
			'order'=>$atts['order'],
			'orderby'=>$atts['orderby'],
		);
		$class='';
		if($atts['type']!="") {
			$types=explode(',',$atts['type']);
			$class=implode(' ',$types);
			$args['tax_query']=array(
				array(
					'taxonomy'=>'client-type',
					'field'=>'slug',
					'terms'=>$types,
				),
			);
		}
		
		$query=new \WP_Query($args);
		if ( $query->have_posts() ) {
			echo '<ul class="clients '.$class.'">';
			while ( $query->have_posts() ) {
				$query->the_post();
				echo '<li><span>';
				if($atts['title']) { 
					echo get_the_title();
				}
				if($atts['image']) { 
					echo get_the_post_thumbnail(get_the_ID(),'clients');
				}
				echo '</span></li>';
			}
			echo '</ul>';
		}
	
		wp_reset_query();
		return ob_get_clean();
	}
	
	public function render_gutenberg_reusable_block($atts=array(),$content=null) {
		
		if(isset($atts['hide']) && $atts['hide'] && $atts['hide']!="") $exclude=explode(',',$atts['hide']);
		
		if(in_array(get_the_ID(),$exclude)) return false;
		
		$block_id=(int)$atts['id'];
        $gblock = get_post( $block_id);

       	echo apply_filters( 'the_content', $gblock->post_content );
		
	}


}