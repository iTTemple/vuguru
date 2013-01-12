<?php
global $shortname;

/*-----------------------------------------------------------------------------------*/
/* = Custom function for query posts
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'max_query_posts' ) ):

	function max_query_posts($showposts, $catArray, $random = false, $offset = false, $order = 'DESC') {

		global $post;
		
		$off = !$offset ? "" : $offset;
		$rand = !$random ? "" : $random;
      
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;		

		$cat_string = "";

		// Check if catArray is a array or single cat	
		if(is_array($catArray)){
			$terms = "";
			foreach($catArray as $index => $term_id){
				// get the gallery name
				$term = get_term_by('id', $index, GALLERY_TAXONOMY);
				if($term){
					$cat_string .= $term->slug.',';
				}			
			}
			$cat_string = substr($cat_string, 0, -1);
		}else{
			$cat_string = $catArray;	
		}

        $defaults = array(
                'paged'                         => $paged,
                'posty_type'                    => 'gallery',
				'post__not_in' 					=> array($post->ID),
                'posts_per_page'                => $showposts,
				GALLERY_TAXONOMY				=> $cat_string,
				'orderby'						=> $rand,
				'order'							=> $order,
				'offset'						=> $offset
			);				

		$query = $defaults;
		return query_posts( $query );
	}
endif;

/*-----------------------------------------------------------------------------------*/
/* = Custom function for query term posts
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'max_query_term_posts' ) ):
	function max_query_term_posts($showposts = PER_PAGE_DEFAULT, $id_array, $type = 'gallery', $random = false, $taxonomy = GALLERY_TAXONOMY, $sorting = false, $filter_current = false){		

		global $post;

		$rand = !$random ? "" : $random;
		$sort = !$sorting ? "" : $sorting;	
		
		$posts_to_query = get_objects_in_term($id_array, $taxonomy);
		
		if($filter_current === true){
			$_array_diff = array( 0 => $post->ID );
			$posts_to_query = array_diff($posts_to_query, $_array_diff);
		}
				
		return query_posts( array( 'showposts'=> $showposts, 'post_type' => $type, 'post__in' => $posts_to_query, 'orderby' => $rand, 'order' => $sort ) );
	}
endif;

/*-----------------------------------------------------------------------------------*/
/* = Custom function for query posts by tags
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'max_query_tag_posts' ) ):
	function max_query_tag_posts($showposts = PER_PAGE_DEFAULT, $type = 'gallery', $tag_slug = false, $paged){	
	
		$tag = !$tag_slug ? "" : $tag_slug;		
		return query_posts( array( 'showposts'=> $showposts, 'post_type' => $type, 'tag'=> $tag, 'paged' => $paged) );		
	}
endif;


/*-----------------------------------------------------------------------------------*/
/* = Get a image url from post id
/*-----------------------------------------------------------------------------------*/

function max_get_post_image_url($id, $size = 'full'){
	return wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $size);
}

if ( ! function_exists( 'max_get_image_path' ) ):
	function max_get_image_path ($post_id = null, $size = 'full') {
		if ($post_id == null) {
			global $post;
			$post_id = $post->ID;
		}
		$theImageSrc = max_get_post_image_url($post_id, $size);
		
		global $blog_id;
		
		if (isset($blog_id) && $blog_id > 0) {
			$imageParts = explode('/files/', $theImageSrc[0]);
			if (isset($imageParts[1])) {
				$theImageSrc[0] = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
			}
		}
		
		return $theImageSrc[0];
	}
endif;

/*-----------------------------------------------------------------------------------*/
/* = New function wether to get the timbthumb image url or the standard url
/*-----------------------------------------------------------------------------------*/

function max_get_custom_image_url($imgID = null, $postID = null, $width = false, $height = false, $cropping = false, $greyscale = false, $isSlider = false, $url = false){
	
	global $blog_id;
	
	$use_timthumb = get_option_max('image_no_timthumb');
	
	// an image url is set -> get url and return
	if($url){
		
		$image_output = vt_resize(NULL, $url, $width, $height, true);		
		$imgUrl = (string) $image_output['url'];
		
		return $imgUrl;
	
	}
	
	if(!empty($use_timthumb) && $use_timthumb == 'true'){

		// don't use timthumb and rop images with vt_resize
		$image_output = vt_resize($imgID, max_get_image_path($postID, "full"), $width, $height, true);
		$imgUrl = (string) $image_output['url'];
		
	}else{
		
		if($isSlider){
			$tmp_img = wp_get_attachment_image_src( $imgID, 'full' );
			$img_src = $tmp_img[0];
		}else{
			$img_src = max_get_image_path( $postID, 'full' );
		}
		
		// get the right path for multisite installation
		if (isset($blog_id) && $blog_id > 0) {
			$imageParts = explode('/files/', $img_src);
			if (isset($imageParts[1])) {
				$img_src = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
			}
		}				
				
		$width		= !empty($width) ? "&amp;w=$width" : "";
		$height		= !empty($height) ? "&amp;h=$height" : "";
		$cropping	= !empty($cropping) ? "&amp;a=$cropping" : "";
		$greyscale	= !empty($greyscale) ? "&amp;f=2" : "";
				
		// Use timthumb
		$imgUrl = get_template_directory_uri().'/timthumb.php?src='. $img_src . $height . $width . $cropping . $greyscale .'&amp;q=100';
		
	}	
	
	return $imgUrl;
	
}

