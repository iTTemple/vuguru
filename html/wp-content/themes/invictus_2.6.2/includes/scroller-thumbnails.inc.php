<?php 
global $meta, $portfolio, $post, $show_slideshow, $_query_has_videos, $isFullsizeGallery, $main_homepage, $isFullsizeVideo;

// Store autoplay options
if(isset($isFullsizeGallery) && !$main_homepage){
	if(!isset($meta[MAX_SHORTNAME.'_page_fullsize_autoplay'])){
		$autoplay = get_option_max('fullsize_autoplay_slideshow');
	}else{
		$autoplay = $meta[MAX_SHORTNAME.'_page_fullsize_autoplay'];
	}
}else{
	$autoplay = get_option_max('fullsize_autoplay_slideshow');
}

// check for fullsize autoplay
$fullsize_autoplay = 'false';
if( (get_option_max('fullsize_autoplay_video') == 'true' && !$isFullsizeVideo) || ($isFullsizeVideo === true && $meta[MAX_SHORTNAME.'_video_autoplay_value'] == 'true')){
	$fullsize_autoplay = 'true';
}

// check for hiding theme elements on video play
$video_show_elements = 'true';
if( (get_option_max('fullsize_video_show_elements') == 'false') ){
	$video_show_elements = 'false';
}

// check for thumbnails
$show_thumbnails = false;
$_show_thumbnails_checker = !empty($meta['max_show_page_fullsize_thumbnails']) ? $meta['max_show_page_fullsize_thumbnails'] : "";
if( ( $main_homepage === true && get_option_max('homepage_show_thumbnails') == 'true') || 
	( $isFullsizeGallery === true && $_show_thumbnails_checker == 'false' )){ 
	$show_thumbnails = true;
}

?>	

