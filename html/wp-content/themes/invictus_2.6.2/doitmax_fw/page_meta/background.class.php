<?php
/* #################################################################################### */
/*
/* Class for Page Background Option Set
 *
 * @author		Dennis Osterkamp aka "doitmax"
 * @copyright	Copyright (c) Dennis Osterkamp
 * @link		http://www.do-media.de
 * @since		Version 1.0
 * @package 	Invictus
 * 
 * @filedesc 	Option set to create the page background meta box options
 *
/* #################################################################################### */

class UIElement_PageBackground extends UIElement {

	public function __construct($type) {
        parent::__construct($type);
	}

	public function getMetaBox() {

		global $wp_gal_cats;

		$this->createMetabox(array(
			'id' => MAX_SHORTNAME.'_page_background_meta_box',
			'title' => __('Background Settings', MAX_SHORTNAME),
			'priority' => "default"
		));

		// Show Background Image?
		$this->addDropdown(array(
			'id' => 'max_show_page_fullsize',
			'label' => __('Show fullsize background?', MAX_SHORTNAME),
			"options" => array("true"=>"Yes", "false"=>"No"),
			"standard" => "true",
			"desc" => __('Choose, if you want to show a fullsize background image for this page. <strong>Settings are disabled, when "Fullsize Background" gallery type was selected.</strong>', MAX_SHORTNAME )
		));

		$this->addGroupOpen(array(
			'id' => MAX_SHORTNAME.'_page_group_background_gallery',
			"display" => false,
			"dependency" => 'max_show_page_fullsize::true'
		));
		
			// Show Background Image?
			$this->addDropdown(array(
				'id' => 'max_show_page_fullsize_type',
				'label' => __('Type of fullsize background', MAX_SHORTNAME),
				"options" => array("single"=>"Single Image", "slideshow"=>"Slideshow"),
				"standard" => "single",
				"desc" => __('Do you want to show a single image or a slideshow from a certain gallery for your background on this page.', MAX_SHORTNAME )
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
				"dependency" => 'max_show_page_fullsize_type::slideshow',
				"desc" => __('Enter the slideshow interval for the fullsize background in milliseconds (ms).', MAX_SHORTNAME)
			));			

			// Fullsize background file URL
			$this->addInput(array( 
				"id" => "max_show_page_fullsize_url",
				"standard" => "",
				"label" => __('URL for Fullsize Background Image', MAX_SHORTNAME),
				"size" => 640,
				"display" => false,
				"dependency" => 'max_show_page_fullsize_type::single',
				"desc" => __('The URL of background image file. If this is blank and no gallery is selected above, a random image from homepage fullsize featured galleries is shown as background image.', MAX_SHORTNAME)
			));
		
		$this->addGroupClose(array(
			'id' => MAX_SHORTNAME.'_page_group_background_gallery',
		));		
				
	}
}
?>