/*-----------------------------------------------------------------------------------*/
/* = New function wether to choose the timthumb script or the standard cropping
/*-----------------------------------------------------------------------------------*/

function max_get_post_custom_image($imgID, $p_id = false, $return = false) {
	
	$use_timthumb = get_option_max('image_no_timthumb');
	
	if(!empty($use_timthumb) && $use_timthumb == 'true'){

		// don't use timthumb
		$output = max_get_no_timthumb_image( $imgID, $return );
		
	}else{
		
		// Use timthumb
		$output = max_get_timthumb_image( $return, $p_id );
		
	}
	
	// return the created image
	if($return === true){
		return $output;
	}else{
		echo $output;
	}	
	
}


function max_get_post_custom_image_shows($imgID, $p_id = false, $return = false) {
	
	$use_timthumb = get_option_max('image_no_timthumb');
	
	if(!empty($use_timthumb) && $use_timthumb == 'true'){

		// don't use timthumb
		$output = max_get_no_timthumb_image( $imgID, $return );
		
	}else{
		
		// Use timthumb
		$output = max_get_timthumb_image_shows( $return, $p_id );
		
	
		
	}
	
	// return the created image
	if($return === true){
		return $output;
	}else{
		echo $output;
	}	
	
}


/*-----------------------------------------------------------------------------------*/
/* = Check for a mobile device
/*-----------------------------------------------------------------------------------*/
//https://github.com/justindocanto/isMobile

function max_get_image_string() {
	
	// get the image we need for the different devices
	$detect = new Mobile_Detect();
	$_img_string = 'full';
	
	if ($detect->isMobile()) { // its a mobile device
		$_img_string = 'mobile';
	}
	if ($detect->isTablet()) { // its a tablet
		$_img_string = 'tablet';
	}
	
	return $_img_string;
	
}

/*-----------------------------------------------------------------------------------*/
/* = New function to calculate the image dimensions
/*-----------------------------------------------------------------------------------*/

function max_calculate_image($dimensions, $url) {
	
	$return = array();
	
	// We have a height but no width
	if( !isset( $dimensions['width'] ) && isset( $dimensions['height'] ) && $url[2] != 0 ) {
		
		// calculate the width depending on its height if the height is set
		$perc_height = $dimensions['height'] * 100 / $url[2];
		$calc_Width = floor($url[1] * ( $perc_height / 100 ));
		
		$result['width'] = "&amp;w=".$calc_Width;
		$result['imgWidth'] = ' width="'.$calc_Width.'"';
		
	}elseif( isset( $dimensions['width'] )){
		
		// the width is set by a template or users input, so use it
		$result['width'] = "&amp;w=".$dimensions['width'];		
		$result['imgWidth'] = ' width="' . $dimensions['width'] . '"';
		
	}else{ 		
		// there is no image width, so leave it blank
		$result['width'] = "";
		$result['imgWidth'] = '';		
	}
	
	// We have a width but no height
	if( !isset( $dimensions['height'] ) && isset( $dimensions['width'] ) && $url[1] != 0 ) {
		
		// calculate the height depending on its height if the height is set
		$perc_width = $dimensions['width'] * 100 / $url[1];
		$calc_height = floor($url[2] * ( $perc_width / 100 ));
	
		$result['height'] = "&amp;h=".$calc_height;
		$result['imgHeight'] = ' height="' .$calc_height . '"';
		
	}elseif( isset( $dimensions['height'] ) ){
		
		// the height is set by a template or users input, so use it
		$result['height'] = "&amp;h=".$dimensions['height'];
		$result['imgHeight'] = ' height="' . $dimensions['height'] . '"';
		
	}else{
		
		// there is no image height, so leave it blank
		$result['height'] = "";
		$result['imgHeight'] = '';
		
	}
	
	return $result;
	
}

