<?php
/**
 * @package WordPress
 * @subpackage Invictus
 */
 
global $showSuperbgimage, $fromGallery, $taxonomy_name, $the_term, $post_terms, $isFullsizeGallery, $isPost, $isBlog, $shortname, $page_obj, $main_homepage, $_query_has_videos, $isFullsizeFlickr;
 
$autoplay = '0';

if( !is_home() && !$main_homepage && !$isFullsizeGallery ){
	wp_reset_query();
}

$show_page_fullsize = get_post_meta(get_the_ID(), 'max_show_page_fullsize', true);

// Check for WP-E-Commerce 
if (function_exists( 'is_plugin_active' ) ) :
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
	if(is_plugin_active( 'wp-e-commerce/wp-shopping-cart.php' ) && get_post_type() == 'wpsc-product'){
		$showSuperbgimage = true;
		$show_page_fullsize = true;
	}	
endif;

// Check for BBPress
if( function_exists('is_bbpress') ) :
	if( is_bbpress() === true ){
		$showSuperbgimage = true;
	}
endif;
 
// Check if social icons should be used
if(get_option_max('social_use')=='true') :

	global $social_array;

	// Prepare Social network icons 
	$iconArray = array();
	$iconUrl = array();
	$iconPath = get_template_directory_uri()."/images/social/";
	if( is_array( get_option_max('social_show') ) ){
		foreach(get_option_max('social_show') as $index => $value) {
			$iconArray[$index] = $value;
			$iconUrl[$index] = get_option_max('social_'.$value);
		}
	}
endif;
 
?>

	</div><!-- #main -->
</div><!-- #page -->

<footer id="colophon" role="contentinfo">
	
	<span class="footer-info">
		
		<?php echo stripslashes( get_option_max('copyright') ) ?>
		
	</span>
	
	<?php if ( get_option_max('fullsize_key_nav') == "true" ){ ?><a href="#keynav" id="fullsizeKeynav" class="keynav tooltip" title="<?php _e('Use Keynav to navigate', MAX_SHORTNAME) ?>"></a><?php } ?>

	<?php
	// Check if social array is enabled
	if(get_option_max('social_use')=='true' && is_array(get_option_max('social_show'))){ 
	?>
	<div id="sociallinks" class="clearfix">
	
		<?php
		// check if social bartender plugin is installed
		if( function_exists( 'social_bartender' ) ){ 
			social_bartender(); 
		}else{	
		
		//Start the generated social network loop
		?>
		<ul>
		<?php
		$blank = get_option_max('social_show_blank') == 'true' ? 'target="_blank"' : '';

			$order=array('facebook','worldofheroes','googleplus ','linkedin ','twitter','youtube ');
			
			$order=array(0=>'facebook',4=>'twitter',5=>'youtube ',2=>'googleplus',3=>'linkedin',1=>'worldofheroes');
			
		//for ($iconCount = 0; $iconCount < count($iconArray); $iconCount++) 
		foreach($order as $key=>$value)	
		{
			$iconCount=$key;
			if ($iconArray[$iconCount] != "")
				if ($iconUrl[$iconCount] !="") // check if url is set
					echo '<li><a href="'.$iconUrl[$iconCount].'" title="'.$iconArray[$iconCount].'" '.$blank.' class="tooltip"><img alt="'.$iconArray[$iconCount].'" src="' . $iconPath . '' . $iconArray[$iconCount] . '.png" /></a></li>';
			}
		?>
		</ul>
		<?php
		}
		?>
	</div>
	<?php } ?>
			
</footer><!-- #colophon -->

<?php if( ( $main_homepage === true || $isFullsizeGallery === true ) && !$isFullsizeFlickr )
	// get the thumbnails if the page is the homepage
	get_template_part( 'includes/scroller', 'thumbnails.inc' );
?>

<?php 

	// check if it is not the homepage and get the random background image post	
			
	if(!$isPost){
		$imageURL = get_post_meta($post->ID, 'max_show_page_fullsize_url', true);
	}else{
		if($isBlog === true){
			$imageURL = get_post_meta($post->ID, $shortname.'_show_page_fullsize_url', true);
		}else{
			$imageURL = get_post_meta($post->ID, $shortname.'_show_page_fullsize_url_value', true);
		}
	}
	
