<?php

namespace PEP;

class CPT {

    public $post;

    /* @var boolean $enable_tags */
    public $enable_tags;

     /*
     * Construct
     *
     * @param int $id Post ID
     */

    public function __construct()
    {

        $this->register_post_type();
		$this->register_term_type();

        add_action('wp', array( $this, 'fill_post'), 1);

        add_action('init', array ($this, 'enable_default_taxonomies'));

    }

    /*
     * Fill the post variable if in the WordPress loop
     * Post available in child classes as $this->post
     *
     * @return WP_Post | false
     */

    public function fill_post() {

        global $post;

        if (isset($post) && !empty($post->ID) && $post->post_type == $this->post_type) {

            $this->post = $post;

            return $this->post;

        } else {

            return false;

        }


    }

    /*
     * Enables the default post taxonomies for this CPT
     */

    public function enable_default_taxonomies()
    {

        if ($this->enable_tags)
            register_taxonomy_for_object_type( 'post_tag', $this->post_type );

    }

    /*
     * Registers the post type
     *
     * @return void
     */

    public function register_post_type() {

        if (is_array($this->get_cpt_vars())) {

            register_post_type($this->post_type, $this->get_cpt_vars());

        }

    }
	
	/*
     * Registers the taxonomies
     *
     * @return void
     */
    public function register_term_type() {
		if(is_array($this->term_types)) {
			foreach($this->term_types as $term_type) {
				$term_vars=$this->get_term_vars();
				if (is_array($term_vars[$term_type])) {
					register_taxonomy( $term_type, $this->post_type, $term_vars[$term_type] );
				}
			}
		}
    }


    /*
     * Get single post type post (only this post type allowed)
     *
     * @param int $id ID to look for
     * @return WP_Post
     */

    protected function get_post($id)
    {

        $post = get_post($id);

        if ($post->post_type == $this->post_type) {
          return $post;
        }

        return null;

    }

}