/*-----------------------------------------------------------------------------------*/
/* = Get a Post Image depending on Options set in Options Panel
/*-----------------------------------------------------------------------------------*/
function max_get_timthumb_image( $return = false, $p_id = false ){
	
	global $shortname, $post, $imgDimensions, $imgDimensions1x, $imgDimensions2x, $p_tpl;	
		
	$detect 		 = new Mobile_Detect(); // get the sting we need for the different devices		
	$size 			 = "full"; // set the attachment image size								
	$post_id 		 = !$p_id ? $post->ID : $p_id; 	// check if its a lightbox or a project page link									
	$photo_item_type = get_post_meta($post_id, $shortname.'_photo_item_type_value', true); // get the item type
	$imgUrl 		 = max_get_post_image_url($post_id, $size); // Get the post image url
	
	// calculate the image dimensions
	if ($detect->isMobile() && !empty($imgDimensions1x) && is_array($imgDimensions1x)) { // its a mobile phone device so we need other images to display properly
		$_dimensions = max_calculate_image($imgDimensions1x, $imgUrl);
	}else{		
		$_dimensions = max_calculate_image($imgDimensions, $imgUrl); // desktop images are larger
	}
			
	// Build the image link
	if ( has_post_thumbnail( $post_id ) ) {
		
		// Get Image URL
		$imgSrc = max_get_image_path($post_id); 	
		$imgFull = max_get_post_image_url($post_id);
					
		// get the title	
		$title = ' title="' . htmlspecialchars(get_the_excerpt()) . '"';
		$alt = ' alt="' . get_the_title() . '"';
		
		$cat_list = array();
		
		foreach(get_the_category() as $category){
			$cat_list[] = $category->cat_ID;
		}
		
		$output = "";
		
		if ( !in_array( get_option_max('general_blog_id'), $cat_list ) ) {
					
			if($photo_item_type == "Lightbox" || $photo_item_type == 'lightbox' || $p_tpl == "template-lightbox.php" ){
								
				$lightbox_type = get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true);
				$lightbox_link = get_post_meta($post_id, $shortname.'_photo_item_custom_lightbox', true);				
				
				if($p_tpl == "template-lightbox.php"){
					
					// check for youtube or vimeo id
					if( $photo_item_type == 'youtube_embed' ){
						$output .= '<a href="http://www.youtube.com/watch?v=' . get_post_meta($post_id, $shortname.'_video_embeded_url_value', true) . '?width=640&amp;height=480" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}else if( $photo_item_type == 'vimeo_embed' ){
						$output .= '<a href="http://www.vimeo.com/' . get_post_meta($post_id, $shortname.'_video_embeded_url_value', true) . '?width=640&amp;height=480" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}else if( $photo_item_type == 'selfhosted_embed' || $photo_item_type == 'selfhosted' ){
						$output .= '<a href="' . get_post_meta($post_id, $shortname.'_video_url_m4v_value', true) . '?iframe=true" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}else{
						$output .= '<a href="' . $imgFull[0] . '" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}
				
				}else{				
				
					if( !empty($lightbox_link) && 'custom' == $lightbox_type ){
		
						$output .= '<a href="' . $lightbox_link . '?iframe=true&amp;width=800&amp;height=600" data-rel="prettyPhoto" data-link="'. get_permalink($post_id).'"'.$title.'>';
	
					}
					
					// Display Lightbox Photo
					if ( get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "Photo" || get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "photo" ){
						
						$output .= '<a href="' . $imgFull[0] . '" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					
					}
					
					// Display Lightbox YouTube Video
					if ( get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "YouTube-Video" || get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "youtube" ){
										
						$output .= '<a href="' . get_post_meta($post_id, $shortname.'_photo_video_youtube_value', true) . '" data-rel="prettyPhoto"'.$title.' data-link="'. get_permalink($post_id).'">';
						
					}
					
					// Display Lightbox Vimeo Video
					if ( get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "Vimeo-Video" || get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "vimeo" ){
	
						$output .= '<a href="' . get_post_meta($post_id, $shortname.'_photo_video_vimeo_value', true) . '" data-rel="prettyPhoto" '.$title.' data-link="'. get_permalink($post_id).'">';
						
					}
				}
												
			}else if($photo_item_type == "Project Page" || $photo_item_type == 'projectpage' || $photo_item_type == 'selfhosted_embed' || $photo_item_type == 'selfhosted' || $photo_item_type == 'youtube_embed' || $photo_item_type == 'vimeo_embed'  ){	

				// Photo Type is a Project Page			
				$output .= '<a href="' . get_permalink($post_id) . '"'.$title.'>';
											
			}else if($photo_item_type == "External Link" || $photo_item_type == 'external' ){	
				
				$target = get_post_meta($post_id, MAX_SHORTNAME.'_external_link_target_value',true);
				$str_target = isset($target) && $target !="" ? $target : "_blank";
				
				// Photo Type is an external Link			
				$output .= '<a href="' . get_post_meta($post_id, $shortname.'_photo_external_link_value', true) . '" target="'.get_post_meta($post_id, MAX_SHORTNAME.'_external_link_target_value',true).'"' . $title . '">';
											
			}else{
				
				// Get the timbthumb image
				$output .=	'<a href="'. $imgFull[0] .'" data-rel="prettyPhoto"' . $title . ' data-link="'. get_permalink($post_id).'">';
			
			}
			
	
		// fallback to lightbox if something is wrong to not break the image link
		}else{		
			
			// Get the timbthumb image
			$output .=	'<a href="'. $imgFull[0] .'" data-rel="prettyPhoto"' . $title . ' data-link="'. get_permalink($post_id).'">';	
			
		}
		
		// get the hisrc image data
		if(is_array($imgDimensions1x) && is_array($imgDimensions2x)) :			
			$data1x = ' data-1x="' . get_template_directory_uri() . '/timthumb.php?src=' . $imgSrc . $_dimensions1x['width'] . $_dimensions1x['height'] . '&amp;amp;a=' . get_cropping_direction( get_post_meta($post_id, $shortname.'_photo_cropping_direction_value', true) ) . '&amp;amp;q=100"';
			$data2x = ' data-2x="' . get_template_directory_uri() . '/timthumb.php?src=' . $imgSrc . $_dimensions2x['width'] . $_dimensions2x['height'] . '&amp;amp;a=' . get_cropping_direction( get_post_meta($post_id, $shortname.'_photo_cropping_direction_value', true) ) . '&amp;amp;q=100"';
		else:
			$data1x = "";
			$data2x = "";
		endif;
		
		// get the image tag, it's always the same 
		$output .= '<img src="' . get_template_directory_uri() . '/timthumb.php?src=' . $imgSrc . $_dimensions['width'] . $_dimensions['height'] . '&amp;amp;a=' . get_cropping_direction( get_post_meta($post_id, $shortname.'_photo_cropping_direction_value', true) ) . '&amp;amp;q=100"'. $alt . $data1x . $data2x . ' />';
		
		// Close Link if its not a disabled link
		if($photo_item_type != "Disable Link" || $photo_item_type != 'disable_link' ) {
			$output .= '</a>';
		}		
		
	}
	
	// pause the script to get the images correctly
	if(function_exists('time_nanosleep')){
		time_nanosleep(0, 100000000);
	}
	
	if($return === true){
		return $output;
	}else{
		echo $output;
	}
}


