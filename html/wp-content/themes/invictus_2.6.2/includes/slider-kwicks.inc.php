<?php 
/**
 * The loop that displays Kwicks Accordion Slider
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
<div id="kwicksHolder" class="page-slider">
	<div id="kwicksBorder">
		
		<div class="flexslider">
			<ul class="slides">
			<?php
				// Catch and create the image for the slider
				$i = 0;
				foreach( $meta[MAX_SHORTNAME.'_featured_image'] as $sort => $value ){
					?>
					<li class="kwick<?php echo $i ?>"> 
						<?php
						max_get_slider_image( $value, 'nivo-slider', $i );
						
						// Get Image URL
						$img_array = image_downsize( $value['imgID'], 'nivo-slider');
						$img_url = $img_array[0];			
						
						?> 
					</li>
					<?php
					$i++;			
				}
			?>		
			</ul>
		</div>
		
	</div>
</div>

<span id="responsiveFlag"></span>

<script type="text/javascript">

	jQuery(document).ready(function() {
		
		var Main = Main || {};
	
		jQuery(window).load(function() {
		
			// generated each item width depending on its container width and amount of kwicks items
			var l = jQuery('.flexslider .slides li').size(),
				w = jQuery('.flexslider .slides').width(),
				liWidth = w / l,
				space = <?php echo $meta[MAX_SHORTNAME.'_photo_slider_kwicks_spacing']; ?>,
				add =  space / l ;
				
			// set the width of each item
			jQuery('.flexslider .slides li').css({ width: ( liWidth + add ) - space  });
		
			window.responsiveFlag = jQuery('#responsiveFlag').css('display');
			Main.gallery = new Gallery();
			
			jQuery(window).resize(function() {
				Main.gallery.update();
			});
					
		});
		
		function Gallery(){
			
			var self = this;
				container = jQuery('.flexslider'),
				clone = container.clone( false );
				
			this.init = function (){
				if( responsiveFlag == 'block' ){
					
					var slides = container.find('.slides');
					
					// The slider itself
					slides.kwicks({  
						min: <?php echo $meta[MAX_SHORTNAME.'_photo_slider_kwicks_min']; ?>,
						spacing : <?php echo $meta[MAX_SHORTNAME.'_photo_slider_kwicks_spacing']; ?>,
						sticky: <?php echo $meta[MAX_SHORTNAME.'_photo_slider_kwicks_sticky']; ?>,
						defaultKwick: <?php echo $meta[MAX_SHORTNAME.'_photo_slider_kwicks_default']; ?>,
						easing: "<?php echo $meta[MAX_SHORTNAME.'_photo_slider_kwicks_easing']; ?>",
						duration: <?php echo $meta[MAX_SHORTNAME.'_photo_slider_kwicks_duration']; ?>
					}).find('li').on('tap, touchstart, touchend', 'a', function (){
						console.log('tap on mobiel device');
					});			
					
				} else {
					container.flexslider({
						animation: "slide",
						slideshow: false,
						smoothHeight: true
					});
				}
			}
			this.update = function () {
				var currentState = jQuery('#responsiveFlag').css('display');
				
				if(responsiveFlag != currentState) {
				
					responsiveFlag = currentState;
					container.replaceWith(clone);
					container = clone;
					clone = container.clone( false );
					
					this.init();	
				}
			}
			
			this.init();
		}
		
	})
	
</script>