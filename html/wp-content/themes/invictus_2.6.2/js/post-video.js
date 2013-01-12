/** Javascript to load the videos on demand **/

	// check for touchable device
	var isTouch =  false;	
	if( jQuery('html').hasClass('touch') ){		
		isTouch = true;			
	}
	
	var video_viewport = jQuery(window).width();
	jQuery(window).smartresize(function(){  
		video_viewport = jQuery(window).width();
	});
	
	// set the global video play to false
	window.videoplay = false;
	
	jQuery('#vimeoplayer, #youtubeplayer, #selfhostedplayer').hide();
	
	// store some variables for further use
	var $_main 				= jQuery('#main'),
		$_page 				= jQuery('#page'),
		$_primary 			= jQuery('#primary'),
		$_myloading 		= jQuery('#my-loading'),
		$_thmbs 			= jQuery('#thumbnails'),
		$_sbgimage			= jQuery("#superbgimage"),
		$_sbgplayer 		= jQuery("#superbgimageplayer"),
		$_supbgimg 			= $_sbgplayer.size() > 0,
		$_fullplayer 		= jQuery('#fullsizeVideo'),
		$_fullvid 			= $_fullplayer.size() > 0,
		$_fullsizetimer 	= jQuery('#fullsizeTimer'),
		$_fullsizestart 	= jQuery('#fullsizeStart'),
		$_fullsizestop 		= jQuery('#fullsizeStop'),
		$_fsg_button		= jQuery('#fsg_playbutton'),
		$_fullsize 			= jQuery('#fullsize'),
		$_scanlines 		= jQuery('#scanlines'),
		$_togthumbs			= jQuery('#toggleThumbs'),
		clickevent 			= 'click',
		fadeInterval 		= 250;
	
	// catch the data-object attr from #thumbnails and parse the json
	var dataObject_Attr = $_thmbs.attr('data-object');	
	var dataObject = jQuery.parseJSON(dataObject_Attr);	
			
	// create the json Object handled by the getScript call
	var json = {
		post_type: 		window.videoUrl.type,
		postID:			window.videoUrl.postID,
		embedded_code: 	window.videoUrl.embedded_code,
		playerID: 		'superbgimageplayer',
		poster_url: 	window.videoUrl.poster_url
	}	
	
	var selfhosted = {};
	if(window.videoUrl.type == 'selfhosted') {
		selfhosted = {
			stretch_video: 	window.videoUrl.stretch_video,
			url_m4v: 	window.videoUrl.url_m4v,
			url_ogv: 	window.videoUrl.url_ogv			
		}
	}
		
	// some options have to be changed on mobile devices
	if(isTouch === true){
		clickevent = 'touchend'; // change click event for play button to touchstart
		dataObject.fullsize_autoplay_video = 'false'; // disable autoplay on mobile devices, they do not work
	}
	
	// toggle player
	jQuery.fn.toggleWithVisibility = function(trigger){
		
		var container = jQuery(this);
		
		if( trigger == 'hide' && container.css('visibility') == 'visible' ) {
			jQuery(this).css({
				'height': '0',
				'visibility': 'hidden'
			});
		} 
	
		if( trigger == 'show' && container.css('visibility') == 'hidden' ) {		
			container.css({
				'height': '100%',
				'visibility': 'visible'
			});
		}
		
	}
	
 	// on video end event function
	function onVideoEndTrigger(){
		
		window.videoplay = false;
		
		if($_supbgimg) {	
		
			$_fullsize.livequery(function(){

				if($_fullsize.find('a[rel]').length > 1){
					jQuery.fn.superbgimage.options.slideshow = 1;
					jQuery(this).nextSlide();			
					$_sbgplayer.hideAllElements('show');
					$_thmbs.fadeIn(fadeInterval);
				}
	
			})
		}
	
		if($_fullvid) {	
			$_fullplayer.hideAllElements('show');
		}
								
	}
		
	// on video play event function
	function onVideoPlayTrigger(){
				
		var playerContainer = $_sbgplayer;
		window.videoplay = true;

		$_myloading.fadeOut(fadeInterval);
		
		// hide active superbgimage	and show the player iframe
		jQuery('#superbgimage, #superbgimage img.activeslide').stop(false, true).fadeOut(fadeInterval);
		
		if ($_supbgimg) {
			
			$_scanlines.css({ zIndex: 0 });
					
			// stop timer and slideshow and change controls
			$_fullsizestart.hide();			
			$_fullsizestop.show();			
			$_fullsizetimer.stopTimer();
			$_fullsize.stopSlideShow();
			$_fullsizestart.removeClass('disabled');			
			$_scanlines.stop(false, true).animate({opacity: 0}, 450, function(){
				jQuery(this).hide();			
			});
			
			// Hide the play button and some page elements
			$_main.fadeOut(fadeInterval);
			$_fsg_button.fadeOut(fadeInterval);
			
			if($_togthumbs.has('slide-down') ){
				// hide or show all page elements on video play
				if( typeof dataObject.video_show_elements != 'undefined' && dataObject.video_show_elements == 'true'){
					$_thmbs.toggleThumbnails('hide', false);
					$_thmbs.fadeOut(fadeInterval);
				}else{
					$_thmbs.hideAllElements('hide');
				}
			};
			
			// hide the page container show make the video visible on mobile devices
			if(video_viewport <= 767 ){
				$_page.hide();
			}else{
				$_page.show();
			}
		
		}	
		
		if(typeof videoPauseTrigger != 'undefined'){
			clearTimeout(videoPauseTrigger);
		}
				
		// hide thumbnails on fullsize video
		if($_fullvid) {
			$_primary.fadeOut(fadeInterval);
			playerContainer = $_fullplayer;
			
			if($_thmbs.size() > 0 ) {
				if( typeof dataObject.video_show_elements != 'undefined' && dataObject.video_show_elements == 'true'){
					$_thmbs.toggleThumbnails('hide', false);
				}else{
					$_thmbs.hideAllElements('hide');
				}
			}else{
				$_fullplayer.hideAllElements('hide');
			}
		}	
		
		if(isTouch){
			playerContainer.toggleWithVisibility('show');
		}
									
	}
	
	// function for video play click trigger
	function onVideoClickTrigger(){
		$_fsg_button.hide();
		$_myloading.fadeIn(fadeInterval);
		$_fullsize.stopSlideShow();	
	}
	
	// on video pause event function
	function onVideoPauseTrigger(){
					
		window.videoplay = false;
					
		var playerContainer = $_sbgplayer;		
		
		if($_supbgimg){
		
			if($_thmbs.size() > 0 ) {
				
				$_thmbs.fadeIn(fadeInterval);
				
				if( dataObject.homepage_show_thumbnails == 'true' && $_togthumbs.has('slide-up') ){
					if(dataObject.video_show_elements == 'true'){
						if(video_viewport >= 768 ){
							$_thmbs.toggleThumbnails('show');
						}
					}else{
						$_thmbs.hideAllElements('show');
					}
				}else{		
					$_thmbs.hideAllElements('show');
				};			
				
			};
	
			$_main.fadeIn(fadeInterval);			
			$_scanlines.stop(false, true).fadeOut(fadeInterval);
			
			// show the page container when pausing video
			if(video_viewport <= 767 ){
				$_page.show();
			}
			
		};
			
		// not a selfhosted video
		if ( json.post_type !== 'selfhosted' || isTouch === true ) {
			$_fsg_button.fadeIn(fadeInterval);
		}					
				
		if($_fullvid) {			
		
			playerContainer = $_fullplayer;
			$_primary.fadeIn(fadeInterval);			
			
			if($_thmbs.size() > 0 ) {
				
				$_thmbs.fadeIn(fadeInterval);
				
				if( typeof dataObject.video_show_elements != 'undefined' && dataObject.video_show_elements == 'true'){
					$_thmbs.toggleThumbnails('show');
				}else{
					$_thmbs.hideAllElements('show');
				}
			}else{
				$_fullplayer.hideAllElements('show');
			}
						
		}

		/* show active superbgimage	and hide the player iframe */
		if(isTouch){		
			playerContainer.toggleWithVisibility('hide');				
			jQuery('#superbgimage, #superbgimage img.activeslide').css({zIndex: 5}).stop(false, true).fadeIn(fadeInterval);
		}		
								
	}	
	
	function prepareVideoComponents(triggerClass, container){
		 		 
		if(isTouch === true){
			jQuery('#superbgimage, #scanlines').fadeOut(fadeInterval);
			jQuery('#superbgplayer, #superbgplayer iframe').show();
		}
		 				
		if($_supbgimg) {
		
			if(dataObject.fullsize_autoplay_video == 'false'){
				if( jQuery.fn.superbgimage.options.slideshow == 1 ){
					$_fullsizetimer.startTimer( jQuery.fn.superbgimage.options.slide_interval );
					$fullsize.startSlideShow();
				}else{
					$_fullsize.stopSlideShow();
				}
			}
		
		}else{		
			$_fullsize.stopSlideShow();				
		}
		
		// hide scanlines if video is
		if(dataObject.fullsize_autoplay_video == 'true' || isTouch === true || json.post_type !== 'selfhosted' ){
			$_scanlines.fadeOut(fadeInterval);
		}
		
		// Show play pause button if video is no autoplay		
		if ( ( dataObject.fullsize_autoplay_video == 'false' && json.post_type !== 'selfhosted' ) || isTouch === true ) {
			$_fsg_button.fadeIn(fadeInterval);
		}
		
		// resize
		jQuery('#'+json.playerID + ' ' + container).css({width:100+"%",height:100+"%"});		
				
		if($_supbgimg) {
				
			jQuery('#fullsize a' + "[rel='" + jQuery.superbg_imgActual + "']").livequery(function(){		
				
				// add class trigger
				jQuery('#'+json.playerID).addClass(triggerClass);
				
			})
		}
		
			
		if($_fullvid) {						
			// add class trigger
			jQuery('#'+json.playerID).addClass(triggerClass);
		}
		
		jQuery('#sidebar, #welcomeTeaser').click(function(event){
			event.stopPropagation();
		})
		
		$_myloading.fadeOut(fadeInterval);
		
	}
	
	// -----------------------------------------------
	// THE YOUTUBE CALLBACKS 
	// -----------------------------------------------	
		
	if (json.post_type === 'youtube_embed') {	 
		 
		jQuery('#superbgimageplayer, #youtubeplayer').show();
		 
		if(!ytplayer){
							
			// 2. This code loads the IFrame Player API code asynchronously.
			var tag = document.createElement('script');
			tag.src = "//www.youtube.com/iframe_api";
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

		}
		
		// 3. This function creates an <iframe> (and YouTube player)
		//    after the API code downloads.
		var ytplayer;
		
		function onYouTubeIframeAPIReady() {	
			
			ytplayer = new YT.Player('youtubePlayer_Frame', {
				height: '100%',
				width: '100%',
				videoId: json.embedded_code,
				playerVars: {
					showinfo: '0',
					autohide: '1',
					hd: '1',
					modestbranding: '1'
				},				
				events: {
					'onReady': onYouTubePlayerReady,
					'onStateChange': onytplayerStateChange
				}
			});
			
		}
		
		// 4. The API will call this function when the video player is ready.
		function onYouTubePlayerReady(playerId) {			
				
			jQuery('#superbgimageplayer, #youtubeplayer').show();
								
			prepareVideoComponents('ytplayer_init', 'iframe')
			
			var $_clickObject = $_fsg_button.add($_main);				
			$_clickObject.add($_page).unbind(clickevent).on(clickevent,function(event){
				
				if(event.target==this){
					onVideoClickTrigger();
					ytplayer.playVideo();
					return false;					
				}
				
			});		
			
			// play video in highres if set
			if(dataObject.fullsize_yt_hd == 'true'){
				ytplayer.setPlaybackQuality('highres');
			}
			
			// load youtube video
			if(dataObject.fullsize_autoplay_video == 'true'){
				ytplayer.playVideo();
			}
						
		}
		
		// 5. The API calls this function when the player's state changes.
		//    The function indicates that when playing a video (state=1),
		//    the player should play for six seconds and then stop.
		var done = false;
		
		function onytplayerStateChange(event) {		
		
			// video is unstarted
			if( event.data == -1 ){
			}
			
			//video is buffered or paused
			if( event.data == YT.PlayerState.BUFFERING || event.data == YT.PlayerState.PAUSED ){
				
				if(typeof videoPauseTrigger != 'undefined'){
					clearTimeout(videoPauseTrigger);
				}			
						
				// if paused clear activity trigger
				if( event.data == YT.PlayerState.PAUSED ){
					videoPauseTrigger = setTimeout( "onVideoPauseTrigger()", 250);					
				}
				
				// if buffering disable start button
				if( event.data == YT.PlayerState.BUFFERING ){
					
				}
			}
			
			// video is playing 
			if(event.data == YT.PlayerState.PLAYING){				
				onVideoPlayTrigger();				
			}
			
			if( event.data == YT.PlayerState.CUED ){
				onYouTubePlayerReady();
			}
			
			// video has ended
			if( event.data == YT.PlayerState.ENDED ){			
				onVideoEndTrigger();
			}
			
		}
			
	}
			
	// resize video containers
	if($_supbgimg) jQuery('#superbgplayer, #superbgimageplayer').css({width:100+"%",height:100+"%"}) // its a full size gallery
	if($_fullvid) jQuery('#fullsizeVideoHolder, #fullsizeVideo').css({width:100+"%",height:100+"%"}) // its a single full size video template
	
		// INIT THE YOUTUBE PLAYER
		if (json.post_type === 'youtube_embed') {		
			
			jQuery('#'+json.playerID).removeClass('vimeoplayer_init jwplayer_init');		
			if(!ytplayer){
				jQuery('#youtubeplayer').append('<iframe id="youtubePlayer_Frame" type="text/html" width="100%" height="100%" src="http://www.youtube.com/embed/'+json.embedded_code+'?enablejsapi=1&version=3&showinfo=0&wmode=transparent" frameborder="0"></iframe>');
			}else{
				ytplayer.cueVideoById(json.embedded_code);
			}
			
			jQuery('#vimeoplayer, #selfhostedplayer').hide();
			jQuery('#youtubeplayer').css({width:100+"%",height:100+"%"});
			
		}
	
		// -----------------------------------------------
		// THE VIMEO PLAYER CALLBACKS
		// -----------------------------------------------
				
		if (json.post_type === 'vimeo_embed') {
						
			jQuery('#youtubeplayer, #selfhostedplayer').hide();
			jQuery('#superbgimageplayer, #vimeoplayer').show();										
						
			jQuery('#vimeoplayer')
				.css({width:100+"%",height:100+"%"})
				.html('<iframe id="vimeoPlayer_Frame" src="http://player.vimeo.com/video/'+json.embedded_code+'?quality=hd&api=1&player_id=vimeoPlayer_Frame&portrait=0&title=0" width="100%" height="100%" frameborder="0"></iframe>');		
						
			var vimeoplayer;	
	
			// function for pause event
			function vimeoPlayerPause(){			
				if(typeof videoPauseTrigger != 'undefined'){
					clearTimeout(videoPauseTrigger);
				}				
				videoPauseTrigger = setTimeout( "onVideoPauseTrigger()", 250);
			}
			
			
			function vimeoPlayerPlay(){
				onVideoPlayTrigger();
			}
			
			function vimeoplayerReady(playerID) {
				
				var $_clickObject = $_fsg_button.add($_main);				
				$_clickObject.add($_page).unbind(clickevent).on(clickevent,function(event){
					
					if(event.target==this){
						onVideoClickTrigger();
						Froogaloop(playerID).api('play');
						return false;
					}
					
				});
				
				var prepare = prepareVideoComponents('vimeoplayer_init','object');
			}
				
			jQuery('#vimeoplayer').show();
			jQuery('#'+json.playerID).removeClass('ytplayer_init jwplayer_init');
	
			jQuery('body').livequery(function(){	
	
				// Enable the API on each Vimeo video
				jQuery('iframe#vimeoPlayer_Frame').each(function(){
					$f(this).addEvent('ready', ready);
					jQuery(this).css({height:'100%',width:'100%'});
				});
				
				function ready(playerID){
					
					vimeoplayerReady(playerID);		
								
					// add the event listeners		
					function setupEventListeners() {
						
						// player is playing
						function onPlay() {
							$f(playerID).addEvent('play',
							function(data) {
								vimeoPlayerPlay();
							});
						}
			
						// player is paused
						function onPause() {		
							$f(playerID).addEvent('pause',
							function(data) {
								vimeoPlayerPause();
							});
						}
						
						// player has finished playback
						function onFinish() {
							$f(playerID).addEvent('finish',
							function(data) {
								onVideoEndTrigger();
							});
						}
						onPlay();
						onPause();
						onFinish();
					}
					
					setupEventListeners();					
										
					if(dataObject.fullsize_autoplay_video == 'true'){
						// Fire an API method
						// http://vimeo.com/api/docs/player-js#reference
						Froogaloop(playerID).api('play');
					}
					
				}
				
			})
			
		}
			
		if (json.post_type === 'selfhosted') {
	
			jQuery('#vimeoplayer, #youtubeplayer').hide();
			jQuery('#selfhostedplayer').css({width:100+"%",height:100+"%"});
	
			jQuery('#'+json.playerID).removeClass('vimeoplayer_init ytplayer_init');
																
			var fileObj = [ { file: selfhosted.url_m4v }, { file: selfhosted.url_ogv } ]  // create file levels object	
			
			if(isTouch===true){		
				var stretch_video = 'uniform';
			}else{
				var stretch_video = json.stretch_video;
			}
			
			// get the player
			jwPlayer = jwplayer('selfhostedplayer').setup({ 
												
				skin: dataObject.directory_uri+"/css/"+dataObject.color_main+"/jwplayer/invictus/invictus.xml",
				
				flashplayer: dataObject.directory_uri+"/js/jwplayer/player.swf",
				modes: [
					{ type: "html5" },
					{ type: "flash", src: dataObject.directory_uri+"/js/jwplayer/player.swf" }
				],
				
				image: json.poster_url,
				autoplay: dataObject.fullsize_autoplay_video,
				
				levels: fileObj,	
				stretching: selfhosted.stretch_video,
				fullscreen: false,
				repeat: "none",
				height: 100 + "%",
				width: 100 + "%",
				events: {
					onReady: function(){
						$_myloading.fadeOut(fadeInterval);	
						jQuery('#superbgimageplayer, #selfhostedplayer').show();
						
						if(isTouch || dataObject.fullsize_autoplay_video == 'false') {
				
							var $_clickObject = $_fsg_button.add($_main);				
							$_clickObject.add($_page).unbind(clickevent).on(clickevent,function(event){
								
								if(event.target==this){
									onVideoClickTrigger();
									jwPlayer.play();
									return false;									
								}
								
							});
							
						}
						
						// prepare the video components
						prepareVideoComponents('jwplayer_init', false);
					},
												
					onPlay: function(event){						
						// init the play trigger
						onVideoPlayTrigger();												
					},
					
					onPause: function(event){
						// init the pause trigger
						onVideoPauseTrigger();
					},
													
					onComplete: function(event){
						jwPlayer.stop();				
						onVideoEndTrigger();
					}
								
				},
				"controlbar.position": 'over'
			})
		}
		