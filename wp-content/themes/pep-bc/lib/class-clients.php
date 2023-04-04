<?php
namespace PEP;

class Clients extends CPT {
	
	public $post_type = 'client';
	public $term_types = array('client-type');
	
	public function __construct()
    {

        parent::__construct();

		register_block_type( 'pep/clients', array('render_callback' => array($this,'block_clients_render_block')) );
		
		add_image_size('clients',175,75,false);
    }
	
	/*
     * CPT vars
     *
     * @return array
     */
    public function get_cpt_vars() {

        return array(
            'labels' => array(
                'name' => __( 'Clients', 'pep' ),
                'singular_name' => __( 'Client', 'pep' ),
                'add_new_item' => __('Add new client', 'pep'),
                'add_new' => __('Add new', 'pep'),
                'new_item' => __('New client', 'pep'),
                'edit_item' => __('Edit client', 'pep'),
            ),
            'menu_icon' => 'dashicons-groups',
            'public' => true,
			'publicly_queryable'=>false,
            'has_archive' => false,
			'show_in_nav_menus'=>false,
            'exclude_from_search' => true,
            'supports' => array(
                'title',
                'thumbnail',
                'editor'
            ),
			'rewrite' => false,
        );
    }
	
	public function get_term_vars() {

        $labels = array(
			'name'              => _x( 'Types', 'taxonomy general name', 'pep' ),
			'singular_name'     => _x( 'Type', 'taxonomy singular name', 'pep' ),
			'search_items'      => __( 'Search Client Types', 'pep' ),
			'all_items'         => __( 'All Types', 'pep' ),
			'parent_item'       => __( 'Parent Type', 'pep' ),
			'parent_item_colon' => __( 'Parent Type:', 'pep' ),
			'edit_item'         => __( 'Edit Type', 'pep' ),
			'update_item'       => __( 'Update Type', 'pep' ),
			'add_new_item'      => __( 'Add new type', 'pep' ),
			'new_item_name'     => __( 'New Type Name', 'pep' ),
			'menu_name'         => __( 'Types', 'pep' ),
		);

		$args = array(
			'public' =>false,
			'show_in_rest'=>false,
			'show_in_nav_menus'=>true,
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => false,
			'rewrite'           => false,
		);
		
		return array('client-type'=>$args);
    }
	
	/* Clients:: Render frontend output */
	public function block_clients_render_block( $attributes, $content ) {
		
		if(is_admin()) return false;
		
           $args=array(
               'post_type' => $this->post_type,
               'posts_per_page' => -1,
			   'orderby' => 'rand',
           );
          
		   if(isset($attributes['show_image']) && $attributes['show_image']==1) {
			   $args['meta_query']=array(array(
					'key' => '_thumbnail_id'
			   ));
			   $args['posts_per_page'] = 10;
		   }
		   
		   if(isset($attributes['types']) && !empty($attributes['types'])) {
			   $types=explode(',',str_replace(' ','',$attributes['types']));
			   
			   $args['tax_query']=array(
				array(
					'taxonomy'=>'client-type',
					'field'=>'id',
					'terms'=>$types,
				),
			   );
		   }
		   
			$clients = get_posts($args);


           if (!empty($clients)) {
			   
			    wp_enqueue_script('carousel');
				wp_enqueue_style('carousel');
				
				$count=count($clients);
				if((int)$attributes['load_slides']>$count) {
					$attributes['load_slides']=$count;
				}
				$class='';
				if(isset($attributes['align'])) $class.='align'.$attributes['align'];
				$class.=' col-'.$attributes['load_slides'];
				if(isset($attributes['show_arrows']) && $attributes['show_arrows']) {
					$class.=' show-arrows';
				}
				
				if(isset($attributes['show_controls']) && $attributes['show_controls']) {
					$class.=' show-controls';
				}
				
				if(isset($attributes['className'])) {
					$class.=' '.$attributes['className'];
				}
		   
				ob_start();
				include(THEME_VIEWS_PATH.'/gutenberg/clients.php');
				$output = ob_get_contents();
				ob_end_clean();
				
				return $output;
				
           }
	}
	
	
}