function max_get_timthumb_image_shows( $return = false, $p_id = false ){
	
	global $shortname, $post, $imgDimensions, $imgDimensions1x, $imgDimensions2x, $p_tpl;	
		
	$detect 		 = new Mobile_Detect(); // get the sting we need for the different devices		
	$size 			 = "full"; // set the attachment image size								
	$post_id 		 = !$p_id ? $post->ID : $p_id; 	// check if its a lightbox or a project page link									
	$photo_item_type = get_post_meta($post_id, $shortname.'_photo_item_type_value', true); // get the item type
	$imgUrl 		 = max_get_post_image_url($post_id, $size); // Get the post image url
	
	// calculate the image dimensions
	if ($detect->isMobile() && !empty($imgDimensions1x) && is_array($imgDimensions1x)) { // its a mobile phone device so we need other images to display properly
		$_dimensions = max_calculate_image($imgDimensions1x, $imgUrl);
	}else{		
		$_dimensions = max_calculate_image($imgDimensions, $imgUrl); // desktop images are larger
	}
			
	// Build the image link
	if ( has_post_thumbnail( $post_id ) ) {
		
		// Get Image URL
		$imgSrc = max_get_image_path($post_id); 	
		$imgFull = max_get_post_image_url($post_id);
					
		// get the title	
		$title = ' title="' . htmlspecialchars(get_the_excerpt()) . '"';
		$alt = ' alt="' . get_the_title() . '"';
		
		$cat_list = array();
		
		foreach(get_the_category() as $category){
			$cat_list[] = $category->cat_ID;
		}
		
		$output = "";
		
		if ( !in_array( get_option_max('general_blog_id'), $cat_list ) ) {
					
			if($photo_item_type == "Lightbox" || $photo_item_type == 'lightbox' || $p_tpl == "template-lightbox.php" ){
								
				$lightbox_type = get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true);
				$lightbox_link = get_post_meta($post_id, $shortname.'_photo_item_custom_lightbox', true);	
				
				$custom_link_new=  get_post_meta($post_id, $shortname.'_photo_item_custom_link', true);	
				
				if($p_tpl == "template-lightbox.php"){
					
					// check for youtube or vimeo id
					if( $photo_item_type == 'youtube_embed' ){
						$output .= '<a href="http://www.youtube.com/watch?v=' . get_post_meta($post_id, $shortname.'_video_embeded_url_value', true) . '?width=640&amp;height=480" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}else if( $photo_item_type == 'vimeo_embed' ){
						$output .= '<a href="http://www.vimeo.com/' . get_post_meta($post_id, $shortname.'_video_embeded_url_value', true) . '?width=640&amp;height=480" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}else if( $photo_item_type == 'selfhosted_embed' || $photo_item_type == 'selfhosted' ){
						$output .= '<a href="' . get_post_meta($post_id, $shortname.'_video_url_m4v_value', true) . '?iframe=true" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}else{
						$output .= '<a href="' . $imgFull[0] . '" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}
				
				}else{				
				
					if( !empty($lightbox_link) && 'custom' == $lightbox_type ){
		
						$output .= '<a href="' . $lightbox_link . '?iframe=true&amp;width=800&amp;height=600" data-rel="prettyPhoto" data-link="'. get_permalink($post_id).'"'.$title.'>';
	
					}
					
					// Display Lightbox Photo
					if ( get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "Photo" || get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "photo" ){
						
						$output .= '<a href="'. $custom_link_new.'?request=ajax"  class="fancybox fancybox.ajax" >';
					
					}
						
					// Display Lightbox YouTube Video
					if ( get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "YouTube-Video" || get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "youtube" ){
										
						$output .= '<a href="'. $custom_link_new.'?request=ajax"  class="fancybox fancybox.ajax" >';
						
					}
					//' . get_post_meta($post_id, $shortname.'_photo_video_youtube_value', true) . '
					// Display Lightbox Vimeo Video
					if ( get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "Vimeo-Video" || get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "vimeo" ){
	
						$output .= '<a href="'. $custom_link_new.'?request=ajax"  class="fancybox fancybox.ajax">';
						
					}
				}
												
			}else if($photo_item_type == "Project Page" || $photo_item_type == 'projectpage' || $photo_item_type == 'selfhosted_embed' || $photo_item_type == 'selfhosted' || $photo_item_type == 'youtube_embed' || $photo_item_type == 'vimeo_embed'  ){	

				// Photo Type is a Project Page			
				$output .= '<a href="'. $custom_link_new.'?request=ajax"'.$title.' class="fancybox fancybox.ajax">';
											
			}else if($photo_item_type == "External Link" || $photo_item_type == 'external' ){	
				
				$target = get_post_meta($post_id, MAX_SHORTNAME.'_external_link_target_value',true);
				$str_target = isset($target) && $target !="" ? $target : "_blank";
				
				// Photo Type is an external Link			
				$output .= '<a href="' . get_post_meta($post_id, $shortname.'_photo_external_link_value', true) . '" target="'.get_post_meta($post_id, MAX_SHORTNAME.'_external_link_target_value',true).'"' . $title . '">';
											
			}else{
				
				// Get the timbthumb image
				$output .=	'<a href="'. $imgFull[0] .'" data-rel="prettyPhoto"' . $title . ' data-link="'. get_permalink($post_id).'">';
			
			}
			
	
		// fallback to lightbox if something is wrong to not break the image link
		}else{		
			
			// Get the timbthumb image
			$output .=	'<a href="'. $imgFull[0] .'" data-rel="prettyPhoto"' . $title . ' data-link="'. get_permalink($post_id).'">';	
			
		}
		
		// get the hisrc image data
		if(is_array($imgDimensions1x) && is_array($imgDimensions2x)) :			
			$data1x = ' data-1x="' . get_template_directory_uri() . '/timthumb.php?src=' . $imgSrc . $_dimensions1x['width'] . $_dimensions1x['height'] . '&amp;amp;a=' . get_cropping_direction( get_post_meta($post_id, $shortname.'_photo_cropping_direction_value', true) ) . '&amp;amp;q=100"';
			$data2x = ' data-2x="' . get_template_directory_uri() . '/timthumb.php?src=' . $imgSrc . $_dimensions2x['width'] . $_dimensions2x['height'] . '&amp;amp;a=' . get_cropping_direction( get_post_meta($post_id, $shortname.'_photo_cropping_direction_value', true) ) . '&amp;amp;q=100"';
		else:
			$data1x = "";
			$data2x = "";
		endif;
		
		// get the image tag, it's always the same 
		$output .= '<img src="' . get_template_directory_uri() . '/timthumb.php?src=' . $imgSrc . $_dimensions['width'] . $_dimensions['height'] . '&amp;amp;a=' . get_cropping_direction( get_post_meta($post_id, $shortname.'_photo_cropping_direction_value', true) ) . '&amp;amp;q=100"'. $alt . $data1x . $data2x . ' />';
		
		// Close Link if its not a disabled link
		if($photo_item_type != "Disable Link" || $photo_item_type != 'disable_link' ) {
			$output .= '</a>';
		}		
		
	}
	
	// pause the script to get the images correctly
	if(function_exists('time_nanosleep')){
		time_nanosleep(0, 100000000);
	}
	
	if($return === true){
		return $output;
	}else{
		echo $output;
	}
}


