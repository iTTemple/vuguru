<?php
/* 
Plugin Name: Media Grid
Plugin URI: http://codecanyon.net/item/media-grid-wordpress-responsive-portfolio/2218545?ref=LCweb
Description: Create stunning responsive portfolios using the responsive grid layout. Display videos, images, galleries and audio files. Choose the colours and the graphic settings. Set the parameters to display in the items description.
Author: Luca Montanari
Version: 1.41
Author URI: http://codecanyon.net/user/LCweb?ref=LCweb
*/  


/////////////////////////////////////////////
/////// MAIN DEFINES ////////////////////////
/////////////////////////////////////////////

// plugin path
$wp_plugin_dir = substr(plugin_dir_path(__FILE__), 0, -1);
define( 'MG_DIR', $wp_plugin_dir);

// plugin url
$wp_plugin_url = substr(plugin_dir_url(__FILE__), 0, -1);
define( 'MG_URL', $wp_plugin_url);


// timthumb url - also for MU
if(is_multisite()){ define('MG_TT_URL', MG_URL . '/classes/timthumb_MU.php'); }
else { define( 'MG_TT_URL', MG_URL . '/classes/timthumb.php'); }



/////////////////////////////////////////////
/////// MAIN SCRIPT & CSS INCLUDES //////////
/////////////////////////////////////////////

// check for jQuery UI slider
function mg_register_scripts() {
    global $wp_scripts;
    if( !is_object( $wp_scripts ) ) {return;}
	
    if( !isset( $wp_scripts->registered['jquery-ui-slider'] ) ) {
		wp_register_script('mg-jquery-ui-slider', MG_URL.'/js/jquery.ui.slider.min.js', 999, '1.8.16', true);
		wp_enqueue_script('mg-jquery-ui-slider');
	}
	else {wp_enqueue_script('jquery-ui-slider');}
 
	return true;
}


// global script enqueuing
function mg_global_scripts() {
	wp_enqueue_script('jquery');

	// admin css & js
	if (is_admin()) {  
		mg_register_scripts();
		wp_enqueue_style('mg_admin', MG_URL . '/css/admin.css', 999);
		
		// chosen
		wp_enqueue_style( 'lcwp-chosen-style', MG_URL.'/js/chosen/chosen.css', 999);
		
		// iphone checks
		wp_enqueue_style( 'lcwp-ip-checks', MG_URL.'/js/iphone_checkbox/style.css', 999);
		
		// LCWP jQuery ui
		wp_enqueue_style( 'lcwp-ui-theme', MG_URL.'/css/ui-wp-theme/jquery-ui-1.8.17.custom.css', 999);
		
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-tabs' );
	}
	
	if (!is_admin()) {
		// switch for standard and old-jquery javascript
		(get_option('mg_old_jquery') != '1') ? $js_path = MG_URL . '/js/frontend.js' : $js_path = MG_URL . '/js/frontend_old_jquery.js';
		
		// frontent JS on header or footer
		if(get_option('mg_js_head') != '1') {
			wp_enqueue_script('mg-frontend-js', $js_path, 100, '', true);	
		}
		else { wp_enqueue_script('mg-frontend-js', $js_path); }

		// frontend css
		if(!get_option('mg_inline_css')) {
			wp_enqueue_style('mg-custom-css', MG_URL. '/css/custom.css', 100);	
		}
		else {add_action('wp_head', 'mg_inline_css', 999);}
	}
}
add_action( 'init', 'mg_global_scripts' );


// USE FRONTEND CSS INLINE
function mg_inline_css(){
	echo '<style type="text/css">';
	require_once(MG_DIR.'/frontend_css.php');
	echo '</style>';
}


/////////////////////////////////////////////
/////// MAIN INCLUDES ///////////////////////
/////////////////////////////////////////////

// admin menu and cpt and taxonomy
include(MG_DIR . '/admin_menu.php');

// mg items metaboxes
include(MG_DIR . '/metaboxes.php');

// shortcode
include(MG_DIR . '/shortcodes.php');

// tinymce btn
include(MG_DIR . '/tinymce_btn.php');

// ajax
include(MG_DIR . '/ajax.php');

////////////

// UPDATE NOTIFIER
include(MG_DIR . '/update-notifier.php');



//////////////////////////////////////////////////
// OVERLAY WRAPPER
function mg_main_overlay() {
	echo '
	<div id="mg_full_overlay">
		<div class="mg_item_load"></div>
		<div id="mg_overlay_content" style="display: none;"></div>
	</div>
	<div id="mg_full_overlay_wrap"></div>
	';
}
add_action('wp_footer', 'mg_main_overlay', 1);




//////////////////////////////////////////////////
// ACTIONS ON PLUGIN ACTIVATION
function mg_init_custom_css() {
	include(MG_DIR . '/functions.php');
	
	// create custom CSS
	if(!mg_create_frontend_css()) {
		if(!get_option('mg_inline_css')) { add_option('mg_inline_css', '255', '', 'yes'); }
		update_option('mg_inline_css', 1);	
	}
	
	// hack for non-latin characters (FROM v1.11)
	if(!get_option('mg_non_latin_char')) {
		if(mg_cust_opt_exists()) {delete_option('mg_non_latin_char');}	
		else {add_option('mg_non_latin_char', '1', '', 'yes');}
	}
	
	// update sliders (for versions < 1.3)
	mg_update_img_sliders();
}
register_activation_hook(__FILE__, 'mg_init_custom_css');


// update sliders function
function mg_update_img_sliders() {
	global $wpdb;
	
	// retrieve all the items
	$args = array(
		'numberposts' => -1, 
		'post_type' => 'mg_items',
	);
	$posts_array = get_posts($args);
	
	if(is_array($posts_array)) {
	
		foreach($posts_array as $post) {
			$gallery_items = get_post_meta($post->ID, 'mg_slider_img', true);
			
			if(is_array($gallery_items) && count($gallery_items) > 0) {
				$new_array = array();
				foreach($gallery_items as $img_url) {
					if(filter_var($img_url, FILTER_VALIDATE_URL)) {
						$query = "SELECT ID FROM ".$wpdb->posts." WHERE guid='".$img_url."'";
						$id = (int)$wpdb->get_var($query);
						
						if(!is_int($id)) {var_dump($id); die('error during sliders update');}
						$new_array[] = $id;
					}
					else {$new_array[] = $img_url;}
				}
				
				delete_post_meta($post->ID, 'mg_slider_img');
				add_post_meta($post->ID, 'mg_slider_img', $new_array, true);
			}	
		}
	}
	
	return true;
}



?>