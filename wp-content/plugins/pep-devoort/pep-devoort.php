<?php
/*
Plugin Name: PEP De Voort Advocaten
Plugin URI: https://pepbc.nl
Description: Custom plugin voor De Voort - Bevat alle theme aanpassingen (php / css / js / images / translations);
Version: 1.0
Author: PEP
Author URI: https://pepbc.nl
License: GPLv2
*/

if ( ! defined( 'ABSPATH' ) )
	die();

define( 'PEP_Theme_VERSION', '0.1.0' );
define( 'PEP_Theme_NAME', 'PEP Theme' );
define( 'PEP_Theme_ID', 'PEP_Theme' );
define( 'PEP_Theme_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'PEP_Theme_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'PEP_Theme_VIEWS_PATH', PEP_Theme_DIR_PATH.'views');
define( 'PEP_Theme_FILE', plugin_basename(__FILE__));

// Class autoloader
spl_autoload_register('PEP_Theme_autoloader');

// Init plugin
add_action( 'after_setup_theme', 'PEP_Theme_init' );

function PEP_Theme_init() {
	global $customizer,$theme_template;
    $PEP_Theme = new \PEP\Theme($theme_template,$customizer);
}

/**
 * Class autoloader
 *
 * @param string $class Class name
 */

function PEP_Theme_autoloader($class)
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
        $directory = implode('/ ', $dirs).'/';
    }
    $file = PEP_Theme_DIR_PATH .'/lib/'.$directory.'class-'.strtolower(str_replace('_', '-', $relative_class)). '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    } else {
		$file = THEME_PATH .'/lib/'.$directory.'class-'.strtolower(str_replace('_', '-', $relative_class)). '.php';
		if (file_exists($file)) {
			require $file;
		}
	}

}