if( !$isFullsizeGallery && !$isFullsizeFlickr ) {

	$_background_type = get_post_meta($post->ID, 'max_show_page_fullsize_type', true);
	$_gallery_array = get_post_meta($post->ID, 'max_show_page_fullsize_gallery', true);
	
	if ( ( !is_home() && $show_page_fullsize == 'true' ) || $showSuperbgimage === true ){ 
	
		// Check if a url for a background image is set
		if( $fromGallery !== true ){
						
			if( is_array($_gallery_array) && count($_gallery_array) > 0 ){
				
				if($_background_type == 'single'){				
					$random_post = max_query_term_posts( 1, $_gallery_array, 'gallery', 'rand' );
				}
				if($_background_type == 'slideshow'){
					$autoplay = '1';
					$random_post = max_query_term_posts( -1, $_gallery_array, 'gallery', 'rand' );
				}		
								
			}else if( $imageURL == "" ){
				
				if( !$taxonomy_name ) 
				{				
					$random_post = max_query_term_posts( 1, get_option_max('fullsize_featured_cat'), 'gallery', 'rand' );				
				}
				else
				{
					$random_post = max_query_term_posts( 1, $the_term->term_id, 'gallery', 'rand' );					
				}
			}
			
		}
		
?>
		<div id="superbgimage">		

			<?php 
			if( $isPost === true && get_post_meta($post->ID, MAX_SHORTNAME.'_show_post_fullsize_value', true) == "true" ) {	
							
				$_background_type = $_background_type;					

				if($_background_type == 'slideshow' && !empty($_background_type) ) {
										
					$random_post = max_query_posts(-1, $_gallery_array, 'rand', false);
											
					$autoplay = '1';
					
					if (have_posts()) : while (have_posts()) : the_post();
					
						// Random image from featured homepage gallery
						$imgUrl_temp = max_get_post_image_url(get_the_ID(), "full");							
						$imgUrl = $imgUrl_temp[0];						
												
						echo '<a class="item" href="'. $imgUrl .'"></a>';						
					
					endwhile;
					endif;								
					
				}else if( $_background_type == 'single' || empty($_background_type) || !isset($_background_type) ){
							
					// Random image from post gallery
					if(get_post_meta($post->ID, $shortname.'_show_random_fullsize_value', true) == 'true'){				
		
						$term_id_array = array();
							foreach($post_terms as $index => $value){
							$term_id_array[$index] = $index;
						}		
		
						$random_post = max_query_term_posts( 1 , $term_id_array, 'gallery', 'rand' );
						
						// No image url set => show featured image		
						$imgUrl_temp = max_get_post_image_url($random_post[0]->ID, "full");							
						$imgUrl = $imgUrl_temp[0];						
							
					
					}else{
	
						if($imageURL == ""){
							// No image url set => show featured image		
							$imgUrl_temp = max_get_post_image_url($post->ID, "full");							
							$imgUrl = $imgUrl_temp[0];							
					
						}else{						
							// Image url is set
							$imgUrl = $imageURL;
						}
											
					}
					
					?>
					<a class="item" href="<?php echo $imgUrl; ?>"></a>
					<?php					
				}
				
			}
			?>			
			
			<?php			
			$img_output = "";
			
			// Get Background image for pages		
			if( $show_page_fullsize == 'true' ){
				
				if($_background_type == 'slideshow' && $random_post){
					
					if (have_posts()) : while (have_posts()) : the_post();
					
						// Random image from featured homepage gallery
						$imgUrl_temp = max_get_post_image_url(get_the_ID(), "full");							
						$imgUrl = $imgUrl_temp[0];						
						
						echo '<a class="item" href="'. $imgUrl .'"></a>';						
					
					endwhile;
					endif;
					
				}else{
					
					if( $imageURL == "" ){
						
						// Random image from featured homepage gallery
						$imgUrl_temp = max_get_post_image_url($random_post[0]->ID, "full");							
						$imgUrl = $imgUrl_temp[0];						
																		
					}else{	
						
						// show image from entered URL				
						$imgUrl = $imageURL;
												
					}
					
					$img_output = '<a class="item" href="'. $imgUrl .'"></a>';
					
				}
			
			}
			
			
			wp_reset_query();
			
			/** Its an archive page **/
			if(is_archive()) {
				$imgUrl = get_option_max('page_background_archive');
			}
			
			/** Its a 404 page **/
			if(is_404()) {
				$imgUrl = get_option_max('page_background_404');
			}		

			/** Its a tag archive page **/
			if(is_tag()) {
				$imgUrl = get_option_max('page_background_tag');
			}
			
			/** Its an search result page **/	
			if(is_search()) {
				$imgUrl = get_option_max('page_background_search');
			}			
								
			if( ( is_archive() || is_404() || is_tag() || is_search() ) && $imgUrl != ""){
				$img_output = '<a class="item" href="'. $imgUrl .'"></a>';
			}			
			
			echo $img_output;
			
			?>		
					
		</div>
		
		<script type="text/javascript">

			jQuery(function($){
				// Options for SuperBGImage
				$.fn.superbgimage.options = {
					<?php if( $_background_type == 'slideshow' ){ ?>
					slide_interval: <?php echo get_post_meta($post->ID, 'max_show_page_fullsize_interval', true) ?>,
					<?php } ?>
					slideshow: <?php echo $autoplay; ?>, // 0-none, 1-autostart slideshow
					randomimage: 0,
					preload: 1,
					z_index: 5
				};

				// initialize SuperBGImage
				$('#superbgimage').superbgimage().hide();	
				
			});
			
		</script>		
	
<?php }
}
?>

<?php

	// Get Google Analyric Code if set in options menu
	$google_id = get_option_max('google_analytics_id');
	if(!empty($google_id)){
			
		// including the google anylytics template google-analytics.inc.php
		get_template_part( 'includes/google', 'analytic.inc' );
			
	}
?>

<?php wp_footer(); ?>
<script type='text/javascript' src='http://a.vimeocdn.com/js/froogaloop2.min.js'></script> 

</body>
</html>