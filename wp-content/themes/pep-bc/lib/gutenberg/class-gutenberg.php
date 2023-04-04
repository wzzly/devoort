<?php
/*
 * Gutenberg blocks functionality
 */
namespace PEP\Gutenberg;

class Gutenberg
{
	public function __construct()
    {
		
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( '/assets/css/style-editor.css' );
		
		// Adds support for block alignments.
		add_theme_support( 'align-wide' );
		
		// Make media embeds responsive.
		add_theme_support( 'responsive-embeds' );
		
		// Adds support for editor font sizes.
		add_theme_support(
			'editor-font-sizes',
			$this->genesis_get_config( 'editor-font-sizes' )
		);
		
		
		add_action( 'enqueue_block_editor_assets', array($this,'block_editor_styles') );
		//add_action( 'after_setup_theme', array($this,'content_width'), 0 );
		
		add_filter( 'body_class', array($this,'blocks_body_classes') );
		
	}
		
	/**
	* Enqueues Gutenberg admin editor fonts and styles.
	*
	* @since 2.7.0
	*/
	function block_editor_styles() {
	
		wp_enqueue_style(
			'pep-gutenberg-fonts',
			'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,700',
			array(),
			THEME_VERSION
		);
	
	}
	
	/**
	* Adds body classes to help with block styling.
	*
	* - `has-no-blocks` if content contains no blocks.
	* - `first-block-[block-name]` to allow changes based on the first block (such as removing padding above a Cover block).
	* - `first-block-align-[alignment]` to allow styling adjustment if the first block is wide or full-width.
	*
	* @since 2.8.0
	*
	* @param array $classes The original classes.
	* @return array The modified classes.
	*/
	public function blocks_body_classes( $classes ) {

		if ( ! is_singular() || ! function_exists( 'has_blocks' ) || ! function_exists( 'parse_blocks') ) {
			return $classes;
		}
	
		if ( ! has_blocks() ) {
			$classes[] = 'has-no-blocks';
			return $classes;
		}
	
		$post_object = get_post( get_the_ID() );
		$blocks      = (array) parse_blocks( $post_object->post_content );
	
		if ( isset( $blocks[0]['blockName'] ) ) {
			$classes[] = 'first-block-' . str_replace( '/', '-', $blocks[0]['blockName'] );
		}
	
		if ( isset( $blocks[0]['attrs']['align'] ) ) {
			$classes[] = 'first-block-align-' . $blocks[0]['attrs']['align'];
		}
	
		return $classes;
	
	}
	
	/**
	* Set content width to match the “wide” Gutenberg block width.
	*/
	public function content_width() {
	
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- See https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/924
		$GLOBALS['content_width'] = apply_filters( 'content_width', 1062 );
	
	}
	
	public function genesis_get_config( $config ) {

		$parent_file = sprintf( '%s/config/%s.php', get_template_directory(), $config );
		$child_file  = sprintf( '%s/config/%s.php', get_stylesheet_directory(), $config );
	
		$data = array();
	
		if ( is_readable( $child_file ) ) {
			$data = require $child_file;
		}
	
		if ( empty( $data ) && is_readable( $parent_file ) ) {
			$data = require $parent_file;
		}
	
		return (array) $data;
	
	}

}