/*-----------------------------------------------------------------------------------*/
/* = Get a Post Image depending on Options set in Options Panel
/*-----------------------------------------------------------------------------------*/

function max_get_no_timthumb_image( $imgID = false, $p_id = false ){
	
	global $shortname, $post, $imgDimensions, $p_tpl;
									
	$post_id = !$p_id ? $post->ID : $p_id;
		
	// check if its a lightbox or a project page link
	$photo_item_type = get_post_meta($post_id, MAX_SHORTNAME.'_photo_item_type_value', true);
		
	//Crop images with vt_resize
	$image_output = vt_resize($imgID, max_get_image_path(get_the_ID(), "full"), $imgDimensions['width'], $imgDimensions['height'], true);
	$imgUrl = (string) $image_output['url'];
	
	// get the cropped fullsize image
	$fullsize_output = vt_resize($imgID, max_get_image_path(get_the_ID(), "full"), 2000, "", true);
	$fullsizeUrl = (string) $fullsize_output['url'];
				
	$output = "";
		
	if( !isset( $imgDimensions['width'] ) ) {
		$width = "";
		$imgWidth = "";
	}else{
		$width = $imgDimensions['width'];
		$imgWidth = ' width="' . $imgDimensions['width'] . '"';
	}
	if( !isset( $imgDimensions['height'] ) ) {
		$height = "";
		$imgHeight = "";
	}else{
		$height = $imgDimensions['height'];
		$imgHeight = ' height="' . $imgDimensions['height'] . '"';
	}
	
	if ( has_post_thumbnail( $post->ID ) ) {
				
		// check if to show lightbox desc and title
		$title = ' title="' . htmlspecialchars(get_the_excerpt()) . '"';		
		
		$cat_list = array();
		
		foreach(get_the_category() as $category){
			$cat_list[] = $category->cat_ID;
		}
																	
		if ( !in_array( get_option_max('general_blog_id'), $cat_list ) ) {
							
	
			// Photo Type is a Lightbox
			if($photo_item_type == "Lightbox" || $photo_item_type == 'lightbox' || $p_tpl == "template-lightbox.php" ){
								
				$lightbox_type = get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true);
				$lightbox_link = get_post_meta($post_id, $shortname.'_photo_item_custom_lightbox', true);				
				
				if($p_tpl == "template-lightbox.php"){
					
					// check for youtube or vimeo id
					if( $photo_item_type == 'youtube_embed' ){
						$output .= '<a href="http://www.youtube.com/watch?v=' . get_post_meta($post_id, $shortname.'_video_embeded_url_value', true) . '?width=640&amp;height=480" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}else if( $photo_item_type == 'vimeo_embed' ){
						$output .= '<a href="http://www.vimeo.com/' . get_post_meta($post_id, $shortname.'_video_embeded_url_value', true) . '?width=640&amp;height=480" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}else if( $photo_item_type == 'selfhosted_embed' || $photo_item_type == 'selfhosted' ){
						$output .= '<a href="' . get_post_meta($post_id, $shortname.'_video_url_m4v_value', true) . '?iframe=true" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}else{
						$output .= '<a href="' . $imgFull[0] . '" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					}
				
				}else{				
													
					// Display Lightbox Photo
					if ( get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "Photo" || get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "photo" ){
						
						$output .= '<a href="' . $fullsizeUrl . '" data-rel="prettyPhoto[gal]" data-link="'. get_permalink($post_id).'"'.$title.'>';
					
					}
					
					// Display Lightbox YouTube Video
					if ( get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "YouTube-Video" || get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "youtube" ){
						
						$output .= '<a href="' . get_post_meta($post_id, $shortname.'_photo_video_youtube_value', true) . '" data-rel="prettyPhoto"'.$title.' data-link="'. get_permalink($post_id).'">';
						
					}
					
					// Display Lightbox Vimeo Video
					if ( get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "Vimeo-Video" || get_post_meta($post_id, $shortname.'_photo_lightbox_type_value', true) == "vimeo" ){
						
						$output .= '<a href="' . get_post_meta($post_id, $shortname.'_photo_video_vimeo_value', true) . '" data-rel="prettyPhoto" '.$title.' data-link="'. get_permalink($post_id).'">';
						
					}
					
				}
				
			}else if($photo_item_type == "Disable Link" || $photo_item_type == 'disable_link' ){ 
			
				// Photo Type is Disabled Link
				$output .= '';
						
			}else if($photo_item_type == "Project Page" || $photo_item_type == 'projectpage' || $photo_item_type == 'selfhosted_embed' || $photo_item_type == 'selfhosted' || $photo_item_type == 'youtube_embed' || $photo_item_type == 'vimeo_embed'  ){	

				// Photo Type is a Project Page			
				$output .= '<a href="' . get_permalink($post_id) . '"'.$title.'>';
											
			}else if($photo_item_type == "External Link" || $photo_item_type == 'external' ){	
				
				$target = get_post_meta($post_id, MAX_SHORTNAME.'_external_link_target_value',true);
				$str_target = isset($target) && $target !="" ? $target : "_blank";
				
				// Photo Type is an external Link			
				$output .= '<a href="' . get_post_meta($post_id, $shortname.'_photo_external_link_value', true) . '" target="'.get_post_meta($post_id, MAX_SHORTNAME.'_external_link_target_value',true).'"' . $title . '>';

			}else{
				// Get the timbthumb image
				$output .=	'<a href="'. $fullsizeUrl .'" data-rel="prettyPhoto"'.$title.' data-link="'. get_permalink($post_id).'">';
			}			
		}else{
			$output .=	'<a href="' . max_get_image_path($post->ID, "full") . '" data-rel="prettyPhoto"'.$title.' data-link="'. get_permalink($post->ID).'" class="rel">';
		}
		
		$img_height = $height ? 'height="' . $height . '"' : "";
		
		if($photo_item_type != "Disable Link" || $photo_item_type != 'disable_link' ){ 
			$output .= '<span class="overlay"><span class="link"></span></span>';
		}
		
		// get the image
		$output .= '<img src="'.$imgUrl.'" width="' . $width . '" '. $img_height . ' alt="' . get_the_title() . '" />';
		
		if($photo_item_type != "Disable Link" || $photo_item_type != 'disable_link' ){ 
			$output .= '</a>';
		}
		
										
	}
	
	if($return === true){
		return $output;
	}else{
		echo $output;
	}
}



