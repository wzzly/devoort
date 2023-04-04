<?php
/**
 * PEP BC
 *
 * This file adds functions to the Genesis PEP Theme.
 *
 * @package PEP BC
 * @author  Bart Pluijms
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */
if (!defined('WP_INSTALLING'))
    add_action('genesis_setup','child_theme_setup', 15);

function child_theme_setup() {
    global $theme_template,$customizer;

    define( 'THEME_VERSION', '0.0.2' );
    define( 'THEME_NAME', 'PEP BC' );
    define( 'THEME_DIR', dirname( get_bloginfo('stylesheet_url')) );
    define( 'THEME_PATH', get_stylesheet_directory());
    define( 'THEME_VIEWS_PATH', get_stylesheet_directory().'/views');

    // Class autoloader, namespace = theme name
    spl_autoload_register('theme_autoloader');

	// Shortcodes
    $shortcodes = new PEP\Shortcodes();
    $client = 	  new PEP\Clients();
   
	// Customizer
	$customizer = new PEP\Customizer();
	$colors =     new PEP\Customizer\Colors();
	$fonts =      new PEP\Customizer\Fonts();
	$layout =     new PEP\Customizer\Layout();
	$company =    new PEP\Customizer\Company();
	$output =     new PEP\Output();

    // Theme setup
	$megamenu = new PEP\MegaMenu();
    $theme_template = new PEP\Template($customizer,$megamenu);

	//$pep=new PEP\Theme($customizer);

    $theme_template->shortcodes = $shortcodes;

	$woosetup = new PEP\WooCommerce\WooSetup($customizer);
	$woocommerce_pep = new PEP\WooCommerce\WooPEP($customizer);

	$blocks=new PEP\Gutenberg\Blocks();
	$gutenberg=new PEP\Gutenberg\Gutenberg();

    $admin=new PEP\Admin();
}

/*
 * Class autoloader
 *
 * @param string $class Class name
 */

function theme_autoloader($class)
{

    // project-specific namespace prefix
    $prefix = 'PEP\\';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_dirs_class = substr($class, $len);

    // Directories
    $directory = '';

    $dirs = explode('\\', $relative_dirs_class);

    $relative_class = array_pop($dirs);

    if (is_array($dirs) && count($dirs)) {

        $directory = strtolower(implode('/ ', $dirs)).'/';

    }
    $file = THEME_PATH .'/lib/'.$directory.'class-'.strtolower(str_replace('_', '-', $relative_class)). '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    } 

}

add_action('wp','pep_check_woo');

function pep_check_woo() {

	if ( ! function_exists( 'is_woocommerce_activated' ) ) {
		function is_woocommerce_activated() {
			if ( class_exists( 'woocommerce' ) ) { return true; } else { 
			return false; }
		}
	}
	
	if(!function_exists('is_woocommerce')) {
		if ( !class_exists( 'woocommerce' ) ) { 
			function is_woocommerce() { return false;}
		}
	}
}

function add_column( $columns ){
	$columns['post_id_clmn'] = 'ID'; // $columns['Column ID'] = 'Column Title';
	return $columns;
}
add_filter('manage_posts_columns', 'add_column', 5);

function column_content( $column, $id ){
	if( $column === 'post_id_clmn')
		echo $id;
}
add_action('manage_posts_custom_column', 'column_content', 5, 2);

/*
 * Unregisters the custom post type client.
 */
function ns_unregister_client_cpt(){
    
    unregister_post_type('client');
}
add_action('init', 'ns_unregister_client_cpt', 9999, 0);


/*
 * exclude relevanssi_noindex class from relevanssi excerpts.
 */
add_filter( 'relevanssi_excerpt_content', 'rlv_remove_noindex_class' );
function rlv_remove_noindex_class( $content ) {
/*
var_dump(preg_replace( '#<(.*) class=".*?relevanssi_noindex".*?</\1>#ms', '', $content));
var_dump($content);
 */
    return preg_replace( '#<(.*) class=".*?relevanssi_noindex".*?</\1>#ms', '', $content );
}

add_filter( 'relevanssi_block_to_render', 'rlv_no_core_image_blocks' );
function rlv_no_core_image_blocks( $block ) {
  if ( 'Expertises NL' === $block['blockName'] ) {
    return null;
  }
  return $block;
}

/* random results when using NO search term */
add_action( 'pre_get_posts', function( $query ) {
    if ( $query->is_search() && empty( $query->query_vars['s'] ) ) {
        $query->set( 'orderby', 'rand' );
    }
} );