<script src='<?php echo get_template_directory_uri(); ?>/js/swfobject.js?ver=3.2.1'></script> 
<script src='<?php echo get_template_directory_uri(); ?>/js/jwplayer/jwplayer.js?ver=3.2.1'></script>

		<!-- the loader -->
		<div id="my-loading">
			<div></div>
		</div>

		<!-- Video play button -->
		<a id="fsg_playbutton" href="#">Play</a>

		<div id="thumbnails" 
				class="clearfix <?php get_option_max('fullsize_controls_position', true); if ( get_option_max('fullsize_mouse_scrub')  == "true" ){ ?> mouse-scrub<?php } ?><?php if ( get_option_max('fullsize_key_nav') == "true" ){ ?> key-nav<?php } ?><?php if ( get_option_max('fullsize_mouse_leave')  == "true" ){ ?> mouse-leave<?php } ?>"
				data-object='{"directory_uri":"<?php echo get_template_directory_uri(); ?>","color_main":"<?php get_option_max('color_main',true); ?>","fullsize_yt_hd":"<?php get_option_max('fullsize_yt_hd',true) ?>","fullsize_interval":"<?php get_option_max('fullsize_interval',true) ?>"<?php if( $show_thumbnails ){ ?>,"homepage_show_thumbnails":"true"<?php } ?>,"fullsize_autoplay_video":"<?php echo $fullsize_autoplay ?>", "video_show_elements": "<?php echo $video_show_elements ?>"}'
			>

			<div class="rel">

				<?php // check if the fullsize overlay title text should be shown ?>
				<?php if ( ( $main_homepage && get_option_max('fullsize_show_title') == 'true' ) || $meta['max_show_page_fullsize_title'] == 'true' ){ ?>
				<div id="showtitle" class="clearfix"> 
					<div class="clearfix title">
						<a href="#" class="lbpModal"><span class="imagetitle">description</span></a>
						<?php if ( get_option_max('fullsize_show_title_excerpt') == 'true' ){ ?><span class="imagecaption">caption</span>
                        	<div class="imagecaption2"><a href="#" class="lbpModal">More...</a>
</div>
                            
						<?php } ?>
						<span class="imagecount small">Image 1 of 1</span>
					</div>
				</div>
				<?php } ?>
				
				<!-- Thumbnail toggle arrow -->
				<a href="#toggleThumbs" id="toggleThumbs" class="slide-up" <?php if ( !$show_slideshow ) echo ('style="display: none"') ?>>Toggle Thumbnails</a>			
						
				<div class="controls" <?php if ( !$show_slideshow ) echo ('style="display: none"') ?>>
					<a href="#prev" id="fullsizePrev" class="fullsize-link fullsize-prev" title="<?php _e('Prev Image', MAX_SHORTNAME) ?>">Prev</a>
					<a href="#start" id="fullsizeStart" class="fullsize-control fullsize-start" title="<?php _e('Start Slideshow', MAX_SHORTNAME) ?>" <?php if($autoplay == 'true' ){ ?>style="display: none;"<?php } ?>>Start Slideshow</a>
					<a href="#stop" id="fullsizeStop" class="fullsize-control fullsize-stop" title="<?php _e('Stop Slideshow', MAX_SHORTNAME) ?>" <?php if($autoplay != 'true' ){ ?>style="display: none;"<?php } ?>>Stop Slideshow</a>				
					<a href="#next" id="fullsizeNext" class="fullsize-link fullsize-next" title="<?php _e('Next Image', MAX_SHORTNAME) ?>">Next</a>
				</div>
				
				<?php if ( get_option_max('fullsize_mouse_scrub')  != "true" ){ ?>
				<a id="scroll_left" href="#scroll-left" class="scroll-link scroll-left">Scroll left</a>
				<a id="scroll_right" href="#scroll-right" class="scroll-link scroll-right">Scroll right</a>
				<?php } ?>
							
				<div id="fullsizeTimer" <?php if ( !$show_slideshow ) echo ('style="display: none"') ?>></div>
				<div id="fullsizeTimerBG" <?php if ( !$show_slideshow ) echo ('style="display: none"') ?>></div>	
				
				<div id="thumbnailContainer" <?php if ( !$show_slideshow ) echo ('style="display: none"') ?>>
				
					<div id="fullsize" class="clearfix pulldown-items">	
						<?php 
						
						$img_greyscale = get_option_max('fullsize_use_greyscale') == 'true' ? " greyscaled" : ""; 
						
						// get the image we need for the different devices
						$detect = new Mobile_Detect();
						$_img_string = max_get_image_string();

						$_preload_images = "0";
						// disable preload of fullsize images
						if ( $detect->isTablet() || $detect->isMobile() ){
							$_preload_images = "0";
						}else if(get_option_max('fullsize_preload') == 'true'){
							$_preload_images = "1";
						}
						
						if (have_posts()) : while (have_posts()) : the_post();
							
							// check if password protected posts should be shown
							$show_protected_post = true;							
							if ( post_password_required() ) {
								if( get_option_max('fullsize_exclude_protected') == 'false' ){
									$show_protected_post = false;								
								}
							}
							
							if ( $show_protected_post ) :

								//Get the page meta informations and store them in an array
								$_post_meta = max_get_cutom_meta_array(get_the_ID());
															
								// get background image wether it is a desktop or a mobile
								$imgUrl_temp = max_get_post_image_url(get_the_ID(), $_img_string);
								
								$imgUrl_big = $imgUrl_temp[0];								
								$photo_link = '#';
								$photo_target = '';									
								$show_title_link = get_option_max('fullsize_remove_title_link');

								if( empty( $show_title_link ) || 'false' == $show_title_link ){
	
									// Check the link value for the post
									if( !empty($_post_meta[MAX_SHORTNAME.'_photo_item_type_value']) && str_replace(" ", "_", strtolower($_post_meta[MAX_SHORTNAME.'_photo_item_type_value'])) == 'external'){
										
										$photo_link = $_post_meta[MAX_SHORTNAME.'_photo_external_link_value'];
										$photo_target = $_post_meta[MAX_SHORTNAME.'_external_link_target_value'];
										
									}else{									
										
										// check if a link should be shown on a fullsize gallery image title
										if( empty( $_post_meta[MAX_SHORTNAME.'_photo_item_fsg_link'] ) || 'true' == $_post_meta[MAX_SHORTNAME.'_photo_item_fsg_link'] ){										
											
											if( !empty( $_post_meta[MAX_SHORTNAME.'_photo_item_custom_link'] ) && '' != $_post_meta[MAX_SHORTNAME.'_photo_item_custom_link'] ){
												
												// Photo link is a custom link
												$photo_link = $_post_meta[MAX_SHORTNAME.'_photo_item_custom_link'];
												$photo_target = $_post_meta[MAX_SHORTNAME.'_photo_item_custom_link_target'];
												
											}else{
												
												// get the permalink for the photo post									
												$photo_link = get_permalink();
												
											}																			
										}
									}
									
								}
							?>
															
							<?php if ( has_post_thumbnail() || $isFullsizeVideo) { ?>												
								<?php
								
								if(!empty($_post_meta[MAX_SHORTNAME.'_photo_item_type_value'])){
									$post_type = str_replace(" ", "_", strtolower($_post_meta[MAX_SHORTNAME.'_photo_item_type_value']));
								}
								
								if(!empty($meta[MAX_SHORTNAME.'_page_item_type_value'])){
									$post_type = str_replace(" ", "_", strtolower($meta[MAX_SHORTNAME.'_page_item_type_value']));
								}
								
								// Store some values							
								$_background_color = !empty($_post_meta[MAX_SHORTNAME.'_photo_item_background_color']) ? $_post_meta[MAX_SHORTNAME.'_photo_item_background_color'] : "";
								$_video_embedded_url = !empty($_post_meta[MAX_SHORTNAME.'_video_embeded_url_value']) ? $_post_meta[MAX_SHORTNAME.'_video_embeded_url_value'] : "";
								$_video_poster_url = !empty($_post_meta[MAX_SHORTNAME . '_video_url_poster_value']) ? $_post_meta[MAX_SHORTNAME . '_video_url_poster_value'] : "";
								$_video_poster_value = !empty($_post_meta[MAX_SHORTNAME . '_video_poster_value']) ? $_post_meta[MAX_SHORTNAME . '_video_poster_value'] : "";
								$_video_url_m4v = !empty($_post_meta[MAX_SHORTNAME.'_video_url_m4v_value']) ? $_post_meta[MAX_SHORTNAME.'_video_url_m4v_value'] : "";
								$_video_url_ogv = !empty($_post_meta[MAX_SHORTNAME.'_video_url_ogv_value']) ? $_post_meta[MAX_SHORTNAME.'_video_url_ogv_value'] : "";
								$_video_fill_value = !empty($_post_meta[MAX_SHORTNAME.'_video_fill_value']) ? $_post_meta[MAX_SHORTNAME.'_video_fill_value'] : "";
								
								// Add a data string to store post information in a json format string					
								$data_add  = " data-url='{";								
								$data_add .= "\"type\":\"".$post_type."\",";
								$data_add .= "\"postID\":\"".get_the_ID()."\",";
								$data_add .= "\"excerpt\":\"".htmlspecialchars(addslashes(get_the_excerpt()))."\",";
								$data_add .= "\"permalink\":\"".$photo_link."\",";
								$data_add .= "\"target\":\"".$photo_target."\",";
								$data_add .= "\"backgroundcolor\":\"".$_background_color."\",";
								$data_add .= "\"embedded_code\":\"".$_video_embedded_url."\"";
								
								if($post_type == 'selfhosted'){
									
									 // Video Preview is an Imager from an URL	
									if($_video_poster_value == 'url'){
										$data_add .= ",\"poster_url\":\"". $_video_poster_url ."\"";
									}			
									// Video Preview is the post featured image or the URL was chosen but not set
									if( $_video_poster_value == 'featured' || ( $_video_poster_value == 'url' && ($_video_poster_value == "" || !$_video_poster_url) ) ){
										$data_add .= ",\"poster_url\":\"". wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ) ."\"";
									}									
									
									$data_add .= ",\"url_m4v\":\"". $_video_url_m4v ."\",";
									$data_add .= "\"url_ogv\":\"". $_video_url_ogv ."\",";
									$data_add .= "\"stretch_video\":\"". $_video_fill_value ."\"";
								}								
								
								$data_add .= "}'";
								
								$_tim_width = false;
								$_img_width = "";
								if(get_option_max('fullsize_use_square') == 'true'){
									$_tim_width = 120;
									$_img_width = 'width="120"';
								}
		
								// get the imgUrl for showing the post image
								$_cropping = !empty($_post_meta[MAX_SHORTNAME.'_photo_cropping_direction_value']) ? $_post_meta[MAX_SHORTNAME.'_photo_cropping_direction_value'] : false;
								$_item_type = !empty($_post_meta[MAX_SHORTNAME.'_photo_item_type_value']) ? str_replace(" ", "_", strtolower($_post_meta[MAX_SHORTNAME.'_photo_item_type_value'])) : "";
								$imgUrl = max_get_custom_image_url(get_post_thumbnail_ID(get_the_ID()), get_the_ID(), $_tim_width, get_option_max('fullsize_thumb_height'), $_cropping );
								
								?>
								<a <?php echo $data_add ?> class="item <?php echo $_item_type .' '; echo $img_greyscale ?>" href="<?php echo $imgUrl_big; ?>" title="<?php the_title() ?>">
		
									<img src="<?php echo $imgUrl; ?>" height="<?php get_option_max('fullsize_thumb_height',true) ?>" <?php echo $_img_width ?> class="is-thumb img-color" title="<?php the_title() ?>" alt="<?php if(get_the_excerpt()){ echo get_the_excerpt(); }else{ echo get_the_title(); } ?>" />
																	
									<?php 
									if( get_option_max('fullsize_use_greyscale') == 'true' ) { 
										// get the imgUrl for showing the post image
										$imgUrl_grey = max_get_custom_image_url(get_post_thumbnail_ID(get_the_ID()), get_the_ID(), $_tim_width, get_option_max('fullsize_thumb_height'), false, true );								
										?>								
										<img src="<?php echo $imgUrl_grey; ?>" height="<?php get_option_max('fullsize_thumb_height',true) ?>" <?php echo $_img_width ?> class="is-thumb img-grey" title="<?php the_title() ?>" alt="<?php if(get_the_excerpt()){ echo get_the_excerpt(); }else{ echo get_the_title(); } ?>" />
									<?php 
									} 
									?>
									<span class="overlay" style="height:<?php get_option_max('fullsize_thumb_height',true) ?>px"></span>
								</a>							
							<?php }	?>
							
						<?php endif; ?>
						
						<?php endwhile; ?>
						<?php endif; ?>
						<?php wp_reset_query() ?>
						
						<div class="scroll-bar-wrap ui-widget-content ui-corner-bottom">
							<div class="scroll-bar"></div>
						</div>			
					
					</div>
					
				</div><!--// #thumbnailContainer -->

			</div>

		</div><!--// #thumbnails -->

		<?php if($_query_has_videos === true){ ?>
		<div id="superbgplayer" style="height: 100%; left: 0; margin: 0; position: fixed; top: 0; width: 100%; z-index: 2; display: none">
			<div id="superbgimageplayer" style="position: relative; z-index: 3; width: 100%; height: 100%; display: none">
				<div id="youtubeplayer"></div>
				<div id="vimeoplayer"></div>
				<div id="selfhostedplayer"></div>
			</div>
		</div>
		<?php } ?>
		
		<script type="text/javascript">
		
			var isLoaded = false;
			var isMobile = false;
			var $fullsize = jQuery('#fullsize');
			var $fullsizetimer = jQuery('#fullsizeTimer');
			var $superbgimage = jQuery('#superbgimage');
			var $superbgimageplayer = jQuery('#superbgimageplayer');					
			
			if(navigator.platform == 'iPad' || navigator.platform == 'iPhone' || navigator.platform == 'iPod'){
				var isMobile = true;
			}	
			
			jQuery(function($){
																	
				// Options for SuperBGImage
				jQuery.fn.superbgimage.options = {
					transition: <?php get_option_max('fullsize_transition',true) ?>, 
					vertical_center: 1,
					slideshow: <?php if($autoplay == 'true' && $show_slideshow === true ){ ?> 1<?php }else{ ?> 0<?php } ?>,
					speed: '<?php get_option_max('fullsize_speed',true) ?>', // animation speed
					randomimage: 0,
					preload: <?php echo $_preload_images ?>,
					slide_interval: <?php get_option_max('fullsize_interval',true) ?>, // invervall of animation
					<?php					
					// check if more than one image is set as fullsize image
					if( $show_slideshow ) { ?>										
					onClick: false, // function-callback click image 
					onHide: superbgimage_hide, // function-callback hide image
					<?php }	?>
					onShow: superbgimage_show // function-callback show image					
				};
				
				<?php
				// check if more than one image is set as fullsize image
				
				
				?>
					// Show thumnails if option is activated				
					jQuery('#fullsize a' + "[rel='" + jQuery.superbg_imgActual + "']").livequery(function(){
		
						var dataUrl = jQuery(this).attr('data-url');	
						window.videoUrl = jQuery.parseJSON(dataUrl);	
											
						if( window.videoUrl.type != "selfhosted" || window.videoUrl.type != "youtube_embed" || window.videoUrl.type != "vimeo_embed" ){
							jQuery('#fullsize a' + "[rel='" + jQuery.superbg_imgActual + "']").expire();
						}
						
										
					});

					// function callback on clicking image, show next slide
					function superbgimage_click(img) {
						$fullsize.nextSlide();
						$fullsizetimer.startTimer( <?php get_option_max('fullsize_interval',true) ?> );
					}
					
					function superbgimage_hide(img) {						
						
						jQuery('#scanlines').css({ zIndex: 15 });
						jQuery('#fsg_playbutton').fadeOut();
						jQuery('#main, #page').addClass('zIndex').unbind('click');
						jQuery('#superbgimageplayer').removeClass();
						
						jQuery('#fsg_playbutton').add(jQuery('#main')).add(jQuery('#page')).unbind('click touchstart touchend');

																						
						$fullsizetimer.stopTimer();
						
						jQuery('#fullsize a.activeslide').animate({ opacity: 0.5, top: 0 });	
		
						jQuery('#superbgimageplayer, #superbgplayer').fadeOut(250);
						
						// clear the video container to stop videos playing when it is a YouTube video player
						if( typeof ytplayer != 'undefined' ) {
							ytplayer.pauseVideo();
						}						
						jQuery('#vimeoplayer, #selfhostedplayer').html('');	
						
						
						jQuery('#superbgimage img.activeslide').fadeIn(250);
								
						<?php // check if the fullsize overlay title text should be shown ?>
						<?php if ( get_option_max('fullsize_show_title') ){ ?>
						// hide title
						jQuery('#showtitle').stop(false, true).animate({ marginBottom: 50, opacity: 0 }, 250, function(){ 
							jQuery(this).css({ marginBottom: 1 }) 
						})
						<?php } ?>
					}
							
					// function callback on showing image
					// get title and display it
					function superbgimage_show(img) {

						jQuery('#superbgimage').css({zIndex: 5}).show();						
						jQuery('#main, #page').addClass('zIndex').unbind('click');
						
						var dataUrl = "";
						window.videoUrl = {};
						
						// Show scanlines only if not in fullscreen mode
						if( jQuery('#expander').hasClass('slide-up') ){
							if(isMobile === false){
								jQuery('#scanlines').show().stop(false, true).animate({opacity: 1}, 450);
							}
						}
						
						jQuery('#fullsize a' + "[rel='" + jQuery.superbg_imgActual + "']").livequery(function(){

							dataUrl = jQuery(this).attr('data-url');	
							window.videoUrl = jQuery.parseJSON(dataUrl);
							
							// change the background color of the body
							if(window.videoUrl.backgroundcolor != ""){
								//jQuery('body, #superbgimage, #superbgimageplayer ').stop().animate({backgroundColor: window.videoUrl.backgroundcolor });
							}else{
								if(jQuery('body.white-theme').length){
									jQuery('body, #superbgimage, #superbgimageplayer').stop().animate({backgroundColor: "#f5f5f5" });
								}
								if(jQuery('body.black-theme').length){
									jQuery('body, #superbgimage, #superbgimageplayer').stop().animate({backgroundColor: "#222" });
								}								
							}
								
							// add alt tag and ken burns to current fullsize gallery image
							jQuery('#superbgimage img.activeslide')					
								.attr('alt', window.videoUrl.excerpt);							
	
							if( window.videoUrl.type == "selfhosted" || window.videoUrl.type == "youtube_embed" || window.videoUrl.type == "vimeo_embed" ){
									
								<?php if(get_option_max('fullsize_autoplay_video') == 'true'){	?>
								jQuery('#fullsize').stopSlideShow();
								<?php } ?>								
								
								//jQuery('#superbgimageplayer').html('');		
																
								$.getScript("<?php echo get_template_directory_uri(); ?>/js/post-video.js", function(data, textStatus, jqxhr){
									jQuery('#superbgimageplayer, #superbgplayer').css({ display: 'block' });								
								})
										
							}else{
								
								jQuery("#my-loading").add(jQuery('#fsg_playbutton')).fadeOut(150);
															
								if( jQuery('#expander').hasClass('slide-up') ){
									if(isMobile === false){
										jQuery('#scanlines').show().stop(false, true).animate({opacity: 1}, 450);
									}
								}								
								
								if( jQuery.fn.superbgimage.options.slideshow == 1 ){
									jQuery.fn.superbgimage.options.slide_interval = <?php get_option_max('fullsize_interval',true) ?>;							
									$fullsizetimer.startTimer( <?php get_option_max('fullsize_interval',true) ?> );
									$fullsize.startSlideShow();
								}							
															
							} 
							
							jQuery('#fullsize a.activeslide').animate({ opacity: 1 });
							
							<?php // check if the fullsize overlay title text should be shown ?>
							<?php if ( get_option_max('fullsize_show_title') ){ ?>							
							
							
							<?php
							// Add margin to showtitle to prevent overlay of active sidebar
							if( ( is_home() ) || 
								( $isFullsizeGallery === true && is_active_sidebar('sidebar-fullsize-gallery') ) 
							) 
							{ 
							?>
							
							function checkTitleLeftMargin(){
							
								var sb_height = jQuery('#branding').height() + jQuery('#sidebar').height() ;
								var b_height = jQuery(window).height() - jQuery('#thumbnails').outerHeight() - jQuery('#showtitle').outerHeight() - jQuery('#colophon').outerHeight();
															
								if( sb_height >= b_height ) {									
									jQuery('#showtitle').stop().animate({ left: 275 },200, 'easeOutQuad');																		
								}else{									
									jQuery('#showtitle').stop().animate({ left: 20 },200, 'easeOutQuad');
								}
																
							}
							
							checkTitleLeftMargin();							

							jQuery(window).resize(function() {	
								clearTimeout(this.id);
								this.id = setTimeout(checkTitleLeftMargin, 500);			
							})
														
							<?php }?>
							
							// change title and show
							jQuery('#showtitle span.imagetitle').html( jQuery('#thumbnails a' + "[rel='" + img + "'] img").attr('title') );
							
							<?php if ( get_option_max('fullsize_show_title_excerpt') == 'true' ){ ?>
							if(window.videoUrl.excerpt != ""){
								jQuery('#showtitle span.imagecaption')
									.html( window.videoUrl.excerpt ).show()
							}else{
								jQuery('#showtitle span.imagecaption').hide();
							}							
							<?php } ?>							
							jQuery('#showtitle div a').attr('href', window.videoUrl.permalink ).attr('target',window.videoUrl.target);
							jQuery('#showtitle .imagecount').html('Image ' + img + ' of ' + jQuery.superbg_imgIndex);
							
							if(jQuery(window).width() >= 481){
								jQuery('#showtitle').stop(false, true).show().animate({ opacity: 1 })
							}
							<?php } ?>
							
						})
										
					}				
				
					// stop slideshow
					jQuery('#fullsizeStop').livequery('click',function() {
						
						jQuery.fn.superbgimage.options.slideshow = 0;										
						$fullsizetimer.stopTimer();
						jQuery('#fullsize').stopSlideShow();
																	
						// show/hide controls
						jQuery(this).hide();						
						jQuery('#thumbnails a.fullsize-start').show();		
						return false;
					});					
				
					// start slideshow
					jQuery('#fullsizeStart:not(.disabled)').livequery('click', function() {
						
						jQuery.fn.superbgimage.options.slideshow = 1;

						// show/hide controls
						jQuery('#thumbnails a.fullsize-stop').show();
						jQuery(this).hide();																
															
						jQuery.fn.superbgimage.options.slide_interval = <?php get_option_max('fullsize_interval',true) ?>;							
						$fullsizetimer.startTimer( <?php get_option_max('fullsize_interval',true) ?> );
						$fullsize.startSlideShow();
						return false;
		
					});
				
				
				
				jQuery('body').addClass('fullsize-gallery');
				
			});
			
		</script>		