/*-----------------------------------------------------------------------------------*/
/* = Get a Post Image depending on Options set in Options Panel
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'max_get_slider_image' ) ):

	function max_get_slider_image( $_meta, $img_slug = 'slides-slider', $sort = 0, $return = false, $greyscale = false, $crop = false){
		
		// Get Image URL
		$theImageSrc = wp_get_attachment_image_src( $_meta['imgID'], $img_slug );
		
		global $blog_id;
					
		$output = "";
			
		$cat_list = array();
					
		$imgWidth =  '';
		$width = MAX_CONTENT_WIDTH;
		$height = 440;
		
		if($img_slug == 'slides-slider') :
			$width = $theImageSrc[1];
			$height = $theImageSrc[2];
		endif;		
		
		foreach(get_the_category() as $category){
			$cat_list[] = $category->cat_ID;
		}
					
		// check greyscale images
		$greyscale = $greyscale == "true" ? "&amp;f=2" : "";
		
		// output the link and the image					
		$_link = wp_get_attachment_url( $_meta['imgID'] );					
		$_add =  ' data-rel="prettyPhoto[gal-'.get_the_ID().']"';
		
		// check title
		$title = isset($_meta['showtitle']) && $_meta['showtitle'] == 'true' ? ' title="' . get_the_title() . '"' : $title = "";
		$_cropping = !empty($_meta['cropping']) ? $_meta['cropping'] : "c";
		
		$img_url = max_get_custom_image_url($_meta['imgID'], false, $width, $height, $_cropping, false, true );

		$output .= '<a href="'. $_link .'" '. $title . $_add .' data-link="'. get_permalink().'">';
		$output .= '<img src="'. $img_url .'" alt="' . htmlspecialchars(get_the_excerpt()) . '"'. $imgWidth .' />';
		$output .= '</a>';

		if($return === true){
			return $output;
		}else{
			echo $output;
		}
		
	}

endif;


/*-----------------------------------------------------------------------------------*/
/* = Get a Post Lightbox CSS Class
/*-----------------------------------------------------------------------------------*/

