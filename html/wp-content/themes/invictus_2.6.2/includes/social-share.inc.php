<?php
		/*-----------------------------------------------------------------------------------*/
		/*  Social Sharing
		/*-----------------------------------------------------------------------------------*/		
		// Check if social sharing is activated and get the needed Scripts
		
		if( get_option_max("post_social") == 'true' && ( 	
				get_option_max( "post_social_facebook" ) == 'true' || 
				get_option_max( "post_social_twitter" ) == 'true' || 
				get_option_max( "post_social_google" ) == 'true' 
			) 
		){			
		?>
		<div class="clearfix entry-share">
			
			<div class="share-text">
				<?php get_option_max( "post_social_text", true ) ?>
			</div>
			
			<?php if( get_option_max( "post_social_pinterest" ) == 'true' ) { // check if pinterest should be shown ?>
			<!-- Pintrest -->
			<div class="share-button share-pinterest">
				<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
				<a href="http://pinterest.com/pin/create/button/?url=<?php echo get_permalink($post->ID) ?>&amp;media=<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>&amp;description=Description" class="pin-it-button" count-layout="horizontal" target="_blank"><img src="//assets.pinterest.com/images/PinExt.png" title="Pin It" alt="Pin It" /></a>	
			</div>
			<?php } ?>			
			
			<?php if( get_option_max( "post_social_facebook" ) == 'true' ) { // Check if facebook like should be shown ?>		
			<!-- Facebook like -->
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) {return;}
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/<?php get_option_max('post_social_language', true) ?>/all.js#xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
			</script>		
			<div class="share-button share-facebook">
				<fb:like href="<?php echo get_permalink($post->ID) ?>" send="false" layout="button_count" show_faces="false" font="tahoma"></fb:like>
			</div>
			<?php } ?>
			
			<?php if( get_option_max( "post_social_twitter" ) == 'true' ) { // check if twitter should be shown ?>
			<!-- Twitter -->	
			<div class="share-button share-twitter">
				<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
				<a href="http://twitter.com/share?url=<?php echo get_permalink($post->ID) ?>&amp;text=<?php echo urlencode(get_the_title()) ?>+|+<?php echo urlencode(get_bloginfo( 'name' )); ?>" class="twitter-share-button" data-lang="en" data-url="<?php echo get_permalink($post->ID) ?>">Tweet</a>
			</div>
			<?php } ?>			
		
			<?php if( get_option_max( "post_social_google" ) == 'true' ) { // check if google+ should be shown ?>
			<!-- Google+ -->
			<div class="share-button share-google-plus">
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script> 
				<g:plusone size="medium" href="<?php echo get_permalink($post->ID) ?>"></g:plusone> 
			</div>
			<?php } ?>		

		</div>	
<?php } ?>