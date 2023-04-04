<?php
namespace PEP;

class Admin extends Template {
	public function __construct()
    {
		add_action('init',array($this,'start_session'));
        add_action('wp',array($this,'maintenance_mode'));
		add_action('enqueue_block_editor_assets', array($this,'gutenberg_scripts'));
    }
	
	public function start_session() {
		if (!session_id())
			session_start();
	}

    public function maintenance_mode() {
		
		$maintenance_mode=get_theme_mod('maintenance_mode','wp_debug');

        if(((WP_DEBUG==true && $maintenance_mode=='wp_debug') || $maintenance_mode=='on') && !is_user_logged_in()) {
			if((isset($_GET['pep']) && $_GET['pep']=='PEP'.date('Y')) || (isset($_SESSION['maintenance']) && $_SESSION['maintenance']=='PEP')) {
				$_SESSION['maintenance']='PEP';
			} else {
				$this->clean_theme();
			}
        }
    }
	
	public function gutenberg_scripts() {
		wp_enqueue_script( 'pep-editor', THEME_DIR . '/assets/js/editor.js', array( 'wp-blocks', 'wp-dom' ), filemtime( get_stylesheet_directory() . '/assets/js/editor.js' ), true );
		wp_enqueue_style( 'pep-editor', THEME_DIR . '/assets/css/style-editor.css', filemtime( get_stylesheet_directory() . '/assets/css/style-editor.css' ),true);
	}
}
