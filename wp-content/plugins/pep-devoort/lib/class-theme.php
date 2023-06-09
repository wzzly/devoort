<?php
/**
 * Main plugin class
 *
 * @version 1.0.0
 * @package PEP_Theme
 * @author Bart Pluijms
 */

namespace PEP;

class Theme extends Template {
    public function __construct($template,$customizer) {
		$this->template=$template;
		$this->customizer=$customizer;

		remove_theme_support( 'core-block-patterns' );

		// Theme translations. You can edit translations with POEDIT
		load_plugin_textdomain( PEP_Theme_ID, false, dirname(plugin_basename(__FILE__)).'/../languages' );

		// Load scripts & styles for this customer
		add_action( 'wp_enqueue_scripts', array($this,'enqueue_scripts_styles_theme') );

		add_shortcode('gutenberg-reusable',array($this,'render_gutenberg_reusable_block'));
		add_shortcode('share-buttons',array($this,'render_sc_share_buttons'));

		add_action( 'after_setup_theme', function () {
			add_image_size( 'header', 1600,450,true );
			add_image_size( 'profile', 400,500,true );
			add_image_size( 'single_blog', 245,245,true );
			remove_image_size( 'ab-block-post-grid-landscape' );
			remove_image_size( 'ab-block-post-grid-square' );
		}, 11 );

		add_filter( 'image_size_names_choose', array($this,'custom_image_sizes') );
		add_filter('the_title',array($this,'trim_title'),10,2);

		add_action('init',array($this,'register_headers'));
		add_action('init',array($this,'register_block_patterns'));

		add_shortcode('header',array($this,'render_headers'));
		add_shortcode('box',array($this,'render_box'));

		add_filter( 'body_class', array($this,'first_header_block_class') );
		//add_filter( 'the_content', array($this,'filter_content_imports'), 1 );

		add_action( 'pre_user_query', array($this,'random_user_query' ));

		add_filter( 'genesis_search_text', array($this,'search_text' ));



		/**
		 *  Define the directory with the font via fontDir configuration key.
		 */
		add_filter( 'dkpdf_mpdf_font_dir', function ( $font_dir ) {
		    // path to wp-content directory
		    $wp_content_dir = trailingslashit( WP_CONTENT_DIR );
		    array_push( $font_dir, $wp_content_dir . 'fonts' );
		    return $font_dir;
		});
		/**
		 * Define the font details in fontdata configuration variable
		 */
		add_filter( 'dkpdf_mpdf_font_data', function( $font_data ) {
		    $font_data['sourcesanspro'] = [
		        'R' => 'SourceSansPro-Regular.ttf',
		    ];
		    return $font_data;
		});

		add_filter( 'dkpdf_before_content', array($this,'dkpdf_before_content' ));

		add_filter( 'dkpdf_after_content', array($this,'dkpdf_after_content' ));



		/* Users */
		//add_action('init',array($this,'change_author_permalinks'));
		//add_filter('query_vars', array($this,'users_query_vars'));
		//add_filter('generate_rewrite_rules',array($this,'user_rewrite_rules'));
		add_shortcode('all-authors',array($this,'render_authors'));
		//add_shortcode('sectie',array($this,'render_section'));
		add_shortcode('sectie',array($this,'render_section_pep'));


		add_shortcode('sectie-posts',array($this,'render_section_posts'));

		add_action('wp',array($this,'conditionals'));

		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

		add_filter( 'wp_nav_menu_items', array($this,'render_mobile_menu_info'), 10, 2 );
		add_action('pep_nav_wrapper_close',array($this,'render_mobile_menu_button'));
		// add_action('pep_nav_wrapper_close',array($this,'render_desktop_menu_button'));


		add_shortcode('faillissementen',array($this,'render_faillissementen_sc'));

		//add_action('profile_update',array($this,'create_post_for_user'));
		add_action('delete_user',array($this,'delete_post_from_user'));

		add_filter('post_type_link',array($this,'filter_user_link'),10,4);


		/* Advanced Search */
		add_action('pep_search_description',array($this,'render_search_description'));
		add_action( 'pre_get_posts', array($this,'advanced_search_query'));
		add_action('genesis_before_entry_content',array($this,'render_advanced_search'));


		add_action('wp_ajax_adv_filters', array($this,'get_archive_posts'));
		add_action('wp_ajax_nopriv_adv_filters',array($this,'get_archive_posts'));



		add_filter('wpseo_breadcrumb_links', array($this,'override_yoast_breadcrumb_trail'));

		add_filter( 'genesis_search_title_text', array($this,'search_title_text'),9999);

		add_filter('dkpdf_header_title',array($this,'dkpdf_header_title'),20,1);

		add_shortcode('user-socials',array($this,'user_socials'));
		add_shortcode('user-getfield',array($this,'user_getfield'));

		add_shortcode('title',array($this,'user_title'));
		add_shortcode('bankruptcy-details',array($this,'render_bankruptcy_details_sc'));

		add_action( 'loop_start', array($this,'remove_titles_all_single_posts' ));

		add_action( 'wp_insert_post', array($this,'my_duplicate_on_publish' ));
	}

	public function conditionals() {
		if(is_author()) {
			remove_action( 'genesis_archive_title_descriptions', 'genesis_do_archive_headings_headline', 10, 3 );
			add_action( 'genesis_archive_title_descriptions', array($this,'filter_author_name'), 10, 3 );
			//add_action('genesis_loop',array($this,'user_render_content'));
			//add_action('genesis_loop', 'genesis_do_loop');
		}

		if(is_singular('specialists')) {
			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
			remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
			remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
			remove_action('genesis_before_loop',array($this->template,'yoast_breadcrumbs'),0);
		}

		if(is_singular('faillissement')) {
			remove_action('genesis_before_loop',array($this->template,'yoast_breadcrumbs'),0);
			remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
			add_action('genesis_before_content',array($this,'render_faill_header'));
			add_action('genesis_entry_header',array($this,'render_bankruptcy_date'),12);
			add_action('genesis_before_loop',array($this->template,'yoast_breadcrumbs'),0);
			add_action('genesis_entry_content',array($this,'render_bankruptcy_details'),0);
		}

		if(is_singular('post')) {
			add_action('genesis_before_content',array($this,'render_post_header'));
			add_action('genesis_entry_footer',array($this,'render_author_info'));
			add_action('genesis_before_content', array($this,'render_blog_header'));
			add_action('genesis_entry_header', array($this, 'render_blog_date'));
		}

		if(is_singular('post')) {
			add_action('genesis_after_content',array($this,'render_share_buttons'));
		}

		if (is_archive() && !is_search()) {

			add_action('genesis_before_loop',array($this,'render_advanced_search'));
			remove_action('genesis_before_loop', array($this->template,'render_archive_header'));

			remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description' );
			//Removes Title and Description on Blog Archive
			remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );
			//Removes Title and Description on Date Archive
			remove_action( 'genesis_before_loop', 'genesis_do_date_archive_title' );
			//Removes Title and Description on Archive, Taxonomy, Category, Tag
			remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );

