<?php
/* Option related functions

/*-----------------------------------------------------------------------------------*/
/*	Get your custom logo
/*-----------------------------------------------------------------------------------*/

function max_get_custom_logo() {
	
	
	if( get_option_max('custom_logo_value') != '' || get_option_max('custom_footer_logo_value') != '' ) {
		$output = "	<style type=\"text/css\">\n";

		if(get_option_max('custom_logo_value') != '') {
			$output .= "#site-title a { background-image: url(". get_option_max('custom_logo_value') .") !important; }\n";
		}
	
		/* Disabled for this theme
		if(get_option_max('custom_footer_logo_value') != '') {
			$output .= "#colophon a.logo { background-image: url(". get_option_max('custom_footer_logo_value') .") !important; }\n";
		}
		*/
		
		$output .= "</style>\n";
	
		echo $output;
		
	}
	
}
//add_action('wp_head', 'max_get_custom_logo');

/*-----------------------------------------------------------------------------------*/
/* Add the FavIcon to the Page
/*-----------------------------------------------------------------------------------*/

function max_get_favicon() {
	
	global $shortname;

	if ( get_option_max('custom_favicon_value') != '') {
		$output = '<link rel="shortcut icon" href="'. get_option_max('custom_favicon_value') .'"/>';
	}else { 
		$output = '<link rel="shortcut icon" href="'. get_template_directory_uri() .'/favicon.png" />';
	}
	
	echo $output;
}

add_action('wp_head', 'max_get_favicon');


/*-----------------------------------------------------------------------------------*/
/* Get JS for the Homepage
/*-----------------------------------------------------------------------------------*/

function max_get_prettyPhoto_js() {
	
	?>
		
	<script type="text/javascript">

		jQuery(document).ready(function($) {
				
			if(jQuery().prettyPhoto) { // only load prettyPhoto if script file is included
			
				jQuery("a[data-rel^='prettyPhoto'], .gallery-icon a[href$='.jpg'], .gallery-icon a[href$='.png'], .gallery-icon a[href$='.gif']").livequery(function(){
					jQuery("a[data-rel^='prettyPhoto'], .gallery-icon a[href$='.jpg'], .gallery-icon a[href$='.png'], .gallery-icon a[href$='.gif']").prettyPhoto({
						hook: 'data-rel',
						animationSpeed: '<?php get_option_max('pretty_speed',true) ?>',
						slideshow: <?php get_option_max('pretty_interval',true) ?>,
						theme: '<?php get_option_max('pretty_theme',true) ?>',
						deeplinking: false,
						callback: function() { 
							if(jQuery('.scroll-pane').size() > 0){
								jQuery('#scroll_left').not('#scroll_left.disabled').show();	
								jQuery('#scroll_right').not('#scroll_right.disabled').show();
							}
						},
						hd: <?php if(get_option_max('pretty_youtube_hd')){ get_option_max('pretty_youtube_hd',true); }else{ echo 'false'; } ?>,
						show_title: <?php if( get_option_max('pretty_title_show') == "true" ) { echo "true"; } else { echo "false"; } ?>,
						overlay_gallery: <?php if(get_option_max('pretty_gallery_show') == 'true') { echo 'true'; }else{ echo 'false'; }?>
						<?php if(get_option_max('pretty_social_tools') != 'true') { echo ', social_tools: false'; } ?>						
					})
				});					
				
			}	
			
			$hide_parts = jQuery('#primary, #sidebar') // fade in content
				.not('.page-template-template-grid-fullsize-php #primary, .page-template-template-grid-fullsize-php #sidebar, .page-template-template-sortable-php #primary, .page-template-template-sortable-php #sidebar, .page-template-template-scroller-php #primary, .single-gallery #primary, .single-gallery #sidebar');
				$hide_parts.hide();				
				<?php 
				// hide and fadeIn in content if option is set
				if ( get_option_max('general_fadein_content') > 0 ) { ?>				
					var show_timeout = setTimeout(function() {
						$hide_parts.fadeIn(450);
					}, <?php get_option_max('general_fadein_content',true) ?>);
				<?php }else{ ?>
				$hide_parts.fadeIn(450);
				<?php } ?>
		});
	
	</script>
		
	<?php
	
}

add_action('wp_head', 'max_get_prettyPhoto_js');

/*-----------------------------------------------------------------------------------*/
/* Output Custom CSS from theme options
/*-----------------------------------------------------------------------------------*/

function max_custom_css() {

		global $shortname;

		$output = '';
		
		$css = get_option_max('custom_css');
		
		if ($css <> '') {
			$output .= $css . "\n";
		}
		
		// Output styles
		if ($output <> '') {
			$output = "<!-- Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
		}
	
}

