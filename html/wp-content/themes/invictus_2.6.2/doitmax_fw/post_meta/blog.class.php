<?php
/* #################################################################################### */
/*
/* Class for Blog Post Option Set
 *
 * @author		Dennis Osterkamp aka "doitmax"
 * @copyright	Copyright (c) Dennis Osterkamp
 * @link		http://www.do-media.de
 * @since		Version 1.0
 * @package 	MaxFrame
 * 
 * @filedesc 	Option set to create the blog post meta box options
 *
/* #################################################################################### */

class UIElement_Blog extends UIElement {

	public function __construct($type) {
        parent::__construct($type);
	}

	public function getMetaBox() {
		
		global $cropping_array;
		
		
		$this->createMetabox(array(
			'id' => MAX_SHORTNAME.'_blog_settings_meta_box',
			'title' => __('Post Settings', MAX_SHORTNAME),
			'priority' => "high"
		));		
	
		
		// Photo item type		
		$this->addDropdown(array(
			'id' => MAX_SHORTNAME."_photo_item_type_value",
			'label' => __('Show a video?', MAX_SHORTNAME),			
			'options' => array('none'=>'No video', 
							   'selfhosted'=> __('Self hosted video', MAX_SHORTNAME), 
							   'youtube_embed'=> __('YouTube embedded video', MAX_SHORTNAME),
							   'vimeo_embed'=> __('Vimeo embedded video', MAX_SHORTNAME)
							  ),
			'standard' => "lightbox",
		));	
		
		/*-----------------------------------------------------------------------------------*/
		/* Embeded Video Options */
		/*-----------------------------------------------------------------------------------*/		

		$this->addGroupOpen(array(
			"id" => MAX_SHORTNAME.'_photo_group_embeded',
			"dependency" => MAX_SHORTNAME.'_photo_item_type_value::{contains}embed',
			"display" => false
		));
							
			/** Embedded Code **/
			$this->addTextarea(array(
				"id" => MAX_SHORTNAME."_video_embeded_value",
				"label" => __('Embed-Code', MAX_SHORTNAME),
				"display" => true,
				"rows" => '8',
				"size" => "480",
				"standard" => "",
				"desc" => __('If you want to embed a video on the photo details page, paste the embed code here. The recommend width is ' . MAX_CONTENT_WIDTH . 'px with any height to fit the content dimensions. <strong>Add "?wmode=transparent" to the URL in your embeded code to avoid content overlapping.</strong>', MAX_SHORTNAME)
			));
			
		$this->addGroupClose(array(
			"id" => MAX_SHORTNAME.'_photo_group_embeded_close'
		));
		
		/*-----------------------------------------------------------------------------------*/
		/*	Self hosted video options
		/*-----------------------------------------------------------------------------------*/

		$this->addGroupOpen(array(
			"id" => MAX_SHORTNAME.'_photo_group_selfhosted',
			"dependency" => MAX_SHORTNAME.'_photo_item_type_value::selfhosted',
			"display" => false
		));
					
			// Video H.264 Url
			$this->addInput(array(
				"id" => MAX_SHORTNAME."_video_url_m4v_value",
				"label" => __('H.264 File URL (required)', MAX_SHORTNAME),
				"size" => 640,
				"display" => true,
				"desc" => __('The URL of your self hosted H.264 video file ( .mp4, .m4v, .f4v ). This file is required to make your video run in all browsers!', MAX_SHORTNAME),
				"standard" => "",
			));
												
			// Video Url OGV
			$this->addInput(array(
				"id" => MAX_SHORTNAME."_video_url_ogv_value",
				"label" => __('OGV File URL (required)', MAX_SHORTNAME),
				"size" => 640,
				"display" => true,
				"desc" => __('The URL of your self hosted OGG Theora video file ( .ogg, .ogv ). This file is required to make your video run in all browsers!', MAX_SHORTNAME),
				"standard" => "",
			));		
										
			// Photo link taregt
			$this->addDropdown(array(
				"id" => MAX_SHORTNAME.'_video_poster_value',
				"label" => __('Video preview image', MAX_SHORTNAME),
				"options" => array( 'featured' => "Post featured image", 'url' => "Image URL"),
				"standard" => 'featured',
				"desc" => __('Choose image you want to use for your video preview. This picture is displayed when the video was not played yet.', MAX_SHORTNAME)
			));
				
			// Video Preview url
			$this->addInput(array(
				"id" => MAX_SHORTNAME."_video_url_poster_value",
				"label" => __('Video preview image URL', MAX_SHORTNAME),
				"size" => 640,
				"display" => false,
				"dependency" => MAX_SHORTNAME.'_video_poster_value::url',
				"desc" => __('The URL of your video preview image file.', MAX_SHORTNAME)
			));									
							
			// Video Height
			$this->addSlider(array(
				"id" => MAX_SHORTNAME."_video_height_value",
				"label" => __('Video Height', MAX_SHORTNAME),
				"max" => 1000,
				"min" => 1,
				"step" => 1,				
				"standard" => "529",
				"desc" => __('The height the size of your videos depends on a wide of 940px. This height is important to make the video look good in all browsers and on all devices.', MAX_SHORTNAME)			
			));

			// Autoplay Video on page ready
			$this->addDropdown(array(
				"id" => MAX_SHORTNAME.'_video_fill_value',
				"label" => __('Stretching', MAX_SHORTNAME),
				"options" => array('none' => __('None', MAX_SHORTNAME),
							   'fill' => __('Fill', MAX_SHORTNAME),
							   'excactfit' => __('Exactfit', MAX_SHORTNAME),
							   'uniform' => __('Uniform', MAX_SHORTNAME)
							   ),
				"standard" => 'fill',
				"desc" => __('Stretching defines how to resize the poster image and video to fit the display. <a href="#" onclick="jQuery(\'#strechInfo\').toggle(); return false;">Show detailed information</a>', MAX_SHORTNAME ).'
											<ul id="strechInfo" style="display: none; font-size: 11px; padding: 10px; background: #eee; margin: 10px 0 0">
											<li><strong>None</strong>: keep the original dimensions.</li>
											<li><strong>Exactfit</strong>: disproportionally stretch the video/image to exactly fit the display.</li>
											<li><strong>Uniform</strong>: stretch the image/video while maintaining its aspect ratio. There\'ll be black borders.</li>
											<li><strong>Fill</strong>: stretch the image/video while maintaining its aspect ratio, completely filling the display.</li>		
											</ul>'
			));
			
			// Autoplay Video on page ready
			$this->addDropdown(array(
				"id" => MAX_SHORTNAME.'_video_autoplay_value',
				'label' => __('Autoplay video', MAX_SHORTNAME),
				"options" => array('true' => __('Yes', MAX_SHORTNAME),
							   'false' => __('No', MAX_SHORTNAME)
							   ),
				"standard" => 'false',
				"desc" => __('If set to "Yes", your video will automatically start on page load.', MAX_SHORTNAME )
			));
			
		$this->addGroupClose(array(
			"id" => MAX_SHORTNAME.'_photo_group_selfhosted_close',
		));		

		// Featured image cropping direction
		$this->addDropdown(array(
			'id' => MAX_SHORTNAME.'_photo_cropping_direction_value',
			'label' => __('Preview cropping direction', MAX_SHORTNAME),
			"options" => $cropping_array,	
			"standard" => "c",
			"desc" => __('Choose the position from where your thumbnail of an image will be cropped if cropping is activated in your Invictus Theme Settings.', MAX_SHORTNAME )
		));
		
		// Show Fullsize Background Image
		$this->addDropdown(array(
			'id' => 'max_show_page_fullsize',
			'label' => __('Show fullsize background image', MAX_SHORTNAME),
			"options" => array('true' => 'Yes',	'false' => 'No'),
			"standard" => "true",
			"desc" => __('Choose, if you want to show a Fullsize Background Image for this gallery page.', MAX_SHORTNAME )
		));

		
		/** Fullsize Background Group **/
		$this->addGroupOpen(array(
			"id" => MAX_SHORTNAME.'_photo_fullsizeshow_group',
			"dependency" => 'max_show_page_fullsize::true',
			"display" => false
		));		
		
			//** Fullsize Background Slideshow
			$this->addDropdown(array(
				'id' => 'max_show_page_fullsize_type',
				'label' => __('Type of fullsize background', MAX_SHORTNAME),
				"options" => array("single"=>"Single Image", "slideshow"=>"Slideshow from Gallery"),
				"standard" => "single",
				"desc" => __('Do you want to show a single image or a slideshow from the gallery the photo is attached to for your background on this page.', MAX_SHORTNAME )
			));
			
			/** Fullsize Background Slideshow **/
			$this->addGroupOpen(array(
				"id" => MAX_SHORTNAME.'_photo_fullsizeslideshow_group',
				"dependency" => 'max_show_page_fullsize_type::slideshow',
				"display" => false
			));			
			
				// Choose the galleries to display			
				$this->addMultiGalleryCheckbox(array(
					'id' => 'max_show_page_fullsize_gallery',
					'label' => __('Available Galleries', MAX_SHORTNAME),
					"standard" => "9999",
					"options" => 'gallery',
					"desc" => __('Choose the Galleries, the single background image or the slideshow images are drawn from.', MAX_SHORTNAME )
				));		
				
				// slideshow speed
				$this->addSlider(array( 
					'id' => 'max_show_page_fullsize_interval',
					"standard" => 3500,
					"max" => 20000,
					"min" => 500,
					"step" => 50,
					"label" => __('Slideshow Interval', MAX_SHORTNAME),
					"desc" => __('Enter the slideshow interval for the fullsize background in milliseconds (ms).', MAX_SHORTNAME)
				));	
				
			$this->addGroupClose(array(	
				"id" => MAX_SHORTNAME.'_photo_fullsizeslideshow_group_close'
			));					
		
								
			//** Fullsize Background URL for Photo posts
			$this->addInput(array(	
				"id" => MAX_SHORTNAME."_show_page_fullsize_url",
				"label" => __('URL for Fullsize Background Image', MAX_SHORTNAME),
				"size" => 640,
				"display" => false,
				"dependency" => MAX_SHORTNAME.'_show_random_fullsize_value::false',
				"standard" => "",
				"desc" => __('Leave blank to show the current featured image as background', MAX_SHORTNAME),
				"dependency" => 'max_show_page_fullsize_type::single',
				"display" => false				
			));
					
			
		$this->addGroupClose(array(	
			"id" => MAX_SHORTNAME.'_photo_fullsizeshow_group_close'
		));
		
			
		
	}
}