			//Removes Title and Description on Blog Template Page
			remove_action( 'genesis_before_loop', 'genesis_do_blog_template_heading' );
			remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );

			add_action('genesis_before_loop', array($this,'render_archive_header_title'));
			add_action('genesis_before_content', array($this,'render_archive_header'));
		}

		if(is_search() ) {
			add_action( 'genesis_loop', array($this,'render_advanced_search'),0);
		}
	}

	public function enqueue_scripts_styles_theme() {
		// Check current theme.css version and load stylesheet
		$css_version=filemtime(PEP_Theme_DIR_PATH.'assets/css/theme.css');
		wp_enqueue_style( 'pep-theme', PEP_Theme_DIR_URL . 'assets/css/theme.css',array(),$css_version);

		// Check if ie-stylesheet is not empty. Load file if needed
		if( filesize(PEP_Theme_DIR_PATH. 'assets/css/ie.css')) {
			$css_version=filemtime(PEP_Theme_DIR_PATH.'assets/css/ie.css');
			wp_register_style( 'pep-theme-ie', PEP_Theme_DIR_URL. 'assets/css/ie.css',array(),$css_version);
			wp_enqueue_style( 'pep-theme-ie' );
		}

		// Check if theme.js exists and is not empty. Load file if needed
		if( file_exists(PEP_Theme_DIR_PATH.'assets/js/theme.js') && filesize(PEP_Theme_DIR_PATH. 'assets/js/theme.js')) {
			$version=filemtime(PEP_Theme_DIR_PATH.'assets/js/theme.js');
			wp_enqueue_script('pep-theme',PEP_Theme_DIR_URL . 'assets/js/theme.js',array( 'jquery','wp-a11y' ),$version,true);
		}

	}

	public function dkpdf_before_content() {

		global $post;

		$output = '';



	  	$pdf_featured_image_attachment = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'full');
	  	$pdf_header_image = sanitize_option( 'dkpdf_pdf_header_image', get_option( 'dkpdf_pdf_header_image' ) );
	    $pdf_header_image_attachment = wp_get_attachment_image_src( $pdf_header_image, 'full' );
	    $pdf_header_show_title = sanitize_option( 'dkpdf_pdf_header_show_title', get_option( 'dkpdf_pdf_header_show_title' ) );
	    $pdf_header_show_pagination = sanitize_option( 'dkpdf_pdf_header_show_pagination', get_option( 'dkpdf_pdf_header_show_pagination' ) );



	$coauthors = get_coauthors();

	if(!empty($coauthors)) {
		foreach($coauthors as $author) {

			$titel 			=	get_field('titel','user_'.$author->data->ID);
			$name			=	$author->data->display_name;

			$blogauthors[]	=	$titel . ' ' . $name;



		}
	}

	// only enter here if any of the settings exists
	if( $pdf_header_image || $pdf_header_show_title || $pdf_header_show_pagination ) {



		$output .= '<div style="width:683px; margin-left:60px; margin-bottom: 30px;">';
				// check if Header logo exists
				if( $pdf_header_image_attachment ) {

					$output .= '<div style="width:59%; height: 180px; background-size: cover; background-position: center;float: left; background-image: url('. $pdf_featured_image_attachment .');">
						<img style="margin-left: 60px;width:auto;height:110px;" src="' . $pdf_header_image_attachment[0] .'">';

					$output .= '</div>';

				}

		$output .= '<div style="background-color:  #7ea6bd; background-image: url(/wp-content/plugins/pep-devoort/assets/images/bg-footer-right.png); height: 180px; overflow: hidden; display: block;width:34.9%;float:left;text-align:center; padding:0px 20px; font-size:1.2rem;font-weight:700;">
				<table style="height: 180px;">
					<tr>
						<td style="vertical-align: middle; height: 120px; text-align: center;">
							<h1 style="margin-top: 0; margin-bottom:0px; padding-bottom: 0; font-weight: bold; font-size: 16px; color: #fff;text-transform: uppercase;font-family: Source Sans Pro;">';

								// check if Header show title is checked
								if ( $pdf_header_show_title ) {

									$output .= get_the_title( $post->ID );

								}


			$output .='</h1></td>
					</tr>
					<tr>
						<td style="vertical-align: middle; height: 20px; text-align: center;">
							
							<h2 style="margin-top:0; padding-top: 20px; font-weight: bold; font-size: 12px; color: #fff; text-transform: uppercase; font-family: Source Sans Pro;">';

								if (is_single()) {
									$output .= implode(' - ',$blogauthors);
								}
								else {
									$output .= 'De Voort Advocaten | Mediators';
								}


			$output .='</h2>
						</td>
					</tr>
				</table>
			</div>

		</div>';

		}

		return $output;


	}

	public function dkpdf_after_content() {

		$output = '';

		if (is_single(get_the_ID())) {

			$coauthors = get_coauthors();
			$counter = 0;

			if(!empty($coauthors)) {
				foreach($coauthors as $author) {

					$titel 			=	get_field('titel','user_'.$author->data->ID);
					$photo 			=	get_field('foto','user_'.$author->data->ID);
					$phone 			= 	get_field('telefoon','user_'.$author->data->ID);
					$email 			= 	get_userdata($author->data->ID)->user_email;
					$name			=	$author->data->display_name;
					$blogauthor		=	$titel . ' ' . $name;

					if ($counter == 0 || $counter > 1) {

						$output .= '<div style="background-color: #eee; width:683px; position: relative; margin-left: 60px;  clear: both; margin-top: 0px; margin-bottom: 20px;">
		    				<div style="width: 130px; float:left;"><img style="height:160px; margin-bottom:0;" src="' . $photo['url'] . '"></div><div style="height:120px; float:left; padding: 20px 20px 20px 0px;"><h3 style="text-transform:uppercase; margin-bottom: 0; font-weight: bold; color: #f7941d;">' . $blogauthor . '</h3><p style="margin-bottom: 20px;"><b>Advocaat</b></p><a href="mailto:' . $email . '"  target="_blank" style="color:#383838;">' . $email .'</a><br><a href="tel:' . $phone . '"  target="_blank"  style="color:#383838;">' . $phone .'</a></div></div>';

	    			}

	    			if ($counter == 1) {

						$output .= '<div style="background-color: #eee; width:683px; position: relative; margin-left: 60px;  clear: both; margin-top: 0px; margin-bottom: 20px;"><div style="height:120px; width: 508px; float:left; padding: 20px 20px 20px 20px;"><h3 style="text-transform:uppercase; margin-bottom: 0; font-weight: bold; color: #f7941d;">' . $blogauthor . '</h3><p style="margin-bottom: 20px;"><b>Advocaat</b></p><a href="mailto:' . $email . '"  target="_blank" style="color:#383838;">' . $email .'</a><br><a href="tel:' . $phone . '"  target="_blank"  style="color:#383838;">' . $phone .'</a></div>
		    				<div style="width: 106px; float:right;"><img style="height:160px; margin-bottom:0;" src="' . $photo['url'] . '"></div></div>';

	    			}

		    		$counter ++;

		    		echo $counter;

				}
			}
		}

	    return $output;
	}

	function my_duplicate_on_publish( $post_id ) {
	    global $post;

	    // don't save for autosave
	    if (is_null($post))
	        {
	             return $post_id;
	        }

	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	        return $post_id;
	    }

	    // dont save for revisions
	    if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
	        return $post_id;
	    }

	    // we need this to avoid recursion see add_action at the end
	    remove_action( 'wp_insert_post', array($this,'my_duplicate_on_publish' ));

	    // make duplicates if the post being saved:
	    // #1. is not a duplicate of another or
	    // #2. does not already have translations

	    $is_translated = apply_filters( 'wpml_element_has_translations', '', $post_id, $post->post_type );

	    if ( !$is_translated ) {
	        do_action( 'wpml_admin_make_post_duplicates', $post_id );
	    }

	    // must hook again - see remove_action further up
	    add_action( 'wp_insert_post', array($this,'my_duplicate_on_publish' ));
	}

	public function render_gutenberg_reusable_block($atts=array(),$content=null) {

		if(isset($atts['hide']) && $atts['hide'] && $atts['hide']!="") $exclude=explode(',',$atts['hide']);

		if(isset($exclude) && $exclude!="") {
			if(in_array(get_the_ID(),$exclude)) return false;
		}

		$block_id=(int)$atts['id'];
        $gblock = get_post( $block_id);

       	echo apply_filters( 'the_content', $gblock->post_content );

	}

	public function register_headers() {



		$labels=array(
			'name'               => __('Headers',PEP_Theme_ID),
			'add_new'			 => __('New header',PEP_Theme_ID),
			'new_item'			 => __('New header',PEP_Theme_ID),
			'add_new_item'		 => __('New header',PEP_Theme_ID),
		);
		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'page',
			'has_archive'        => false,
			'hierarchical'       => false,
			'exclude_from_search' => true,
			'menu_position'      => 5,
			'menu_icon'			 => 'dashicons-images-alt2',
			'show_in_rest'		 => true,
			'supports'           => array( 'title', 'editor'),
		);

		register_post_type( 'header', $args );


		$labels=array(
			'name'               => __('Bankruptcy',PEP_Theme_ID),
			'add_new'			 => __('New',PEP_Theme_ID),
			'new_item'			 => __('New',PEP_Theme_ID),
			'add_new_item'		 => __('New',PEP_Theme_ID),
		);
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug'=>__('bankruptcies',PEP_Theme_ID)),
			'capability_type'    => 'page',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 6,
			'menu_icon'			 => 'dashicons-megaphone',
			'show_in_rest'		 => true,
			'supports'           => array( 'title', 'editor','custom-fields','thumbnail','page-attributes'),
		);

		register_post_type( 'faillissement', $args );

		$labels=array(
			'name'               => __('Specialists',PEP_Theme_ID),
		);
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug'=>__('advocaten-mediators',PEP_Theme_ID)),
			'capability_type'    => 'page',
			'has_archive'        => false,
			'hierarchical'       => true,
			'menu_position'      => 10,
			'menu_icon'			 => 'dashicons-universal-access',
			'show_in_rest'		 => true,
			'supports'           => array( 'title', 'editor','custom-fields','thumbnail','page-attributes'),
		);

		register_post_type( 'specialists', $args );
	}

	public function render_headers($atts=array(),$content=null) {
		$atts = shortcode_atts(
        array(
            'text' => __('De Voort Advocaten | Mediators',PEP_Theme_ID),
            'subtext' => __('Excelling together',PEP_Theme_ID),
			'search'=>false,
			'buttons'=>false,
        ), $atts, 'header' );

		$args=array(
			'role__not_in'=>array('administrator'),
			'orderby'=>'rand',
			'order'=>'DESC',
			'number'=>25,
		);


		$users=get_users($args);

		ob_start();
		$old_color='';

		$i=0;

		foreach($users as $user) {
			$color=rand(0,3);
			if($old_color==$color) {$color=rand(0,3);}

			$afb=get_field('foto4','user_'.$user->ID);
			if(empty($afb)) {
				$afb=get_field('foto','user_'.$user->ID);
			}

			if(isset($afb['sizes']['thumbnail'])) {
				$title=get_field('titel','user_'.$user->ID);
				$name=trim($title.' '.$user->data->display_name);
				$author_id=get_user_meta($user->ID,'user_post',true);
				$url=get_permalink($author_id);
				echo '<div class="user overlay-'.$color.'"><figure><a href="'.esc_url($url).'"><img src="'.$afb['sizes']['thumbnail'].'" alt="'.$afb['alt'].'" width="'.$afb['sizes']['thumbnail-width'].'" height="'.$afb['sizes']['thumbnail-height'].'"><figcaption><span>'.$name.' <span class="cta">'.__('More information',PEP_Theme_ID).'</span></span></figcaption></a></figure></div>';
				if($i==20) break;
				$i++;
			}

			$old_color=$color;
		}

		$return=ob_get_clean();
		$return='<div class="user-header-grid">'.$return.'</div>';

		if($atts['text']!="") {
			$return.='<div class="header-text-box"><div class="header-text-box-grid"><h1 class="h1">'.$atts['text'].' <span>'.$atts['subtext'].'</span></h1></div>';

			if($atts['search']=='show') {
				$return.='<div class="header-search-box">'.get_search_form(array('echo'=>false)).'</div>';
			}
			if($atts['buttons']=='show') {
				if(ICL_LANGUAGE_CODE=='en') {
					$return.='<div class="header-action-box"><a href="/en/expertises/">'.__('Expertises',PEP_Theme_ID).'</a><a href="/en/specialists/">'.__('All specialists',PEP_Theme_ID).'</a></div>';
				}
				if(ICL_LANGUAGE_CODE=='nl') {
					$return.='<div class="header-action-box"><a href="/expertises/">'.__('Expertises',PEP_Theme_ID).'</a><a href="/advocaten-mediators/">'.__('All specialists',PEP_Theme_ID).'</a></div>';
				}

			}

			$return.='</div>';

		}

		return '<div class="alignfull"><div class="headers user-header">'.$return.'</div></div>';
	}

	public function first_header_block_class( $c ) {

		global $post;

		if(is_singular('specialists')) {
			$c[]='page-template page-template-page_author page-template-page_author-php';
		}

		if( isset($post->post_content) && has_shortcode( $post->post_content, 'headers' ) ) {
			$c[] = 'first-block-core-cover';
		}
		return $c;
	}



	public function random_user_query( $class ) {
		if( 'rand' == $class->query_vars['orderby'] )
			$class->query_orderby = str_replace( 'user_login', 'RAND()', $class->query_orderby );

		return $class;
	}

	function search_text( $text ) {
		return  __('Find a specialist, expertise, news item, etc.',PEP_Theme_ID); //Zoek een advocaat, expertise, nieuwsbericht, etc
	}

	//change author/username base to users/userID
	public function change_author_permalinks() {

		global $wp_rewrite;
		// Change the value of the author permalink base to whatever you want here
		$wp_rewrite->author_base = 'authors';
		$wp_rewrite->flush_rules();
	}


	public function users_query_vars($vars) {
		// add lid to the valid list of variables
		$new_vars = array('advocaten-mediators');
		$vars = $new_vars + $vars;
		return $vars;
	}

	public function user_rewrite_rules( $wp_rewrite ) {
		$newrules = array();
		$new_rules['advocaten-mediators/(\d*)$'] = 'index.php?author=$matches[1]';
		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
	}

	public function render_blog_date() {
		global $post;
		echo '<span class="blog-date">'.__('Published',PEP_Theme_ID).': ' . get_the_date() . '</span>';
		$authors=get_coauthors();
		//print_r($authors);
		$blogauthors=array();
		if(!empty($authors)) {
			echo '<span class="blog-authors"> '.__('Author(s):',PEP_Theme_ID).' ';
			foreach($authors as $author) {
				$titel = get_field('titel','user_'.$author->data->ID);
				$name=$author->data->display_name;
				$blogauthors[]= $titel.' '.$name;
			}
			echo implode(' - ',$blogauthors);
			echo '</span>';
		}

	}

	public function user_render_content() {
		$author = get_user_by( 'slug', get_query_var( 'author_name' ) );

		$author_id=$author->ID;

		$user_data=get_userdata($author_id);
		$phone = get_field('telefoon','user_'.$author_id);
		$specialisatie='';
		$specialismen=get_field('specialismen','user_'.$author_id);

		if(!empty($specialismen)) {
			$specialisatie = '<ul>';
			foreach($specialismen as $tag_id) {
				$tag=get_tag($tag_id);

				$specialisatie .= '<li><a href="'.get_tag_link($tag_id).'">'.$tag->name.'</a></li>';
			}
			$specialisatie .= '</ul>';
		}

		$user=array(
			'titel'=>get_field('titel','user_'.$author_id),
			'foto'=>get_field('foto','user_'.$author_id),
			'foto3'=>(int)get_field('foto3','user_'.$author_id),
			'box'=>array(
//				'Telefoon'=>get_field('telefoon','user_'.$author_id),
				'Telefoon'=>'<a href="tel:'.$phone.'">'.$phone.'</a>',
				//'Fax'=>get_field('fax','user_'.$author_id),
				'E-mail'=>'<a href="mailto:'.$user_data->user_email.'">'.$user_data->user_email.'</a>',
//				'Geboren'=>get_field('geboren','user_'.$author_id),
				//'Advocaat sinds'=>get_field('advocaat_sinds','user_'.$author_id),
				//'Partner sinds'=>get_field('partner_sinds','user_'.$author_id),
				//'Vreemde talen'=>get_field('vreemde_talen','user_'.$author_id),
				'Functie'=>get_field('functie','user_'.$author_id),
				'Expertises'=>$specialisatie,
				'Aandachtsgebieden'=>get_field('aandachtsgebied','user_'.$author_id),
			),
			'social'=>array(
				'LinkedIn'=>get_the_author_meta( 'linkedin', $author_id ),
				'Twitter'=>'https://twitter.com/'.get_the_author_meta( 'twitter', $author_id ),
			),
			'info'=>array(
				''=>get_field('about','user_'.$author_id),
				'Nevenfuncties / activiteiten'=>get_field('nevenfunctiesactiviteiten','user_'.$author_id),
				//'Aandachtsgebied'=>get_field('aandachtsgebied','user_'.$author_id),
				//'Lidmaatschappen'=>get_field('lidmaatschappen','user_'.$author_id),
				'Publicaties'=>get_field('publicaties','user_'.$author_id),
				//'Bijzonderheden'=>get_field('bijzonderheden','user_'.$author_id),
				//'Registratie rechtsgebieden'=>get_field('registratie_rechtsgebieden','user_'.$author_id),
			),
		);


		include(PEP_Theme_VIEWS_PATH.'/user.php');


	}

	public function filter_author_name( $heading = '', $intro_text = '', $context = '' ) {
		if ( $context && $heading ) {
			$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
			$titel=get_field('titel','user_'.$author->ID).' ';
			printf( '<h1 %s>%s%s</h1>', genesis_attr( 'archive-title' ), $titel,strip_tags( $heading ) );
		}

	}



	public function render_authors($atts,$content) {

		/*$args=array(
			'role__in'=>array('contributor','editor','author'),
			'meta_key'=>'last_name',
			'orderby'=>'meta_value',
		);

		$authors=get_users($args);
		*/

		$args=array(
			'post_type'=>'specialists',
			'posts_per_page'=>-1,
			'meta_key'=>'lastname',
			'orderby'=>'meta_value',
			'order'=>'ASC',
		);
		$authors=get_posts($args);

		ob_start();

		// echo '<ul class="users">';
		// foreach($users as $user) {

		// 	$user_id=$user->ID;

		// 	$foto=get_field('foto','user_'.$user_id);


		// 	if(!empty($foto) && isset($foto['sizes']['thumbnail'])) {
		// 		$url=get_author_posts_url($user_id);
		// 		$name=get_the_author_meta('display_name',$user_id);
		// 		$titel=get_field('titel','user_'.$user_id).' ';

		// 		echo '<li><a href="'.esc_url($url).'">';
		// 		echo '<img src="'.$foto['sizes']['thumbnail'].'" alt="'.$name.'" width="'.$foto['sizes']['thumbnail-width'].'" height="'.$foto['sizes']['thumbnail-height'].'">';

		// 		echo $titel.$name;

		// 		$specialismen = get_field('specialismen','user_'.$user_id);

		// 		if(!empty($specialismen)) {
		// 			echo '<ul class="specialismen">';
		// 			foreach($specialismen as $tag_id) {
		// 				$tag=get_tag($tag_id);
		// 				echo '<li><a href="'.get_tag_link($tag_id).'">'.$tag->name.'</a></li>';
		// 			}
		// 			echo '</ul>';
		// 		}

		// 		echo '</a></li>';
		// 	}

		// }
		// echo '</ul>';


		if(!empty($authors)) {
			foreach($authors as $author) {

				// echo $author->ID;

				$author_id=get_post_meta($author->ID,'user_id',true);

				$photo=get_field('foto','user_'.$author_id);
				$specialismen = get_field('specialismen','user_'.$author_id);
				$titel = get_field('titel','user_'.$author_id);
				$name=get_post_meta($author->ID,'display_name',true);
				$author_link=get_permalink($author->ID);
				$phone=get_field('telefoon','user_'.$author_id);
				$email=get_post_meta($author->ID,'user_email',true);
				$author_more=get_author_posts_url($author_id);
				include(PEP_Theme_VIEWS_PATH.'/author.php');
			}
		}
		return ob_get_clean();
	}

	public function render_section($atts,$content) {

		if(!isset($atts['users']) && !isset($atts['tag'])) return;

		$users=array();


		$args=array(
			'post_type'=>'specialists',
			'posts_per_page'=>-1,
			'meta_key'=>'lastname',
			'orderby'=>'meta_value',
			'order'=>'ASC',
		);

		//$args['meta_key']='specialismen';
		//$args['value']=8;
		//$args['meta_compare']='LIKE';
		if(isset($atts['tag'])) {
			$args['meta_query']=array(
					'relation'=>'AND',
					array(
						'key'=>'specialismen',
						//'value'=>'"'.(int)$atts['tag'].'"',
						//'value'=>'"'.(int)$atts['tag'].'"',
						'value'=>(int)$atts['tag'],
						'compare'=>'LIKE',
					),
				);
			//$users=get_users($args);
		}
		//echo '<pre>';
		//print_r($args);
		//echo '</pre>';



		$users=get_posts($args);

		/*if(isset($atts['users'])) {
			$usernames=explode(',',$atts['users']);
			foreach($usernames as $username) {
				$user=get_user_by('login',$username);
				$users[]=$user;
			}
		}*/

		ob_start();


		$old_color='';

		foreach($users as $user) {
			$color=rand(0,3);

			$meta=get_post_meta($user->ID,'specialismen',true);

			$user_id=get_post_meta($user->ID,'user_id',true);

			if($old_color==$color) {$color=rand(0,3);}

			$afb=get_field('foto4','user_'.$user_id);
			if(empty($afb)) {
				$afb=get_field('foto','user_'.$user_id);
			}

			if(isset($afb['sizes']['thumbnail'])) {
				$title=get_field('titel','user_'.$user_id);
				$author=get_user_by('ID',$user_id);
				$name=trim($title.' '.$author->data->display_name);

				//$author_id=get_user_meta($user_id,'user_post',true);
				$url=get_permalink($user->ID);

				echo '<div class="user overlay-'.$color.'"><figure><a href="'.esc_url($url).'"><img src="'.$afb['sizes']['thumbnail'].'" alt="'.$afb['alt'].'" width="'.$afb['sizes']['thumbnail-width'].'" height="'.$afb['sizes']['thumbnail-height'].'"><figcaption><span>'.$name.' <span class="cta">'.__('More information',PEP_Theme_ID).'</span></span></figcaption></a></figure></div>';
			}

			$old_color=$color;
		}

		$return=ob_get_clean();
		$return='<div class="user-header-grid">'.$return.'</div>';

		return '<div class=""><div class="headers user-section">'.$return.'</div></div>';
	}


	public function render_section_pep($atts,$content) {
		if(!isset($atts['users']) && !isset($atts['tag'])) return;
		$devoort_users = get_users();
		$old_color='';

		ob_start();

		foreach ( $devoort_users as $user ) {
			//print_r($user);
			if($user->user_email == "babs@pepbc.nl") {

			} elseif($user->user_email == "helpdesk@pepbc.nl") {

			} elseif($user->user_email == "info@devoort.nl") {

			} else {
				$user_id = $user->ID;
				$specialismes=get_field('specialismen','user_'.$user_id);
// changed by wes.digital				if (in_array($atts['tag'], $specialismes)) {
				if (is_array($specialismes) && in_array($atts['tag'], $specialismes)) {
					$color=rand(0,3);
					if($old_color==$color) {$color=rand(0,3);}


					$afb=get_field('foto4','user_'.$user_id);
					if(empty($afb)) {
						$afb=get_field('foto','user_'.$user_id);
					}

					if(isset($afb['sizes']['thumbnail'])) {
						$title=get_field('titel','user_'.$user_id);
						$author=get_user_by('ID',$user_id);
						$name=trim($title.' '.$author->data->display_name);
						$user_page = get_field('user_post', 'user_'.$user_id);
						//$author_id=get_user_meta($user_id,'user_post',true);
						$url=get_permalink($user_page);


						echo '<div class="user overlay-'.$color.'"><figure><a href="'.esc_url($url).'"><img src="'.$afb['sizes']['thumbnail'].'" alt="'.$afb['alt'].'" width="'.$afb['sizes']['thumbnail-width'].'" height="'.$afb['sizes']['thumbnail-height'].'"><figcaption><span>'.$name.' <span class="cta">'.__('More information',PEP_Theme_ID).'</span></span></figcaption></a></figure></div>';
					}

					$old_color=$color;
				}
			}
		}


		$return=ob_get_clean();
		$return='<div class="user-header-grid">'.$return.'</div>';
		return '<div class=""><div class="headers user-section">'.$return.'</div></div>';
	}


	public function render_section_posts($atts,$content) {

		if(!isset($atts['tag'])) return;

		$args=array(
			'posts_per_page'=>2,
			'post_type'=>'post',
			'tag__in'=>array($atts['tag']),
			'suppress_filters'=>false
		);

		$posts=get_posts($args);

		ob_start();
		$i=0;
		foreach($posts as $item) {
			setup_postdata( $item );
			$bg_url=get_the_post_thumbnail_url($item,'large');
			if ($i % 2) {
				$right_or_left='has-media-on-the-right';
			} else {
				$right_or_left='';
			}
			?>
			<div class="wp-block-media-text is-stacked-on-mobile <?php echo $right_or_left;?> is-image-fill has-gutenberg-7-background-color has-background">
				<figure class="wp-block-media-text__media" style="background-image:url(<?php echo $bg_url;?>);background-position:50% 50%">
					<?php echo get_the_post_thumbnail($item,'large');?>
				</figure>
				<div class="wp-block-media-text__content">
					<h3 class="has-gutenberg-2-color has-text-color"><?php echo $item->post_title;?></h3>
					<p><?php echo get_the_excerpt($item);?></p>
					<p class="has-text-align-right"><a href="<?php echo get_permalink($item);?>"><span><strong><?php _e('Read more',PEP_Theme_ID);?></strong></span> <span class="screen-reader-text"><?php sprintf(__('about %s',PEP_Theme_ID),$item->post_title);?></span></a></p>
				</div>
			</div>
			<?php
			$i++;
		}
		wp_reset_postdata();
		$return=ob_get_clean();
		$return='<div class="section-posts">'.$return.'</div>';

		return '<div class=""><div class="headers section-posts-header">'.$return.'</div></div><a class="more-articles" href="https://www.devoort.nl/nieuws/?expertise=' . $atts['tag'] . '">Lees alle artikelen over ' . strtolower(get_the_title()) . '<span class="screen-reader-text">vanaf ' . strtolower(get_the_title()) . '</span></a>';
	}

	public function custom_image_sizes($sizes) {
		return array_merge( $sizes, array(
			'header' => __( 'Header',PEP_Theme_ID ),
		) );
	}

	public function render_box($atts=array(),$content='') {
		$atts = shortcode_atts(
        array(
            'text' => __('De Voort Advocaten | Mediators',PEP_Theme_ID),
            'subtext' => __('Excelling together',PEP_Theme_ID),
			'search'=>'show',
			'buttons'=>false,
        ), $atts, 'box' );

		$return='<div class="header-text-box"><div class="header-text-box-grid"><span class="h1">'.$atts['text'].' <span>'.$atts['subtext'].'</span></span></div>';

		if($atts['search']=='show') {
			$return.='<div class="header-search-box">'.get_search_form(array('echo'=>false)).'</div>';
		}
		if($atts['buttons']=='show') {
			if(ICL_LANGUAGE_CODE=='en') {
				$return.='<div class="header-action-box"><a href="/en/expertises/">'.__('Expertises',PEP_Theme_ID).'</a><a href="/en/specialists/">'.__('All specialists',PEP_Theme_ID).'</a></div>';
			}
			if(ICL_LANGUAGE_CODE=='nl') {
				$return.='<div class="header-action-box"><a href="/expertises/">'.__('Expertises',PEP_Theme_ID).'</a><a href="/advocaten-mediators/">'.__('All specialists',PEP_Theme_ID).'</a></div>';
			}

		}

		$return.='</div>';


		return $return;
	}

	public function render_post_header() {

		include(PEP_Theme_VIEWS_PATH.'/post-header.php');

	}

	public function render_share_buttons($title='') {
		ob_start();
		if($title=="") $title=__('Share this article via:',PEP_Theme_ID)
		?>
		<div class="share-buttons">
			<h5><?php echo $title;?></h5>
			<ul class="share-buttons-list">
			<li><a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo get_permalink(); ?>" target="_blank">
				<img src="/wp-content/uploads/2021/01/linkedin.png" alt="LinkedIn" />
			</a></li>
			<li><!--<a href="#" onclick="jQuery('#gform_wrapper_1').toggle(); return false;" class="email-article" target="_blank"> -->
				<a href="mailto:?subject=Misschien interessant voor jou&amp;body=Bekijk dit artikel eens op <?php echo get_permalink(); ?>."
   title="Deel per e-mail">
				<img src="/wp-content/uploads/2021/01/email.png" alt="E-mail" />
			</a></li>
			<li><a href="#" onclick="window.print(); return false;">
				<img src="/wp-content/uploads/2021/01/printing.png" alt="Print" />
			</a></li>
			<li><?php echo do_shortcode('[dkpdf-button]');?></li>
			</ul>
		</div>
		<?php
		// echo do_shortcode('[gravityform id="1" title="true" description="false" ajax="true"]');
		return ob_get_clean();
	}

	public function render_author_info() {

		echo $this->render_share_buttons();
		$authors=get_coauthors();

		if(!empty($authors)) {
			foreach($authors as $author) {

				$photo=get_field('foto2','user_'.$author->data->ID);
				if(empty($photo)) {
					$photo=get_field('foto','user_'.$author->data->ID);
				}
				$specialismen = get_field('specialismen','user_'.$author->data->ID);
				$titel = get_field('titel','user_'.$author->data->ID);
				$name=$author->data->display_name;
				if(count($authors)>1 && strpos(strtolower($name), 'de voort') !== false) continue;

				$author_id=get_user_meta($author->data->ID,'user_post',true);
				$author_link=get_permalink($author_id);
				$author_more=get_author_posts_url($author->data->ID);

				$phone=get_field('telefoon','user_'.$author->data->ID);
				$email=$author->data->user_email;
				if($email!='helpdesk@pepbc.nl') {
					include(PEP_Theme_VIEWS_PATH.'/author.php');
				}
			}
		}
	}

	function render_blog_header() {

		global $post;

		$featured_image = get_post_thumbnail_id( $post->ID );

		$authors=get_coauthors();
		$author_photos = [];
		$author_names = [];

		if(!empty($authors)) {
			foreach($authors as $author) {
				$photo=get_field('foto3','user_'.$author->data->ID);
				if(empty($photo)) {
					$photo=get_field('foto','user_'.$author->data->ID);
				}
				$titel = get_field('titel','user_'.$author->data->ID);
				$name=$titel.' '.$author->data->display_name;
				if (!empty($photo)) {
					array_push($author_photos, $photo);
				}
				array_push($author_names, $name);
			}
		}


		$first_picture = wp_get_attachment_url($featured_image, 'full');
		$second_picture='';
		if(isset($author_photos[1])) {
			$second_picture = wp_get_attachment_url($author_photos[1], 'full');
		}

		$first_name = $author_names[0];
		$second_name='';
		if(isset($author_names[1])) {
			$second_name = $author_names[1];
		}

		if($first_name!="" && strpos(strtolower($second_name), 'de voort') !== false) {
			$second_name='';
		}

		if($second_name!="" && strpos(strtolower($first_name), 'de voort') !== false) {
			$first_name=$second_name;
			$first_picture=$second_picture;
		}

		if (empty($first_picture)) {
			$first_picture = '/wp-content/uploads/2020/12/computer-icons-user-profile-google-account-photos-icon-account.jpg';
		}

		if (empty($second_picture)) {
			$second_picture = '/wp-content/uploads/2020/12/computer-icons-user-profile-google-account-photos-icon-account.jpg';
		}


		 $class_1='';
		// if($first_picture['sizes']['single_blog-width']==$first_picture['sizes']['single_blog-height']) $class_1='image-square';
		 $class_2='';
		// if($second_picture['sizes']['single_blog-width']==$second_picture['sizes']['single_blog-height']) $class_2='image-square';

		if (!empty(trim($second_name)) && trim($second_name)!='helpdesk@pepbc.nl') {
			echo '<figure id="blog-header" class="blog-header alignwide size-large"><div class="title-author-box"><div class="featured-image"><img class="'.$class_1.'" src="' . $first_picture . '" alt="" ></div><div class="article-title"><h1>' . get_the_title() . '</h1><h2>' . $first_name . ' - ' . $second_name . '</h2></div></div></figure>';
		}
		elseif(trim($first_name)!='helpdesk@pepbc.nl') {
			echo '<figure id="blog-header" class="blog-header single-author alignwide size-large"><div class="title-author-box"><div class="featured-image"><img class="'.$class_1.'" src="' . $first_picture . '" alt="" ></div><div class="article-title"><h1>' . get_the_title() . '</h1><h2>' . $first_name . '</h2></div></div></figure>';
		} else {
			echo '<figure id="blog-header" class="blog-header no-author alignwide size-large"><div class="title-author-box"><div class="article-title"><h2>' . get_the_title() . '</h2></div></div></figure>';
		}
	}

	function render_archive_header() {
		//echo '<figure class="wp-block-image alignwide size-large" data-view="in-view"><img loading="lazy" width="1024" height="683" src="/wp-content/uploads/2020/10/kantoor-1024x683.jpg" alt="" class="wp-image-168" srcset="/wp-content/uploads/2020/10/kantoor-1024x683.jpg 1024w, /wp-content/uploads/2020/10/kantoor-350x233.jpg 350w, /wp-content/uploads/2020/10/kantoor-906x604.jpg 906w, /wp-content/uploads/2020/10/kantoor-768x512.jpg 768w, /wp-content/uploads/2020/10/kantoor-1536x1024.jpg 1536w, /wp-content/uploads/2020/10/kantoor-112x75.jpg 112w, /wp-content/uploads/2020/10/kantoor-160x107.jpg 160w, /wp-content/uploads/2020/10/kantoor-600x400.jpg 600w, /wp-content/uploads/2020/10/kantoor-300x200.jpg 300w, /wp-content/uploads/2020/10/kantoor.jpg 1981w" sizes="(max-width: 1024px) 100vw, 1024px"></figure>';

		if (function_exists('z_taxonomy_image_url') && !empty(z_taxonomy_image_url())) {
			$image=z_taxonomy_image_url();
		} elseif(is_author()) {
			$image=get_the_post_thumbnail_url(177,'header');

		} else {
			$image='/wp-content/uploads/2020/10/kantoor-1600x450.jpg';
		}
		echo '<div class="entry-content"><figure class="wp-block-image alignwide size-header" data-view="in-view" style="background-image:url('.$image.');"><img width="1600" height="450" src="'.$image.'" alt="" class=""></figure>';
		echo do_shortcode('[box]');
		echo '</div>';
	}

	public function remove_titles_all_single_posts() {
	    if ( is_singular('post') ) {
	        remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	    }
	}

	public function render_mobile_menu_info($menu,$args) {
			// if(!wp_is_mobile()) return $menu;
			if ( 'primary' !== $args->theme_location )return $menu;

			if(ICL_LANGUAGE_CODE!='nl') {
				$phone_number=get_theme_mod('company_phone_int',get_theme_mod('company_phone'));
			} else {
				$phone_number=get_theme_mod('company_phone');
			}

			ob_start();
			echo '<div class="nav-extra-info">';
			?>
			<p class="has-gutenberg-2-color has-text-color has-large-font-size"><strong><?php _e('Excelling together.',PEP_Theme_ID);?></strong></p>
			<p><i class="fas fa-phone"></i><a href="tel:<?php echo str_replace(' ','',str_replace('-','',$phone_number));?>" class="nav-phone"><?php echo $phone_number;?></a></p>
			<?php
			get_search_form(array('echo'=>true));
			echo '</div>';
			$data=ob_get_clean();
			$menu=$menu.' <li class="extra-nav-data hide-desktop">'.$data.'</li>';
			return $menu;
	}

	public function render_mobile_menu_button() {
		if(ICL_LANGUAGE_CODE!='nl') {
			$phone_number=get_theme_mod('company_phone_int',get_theme_mod('company_phone'));
		} else {
			$phone_number=get_theme_mod('company_phone');
		}
		?>
		<div class="hide-desktop mobile-header-icons">
			<ul>
				<li>
					<a href="tel:<?php echo str_replace(' ','',str_replace('-','',$phone_number));?>" class="nav-phone"><i class="fas fa-phone"></i><span class="screen-reader-text"><?php echo $phone_number;?></span></a>
				</li>
				<li>
					<a href="#" class="trigger-search"><i class="fas fa-search"></i><span class="screen-reader-text"><?php _e('Search',PEP_Theme_ID);?></span></a>
				</li>
				<?php
					$url = get_the_permalink();

					$wpml_permalinkEN = apply_filters( 'wpml_permalink', $url , 'en' );
					$wpml_permalinkNL = apply_filters( 'wpml_permalink', $url , 'nl' );
				?>
				<li class="WPMLmenu">
					<a title="NL" href="<?php echo $wpml_permalinkNL; ?>">
						<span class="wpml-ls-display">NL</span>
					</a>
				</li>
				<li class="WPMLmenu">
					<a title="EN" href="<?php echo $wpml_permalinkEN; ?>">
						<span class="wpml-ls-display">EN</span>
					</a>
				</li>
			</ul>
		</div>
		<?php
	}

	public function render_desktop_menu_button() {
		if(ICL_LANGUAGE_CODE!='nl') {
			$phone_number=get_theme_mod('company_phone_int',get_theme_mod('company_phone'));
		} else {
			$phone_number=get_theme_mod('company_phone');
		}
		?>
		<div class="hide-mobile mobile-header-icons">
			<ul>
				<li>
					<a href="tel:<?php echo str_replace(' ','',str_replace('-','',$phone_number));?>" class="nav-phone"><i class="fas fa-phone"></i><span class="screen-reader-text"><?php echo $phone_number;?></span></a>
				</li>
				<li>
					<a href="#" class="trigger-search"><i class="fas fa-search"></i><span class="screen-reader-text"><?php _e('Search',PEP_Theme_ID);?></span></a>
				</li>
			</ul>
		</div>
		<?php
	}

	public function render_faillissementen_sc($atts,$content) {
		$args=array(
			'posts_per_page'=>-1,
			'post_type'=>'faillissement',
			'orderby'=>'title',
			'order'=>'ASC',
			'suppress_filters'=>false,
			'meta_query'=>array(
				array(
					'key'=>'datum_uitspraak',
				),
			),
		);
		$the_query = new \WP_Query( $args );
		// The Loop

		ob_start();

		?>

		<fieldset class="curators">
			<legend class="screen-reader-text"><?php _e('Filter bankruptcy by curator.',PEP_Theme_ID);?></legend>

		<?php
		$user_display_names=$users=array();
		if($atts['user']!="") {
			?><div><div class="headers user-section">
			<div class="user-header-grid">
			<?php
			$users=explode(',',$atts['user']);
			foreach($users as $user_id) {

				$afb=get_field('foto4','user_'.$user_id);
				if(empty($afb)) {
					$afb=get_field('foto','user_'.$user_id);
				}

				if(isset($afb['sizes']['thumbnail'])) {
					$title=get_field('titel','user_'.$user_id);
					$user=get_user_by('ID',$user_id);

					$name=trim($title.' '.$user->data->display_name);
					$user_display_names[$user_id]=$name;
					echo '<div class="user"><input type="checkbox" name="curator[]" id="curator_'.$user_id.'" class="curator-search" value="'.$user_id.'"><label for="curator_'.$user_id.'"><figure><img src="'.$afb['sizes']['thumbnail'].'" alt="'.$afb['alt'].'" width="'.$afb['sizes']['thumbnail-width'].'" height="'.$afb['sizes']['thumbnail-height'].'"><figcaption><span>'.$name.'</span></figcaption></figure></label></div>';
				}
			}
			?>
			</div></div></div>
			<div class="bankruptcy-search-grid">
			<label for="bankruptcy_search"><?php _e('Or search for a keyword',PEP_Theme_ID);?></label><div><input type="text" value="" placeholder="<?php _e('Result should contain the following',PEP_Theme_ID);?>" id="bankruptcy_search"></div>
			</div><?php

		}

		if ( $the_query->have_posts() ) {
			?>
			<div id="bankruptcy" aria-live="polite">
			<table class="bankruptcy-table">
				<caption class="screen-reader-text"><?php _e('Table with overview Bankruptcy',PEP_Theme_ID);?></caption>
				<thead>
					<th scope="col" class="case-number"><?php _e('Number',PEP_Theme_ID);?></th>
					<th scope="col" class="name"><?php _e('Company Name',PEP_Theme_ID);?></th>
					<th scope="col" class="curator"><?php _e('Curator',PEP_Theme_ID);?></th>
				</thead>
				<tbody>
			<?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post();

				$users=get_field('author');
				$author=array();
				$curators='';
				foreach($users as $user_id) {
					$user_id=get_post_meta($user_id,'user_id',true);
					$user=get_user_by('ID',$user_id);
					if(isset($user_display_names[$user_id])) $author[]=$user_display_names[$user_id];

					$curators.=' curator-'.$user_id;

				}
				$author=implode(',<br> ',$author);


				$title_name=get_field('naam_failliet');
				if($title_name=="") $title_name=get_the_title();

				?>
				<tr class="curator<?php echo $curators;?>">
					<td class="case-number" data-title="<?php _e('Number',PEP_Theme_ID);?>"><a href="<?php echo get_permalink();?>"><?php echo get_field('detail_info_number');?></a></td>
					<td class="name" data-title="<?php _e('Company Name',PEP_Theme_ID);?>"><?php echo $title_name;?></td>
					<td class="curator" data-title="<?php _e('Curator',PEP_Theme_ID);?>"><?php echo $author;?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
			</table>
			<div>
			<script>
				jQuery(document).ready(function($) {
					$('.curator-search').on('change',function() {
						$('.bankruptcy-table tbody tr').addClass('hide-curator');
						var no_users=true;
						$('.curator-search:checked').each(function() {
							var user_id=$(this).val();
							no_users=false;
							$('.bankruptcy-table tbody tr.curator-'+user_id).removeClass('hide-curator');
						});
						if(no_users==true) {
							$('.bankruptcy-table tbody tr').removeClass('hide-curator');
						}
					});
					$('#bankruptcy_search').on('keyup',function() {
						var value=$(this).val().toLowerCase();
						$('.bankruptcy-table tbody tr').filter(function() {
							 $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
						});
					});

					$('.bankruptcy-table tbody tr').on('click',function() {
						var url=$(this).find('a').attr('href');
						location.href=url;
					});
				});
			</script>
			<?php

		} else {
			// no posts found
		}
		/* Restore original Post Data */
		wp_reset_query();

		return ob_get_clean();
	}

	public function create_post_for_user($user_id) {

		$post_id=(int)get_user_meta($user_id,'user_post',true);
		$user=get_user_by('ID',$user_id);
		$display_name=$user->data->display_name;
		$titel=strtolower(get_field('titel','user_'.$user_id));
		$achternaam=get_field('lastname_filter','user_'.$user_id);

		if($achternaam=="") return $user_id;

		$foto1=get_field('foto','user_'.$user_id);

		$foto3_id=get_field('foto3','user_'.$user_id);
		$foto3=wp_get_attachment_image_src($foto3_id,'full');

		ob_start();

		$specialisatie='';
		$specialismen=get_field('specialismen','user_'.$user_id);

		$telefoon=get_field('telefoon','user_'.$user_id);
		if($telefoon=="") $telefoon=get_theme_mod('company_phone');

		if(!empty($specialismen)) {
			$specialisatie = '<ul class="expertises">';
			foreach($specialismen as $tag_id) {
				$tag=get_tag($tag_id);

				$specialisatie .= '<li><a href="'.get_tag_link($tag_id).'">'.$tag->name.'</a></li>';
			}
			$specialisatie .= '</ul>';
		}

		$aandachtsgebied=get_field('aandachtsgebied','user_'.$user_id);
		if($aandachtsgebied!="") {
			$aandachtsgebied='<li><strong>Aandachtsgebieden:</strong> '.$aandachtsgebied.'</li>';
		}

		$nevenfuncties=get_field('nevenfunctiesactiviteiten','user_'.$user_id);
		?>
		<!-- wp:image {"align":"wide","id":1762,"sizeSlug":"header","linkDestination":"none"} -->
<figure class="wp-block-image alignwide size-header"><img src="/wp-content/uploads/2021/02/specialisten-2-1600x450.jpg" alt="<?php echo $display_name;?>" class="wp-image-1762"/></figure>
<!-- /wp:image -->

<!-- wp:shortcode -->
[box]
<!-- /wp:shortcode -->

<!-- wp:yoast-seo/breadcrumbs /-->

<!-- wp:heading {"level":1} -->
<h1><?php echo $titel;?> <?php echo $display_name;?></h1>
<!-- /wp:heading -->
<!-- wp:media-text {"align":"","mediaPosition":"right","mediaId":<?php echo $foto1['ID'];?>,"mediaLink":"/<?php echo $foto1['name'];?>/","mediaType":"image","verticalAlignment":"top","imageFill":true,"backgroundColor":"gutenberg_7"} -->
<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-top is-image-fill has-gutenberg-7-background-color has-background"><?php if($foto1['ID']!="" && $foto1['url']!="") { ?><figure class="wp-block-media-text__media" style="background-image:url(<?php echo $foto1['url'];?>);background-position:50% 50%"><img src="<?php echo $foto1['url'];?>" alt="" class="wp-image-<?php echo $foto1['ID'];?> size-full"/></figure><?php } ?><div class="wp-block-media-text__content"><!-- wp:list -->
<ul><li><strong>Telefoon: </strong><a href="tel:<?php echo $telefoon;?>"><?php echo $telefoon;?></a></li><li><strong>E-mail:</strong> <a href="mailto:<?php echo $user->data->user_email;?>"><?php echo $user->data->user_email;?></a></li><li><strong>Functie: </strong><?php echo get_field('functie','user_'.$user_id);?></li><li>[user-socials user_id=<?php echo $user_id;?>]</li><?php if($specialisatie!="") { ?><li><strong>Expertises:</strong> <?php echo $specialisatie;?></li><?php } echo $aandachtsgebied; if($nevenfuncties!="") { ?><li><strong>Nevenfuncties:</strong> <?php echo $nevenfuncties;?></li> <?php } ?></ul>
<!-- /wp:list -->
<?php if(get_field('overige_nevenfuncties','user_'.$user_id)!="" || get_field('publicaties','user_'.$user_id)!="") { ?>
<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><em>Voor overige nevenfuncties en publicaties zie onderaan deze pagina.</em></p>
<!-- /wp:paragraph --><?php } ?></div></div>
<!-- /wp:media-text -->

<?php echo get_field('about','user_'.$user_id);?>

<!-- wp:genesis-blocks/gb-spacer -->
<div style="color:#ddd" class="wp-block-genesis-blocks-gb-spacer gb-block-spacer gb-divider-solid gb-divider-size-1"><hr style="height:30px"/></div>
<!-- /wp:genesis-blocks/gb-spacer -->

<?php if($foto3_id!="" && $foto3[0]!="") { ?><!-- wp:image {"id":<?php echo $foto3_id;?>,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo $foto3[0];?>" alt="<?php echo $display_name;?>" class="wp-image-<?php echo $foto3_id;?>"/></figure>
<!-- /wp:image -->
<?php } ?>

<!-- wp:genesis-blocks/gb-spacer {"spacerHeight":50} -->
<div style="color:#ddd" class="wp-block-genesis-blocks-gb-spacer gb-block-spacer gb-divider-solid gb-divider-size-1"><hr style="height:50px"/></div>
<!-- /wp:genesis-blocks/gb-spacer -->

<?php if(get_field('overige_aandachtsgebieden','user_'.$user_id)!="") { ?>
<!-- wp:heading -->
<h2>Overige aandachtsgebieden</h2>
<!-- /wp:heading -->

<?php echo get_field('overige_aandachtsgebieden','user_'.$user_id);?>
<?php } ?>
<?php if(get_field('overige_nevenfuncties','user_'.$user_id)!="") { ?>
<!-- wp:heading -->
<h2>Overige nevenfuncties</h2>
<!-- /wp:heading -->

<?php echo get_field('overige_nevenfuncties','user_'.$user_id);?>
<?php } ?>
<?php if(get_field('publicaties','user_'.$user_id)!="") { ?>
<!-- wp:heading -->
<h2>Publicaties</h2>
<!-- /wp:heading -->

<?php echo get_field('publicaties','user_'.$user_id);?>
<?php } ?>
<?php if(get_field('registratie_rechtsgebieden','user_'.$user_id)!="") { ?>
<!-- wp:heading -->
<h2>Rechtsgebiedenregister</h2>
<!-- /wp:heading -->
<?php echo get_field('registratie_rechtsgebieden','user_'.$user_id);
}

		$content=ob_get_clean();

		$post_data=array(
			'ID'=>0,
			'post_title'=>$titel.' '.$display_name,
			'post_content'=>$content,
			'post_type'=>'specialists',
			'post_status'=>'publish',
			'page_template'=>'page_author.php',
		);
		if($post_id!="" && $post_id!=0) {
			$post_data['ID']=$post_id;
			//$post_id=wp_update_post($post_data);
		} else {
			$post_id=wp_insert_post($post_data);

		}


		$lastname_filter=get_field('lastname_filter','user_'.$user_id);
		if($lastname_filter=="") {
			$userdata=get_userdata($user_id);
			$lastname_filter=$userdata->last_name;

		}

		update_post_meta($post_id,'get_author',$user_id);
		update_post_meta($post_id,'user_email',$user->data->user_email);
		update_post_meta($post_id,'display_name',$display_name);
		update_post_meta($post_id,'lastname',$lastname_filter);
		update_post_meta($post_id,'user_id',$user_id);
		update_post_meta($post_id,'specialismen',(array)get_field('specialismen','user_'.$user_id));

		$thumbnail_id=get_field('foto3','user_'.$user_id);
		if(empty($thumbnail_id)) {
			$user_foto=get_field('foto','user_'.$user_id);
			$thumbnail_id=$user_foto['ID'];
		}

		set_post_thumbnail($post_id,$thumbnail_id);


		update_user_meta($user_id,'user_post',$post_id);
		//update_user_meta($user_id,'page_id',$post_id);

	}

	public function delete_post_from_user($user_id) {
		$post_id=(int)get_user_meta($user_id,'user_post',true);
		wp_delete_post($post_id,true);
	}

	public function filter_user_link($post_link,$post,$leavename,$sample) {

		if($post->post_type!='user') return $post_link;

		$user_id=(int)get_post_meta($post->ID,'user_id',true);
		if(isset($user_id) && $user_id>0) {
			$post_link=get_author_posts_url($user_id);
		}

		return $post_link;
	}

	public function render_search_description() {
		?>
		<p class="description">
			<?php _e('Use the search field or select the expertise within which you want to search.',PEP_Theme_ID);?>
		</p>
		<?php
	}

	public function advanced_search_query($query) {
		if(is_admin()) return $query;
		$post_type = [];
		if ( isset( $_REQUEST['search'] ) && $_REQUEST['search'] == 'advanced' && ! is_admin() && $query->is_search && $query->is_main_query() ) {
			if(isset($_GET['post_type'])) {
				$post_types=$post_type=explode(',',$_GET['post_type']);

				if(in_array('articles',$post_type)) {
					$post_types=array();
					$post_types[]='post';
					//$post_types[]='page';
				}

				$query->set( 'post_type', $post_types );
			}
			$meta_query = array(
				'relation' => 'AND',
				'no_sitemap' => array(
					'key' => 'no_sitemap',
					'compare'=>'NOT EXISTS',
				),
				'no_index' => array(
					'key'=>'_yoast_wpseo_meta-robots-noindex',
					'compare'=>'NOT EXISTS',
				),
			);
			$query->set('meta_query',$meta_query);


			if(isset($_GET['tag'])) {
				$tag=$_GET['tag'];

				$query->set( 'tag__in', $tag );

			}

			if(isset($_GET['cat'])) {
				$cat=$_GET['cat'];
				$query->set( 'category__in', $cat );
			}
		}
	}

	public function render_advanced_search() {
		if(!is_search() && !is_page('zoeken') && !is_page('search') && !is_archive() ) return false;
		global $wp;
		$current_page=home_url( $wp->request );

		/* Get all post types */
		$post_types=get_post_types(array('public'=>true,'_builtin'=>false),'objects');

		$post_type_list=array();
		$post_type_list['articles']=__('Articles',PEP_Theme_ID);
		foreach($post_types as $post_type) {
			$post_type_list[$post_type->name]=__($post_type->label,PEP_Theme_ID);
		}
		unset($post_type_list['client']);
		unset($post_type_list['guest-author']);

		//$post_type_list['user']=__('Specialists',PEP_Theme_ID);

		if(!isset($_GET['post_type'])) $_GET['post_type']='any';

		/* Get all post categories */
		$categories=get_categories();
		$category_list=array();
		foreach($categories as $category) {
			$category_list[$category->term_id]=$category->name;
		}

		/* Get all post categories */
		$tags=get_tags(array('hide_empty'=>false));
		$tag_list=array();
		foreach($tags as $tag) {
			$tag_list[$tag->term_id]=$tag->name;
		}
		
        $this->print_inline_script();
        include(PEP_Theme_VIEWS_PATH.'/advanced-search.php');
    }

    private function print_inline_script() {
        ?>
        <script>
            jQuery(document).ready(function ($) {
                function updateSearchResults() {
                    var searchForm = $('.advanced-search');
                    var formData = searchForm.serialize();

                    $.ajax({
                        url: ajax_search_object.ajaxurl,
                        type: 'GET',
                        data: formData,
                        beforeSend: function () {
                            // Show a loader or message if needed.
                        },
                        success: function (response) {
                            // Replace search results with new content.
                            $('.search-results-container').html(response);
                        },
                        error: function () {
                            // Display an error message if needed.
                        },
                    });
                }

                $('.tag-list input[type="checkbox"]').on('change', function () {
                    updateSearchResults();
                });
            });
        </script>
        <?php
    }



	public function register_block_patterns() {
		register_block_pattern(
			'pep/header',
			array(
				'title'       => __( 'Page Header', PEP_Theme_ID),
				'description' => _x( 'Creates default page header', 'Block pattern description', PEP_Theme_ID ),
				'content'     => "<!-- wp:image {\"align\":\"wide\",\"id\":168,\"sizeSlug\":\"large\"} --><figure class=\"wp-block-image alignwide size-large\"><img src=\"/wp-content/uploads/2020/10/kantoor-1024x683.jpg\" alt=\"\" class=\"wp-image-168\"/></figure><!-- /wp:image --><!-- wp:shortcode -->[box]<!-- /wp:shortcode --><!-- wp:heading {\"level\":1}--><h1>De Voort Advocaten | Mediators</h1><!-- /wp:heading -->",
				'categories'  => array('header'),
			)
		);
	}

	public function filter_content_imports($content) {
		return $content;
	}

	public function override_yoast_breadcrumb_trail($links) {

		if(is_author() || get_post_type()=='specialists') {

			$author_links=array(
				$links[0],
				array(
					'url' =>get_permalink(icl_object_id(177,'page')),
					'text'=>get_the_title(icl_object_id(177,'page')),
					'id'=>icl_object_id(177,'page'),
				),
				$links[1],
			);

			$links=$author_links;
		}


		if(get_post_type()=='faillissement') {

			$author_links=array(
				$links[0],
				array(
					'url' =>get_permalink(icl_object_id(554,'page')),
					'text'=>get_the_title(icl_object_id(554,'page')),
					'id'=>icl_object_id(554,'page'),
				),
				$links[1],
			);

			$links=$author_links;
		}



		$new_links=array();
		foreach($links as $link) {
			$new_links[]=array(
				'url'=>$link['url'],
				'text'=>$this->substrwords($link['text'],50),
				'id'=>((isset($link['id'])) ? $link['id'] : ''),
			);
		}
		return $new_links;
	}

	public function substrwords($text, $maxchar, $end='...') {
    if (strlen($text) > $maxchar || $text == '') {
        $words = preg_split('/\s/', $text);
        $output = '';
        $i      = 0;
        while (1) {
            $length = strlen($output)+strlen($words[$i]);
            if ($length > $maxchar) {
                break;
            }
            else {
                $output .= " " . $words[$i];
                ++$i;
            }
        }
        $output .= $end;
    }
    else {
        $output = $text;
    }
    return $output;
	}

	public function trim_title($title,$id) {
		if((is_archive() || is_home() || is_front_page()) && 'post'==get_post_type($id)) {
			return $this->substrwords($title,50);
		}
		return $title;
	}

	function search_title_text($default) {
		 $title = __( 'Search Results for:', 'genesis' );
		 if(get_search_query()=="") {
			 $title=__('Search',PEP_Theme_ID);
		 }

		 return $title;

	}

	function dkpdf_header_title($title) {
		$authors=get_coauthors();
		$author='';
		$author_list=array();
		foreach($authors as $user) {
			if($user->data->display_name=='helpdesk@pepbc.nl') continue;
			$author_list[]=$user->data->display_name;
		}
		if(!empty($author_list)) {
			$author='<br>'.implode(', ',$author_list);
		}

		return '<b>'.$title.'</b><br>Gepubliceerd: '.get_the_date().$author;
	}

	public function render_archive_header_title() {
		if(is_search()) {
			global $wp_query;
			remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
			return true;
		}

		global $wp_query;

		$term = is_tax() ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();

		if(isset($term->term_id)) {
			remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
			$heading = get_term_meta( $term->term_id, 'headline', true );
			if ( empty( $heading ) && genesis_a11y( 'headings' ) ) {
				$heading = $term->name;
			}

			include(PEP_Theme_VIEWS_PATH.'/archive-header.php');

			//$term=$this->get_post_primary_category(get_the_ID());
		}
	}

	public function user_socials($atts,$content) {
		if((int)$atts['user_id']==0) return false;

		$author_id=(int)$atts['user_id'];

		$user=array('social'=>array(
			'LinkedIn'=>get_the_author_meta( 'linkedin', $author_id ),
		));


		$twitter=get_the_author_meta( 'twitter', $author_id );

		if($twitter!="") {
			$user['social']['Twitter']='https://twitter.com/'.$twitter;
		}

		ob_start();
		if(!empty($user['social'])) {
			echo '<ul class="user-social">';
		foreach($user['social'] as $label=>$value) {
			if($value!="") echo '<li class="'.sanitize_title($label).'"><a class="flaticon flaticon-'.sanitize_title($label).'" href="'.$value.'" target="_blank"><span class="screen-reader-text">'.sprintf(__('Follow us on %s',PEP_Theme_ID),$label).'</span></a></li>';
		}
		echo '</ul>';
		}
		return ob_get_clean();

	}

	public function user_getfield($atts,$content) {
		if((int)$atts['user_id']==0) return false;
		if($atts['field']=="") return false;

		$author_id=(int)$atts['user_id'];
		$field=$atts['field'];
		if ($field == "telefoon") {
			$user_info = get_userdata($author_id);
			$telefoon=get_field('telefoon','user_'.$author_id);
			$val = "<a href='tel:".$telefoon."'>" . $telefoon . "</a>";
		} elseif ($field == "email") {
			$user_info = get_userdata($author_id);
			$user_mail = $user_info->user_email;

			$val = "<a href='mailto:".$user_mail."'>" . $user_mail . "</a>";
		} elseif ($field == "specialismen") {
			$temp = get_field( $field, 'user_' . $author_id );
			$specialismenStr = '<ul class="expertises">';
			foreach ($temp as $term_id ) {
				$specialismenStr .= "<li><a href='".get_term_link( $term_id )."'><span>".get_term( $term_id )->name."</span></a></li>";
			}
			$specialismenStr .= '</ul>';
			$val = $specialismenStr;
		} elseif ($field == "titel-naam") {
			$user_info = get_userdata($author_id);
			$title = get_field('titel', 'user_' . $author_id);
			$first_name = $user_info->first_name;
			$last_name = $user_info->last_name;

			$val = $title . ' ' . $first_name . ' ' . $last_name;
		}

		elseif ($field == "foto3") {
			$picture_id = get_field('foto3', 'user_' . $author_id);
			$val = '<img src="' . wp_get_attachment_url( $picture_id, 'full' ) . '">';
		}


		else {
			$val = get_field( $field, 'user_' . $author_id );
		}

		ob_start();
		echo $val;
		return ob_get_clean();
	}

	public function user_title($atts,$content) {
		global $post;
		$author_id=get_field('get_author');
		ob_start();
		echo '<pre>';
		print_r($author_id);
		//return get_field('titel','user_'.$author_id[0]);
		return ob_get_clean();

	}

	public function render_bankruptcy_details_sc($atts,$content) {
		ob_start();


		$title_name=get_field('naam_failliet');
		if($title_name=="") $title_name=get_the_title();
		?>
		<ul class="bankruptcy-details">
			<li><span><?php _e('Case number:',PEP_Theme_ID);?></span> <?php echo get_field('detail_info_number');?></li>
			<li><span><?php _e('Company name:',PEP_Theme_ID);?></span> <?php echo $title_name;?></li>
			<?php if(get_field('adres')!="") { ?>
			<li><span><?php _e('Address:',PEP_Theme_ID);?></span> <?php echo get_field('adres');?></li>
			<?php }
			if(get_field('plaats')!="") { ?>
			<li><span><?php _e('City:',PEP_Theme_ID);?></span> <?php echo get_field('plaats');?></li>
			<?php } ?>

			<?php $authors=get_field('author');

			$curator=array();
			foreach($authors as $author) {
				$curator[]='<a href="'.get_permalink($author).'">'.get_the_title($author).'</a>';
			}
			$authors=implode(', ',$curator);
			if(!empty($authors)) {
			?>
			<li><span><?php _e('Curator:',PEP_Theme_ID);?></span> <?php echo $authors;?></li>

			<?php
			}
			if($url=get_field('verwijzing_publicatie_openbare_verslagen')) {

				if (strpos($url, 'rechtspraak.nl') !== false) {
					?>
					<li><span><?php _e('Public reports:',PEP_Theme_ID);?></span> <a target="_blank" rel="noopener" href="<?php echo $url;?>"><?php _e('View the Central Insolvency Register at rechtspraak.nl',PEP_Theme_ID);?></a></li>
					<?php

				} else {
					?>
					<li><span><?php _e('Public reports:',PEP_Theme_ID);?></span> <a target="_blank" rel="noopener" href="<?php echo $url;?>"><?php echo $url;?></a></li>
					<?php
				}
			} ?>
		</ul>
		<?php
		$download=get_field('vonnis_faillissement');
		$file2=get_field('file_2');
		$file3=get_field('file_3');

		if(!empty($download) || !empty($file2) || !empty($file3)) {
		?>
		<h4><?php _e('Download files:',PEP_Theme_ID);?></h4>
			<ul>
			<?php if($download['url']!="") { ?><li><a href="<?php echo $download['url'];?>" target="_blank"><?php _e('Bankruptcy judgment',PEP_Theme_ID);?></a></li><?php } ?>
			<?php if($file2['url']!="") { ?><li><a href="<?php echo $file2['url'];?>" target="_blank"><?php echo $file2['title'];?></a></li><?php } ?>
			<?php if($file3['url']!="") { ?><li><a href="<?php echo $file3['url'];?>" target="_blank"><?php echo $file3['title'];?></a></li><?php } ?>
			</ul>
		<?php
		}


		return ob_get_clean();

	}
	public function render_bankruptcy_date(){
		if(get_field('datum_uitspraak')!="") {
			$date_string=get_field('datum_uitspraak');
			$date = \DateTime::createFromFormat('d/m/Y', $date_string);
			$date=date_i18n(get_option('date_format'),strtotime($date->format(get_option('date_format'))));
			echo '<span class="statement-date">'.sprintf(__('Statement date: %s',PEP_Theme_ID),$date).'</span>';
		}
	}

	public function render_faill_header() {
		$url=get_the_post_thumbnail_url(icl_object_id(554,'page'),'header');
		echo '<figure id="blog-header" class="blog-header no-author alignwide size-large" style="background:url('.$url.') no-repeat center center / cover !important;"><div class="title-author-box">
			<div class="article-title"><span>' . __(' Bankruptcy ',PEP_Theme_ID) . '</span></div></div>
		</figure>';


		include(PEP_Theme_VIEWS_PATH.'/bankruptcy-header.php');
	}

	public function render_bankruptcy_details() {
		global $post;
		$content=get_the_content();
		if(!has_shortcode($content,'bankruptcy-details')) {
			echo $this->render_bankruptcy_details_sc(false,false);
		}
	}

	public function render_sc_share_buttons($atts,$content) {
		return $this->render_share_buttons(__('Share this page via:',PEP_Theme_ID));
	}

	public function filter_search() {

		/* Get all post categories */
		$tags=get_tags(array('hide_empty'=>false));
		$author_list=$tag_list=array();
		foreach($tags as $tag) {
			$tag_list[$tag->term_id]=$tag->name;
		}

		$args=array(
			'post_type'=>'specialists',
			'posts_per_page'=>-1,
			'meta_key'=>'lastname',
			'orderby'=>'meta_value',
			'order'=>'ASC',
		);
		$authors=get_posts($args);

		if(!empty($authors)) {
			foreach($authors as $author) {
				$author_id=get_post_meta($author->ID,'user_id',true);
				$name=get_post_meta($author->ID,'display_name',true);
				$titel = get_field('titel','user_'.$author_id);
				$author_list[$author_id]=array('fullname'=>$titel.' '.$name,'slug'=>'cap-'.sanitize_title($name));
			}
		}
		?>
		<form id="advanced-filter">
			<label for="search_text" class="screen-reader-text"><?php _e("I'm looking for...",PEP_Theme_ID);?></label>
			<input type="search" name="keyword" id="search_text" placeholder="<?php _e("I'm looking for...",PEP_Theme_ID);?>" required>
			<div class="filters">
			<details id="expertise">
				<summary><?php _e('Expertise',PEP_Theme_ID);?> <span class="counter"></span></summary>
				<fieldset>
					<div class="filter-col-1">
						<legend><?php _e('Expertise',PEP_Theme_ID);?></legend>
						<p><?php _e('Select the desired filter items',PEP_Theme_ID); // Selecteer de gewenste filteritems ?>
					</div>
					<div class="filter-col-2">
						<input type="text" class="filter-tags" placeholder="<?php _e('Start typing...',PEP_Theme_ID); //Begin met typen... ?>">
						<?php
						if(!empty($tag_list)) {
							echo '<ul class="tag-list">';
							foreach ($tag_list as $term_id=>$tag_name) {
								echo '<li data-name="'.strtolower($tag_name).'"><input type="checkbox" id="tag_'.$term_id.'" name="tag[]" value="'.$term_id.'"><label for="tag_'.$term_id.'">'.$tag_name.'</label></li>';
							}
							echo '</ul>';
						}
						?>
					</div>
				</fieldset>
			</details>
			<details id="author">
				<summary><?php _e('Author',PEP_Theme_ID);?> <span class="counter"></span></summary>
				<fieldset>
					<div class="filter-col-1">
						<legend><?php _e('Author',PEP_Theme_ID);?></legend>
						<p><?php _e('Select the desired filter items',PEP_Theme_ID); // Selecteer de gewenste filteritems ?>
					</div>
					<div class="filter-col-2">
						<input type="text" class="filter-authors" placeholder="<?php _e('Start typing...',PEP_Theme_ID); //Begin met typen... ?>">
						<?php
						if(!empty($author_list)) {
							echo '<ul class="author-list">';
							foreach ($author_list as $author_id=>$name) {
								echo '<li data-name="'.strtolower($name['fullname']).'"><input type="checkbox" id="author_'.$author_id.'" name="author[]" value="'.sanitize_title($name['slug']).'"><label for="author_'.$author_id.'">'.$name['fullname'].'</label></li>';
							}
							echo '</ul>';
						}
						?>
					</div>
				</fieldset>
			</details>
			</div>
			<div class="loading-spinner">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgba(255, 255, 255, 0); display: block; shape-rendering: auto;" width="32px" height="32px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
					<circle cx="50" cy="50" fill="none" stroke="#8db4dc" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
					  <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"/>
					</circle>
				</svg>
			</div>
			<button type="submit"><?php _e('Search',PEP_Theme_ID);?></button>
		</form>
		<script>
			jQuery(document).ready(function($) {

				var getUrlParameter = function getUrlParameter(sParam) {
				    var sPageURL = window.location.search.substring(1),
				        sURLVariables = sPageURL.split('&'),
				        sParameterName,
				        i;

				    for (i = 0; i < sURLVariables.length; i++) {
				        sParameterName = sURLVariables[i].split('=');

				        if (sParameterName[0] === sParam) {
				            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
				        }
				    }
				    return false;
				};


				if (window.location.href.indexOf('?expertise=') > 0) {
					var details=$(this).closest('details');
					var counter = 1;
				    var expertise = getUrlParameter('expertise');
				    $('#tag_' + expertise).trigger('click');
				    search_filter();
				    $('#expertise .counter').html('<span>'+counter+'</span>');

				}

				$('#advanced-filter ul input').on('change',function(){
					var list=$(this).closest('ul');
					var details=$(this).closest('details');
					var counter=0;
					$(list).find('input:checked').each(function() {
						counter+=1;
					});
					if(counter>0) {
						$(details).find('.counter').html('<span>'+counter+'</span>');
					} else {
						$(details).find('.counter').html('');
					}
				});
				$('.filter-tags').on('keyup',function() {
					var value=$(this).val().toLowerCase();
					$('.tag-list li').filter(function() {
						 $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
					});
				});
				$('.filter-authors').on('keyup',function() {
					var value=$(this).val().toLowerCase();
					$('.author-list li').filter(function() {
						 $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
					});
				});

				$('#advanced-filter input[type="checkbox"]').on('change',function() {
					search_filter();
				});

				$('#advanced-filter input[type="search"]').on('change keyup',function() {
					var value=$(this).val();
					if(value.length>3 || value.length==0) {
						search_filter();
					}
				});

				$('#advanced-filter').submit(function(e){
					e.preventDefault();
					search_filter();

					return false;
				});



				function search_filter() {
					var filter = $('#advanced-filter');

					clearTimeout($(filter).data('timer'));

					var timer =setTimeout(function(){
					$.ajax({
						url:ajax.ajax_url,
						data: {
							nonce: ajax.nonce,
							inputs: filter.serialize(),
							action: 'adv_filters',
						},
						type:'POST',
						beforeSend:function(xhr){
							$('.loading-spinner').fadeIn();
							filter.find('button').text('<?php _e("Processing...",PEP_Theme_ID);?>').prop('disabled',true); // changing the button label
						},
						success:function(data){
							filter.find('button').text('<?php _e("Search",PEP_Theme_ID);?>').prop('disabled',false); // changing the button label back
							$('.archive-wrapper.cards > div').html(data); // insert data

							if(filter.serialize()!="keyword=") {
								$('.archive-pagination.pagination').hide();
							} else {
								$('.archive-pagination.pagination').fadeIn(200);
							}
							$('.loading-spinner').fadeOut();
							clearTimeout(window.timer);
						}
					});
					},1000);
					$(filter).data('timer', timer);
				}

			});
		</script>
		<?php
	}

	function get_archive_posts(){
		if(!wp_verify_nonce($_POST['nonce'],'pep-ajax-nonce')) wp_die();

		$values=array();
		parse_str($_POST['inputs'],$values);

		$args=array(
			'post_type'=>'post',
		);

		if(isset($values['keyword']) && !empty($values['keyword'])) {
			$args['posts_per_page']=50;
			$args['s']=esc_attr( $values['keyword'] );
		}

		if(isset($values['tag']) && !empty($values['tag']) && is_array($values['tag'])) {
			if(count($values['tag'])>6) {
				$args['posts_per_page']=20;
			} else {
				$args['posts_per_page']=-1;
			}
			$args['tag__in']=$values['tag'];
		}

		if(isset($values['author']) && !empty($values['author']) && is_array($values['author'])) {
			if(count($values['author'])>6) {
				$args['posts_per_page']=20;
			} else {
				$args['posts_per_page']=-1;
			}
			$args['tax_query']=array(
				array(
					'taxonomy'=>'author',
					'field'=>'slug',
					'terms'=>$values['author'],
					'operator'=>'IN',
				)
			);
		}

		$the_query = new \WP_Query( $args );
		if( $the_query->have_posts() ) {
			while( $the_query->have_posts() ): $the_query->the_post();

				include(THEME_VIEWS_PATH.'/card.php');

			endwhile;
			wp_reset_postdata();
		} else {
			_e('No results found',PEP_Theme_ID);
		}
		die();
	}

}
