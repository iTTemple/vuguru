<?php 
global $meta, $portfolio, $post, $post_meta;

$meta = max_get_cutom_meta_array(get_the_ID());

$embededCode = $meta[MAX_SHORTNAME.'_video_embeded_value'];

$_m4v = $meta[MAX_SHORTNAME.'_video_url_m4v_value'];
$_ogv = $meta[MAX_SHORTNAME.'_video_url_ogv_value'];

// Video Preview is an Imager from an URL	
if($meta[MAX_SHORTNAME.'_video_poster_value'] == 'url'){
	$_poster_url = $meta[MAX_SHORTNAME.'_video_url_poster_value'];
}

// Video Preview is the post featured image or the URL was chosen but not set
if( $meta[MAX_SHORTNAME.'_video_poster_value'] == 'featured' || ( $meta[MAX_SHORTNAME.'_video_poster_value'] == 'url' && $meta[MAX_SHORTNAME.'_video_poster_value'] == "" ) ){
	
	$_previewUrl = max_get_image_path($post->ID, 'full');
	
	// get the imgUrl for showing the post image
	$_poster_url = max_get_custom_image_url(get_post_thumbnail_ID(get_the_ID()), get_the_ID(), MAX_CONTENT_WIDTH, $meta[MAX_SHORTNAME.'_video_height_value'] );												

}

?>

<div class="entry-video-wrapper">
<div class="entry-video" style="width: 940px">

<?php if( $meta[MAX_SHORTNAME.'_photo_item_type_value'] == "selfhosted" ) { ?>	

<?php $video_ratio = 100 * $meta[MAX_SHORTNAME.'_video_height_value'] / 940; ?>

	<div class="video_wrapper" style="padding-bottom: <?php echo $video_ratio ?>%;">
		<div class="video_wrapper_inside">
		
			<video id="postVideo_<?php echo $post->ID ?>" poster="<?php echo $_poster_url ?>">
			<?php if($_m4v != '') : ?><source src="<?php echo $_m4v ?>" type="video/mp4" /><?php endif; ?>
			<?php if($_ogv != '') : ?><source src="<?php echo $_ogv ?>" type="video/ogv" /><?php endif; ?>
			</video>
		
		</div>
	</div>

<?php max_get_video_js($post->ID); ?>
							
<?php } else if ( $meta[MAX_SHORTNAME.'_photo_item_type_value'] == "youtube_embed" || $meta[MAX_SHORTNAME.'_photo_item_type_value'] == "vimeo_embed" ){ ?>
						
	<?php echo stripslashes(htmlspecialchars_decode($embededCode)); ?>
			
<?php } ?>
</div>
</div>