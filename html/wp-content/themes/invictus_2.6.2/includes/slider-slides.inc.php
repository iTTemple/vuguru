<?php 
/**
 * The loop that displays Slides Slider
 *
 *
 * @package WordPress
 * @subpackage Invictus
 * @since Invictus 2.1
 */

global $meta, $post, $post_meta;

$meta = max_get_cutom_meta_array(get_the_ID());

$no_hover = get_option_max('image_show_fade') == 'false' ? ' no-hover' : "";

?>


<!--BEGIN slider --> 
<div id="slider-<?php echo get_the_id() ?>" class="slides-slider page-slider" data-loader="<?php echo get_template_directory_uri(); ?>/css/<?php get_option_max('color_main',true) ?>/loading.gif"> 
			
	<?php			
	$_temp_meta['imgID'] = get_post_thumbnail_id($post->ID);
	
	if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { 
	?>
	<div class="slide<?php echo $no_hover?>">
	<?php 			
		max_get_slider_image( $_temp_meta, 'full' );						
	?>
	</div>
	<?php
	}
	
	// Catch and create the image for the slider
	$i = 1;
			
	foreach( $meta[MAX_SHORTNAME.'_featured_image'] as $sort => $value ){
	?>
	<div class="slide<?php echo $no_hover?>">								
	<?php
		max_get_slider_image( $value, 'full', $i );
		$i++;
	?>
	</div>
	<?php }	?>
		
</div>
<!--END .slider --> 

<script type="text/javascript">
	jQuery(document).ready(function(){
		
		var slidesContainer = jQuery("#slider-<?php echo get_the_id() ?>"),
			slidesTimeout;
			
		// set a timeout to match the fadeIn content
		slidesTimeout = setTimeout(function(){
			
			var slides = jQuery("#slider-<?php echo get_the_id() ?>").slides({
				responsive: true,
				preload: {
					active: true, // [Boolean] Preload the slides before showing them, this needs some work
					image: jQuery("#slider-<?php echo get_the_id() ?>").attr('data-loader') // [String] Define the path to a load .gif, yes I should do something cooler
				},
				autoHeight: true,
				navigation: true, // [Boolean] Auto generate the naviagation, next/previous buttons
				pagination: true, // [Boolean] Auto generate the pagination
				effects: {
					navigation: "fade",  // [String] Can be either "slide" or "fade"
					pagination: "fade" // [String] Can be either "slide" or "fade"
				},
				fade: {
					interval: 250, // [Number] Interval of fade in milliseconds
					crossfade: false, // [Boolean] TODO: add this feature. Crossfade the slides, great for images, bad for text
					easing: "" // [String] Dependency: jQuery Easing plug-in <http://gsgd.co.uk/sandbox/jquery/easing/>
				},
				<?php if($meta[MAX_SHORTNAME.'_photo_slider_slides_autoplay'] == 'true'){ ?>
				playInterval: <?php echo $meta[MAX_SHORTNAME.'_photo_slider_slides_pause'] ?>, // [Number] Time spent on each slide in milliseconds
				pauseInterval: <?php echo $meta[MAX_SHORTNAME.'_photo_slider_slides_pause'] ?>, // [Number] Time spent on pause, triggered on any navigation or pagination click
				<?php } ?>
				loaded: function(){
					jQuery("#slider-<?php echo get_the_id() ?> .slidesContainer, #slider-<?php echo get_the_id() ?> .slidesControl").animate({ height: jQuery("#slider-<?php echo get_the_id() ?> img:first").height() }, 250);
				},
				navigateEnd: function( current ){
					jQuery("#slider-<?php echo get_the_id() ?> .slidesContainer, #slider-<?php echo get_the_id() ?> .slidesControl").animate({ height: jQuery("#slider-<?php echo get_the_id() ?> .slide").eq(current-1).height() }, 250);
				},			
			})
			
			slidesContainer.find('img').each(function(){
				jQuery(this).width(slides.width());
			})
			
			jQuery(window).resize(function() {
				slidesContainer.find('img').each(function(){
					jQuery(this).width(slides.width());
				})
			})
			
		}, <?php get_option_max('general_fadein_content',true) ?> + 50 );	
		
	})

</script>