add_action('wp_head', 'max_custom_css');

/*-----------------------------------------------------------------------------------*/
/*	Get Video JS
/*-----------------------------------------------------------------------------------*/

function max_get_video_js($post_id) {
	
	if(!empty($post_id)){
		$meta = max_get_cutom_meta_array($post_id);
	}
		
	$_m4v = $meta[MAX_SHORTNAME.'_video_url_m4v_value'];
	$_ogv = $meta[MAX_SHORTNAME.'_video_url_ogv_value'];	

	// Video Preview is an Imager from an URL	
	if($meta[MAX_SHORTNAME.'_video_poster_value'] == 'url'){
		$_poster_url = $meta[MAX_SHORTNAME.'_video_url_poster_value'];
	}
	
	// Video Preview is the post featured image or the URL was chosen but not set
	if( $meta[MAX_SHORTNAME.'_video_poster_value'] == 'featured' || ( $meta[MAX_SHORTNAME.'_video_poster_value'] == 'url' && $meta[MAX_SHORTNAME.'_video_poster_value'] == "" ) ){
		$_imgID = get_post_thumbnail_id(get_the_ID());			
		$_previewUrl = max_get_image_path($post_id, 'full');
		
		// get the imgUrl for showing the post image
		$_poster_url = max_get_custom_image_url(get_post_thumbnail_ID(get_the_ID()), get_the_ID(), MAX_CONTENT_WIDTH, $meta[MAX_SHORTNAME.'_video_height_value'] );												
		
	}
	
	 ?>
		<script type="text/javascript">
			jQuery(document).ready(function($){			
					
				jwplayer("postVideo_<?php echo get_the_ID() ?>").setup({ 
					skin: "<?php echo get_template_directory_uri(); ?>/css/<?php get_option_max('color_main',true) ?>/jwplayer/invictus/invictus.xml",
					modes: [
						{ type: "html5" },
						{ type: "flash", src: "<?php echo get_template_directory_uri(); ?>/js/jwplayer/player.swf" }
					],
					levels: [
						{ file: <?php if($_m4v != '') : ?>"<?php echo $_m4v ?>"<?php endif; ?> },    // H.264 version
						{ file: <?php if($_ogv != '') : ?>"<?php echo $_ogv ?>"<?php endif; ?> }     // Ogg Theora version
					],
					image: "<?php echo html_entity_decode($_poster_url) ?>",
					stretching: "<?php echo $meta[MAX_SHORTNAME.'_video_fill_value'] ?>",							
					autostart: <?php echo $meta[MAX_SHORTNAME.'_video_autoplay_value'] ?>,
					bufferlength: 3,
					fullscreen: false,
					repeat: "none",
					width: "100%",
					height: "100%",
					events: {
						onPlay: function(event){
							var togMyThumbs = jQuery('#toggleThumbs').toggleThumbnails('hide');
						}
					},
					"controlbar.idlehide": true,
					"controlbar.position": 'over'		
				})
				
				
				
			});
		</script>
	<?php
}

/*-----------------------------------------------------------------------------------*/
/*	Get Infinite Scroll JS
/*-----------------------------------------------------------------------------------*/

function max_get_infinitescroll_js() {
?>
	<script type="text/javascript"> 
		
		//<![CDATA[
		jQuery(document).ready(function($) {								
	
			var $container = jQuery('#portfolioList'),
				$pagination = jQuery('.pagination');
				
				// hide pagination
				$pagination.hide();	
				
			jQuery(window).load(function(){			   
				
				$container.infinitescroll({
					navSelector  : '.pagination',    // selector for the paged navigation 
					nextSelector : '.pagination a',  // selector for the NEXT link (to page 2)
					itemSelector : '.portfolio-list li.item',     // selector for all items you'll retrieve
					extraScrollPx: 250,
					pixelsFromNavToBottom: 100,
					loading: {
						msgText: '<?php _e('Loading new photos...', MAX_SHORTNAME) ?>',
						finishedMsg: 'No more photos to load.',
						img: '<?php echo get_template_directory_uri(); ?>/css/<?php get_option_max('color_main',true) ?>/loading.gif',
						selector: '.infscr-loading'
						}
					},
					// call Isotope as a callback
					function( newElements ) {
						$( newElements ).find('img').imagesLoaded(function(){
							if( jQuery().isotope ){
								$container.isotope( 'appended', $( newElements ) ); 
							}else{
								$container.append( $( newElements ) );
							}
							$container.css({ background: 'none' }).find('li.item').css({ visibility: 'visible' });
						});						
					}
				);
										
			});				
				
		});
		//]]>
	</script>
	
	<div class="infscr-loading"></div>
		
<?php
}
?>