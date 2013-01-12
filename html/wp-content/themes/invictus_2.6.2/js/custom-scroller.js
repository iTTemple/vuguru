jQuery(function($) {
		

	// check for touchable device
		var isTouch =  false;	
		if( jQuery('html').hasClass('touch') ){		
			isTouch = true;			
		}

		//scrollpane parts
		var scrollPane = jQuery( ".scroll-pane" ),
			scrollContent = jQuery( ".scroll-content" );

		// set scrollInterval
		var scrollInterval = 25, // scroll steps in px
			timer_speed = 10 // scroll timer speed in milliseconds
					
		// show scroll arrows on hover
		scrollPane.hover( 
			function(){
				jQuery(".scroller-arrow:not(.disabled)").stop(false, true).fadeIn();
			},
			function(){ 
				jQuery(".scroller-arrow:not(.disabled)").stop(false, true).fadeOut();
			}
		);				
		
		var speed = 0;		
		
		function prepareScrollerComponents() {
			
			if(navigator.platform == 'iPad' || navigator.platform == 'iPhone' || navigator.platform == 'iPod'){
				jQuery("#scroll_left, #scroll_right").css({ display: 'block' });
			}
			
			$cw = 0;
			jQuery('li.item', scrollContent).each(function(){ 
				$cw = $cw + jQuery(this).outerWidth(true);
			})
			
			scrollContent.width( $cw );
			
			// calculate the scrolling speed
			speed = (scrollInterval * 100) / $cw;
						
			setScrollerWidth();
						
			if(isTouch === true){

				scrollPane.css({ overflow: 'hidden'}).overscroll({
					direction: 'horizontal'
				}).on('overscroll:dragstart overscroll:dragend overscroll:driftstart overscroll:driftend', function(event){
				})				
				
			}else{			
				scrollbar.slider('option', 'value', 0);			
			}
			
			scrollContent.css({ visibility: 'visible' });
			scrollPane.css({ background: 'none' });
			
		}
		
		jQuery(window).load(function(){
								
			prepareScrollerComponents();
						
		});
		
		jQuery(window).smartresize(function(){  
								
			prepareScrollerComponents();
						
		});
		
		
		var slide_handler = function(e, ui) {
			
			if(isTouch === false){
			
				if(ui.value == 0){
					jQuery("#scroll_left").addClass('disabled').stop(false, true).fadeOut();
					jQuery("#scroll_right").removeClass('disabled').stop(false, true).fadeIn();
				}
				
				if(ui.value > 0 && ui.value < 100){
					jQuery("#scroll_left").removeClass('disabled').stop(false, true).fadeIn();
					jQuery("#scroll_right").removeClass('disabled').stop(false, true).fadeIn();
				}
				if(ui.value == 100){
					jQuery("#scroll_left").removeClass('disabled').stop(false, true).fadeIn();
					jQuery("#scroll_right").addClass('disabled').stop(false, true).fadeOut();
				}
			
			}
			
			if ( scrollContent.width() > scrollPane.width() ) {
				scrollContent.css( "margin-left", Math.round(
					ui.value / 100 * ( scrollPane.width() - scrollContent.width() )
				) + "px" );
			} else {
				scrollContent.css( "margin-left", 0 );
			}
		};					
		
		//build slider
		if(isTouch === false){
			var scrollbar = jQuery( ".scroll-bar" ).slider({
				slide: slide_handler,
				change: slide_handler
			});
		}
		
	jQuery('.scroll-content-item:last').css({marginRight: 0});

	jQuery(window).load(function(){
		
		if(isTouch === false){														
		
			// Mousewheel plugin
			jQuery(scrollPane).add(jQuery(scrollPane).find('li')).mousewheel(function(event, delta) {
				var value = scrollbar.slider('option', 'value');
								
				if (delta >= 0) { value -= speed; }
				else if (delta <= 0) { value += speed; }
																	
				// Ensure that its limited between 0 and 100
				value = Math.max(0, Math.min(100, value));
															
				scrollbar.slider('option', 'value', value);
				
				event.preventDefault();
				
			});
		
		}
	
	})
	

	var isiPad = navigator.userAgent.match(/iPad/i) != null;
	var isiPhone = navigator.userAgent.match(/iPhone/i) != null;
	
	// trigger for scroll right event
	$.fn.mouseenter_trigger_right = function(){

		var maxWidth = ( scrollContent.width() - jQuery(window).width() ) * -1 ;
		
		timer = setInterval(function() { 
		
			jQuery("#scroll_left").removeClass('disabled').stop(false, true).fadeIn();
																
			var slider = jQuery('.scroll-bar');
			var curSlider = slider.slider("option", "value");
			curSlider += speed; // += and -= directions of scroling with MouseWheel

			// Ensure that its limited between 0 and 100
			curSlider = Math.max(0, Math.min(100, curSlider));
						
			if (curSlider > slider.slider("option", "max")){
				jQuery("#scroll_right").addClass('disabled');
				curSlider = slider.slider("option", "max");
			} else if (curSlider < slider.slider("option", "min")){
				curSlider = slider.slider("option", "min");
			}else{
				
			}					
			
			scrollbar.slider('option', 'value', curSlider);						
		
		}, timer_speed);					
		
	}
	
	// trigger for scroll left event
	$.fn.mouseenter_trigger_left = function(){
		
		timer = setInterval(function() { 
	
			jQuery("#scroll_right").removeClass('disabled');
								
			var slider = jQuery('.scroll-bar');;
			var curSlider = slider.slider("option", "value");
			curSlider -= speed; // += and -= directions of scroling with MouseWheel
			
			// Ensure that its limited between 0 and 100
			curSlider = Math.max(0, Math.min(100, curSlider));						
			
			if (curSlider > slider.slider("option", "max")){
				curSlider = slider.slider("option", "max");
			}else if (curSlider < slider.slider("option", "min")){
				jQuery("#scroll_left").addClass('disabled');
					curSlider = slider.slider("option", "min");
			}
						
			scrollbar.slider('option', 'value', curSlider);					
						
		}, timer_speed);

						
	}
	

	if( isTouch === false ){

		jQuery("#scroll_right").mouseenter(function(){
			jQuery(this).mouseenter_trigger_right();
		});
		
		jQuery("#scroll_left").mouseenter(function(){
			jQuery(this).mouseenter_trigger_left();
		});

		jQuery("#scroll_right,#scroll_left").mouseleave(function() {
			clearInterval(timer);
		});					
		
	}
		
	function setScrollerWidth(){
		var origWidth = jQuery(".scroll-bar").width();//read the original slider width
		var sliderWidth = origWidth;//the width through which the handle can move needs to be the original width minus the handle width
		var sliderMargin =  (origWidth - sliderWidth) * 0.5;//so the slider needs to have both top and bottom margins equal to half the difference					
		jQuery(".scroll-bar-wrap").css({ paddingRight: jQuery('.scroll-bar .ui-slider-handle').width() });//set the slider height and margins
		jQuery(".scroll-bar").css({ right: jQuery('.scroll-bar .ui-slider-handle').width() })
		
		jQuery('.scroll-bar .ui-slider-handle').text('::');
		
	}
	
	// Show the scroll-bar-wrap when all images are loaded
	jQuery('#portfolioList img').imagesLoaded(function(){
		jQuery('.scroll-bar-wrap').show();
	})								

});