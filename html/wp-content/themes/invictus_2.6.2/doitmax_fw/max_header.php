<?php

/** Tell WordPress to run max_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'max_setup' );

if ( ! function_exists( 'max_setup' ) ):

/*-----------------------------------------------------------------------------------*/
/*	Register and load common JS and CSS for various WordPress features.
/*-----------------------------------------------------------------------------------*/

function max_setup() {

	// Init JQuery scripts
	function theme_add_scripts() {
		
		global $post;
		
		if (!is_admin()) {		
			wp_deregister_script('jquery');				
					
			wp_register_script('jquery', get_template_directory_uri() . '/js/jquery.min.js', 'jquery', '1.8.2');
			wp_register_script('jquery-ui-custom', get_template_directory_uri() . '/js/jquery-ui.min.js', 'jquery', '1.8.3');
			
			wp_register_script('validation', get_template_directory_uri() . '/js/jquery.validate.js', 'jquery', '1.7', TRUE);			
			wp_register_script('modenizr', get_template_directory_uri() . '/js/modenizr.min.js', 'jquery', '2.6.1 ', false);
			wp_register_script('respond', get_template_directory_uri() .'/js/respond.min.js', 'jquery', '1.0', false);

			wp_register_script('easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js', 'jquery', '1.3', TRUE);			
			wp_register_script('jquery-mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel.min.js', 'jquery', '1.0', TRUE);
			wp_register_script('jquery-livequery', get_template_directory_uri() . '/js/jquery.livequery.min.js', 'jquery', '1.1.1', false);

			wp_register_script('prettyphoto', get_template_directory_uri() .'/js/prettyPhoto/jquery.prettyPhoto.js', 'jquery', '3.1.3', TRUE);
			wp_register_script('superbgimage', get_template_directory_uri() . '/js/jquery.superbgimage.min.js', 'jquery', '1.0');	
			wp_register_script('touchswipe', get_template_directory_uri() . '/js/jquery.touchswipe.min.js', 'jquery', '1.3.3', TRUE);
			wp_register_script('overscroll', get_template_directory_uri() . '/js/jquery.overscroll.min.js', 'jquery', '1.6.3', TRUE);
			wp_register_script('superfish', get_template_directory_uri() .'/js/superfish.js', 'jquery', '3.1', TRUE);
			wp_register_script('fitvids', get_template_directory_uri() .'/js/jquery.fitvids.js', 'jquery', '1.0', TRUE);
			
			
			// check for iphone, ipad or other devices
			$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
			$isiPhone = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone');
			$isiPod = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPod');
			
			if($isiPad === true || $isiPhone === true || $isiPod === true){
				wp_register_script('superfish-touch', get_template_directory_uri() .'/js/jquery.sftouchscreen.js', 'jquery', '3.1', true);
			}		
						
			wp_register_script('tipsy', get_template_directory_uri() .'/js/tipsy/jquery.tipsy.min.js', 'jquery', '1.0', TRUE);
			wp_register_script('isotope', get_template_directory_uri() .'/js/jquery.isotope.min.js', 'jquery', '1.5.20', TRUE);
			wp_register_script('infinitescroll', get_template_directory_uri() .'/js/jquery.infinitescroll.min.js', 'jquery', '2.0b2.110713', TRUE);
			wp_register_script('flickrush', get_template_directory_uri() .'/js/jquery.flickrush.min.js', 'jquery', '1.0', TRUE);
			wp_register_script('galleria', get_template_directory_uri() .'/js/galleria/galleria-1.2.5.min.js', 'jquery', '1.2.5', TRUE);
			wp_register_script('flickr-fullsize', get_template_directory_uri() .'/js/supersized.flickr.js', 'jquery', '1.0');
		
			wp_register_script('custom-superbgimage', get_template_directory_uri() .'/js/custom-superbgimage.js', 'jquery', '1.0');
			wp_register_script('custom-scroller', get_template_directory_uri() .'/js/custom-scroller.js', 'jquery', '1.0', TRUE);			
			wp_register_script('custom-script', get_template_directory_uri() .'/js/custom.js', array('jquery-ui-custom', 'modenizr', 'isotope', 'jquery-livequery', 'superfish', 'fitvids', 'easing', 'prettyphoto', 'superbgimage', 'tipsy' ), '1.0', TRUE );
			
			// Load the allover needed scripts
			if($isiPad === true || $isiPhone === true || $isiPod === true){
				wp_enqueue_script('superfish-touch');	
			}
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'custom-script' );	
			
			// only get, if its a mobile device			
			if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) {
			  wp_enqueue_script( 'touchswipe' );
			  wp_enqueue_script( 'overscroll' );
			}
					
		}
	}	

	// Init additional needed CSS Styles
	function theme_add_styles() {	
		if (!is_admin()) {		
			wp_enqueue_style('prettyphoto', get_template_directory_uri().'/js/prettyPhoto/prettyPhoto.css', false, false);			
			wp_enqueue_style('tipsy', get_template_directory_uri().'/js/tipsy/tipsy.css', false, false);
			wp_enqueue_style('responsive', get_template_directory_uri().'/css/responsive.css', false, false);
			wp_enqueue_style('custom', get_template_directory_uri().'/css/custom.css', false, false);
		}
	}	
	add_action('init', 'theme_add_styles');
	add_action('init', 'theme_add_scripts');
	
	// load the following scripts only on homepage
	function max_index_scripts() {
		if (is_home()){
			wp_enqueue_script('jquery-mousewheel');
			wp_enqueue_script('custom-superbgimage');
		}
	}
	add_action('wp_print_scripts', 'max_index_scripts');	

	// load the following scripts only when using IE - shame on you
	function max_ie_scripts() {
		global $is_IE;
		if($is_IE){
			wp_enqueue_script('respond');
		}
	}
	add_action('wp_print_scripts', 'max_ie_scripts');

}
endif;

?>