function max_get_post_lightbox_class(){
	
	global $shortname, $post, $imgDimensions, $p_tpl;
	
	$link_type = get_post_meta($post->ID, MAX_SHORTNAME.'_photo_item_type_value', true);
	$lightbox_type = get_post_meta($post->ID, MAX_SHORTNAME.'_photo_lightbox_type_value', true);
	
	$class = "";
	$class2 = "";

	if( $p_tpl == "template-lightbox.php" ) {
		
		$class = "lightbox";
		
	}else{
		
		switch($link_type){
		
			case 'lightbox':
			case "Lightbox":
			
				$class = "lightbox";
			
				switch($lightbox_type){
			
					case "Photo":
					case "photo": 
						$class2 = " photo";
					break;
					
					case "YouTube-Video":
					case "youtube":
						$class2 = " youtube-video";
					break;		
					
					case "Vimeo-Video":
					case "vimeo":
						$class2 = " vimeo-video";
					break;
					
					default: 
						$class2 = "";
					break;
				}
				
			break;
			
			case 'projectpage':
			case 'Project Page':
			
				$class = "link";
				
			break;
			
			case 'external':
			case 'External Link':
			
				$class = "external";
				
			break;
			
			case 'selfhosted':
			case 'youtube_embed':
			case 'vimeo_embed':
	
				$class = "video";
				
			break;			
	
			default: $class = "photo";
			break;
	
		}
			
		return $class.$class2;
	
	}
	
}




