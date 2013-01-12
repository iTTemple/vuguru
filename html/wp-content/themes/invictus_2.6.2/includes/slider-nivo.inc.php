<?php 
/**
 * The loop that displays Nivo Slider
 *
 *
 * @package WordPress
 * @subpackage Invictus
 * @since Invictus 2.1
 */

global $meta, $post, $post_meta;

$meta = max_get_cutom_meta_array(get_the_ID());

?>
<!--BEGIN slider --> 
<div id="nivoHolder"  class="page-slider">
	<div class="nivo-border">
		<div id="nivoSlider-post<?php echo get_the_ID() ?>" class="nivoSlider">	
		
			<?php			
			$_temp_meta['imgID'] = get_post_thumbnail_id($post->ID);
			
			if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { 
				max_get_slider_image( $_temp_meta, 'full' );						
			}		
				
			// Catch and create the image for the slider
			$i = 0;
			foreach( $meta[MAX_SHORTNAME.'_featured_image'] as $sort => $value ){
				max_get_slider_image( $value, 'full', $i );
				$i++;			
			}
		?>		
		</div>	

		<?php
			// Catch and create the caption for the slider
			$i = 0;
			foreach( $meta[MAX_SHORTNAME.'_featured_image'] as $sort => $value ){
				
				// Get Image URL
				$img_array = image_downsize( $value['imgID'], 'full');
				$img_url = $img_array[0];			
				
				?> 

				<?php 			
				$i++;
			}
		?>	
	</div>
</div>

<script type="text/javascript">
	jQuery(window).load(function() {
		jQuery('.nivoSlider').nivoSlider({
			effect: '<?php echo $meta[MAX_SHORTNAME.'_photo_slider_nivo_effect']; ?>',
			slices: '<?php echo $meta[MAX_SHORTNAME.'_photo_slider_nivo_slices']; ?>',
			animSpeed: '<?php echo $meta[MAX_SHORTNAME.'_photo_slider_nivo_speed']; ?>',
			pauseTime: '<?php echo $meta[MAX_SHORTNAME.'_photo_slider_nivo_pause']; ?>',
			directionNav: true,
			directionNavHide: false,
			captionOpacity: 1			
		});
		
		jQuery('.nivoSlider .nivo-caption')
			.css({ background: 'rgba(0,0,0,0.7)' })
	});
</script>