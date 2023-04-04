<?php
/*
 * Template functionality
 */
namespace PEP;

class Template
{
    /* Object instances */
    public $shortcodes;
    public $widgets;
    /* @var WP_User Currently logged in user */
    public $user;

	public static $default_excerpt_length = 20;

    public $current_layout;

    /*
     * Initiation of class
     *
     * @param Shortcodes $shortcodes
     * @param Widgets $widgets
     */

    public function __construct($customizer,$megamenu)
    {
		$this->megamenu=$megamenu;
		add_theme_support( 'woocommerce' );

		// Adds support for HTML5 markup structure.
		add_theme_support( 'html5', genesis_get_config( 'html5' ) );

		// Adds support for accessibility.
		add_theme_support( 'genesis-accessibility', genesis_get_config( 'accessibility' ) );

		// Adds viewport meta tag for mobile browsers.
		add_theme_support( 'genesis-responsive-viewport' );

		// Adds custom logo in Customizer > Site Identity.
		add_theme_support( 'custom-logo', genesis_get_config( 'custom-logo' ) );

		// Renames primary and secondary navigation menus.
		add_theme_support( 'genesis-menus', genesis_get_config( 'menus' ) );

		// Adds image sizes.
		add_image_size('client', 160,160,false);

		// Adds support for after entry widget.
		add_theme_support( 'genesis-after-entry-widget-area' );

		// Adds support for 3-column footer widgets.
		add_theme_support( 'genesis-footer-widgets', get_theme_mod('footer_widgets',4) );

		// Add structural wraps
		add_theme_support( 'genesis-structural-wraps', array('header','footer-widgets','footer',) );


		// Enqueue scripts and styles
		add_action( 'wp_enqueue_scripts', array($this,'enqueue_scripts_styles') );

		// Sets up the Theme.
		add_action( 'after_setup_theme', array($this,'after_setup') );

		// Removes header right widget area.
		//unregister_sidebar( 'header-right' );

		
		// Add Notification widget area
		genesis_register_sidebar( array(
			'id' => 'notification',
			'name' => __('Header Notification bar','pep'),
			'description' => 'Adds a notification bar to the header',
			)
		);
		// Add Top widget area
		genesis_register_sidebar( array(
			'id' => 'top',
			'name' => __('Header top bar','pep'),
			'description' => 'Adds a topbar to the header',
			)
		);

		// Add Prefooter widget area
		genesis_register_sidebar( array(
			'id' => 'pre-footer',
			'name' => __('Pre footer','pep'),
			'description' => 'Adds an extra bar above the footer widgets',
			)
		);

		add_action( 'wp', array($this,'add_top_bar') );
		add_action( 'wp', array($this,'add_pre_footer') );

		// Removes secondary sidebar.
		unregister_sidebar( 'sidebar-alt' );

		// Removes site layouts.
		genesis_unregister_layout( 'content-sidebar-sidebar' );
		genesis_unregister_layout( 'sidebar-content-sidebar' );
		genesis_unregister_layout( 'sidebar-sidebar-content' );

		// Removes output of primary navigation right extras.
		remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
		remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

		//add_action( 'genesis_theme_settings_metaboxes', array($this,'remove_metaboxes') );

		// Displays custom logo.
		add_action( 'genesis_site_title', 'the_custom_logo', 0 );

		// Repositions primary navigation menu.
		remove_action( 'genesis_after_header', 'genesis_do_nav' );
		add_action( 'genesis_header', array($this,'nav_wrapper_open'), 11 );
		add_action( 'genesis_header', 'genesis_do_nav', 12 );
		add_action( 'genesis_header', array($this,'nav_wrapper_close'), 13 );

		// Repositions the secondary navigation menu.
		remove_action( 'genesis_after_header', 'genesis_do_subnav' );
		add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

		add_filter( 'body_class', array($this,'body_class') );
		add_filter( 'wp_nav_menu_args', array($this,'secondary_menu_args') );
		add_filter( 'excerpt_length',               [ $this, 'default_excerpt_length' ]);
		//add_filter( 'get_the_excerpt',              [ $this, 'filter_excerpt'] );
		add_filter( 'genesis_attr_site-description',[ $this, 'add_site_description_class' ] );

		/* Init sitemap */
		add_action('genesis_before',array($this,'sitemap_init'));

		add_filter('genesis_pre_get_option_footer_text', array($this,'footer_creds_filter'));

		/* Theme default settings */
		add_filter( 'genesis_theme_settings_defaults', array($this,'theme_defaults') );
		add_action( 'after_switch_theme', array($this,'theme_setting_defaults') );
		add_filter( 'simple_social_default_styles', array($this,'social_default_styles') );

		add_action('genesis_before_loop',array($this,'yoast_breadcrumbs'),0);


		add_filter( 'genesis_search_button_text', function() { return __('Search','pep');} );
		add_filter( 'genesis_search_form_label', function() { return __('Search','pep');} );
		add_filter( 'genesis_search_text', function() { return __('Search','pep');} );

		add_action( 'wp',[ $this, 'template_hooks' ] ); // Contains all structural website hooks

		add_filter( 'genesis_post_info', array($this,'post_info') );

		add_action('genesis_before_header',array($this,'load_gtm'));

		// WooCommerce script / style removals, moved to includes/classes/class-template.php

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', array($this,'disable_emojis_tinymce') );
		add_filter( 'wp_resource_hints', array($this,'disable_emojis_remove_dns_prefetch'), 10, 2 );

		add_action('genesis_footer',array($this,'render_copyright'));

		//Register new gutenberg blocks
		register_block_type( 'pep/countup', array('render_callback' => array($this,'render_block_countup')) );

		add_filter('the_content',array($this,'overwrite_youtube_block'),99,1);

		add_action('wp',array($this,'template_conditionals'));

		add_filter( 'genesis_attr_entry', array($this,'add_entry_class') );
		
		/* Add extra fields to category */
		add_action('post_tag_edit_form_fields', array($this,'extra_category_fields'), 10, 2);
		add_action('post_tag_add_form_fields', array($this,'extra_category_fields'), 10, 2);
		add_action('category_edit_form_fields', array($this,'extra_category_fields'), 10, 2);
		add_action('category_add_form_fields', array($this,'extra_category_fields'), 10, 2);
		add_action('edited_category', array($this,'save_category_fields'), 10, 2);
		add_action('create_category', array($this,'save_category_fields'), 10, 2);
		add_action('edited_post_tag', array($this,'save_category_fields'), 10, 2);
		add_action('create_post_tag', array($this,'save_category_fields'), 10, 2);
		
		add_filter('gform_file_upload_markup',array($this,'gform_file_upload_markup'),10,4);
		
		add_filter('genesis_skip_links_output',array($this,'remove_nav_skiplink'),999);
		
		remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
		add_action( 'genesis_after_entry', array($this,'after_entry_widget'), 9 );
		
		add_filter('get_custom_logo',array($this,'filter_custom_logo'));
		
		add_filter( 'wp_nav_menu_args', array($this,'navigation_walker') );
		
		if(get_theme_mod('show_search','no')=='yes') {
			add_action('pep_nav_wrapper_close',array($this,'render_nav_search'));
		}

		/* Category / Archive header & images */
		
		add_filter( 'genesis_attr_archive-header', array($this,'archive_header') );
		
		if(get_theme_mod('archive_header_description','inner')=='inner') {
			add_action('pep_archive_after_inner_header',array($this,'show_archive_description'));
		} else {
			add_action('pep_archive_after_header',array($this,'show_archive_description'));
		}
		
		if(WP_DEBUG==false) {
			add_action('wp_head',array($this,'google_head_scripts'),100);
			add_action('wp_body_open',array($this,'google_body_scripts'),0);
		}
		
		add_filter( 'gform_notification', array($this,'change_notification_format'), 10, 3 );

	}