/*-----------------------------------------------------------------------------------*/
/* = Custom excerpt function
/*-----------------------------------------------------------------------------------*/

function max_get_the_excerpt( $echo = false ){
	
	global $shortname, $post;
	
	$excerpt = $post->post_excerpt; 
	
	if ($excerpt != "" ) {
		if ( $echo === true ) { 
			the_excerpt();
		}else{
			return $excerpt;
		}
	}
	
	return false;
	
}

/*-----------------------------------------------------------------------------------*/
/* = Get all the meta fields for a page or post and store it in an array
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'max_get_cutom_meta_array' ) ):

	function max_get_cutom_meta_array( $id = 0 ){
		//if we want to run this function on a page of our choosing them the next section is skipped.
		//if not it grabs the ID of the current page and uses it from now on.
		if ($id == 0) :
			global $wp_query;		
			$content_array = $wp_query->get_queried_object();
			@$id = $content_array->ID;
		endif;   
	
		//knocks the first 3 elements off the array as they are WP entries and i dont want them.
		$first_array = @get_post_custom_keys($id);
	
		if(count($first_array)){
			//first loop puts everything into an array, but its badly composed
			foreach ($first_array as $key => $value) :
				   $second_array[$value] =  get_post_meta($id, $value, FALSE);
		
					//so the second loop puts the data into a associative array
					foreach($second_array as $second_key => $second_value) :
							   $result[$second_key] = $second_value[0];
					endforeach;
			 endforeach;
		}else{
			return false;
		}
	
		//and returns the array.
		return $result;
		
	}
	
endif;

/*-----------------------------------------------------------------------------------*/
/* = Get custom prev and next links for custom taxonomy
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'max_get_custom_prev_next' ) ):

	function max_get_custom_prev_next( $term_ids, $order_by = 'date', $order = 'DESC', $post_type = "gallery", $taxonomy = GALLERY_TAXONOMY ){
		
		global $post;
		
		// query all other posts from the current post categories
		$_nav_posts = max_query_term_posts( 9999, $term_ids, $post_type, $order_by, $taxonomy, $order );
							
		foreach($_nav_posts as $_index => $_value){							
			$_id_array[] = $_value->ID;							
		}	
		
		// prepare some values
		$_search_id = $post->ID;	
		$_first_id = current($_id_array);
		$_last_id = $_id_array[sizeof($_id_array)-1];		
		
		$_current_key = array_search($_search_id, $_id_array);	
		$_current_value = $_id_array[$_current_key];						
						
		$_prev_id = "";
		$_next_id = "";
					
		// get next post_id
		if($_search_id != $_last_id){
			$_next_id = $_current_key + 1;
			$_next_value = $_id_array[$_next_id];
		}
		
		// get prev post_id
		if($_search_id != $_first_id){
			$_prev_id = $_current_key - 1;
			$_prev_value = $_id_array[$_prev_id];
		}
		
		$_return_values = array(
			'prev_id' => @$_prev_value,
			'next_id' => @$_next_value
		);
		
		return $_return_values;
		
	}
	
endif;

?>