	public function template_conditionals() {
		add_action('genesis_before_loop',array($this,'render_archive_header'),10);
	}

	public function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}

	public function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
		if ( 'dns-prefetch' == $relation_type ) {
			/** This filter is documented in wp-includes/formatting.php */
			$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

			$urls = array_diff( $urls, array( $emoji_svg_url ) );
		}

		return $urls;
	}

	/**
	* Enqueues scripts and styles.
	*
	* @since 1.0.0
	*/
	public function enqueue_scripts_styles() {
		
		wp_register_script(
			'pep',
			get_stylesheet_directory_uri() . '/assets/js/pep.js',
			array( 'jquery','wp-a11y' ),
			THEME_VERSION,
			true
		);
		
		$localize=apply_filters('pep_localize_theme_js',array(
			'ajax_url'=>admin_url( 'admin-ajax.php' ),
			'locale'=>substr(get_locale(), 0, 2),
			'new_window'=>__('Opens in a new window','pep'),
			'nonce'=>wp_create_nonce( 'pep-ajax-nonce' ),
			'search_cats_shown'=>__('Article categories shown.','pep'),
			'search_cats_hidden'=>__('Article categories hidden.','pep'),
		));

		wp_localize_script(
			'pep',
			'ajax',
			$localize
		);
		
		wp_enqueue_script('pep');
		
		
		$slick_version=filemtime(THEME_PATH.'/assets/js/slick.js');
		wp_register_script(
			'carousel',
			get_stylesheet_directory_uri() . '/assets/js/slick.js',
			array('jquery'),
			$slick_version,
			true
		);

		wp_register_script(
			'countup',
			get_stylesheet_directory_uri() . '/assets/js/jquery.countimator.min.js',
			array('jquery'),
			THEME_VERSION,
			true
		);

		wp_register_script(
			'countup-wheel',
			get_stylesheet_directory_uri() . '/assets/js/jquery.countimator.wheel.min.js',
			array('jquery','countup'),
			THEME_VERSION,
			true
		);

		wp_register_style(
			'carousel',
			get_stylesheet_directory_uri() . '/assets/css/slick.css',
			THEME_VERSION
		);

		wp_register_style(
			'countup-wheel',
			get_stylesheet_directory_uri() . '/assets/css/jquery.countimator.wheel.css',
			THEME_VERSION
		);

		wp_enqueue_style( 'archives', THEME_DIR.'/assets/css/archives.css');

		$css_version=filemtime(THEME_PATH.'/assets/css/ie.css');
		wp_register_style( 'ie-theme', THEME_DIR. '/assets/css/ie.css',array(),$css_version);
		wp_enqueue_style( 'ie-theme' );
	}

	public function after_setup() {
		load_child_theme_textdomain( 'pep', THEME_PATH . '/languages');
	}

	public function sitemap_init() {
		if(!is_page('sitemap')) return false;
		add_action('genesis_entry_content',array($this,'render_sitemap'));
	}

	public function render_sitemap() {
		global $post;

		$args=array(
			'public'=>true,
		);
		$post_types=get_post_types($args,'object');

		$post_types=array_merge(array_flip(array('product', 'post', 'page')), $post_types);

		unset($post_types['attachment'],$post_types['client'],$post_types['product']);

		foreach($post_types as $post_type) {
			if(!isset($post_type->name)) break;

			$args = array(
				'post_status'=>'publish',
				'posts_per_page' => -1,
				'post_type'=>$post_type->name,
				'orderby'=>'title',
				'order'=>'ASC',
				'suppress_filters'=>false,
				'exclude'=>get_the_ID(),
				/*'meta_query' => array(
					'relation' => 'AND',
					'no_sitemap' => array(
						'key' => 'no_sitemap',
						'compare'=>'NOT EXISTS',
					),
					'no_index' => array(
						'key'=>'_yoast_wpseo_meta-robots-noindex',
						'compare'=>'NOT EXISTS',
					),
				),*/
			);

			if($post_type->name=='post') {
				/*$args['date_query']= array(
					'after' => array(
						'year'  => date("Y",strtotime("-1 year")),
						'month' => date("n"),
						'day'   => 1,
					),
				);*/
			}

			if($post_type->name=='product') {
				$args['tax_query']= array(
					array(
						'taxonomy'         => 'product_visibility',
						'terms'            => array( 'exclude-from-catalog', 'exclude-from-search'),
						'field'            => 'name',
						'operator'         => 'NOT IN',
						'include_children' => false,
					),
				);
			}
			$query = new \WP_Query($args);
			if ( $query->have_posts() ) {
				include(THEME_VIEWS_PATH.'/sitemap.php');
				/* Restore original Post Data */
			}
			wp_reset_postdata();

		}

	}

	/**
	* Reduces secondary navigation menu to one level depth.
	*
	* @since 2.2.3
	*
	* @param array $args Original menu options.
	* @return array Menu options with depth set to 1.
	*/
	public function secondary_menu_args( $args ) {

		if ( 'secondary' !== $args['theme_location'] ) {
			return $args;
		}

		$args['depth'] = 1;
		return $args;

	}

	/* Add copyright and blog name to footer */
	public function footer_creds_filter( $creds ) {
		$creds = '[footer_copyright] &middot; <a href="/">'.get_bloginfo('name').'</a>';
		return $creds;
	}

	/**
	* Updates theme settings on reset.
	*
	* @since 2.2.3
	*
	* @param array $defaults Original theme settings defaults.
	* @return array Modified defaults.
	*/
	public function theme_defaults( $defaults ) {

		$defaults['blog_cat_num']              = 6;
		$defaults['breadcrumb_front_page']     = 0;
		$defaults['content_archive']           = 'full';
		$defaults['content_archive_limit']     = 0;
		$defaults['content_archive_thumbnail'] = 0;
		$defaults['posts_nav']                 = 'numeric';
		$defaults['site_layout']               = 'full-width-content';

		return $defaults;

	}

	/**
	* Updates theme settings on activation.
	*
	* @since 2.2.3
	*/
	public function theme_setting_defaults() {

		if ( function_exists( 'genesis_update_settings' ) ) {

			genesis_update_settings(
				array(
					'blog_cat_num'              => 6,
					'breadcrumb_front_page'     => 0,
					'content_archive'           => 'excerpts',
					'content_archive_limit'     => 0,
					'content_archive_thumbnail' => 0,
					'posts_nav'                 => 'numeric',
					'site_layout'               => 'full-width-content',
				)
			);

		}

		update_option( 'posts_per_page', 12 );

	}

	/**
	* Set Simple Social Icon defaults.
	*
	* @since 1.0.0
	*
	* @param array $defaults Social style defaults.
	* @return array Modified social style defaults.
	*/
	public function social_default_styles( $defaults ) {

		$args = array(
			'alignment'              => 'alignleft',
			'background_color'       => '#f5f5f5',
			'background_color_hover' => '#333333',
			'border_radius'          => 3,
			'border_width'           => 0,
			'icon_color'             => '#333333',
			'icon_color_hover'       => '#ffffff',
			'size'                   => 40,
		);

		$args = wp_parse_args( $args, $defaults );

		return $args;

	}

	/**
	* Gets default link color for Customizer.
	* Abstracted here since at least two functions use it.
	*
	* @since 2.2.3
	*
	* @return string Hex color code for link color.
	*/
	public function customizer_get_default_link_color() {

		return '#0073e5';

	}

	/**
	* Gets default accent color for Customizer.
	* Abstracted here since at least two functions use it.
	*
	* @since 2.2.3
	*
	* @return string Hex color code for accent color.
	*/
	public function customizer_get_default_accent_color() {

		return '#0073e5';

	}

	public function customizer_get_default_button_color() {

		return '#0073e5';

	}

	public function customizer_get_default_button_accent_color() {

		return '#111';

	}

	/**
	* Calculates if white or gray would contrast more with the provided color.
	*
	* @since 2.2.3
	*
	* @param string $color A color in hex format.
	* @return string The hex code for the most contrasting color: dark grey or white.
	*/
	public function color_contrast( $color ) {

		$hexcolor = str_replace( '#', '', $color );
		$red      = hexdec( substr( $hexcolor, 0, 2 ) );
		$green    = hexdec( substr( $hexcolor, 2, 2 ) );
		$blue     = hexdec( substr( $hexcolor, 4, 2 ) );

		$luminosity = ( ( $red * 0.2126 ) + ( $green * 0.7152 ) + ( $blue * 0.0722 ) );

		return ( $luminosity > 128 ) ? '#333333' : '#ffffff';

	}

	/**
	* Generates a lighter or darker color from a starting color.
	* Used to generate complementary hover tints from user-chosen colors.
	*
	* @since 2.2.3
	*
	* @param string $color A color in hex format.
	* @param int    $change The amount to reduce or increase brightness by.
	* @return string Hex code for the adjusted color brightness.
	*/
	public function color_brightness( $color, $change ) {

		$hexcolor = str_replace( '#', '', $color );

		$red   = hexdec( substr( $hexcolor, 0, 2 ) );
		$green = hexdec( substr( $hexcolor, 2, 2 ) );
		$blue  = hexdec( substr( $hexcolor, 4, 2 ) );

		$red   = max( 0, min( 255, $red + $change ) );
		$green = max( 0, min( 255, $green + $change ) );
		$blue  = max( 0, min( 255, $blue + $change ) );

		return '#' . dechex( $red ) . dechex( $green ) . dechex( $blue );

	}

	/*
     * Get post image, if no image found, show placeholder
     *
     * @param int $post_id The post id
     * @param string $size Post thumbnail size, as registered by WP
     * @param string $type Post image type, 'post', 'expert', 'column'
     * @param boolean $raw Whether to show the raw image url or wrapped in image holder
     * @return string Image output
     */

    public static function get_post_image($post_id, $size = 'thumbnail', $type = 'post', $raw = false)
    {
        if (has_post_thumbnail($post_id)) {
            $url = get_the_post_thumbnail_url($post_id, $size);
        } else {
            $url = THEME_DIR.'/images/placeholders/placeholder_'.$type.'_'.$size.'.jpg';
        }

        return $url;
    }

	public function add_top_bar() {
		add_action( 'genesis_before_header', array($this,'render_top_bar'));
	}

	public function render_top_bar() {
		do_action('pep_before_topbar');
		if(is_active_sidebar('notification')) {
			include(THEME_VIEWS_PATH.'/sidebar-notification.php');
		}
		if(is_active_sidebar('top')) {
			include(THEME_VIEWS_PATH.'/sidebar-top.php');
		}
		do_action('pep_after_topbar');

	}

	public function add_pre_footer() {
		add_action( 'genesis_before_footer', array($this,'render_pre_footer'),0);
	}

	public static function render_pre_footer() {
		if(is_active_sidebar('pre-footer'))
			include(THEME_VIEWS_PATH.'/sidebar-pre-footer.php');
	}

	public static function yoast_breadcrumbs() {
		if ( function_exists('yoast_breadcrumb') && !is_front_page() && !is_page_template('template-blocks.php')&& !is_page_template('page_author.php') && !is_page_template('template-alt.php') && !is_woocommerce()) {
			yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumb breadcrumbs">','</div>');
		}
	}

	public static function template_hooks() {
		global $post;
		if ((is_archive() || is_home() ||  is_search()) && !is_woocommerce()) {

            remove_action('genesis_loop', 'genesis_do_loop');
            add_action('genesis_loop', array(__class__, 'render_blog'),10);
            //add_action( 'genesis_before_entry', array($this,'featured_post_image'),0 );

        }
	}
	/*
     * Blog
     */

    public static function render_blog()
    {
        global $post;

        add_filter('excerpt_more', '__return_false');
		$title=single_cat_title('',false);
		
		if(empty($title)) {
			if(is_archive() && get_post_type()=='activity' || (isset($_GET['post_type']) && $_GET['post_type']=='activity')) { 
				$title=esc_attr(get_theme_mod('activity_title',__('Agenda & Events','pep')));
			}
			if(is_search()) {
				$title=__('Search results','pep');
			}
		}
		
		$aria_label=sprintf(__('Overview: %s','pep'),$title);
		$thumb_size=get_theme_mod('archive_thumbnail','thumbnail');
		$show_excerpt=get_theme_mod('show_excerpt',1);
		$readmore=get_theme_mod('readmore',__('Read more','pep'));

        include(THEME_VIEWS_PATH.'/blog.php');
    }

    public function featured_post_image() {
		if ( !is_archive() && !is_search()) return true;

		if(is_archive()) {
			the_post_thumbnail('thumbnail');
		}
		if(is_search()) {
			the_post_thumbnail('woocommerce_thumbnail');
		}

	}

	/**
     * Default excerpt length
     *
     * @return int Excerpt length in characters
     */

    public function default_excerpt_length()
    {

        return self::$default_excerpt_length;

    }
    /**
     * Add class for screen readers to site description.
     * This will keep the site description mark up but will not have any visual presence on the page
     * This runs if their is a header image set in the Customiser.
     *
     * @param string $attributes Add screen reader class if custom logo is set.
     * @return array Array of attributes
     */

     public function add_site_description_class( $attributes )
     {

    	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
    		$attributes['class'] .= ' screen-reader-text';
    		return $attributes;
    	} else {
    		return $attributes;
    	}

     }

	 public function filter_excerpt( $excerpt ) {
		$output='';
        $link_text=__('Read more','pep');
		global $post;
		if ( $excerpt) {
			if(get_post_type()=='post') {
			$link_text=__('Read blog','pep');
			$output= '<p>'.wp_trim_words(strip_tags($excerpt),15,'...').'</p>';
            $output.= '<p class="wp-block-button clearfix"><a class="wp-block-button__link readmore button button-d read-more" href="'.get_permalink().'">'.$link_text.'<span class="screen-reader-text">: '.get_the_title().'</span></a></p>';
			} elseif(get_post_type()=='product') {
				$link_text=__('View product','pep');
				$_product = wc_get_product( $post->ID );
				$price=wc_price($_product->get_price());
				$output.= '<p class="wp-block-button is-style-outline clearfix"><a class="wp-block-button__link readmore button button-d read-more" href="'.get_permalink().'"><em>'.$link_text.'</em> <span class="screen-reader-text">: '.get_the_title().'</span> '.$price.'</a></p>';
			}
		}
		return $output;

	}

	public function post_info($post_info) {
		if (!is_singular('post')) return $post_info;
				
		$cat=$this->get_post_primary_category(get_the_ID());
		$cat_id=$cat['primary_category']->term_id;
		$show_date = get_term_meta($cat_id, 'show_date', true);
		$show_author = get_term_meta($cat_id, 'show_author', true);
		
		$post_info=array();
		if($show_date) $post_info[]='[post_date]';
		if($show_author) $post_info[]='[post_author]';
		if(is_array($post_info)) {
			$post_info=implode(apply_filters('pep_post_info_separator',' - '),$post_info);
		}
		
		return $post_info;
	}

	public function navigation_walker( $args ) {
		if( isset( $args['menu_class'] ) && 'menu genesis-nav-menu menu-primary js-superfish' === $args['menu_class'] ) {
			$args['walker'] = $this->megamenu;
		}

		return $args;
	}

	public function load_gtm() {
		if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); }
	}

	public function body_class($classes) {

		$x=1;
		
		if(get_theme_mod('show_search','no')=='yes') {
			$classes[]='show-nav-search';
		}

		while ($x <= 5) {
			if(is_active_sidebar('footer-'.$x)) {
				$classes['footer-widgets']='ftr-widget-'.$x;
			}
			$x++;
		}

		if(function_exists('is_shop')) {
			if(is_shop()) $classes[]='woo-main-shop';
		}

		if(is_search() || is_page('zoeken') || is_page('search')) {
			$classes[]='is-search';
		}
		
		if (function_exists('z_taxonomy_image_url') && !empty(z_taxonomy_image_url()) && is_archive()) {
			$classes[]='archive-header-image';
		}

		return $classes;
	}

	public function render_copyright() {
		$rel='rel="nofollow"';
		if(is_front_page()) $rel='';

		if ( class_exists( 'WooCommerce' ) ) {
			$txt=__('Webshop by PEP','pep');
		} else {
			$txt=__('Website by PEP','pep');
		}

		echo apply_filters('pep_footer_copyright',sprintf('<a class="pep-copyright" href="https://pepbc.nl/" %s target="_blank">%s</a>',$rel,$txt),$rel,$txt);
	}

    public function clean_theme() {

        #Send Headers
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
        header('Retry-After: 86400');

        #Replace Content
		$this->find_and_remove( 'genesis_do_loop' );

        #Remove Menus
        remove_theme_support( 'genesis-menus' );

        #Remove Footer Widgets
        remove_theme_support( 'genesis-footer-widgets' );

        #Remove Skip Links, Breadcrumbs & Comments
        $this->find_and_remove( 'genesis_skip_links' );
        $this->find_and_remove( 'genesis_do_breadcrumbs' );
        $this->find_and_remove( 'genesis_get_comments_template' );

        #Remove Header
        $this->find_and_remove( 'genesis_header', 'all' );
        $this->find_and_remove( 'genesis_do_header', 'all' );
        $this->find_and_remove( 'genesis_after_header', 'all' );
        $this->find_and_remove( 'genesis_before_header', 'all' );

        #Remove Footer
        $this->find_and_remove( 'genesis_footer', 'all' );
        $this->find_and_remove( 'genesis_do_footer', 'all' );
        $this->find_and_remove( 'genesis_after_footer', 'all' );
        $this->find_and_remove( 'genesis_before_footer', 'all' );
		$this->find_and_remove( 'get_header', 'all' );

        add_action('genesis_loop',array($this,'temporary_content'));
		
		
    }

    // Finds & Remoces Actions
	protected function find_and_remove( $remove = '', $filter = '' ) {

		global $wp_filter;

		foreach( $wp_filter as $tag => $actions )

			foreach( $actions as $priority => $functions )

				foreach( $functions as $function => $data )

					if( $remove == $function ||

					  ( $remove == $tag && 'all' == $filter )) remove_action( $tag, $function, $priority );

	}

    public function temporary_content() {
		$default_content='<p><span class="large">Wij bouwen aan deze website</span></p><p>Nieuwsgierig? Neem dan even <a href="https://pepbc.nl/contact/">contact met ons</a> op.</p>';
		$style=get_theme_mod('maintenance_style','pep');
		$logo=get_theme_mod('maintenance_logo');
		
		$content=get_theme_mod('maintenance_content',$default_content);
		
		if($style=='pep') $content='<p class="logo"><a href="https://pepbc.nl/">PEP</a></p>'.$content;
		
        include(THEME_VIEWS_PATH.'/maintenance.php');
    }

	public function render_block_countup($attributes, $content) {

		if(is_admin()) return false;

		$element='count-up-'.rand(0,9999);
		$attr = shortcode_atts( array(
			'number'=>100,
			'description'=>'',
			'start' => 0,
			'end' => 100,
			'speed' => 5000,
			'digits' => 1,
			'decimals' => 0,
			'decimalDelimiter' => ',',
			'thousandDelimiter' => '.',
			'prefix'=>'',
			'suffix'=>'%',
			'circleColor'=>'#ffffff',
			'textColor'=>'#000000',
		), $attributes );
		wp_enqueue_script('countup');
		wp_enqueue_script('countup-wheel');
		wp_enqueue_style('countup-wheel');

		$attr['number']=str_replace(',','.',$attr['number']);

		$data='';
		$data.='data-value="'.$attr['number'].'" ';
		$data.='data-count="'.$attr['start'].'" ';
		$data.='data-min="'.$attr['start'].'" ';
		$data.='data-max="'.$attr['end'].'" ';
		$data.='data-duration="'.$attr['speed'].'" ';
		$data.='data-pad="'.$attr['digits'].'" ';
		$data.='data-decimals="'.$attr['decimals'].'" ';
		$data.='data-decimal-delimiter="'.$attr['decimalDelimiter'].'" ';
		$data.='data-thousand-delimiter="'.$attr['thousandDelimiter'].'" ';

		if($attr['prefix']) $attr['prefix']='<span class="counter-prefix">'.$attr['prefix'].'</span>';
		if($attr['suffix']) $attr['suffix']='<span class="counter-suffix">'.$attr['suffix'].'</span>';

		ob_start();
		?>
		jQuery(document).ready(function($) {
			$("#<?php echo $element;?>").countimator();

			$("#<?php echo $element;?>").height($("#<?php echo $element;?>").width());
		});
		<?php
		$inline_script=ob_get_contents();
		ob_end_clean();
		wp_add_inline_script( 'countup', $inline_script );

		ob_start();
		?>
		#<?php echo $element;?>.counter-wheel {background-color:<?php echo $attributes['circleColor'];?>;color:<?php echo $attributes['textColor'];?>;}
		#<?php echo $element;?>.counter-wheel + .counter-description {color:<?php echo $attributes['textColor'];?>;}
		<?php
		$inline_style=ob_get_contents();
		ob_end_clean();
		wp_add_inline_style( 'countup-wheel', $inline_style );

		ob_start();
		include(THEME_VIEWS_PATH.'/gutenberg/countup.php');
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	
	/*
	 * YouTube oembed overwrite
	 * - Use cookie-free domain
	 * - Remove keyboard controls / keyboard shortcuts for accessibility
	 * - Remove related videos
	*/
	public function overwrite_youtube_block($content) {
		//https://www.youtube.com/watch?v=8N_tupPBtWQ


		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $content, $match);
		$string     = $content;
		$search     = '/youtube\.com\/watch\?v=([a-zA-Z0-9]+)/smi';
		$replace    = "youtube-nocookie.com/embed/$1";
		//$content = preg_replace($search,$replace,$string);


		//$youtube_id = $match[1];

		//$content=str_replace('https://www.youtube.com','https://www.youtube-nocookie.com',$content);
		$content=str_replace('wmode=opaque','wmode=opaque&disablekb=1&rel=0',$content);
		return $content;
	}


	public function add_entry_class($attributes) {
		if(is_search() || is_category() || is_archive() || (strpos($attributes['class'],'entry')!==false && strpos($attributes['class'],'type-activity')!==false && !is_single())) {
			$attributes['class'].=' card';
		}
		if(is_singular() && !is_page()) {
			$attributes['class'].=' single-entry';
		}
		global $wp_query;
		
		if(is_admin()) {
			$attributes['class'].=' card';
		}
		return $attributes;
	}
	
	public function nav_wrapper_open() {
		?><div id="nav-wrapper"><?php
		do_action('pep_nav_wrapper_open');
	}
	
	public function nav_wrapper_close() {
		do_action('pep_nav_wrapper_close');
		?></div><?php
	}
	
	public function extra_category_fields($term) {
		// we check the name of the action because we need to have different output
		// if you have other taxonomy name, replace category with the name of your taxonomy. ex: book_add_form_fields, book_edit_form_fields
		if (current_filter() == 'category_edit_form_fields' || current_filter()=='post_tag_edit_form_fields') {
			$show_date = get_term_meta($term->term_id, 'show_date', true);
			$show_author = get_term_meta($term->term_id, 'show_author', true);
			?>
			<tr class="form-field">
				<th valign="top" scope="row"><span class="label"><?php _e('Show date','pep'); ?></span></th>
				<td>
					<input type="radio" name="term_fields[show_date]" <?php checked($show_date,1);?> id="term_fields_show_date_yes" value="1"> <label for="term_fields_show_date_yes"><?php _e('Yes','pep');?></label>
					<input type="radio" name="term_fields[show_date]" <?php checked($show_date,0);?> id="term_fields_show_date_no" value="0"> <label for="term_fields_show_date_no"><?php _e('No','pep');?></label>
					<p class="description"><?php _e('Show date on archives and single posts'); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th valign="top" scope="row"><span class="label"><?php _e('Show author','pep'); ?></span></th>
				<td>
					<input type="radio" name="term_fields[show_author]" <?php checked($show_author,1);?> id="term_fields_show_author_yes" value="1"> <label for="term_fields_show_author_yes"><?php _e('Yes','pep');?></label>
					<input type="radio" name="term_fields[show_author]" <?php checked($show_author,0);?> id="term_fields_show_author_no" value="0"> <label for="term_fields_show_author_no"><?php _e('No','pep');?></label>
					<p class="description"><?php _e('Show author on single posts'); ?></p>
				</td>
			</tr>
		<?php } elseif (current_filter() == 'category_add_form_fields') {
			?>
			<fieldset class="form-field">
				<legend class="label"><?php _e('Show date','pep'); ?></legend>
				<input type="radio" name="term_fields[show_date]" id="term_fields_show_date_yes" checked value="1"> <label class="inline" style="display:inline !important;" for="term_fields_show_date_yes"><?php _e('Yes','pep');?></label><br>
				<input type="radio" name="term_fields[show_date]" id="term_fields_show_date_no" value="0"> <label class="inline" style="display:inline !important;" for="term_fields_show_date_no"><?php _e('No','pep');?></label>
				<p class="description"><?php _e('Show date on archives and single posts'); ?></p>
			</fieldset>
			<fieldset class="form-field">
				<legend class="label"><?php _e('Show author','pep'); ?></legend>
				<input type="radio" name="term_fields[show_author]" id="term_fields_show_author_yes" checked value="1"> <label class="inline" style="display:inline !important;" for="term_fields_show_author_yes"><?php _e('Yes','pep');?></label><br>
				<input type="radio" name="term_fields[show_author]" id="term_fields_show_author_no" value="0"> <label class="inline" style="display:inline !important;" for="term_fields_show_author_no"><?php _e('No','pep');?></label>
				<p class="description"><?php _e('Show author on single posts'); ?></p>
			</fieldset>
		<?php
		}
	}
	
	public function save_category_fields($term_id) {
		if (!isset($_POST['term_fields'])) {
			return;
		}

		foreach ($_POST['term_fields'] as $key => $value) {
			update_term_meta($term_id, $key, sanitize_text_field($value));
		}
	}
	
	private function get_post_primary_category($post_id, $term='category', $return_all_categories=false){
		$return = array();

		if (class_exists('WPSEO_Primary_Term')){
			// Show Primary category by Yoast if it is enabled & set
			$wpseo_primary_term = new \WPSEO_Primary_Term( $term, $post_id );
			$primary_term = get_term($wpseo_primary_term->get_primary_term());
	
			if (!is_wp_error($primary_term)){
				$return['primary_category'] = $primary_term;
			}
		}
	
		if (empty($return['primary_category']) || $return_all_categories){
			$categories_list = get_the_terms($post_id, $term);
	
			if (empty($return['primary_category']) && !empty($categories_list)){
				$return['primary_category'] = $categories_list[0];  //get the first category
			}
			if ($return_all_categories){
				$return['all_categories'] = array();
	
				if (!empty($categories_list)){
					foreach($categories_list as &$category){
						$return['all_categories'][] = $category->term_id;
					}
				}
			}
		}

		return $return;
	}
	
	public function gform_file_upload_markup($default,$file_info, $form_id, $id) {
		return "<button alt='" . esc_attr__( 'Delete file', 'pep' ) . "' class='gform_delete' onclick='gformDeleteUploadedFile({$form_id}, {$id}, this);' onkeypress='gformDeleteUploadedFile({$form_id}, {$id}, this);' /> ".__("Delete File:","pep")." " . esc_html( $file_info['uploaded_filename'] ) . "</button>";
	}
	
	public function remove_nav_skiplink($links) {
		unset( $links['genesis-nav-primary'] );
		unset( $links['nav-wrapper'] );
		return $links;
	}
	
	public function after_entry_widget() {
		if ( ! is_singular( ) )
        return;

        genesis_widget_area( 'after-entry', array(
            'before' => '<div class="after-entry widget-area">',
            'after'  => '</div>',
        ) );
	}
	
	function filter_custom_logo($logo) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$html = sprintf( '<a href="%1$s" class="custom-logo-link" rel="home" title="'.__('Back to home','pep').'" itemprop="url">%2$s</a>',
            esc_url( home_url( '/' ) ),
            wp_get_attachment_image( $custom_logo_id, 'full', false, array(
                'class'    => 'custom-logo',
            ) )
        );
		return $html;  
	}
	
	function render_nav_search() {
		?>
		<div class="nav-search">
			<a href="?s=" class="nav-search-link"><span class="screen-reader-text"><?php _e('Search','pep');?></span></a>
			<?php 
			echo get_search_form();
			?>
		</div>
		<?php
	}
	
	
	public function render_archive_header() {
		if(is_search()) {
			global $wp_query;
			remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
			return true;
		}
		//if(!is_archive() && !is_tax()) return true;
		
		
		global $wp_query;
			
		$term = is_tax() ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();
		
		if(isset($term->term_id)) {
			remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
			$heading = get_term_meta( $term->term_id, 'headline', true );
			if ( empty( $heading ) && genesis_a11y( 'headings' ) ) {
				$heading = $term->name;
			}
		
			include(THEME_VIEWS_PATH.'/archive-header.php');
		
			$term=$this->get_post_primary_category(get_the_ID());
		}
	}
	
	public function get_archive_description() {
		global $wp_query,$wp_embed;
		$term = is_tax() ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();
		$intro_text = get_term_meta( $term->term_id, 'intro_text', true );
		$intro_text = $wp_embed->autoembed( $intro_text );
		$intro_text = do_shortcode( $intro_text );
		$intro_text = wpautop( $intro_text );
		$intro_text = apply_filters( 'genesis_term_intro_text_output', $intro_text ?: '' );
		return $intro_text;
	}
	
	public function archive_header($attribute) {
		
		$attribute['class'].=' archive-description taxonomy-archive-description taxonomy-description';
				
		if (function_exists('z_taxonomy_image_url') && !empty(z_taxonomy_image_url())) {
			$attribute['style']='background-image:url('.z_taxonomy_image_url().');';
			
			$alignwidth=get_theme_mod('archive_header_width','alignfull');
			
			$attribute['class'].=' '.$alignwidth;
		}
		
		return $attribute;
	}
	
	public function google_head_scripts() {
		$ua_code=esc_attr(get_theme_mod('company_gtag'));
		$gtm_code=esc_attr(get_theme_mod('company_gtm'));
		if($ua_code!="") {
			?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ua_code;?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  <?php if(current_user_can('editor') || current_user_can('administrator') ) { ?>
  window['ga-disable-<?php echo $ua_code;?>'] = true;
  <?php } ?>
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?php echo $ua_code;?>', { 'anonymize_ip': true });
</script>
			<?php 
		} elseif($gtm_code!="") {
			?>
			<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo $gtm_code;?>');</script>
<!-- End Google Tag Manager -->
<?php 
		}
		
	}
	public function google_body_scripts() {
		$gtm_code=esc_attr(get_theme_mod('company_gtm'));
		if($gtm_code!="") {
			?>
			<!-- Google Tag Manager (noscript) -->
			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtm_code;?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Google Tag Manager (noscript) -->
			<?php 
		}
	}
	
	public function show_archive_description() {
		$intro_text=$this->get_archive_description();
		if(!empty($intro_text)) { ?>
		<div class="archive-description-text">
			<?php echo $intro_text;?>
		</div>
		<?php } 
	}
	
	public function change_notification_format( $notification, $form, $entry ) {
		\GFCommon::log_debug( 'gform_notification: change_notification_format() running.' );
		// Do the thing only for a notification with the name Text Notification

		\GFCommon::log_debug( 'gform_notification: format changed to multipart.' );
		// Change notification format to multipart from the default html
		$notification['message_format'] = 'multipart';

		return $notification;
	}
	
}