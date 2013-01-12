<?php
/**
 * Template Name: Portfolio Fullsize Gridview
 *
 * @package WordPress
 * @subpackage Invictus
 * @since Invictus 1.0
 */

get_header(); 

wp_reset_query();

// set the image dimensions for this portfolio template
$imgDimensions = array( 'width' => get_option_max( 'image_fullsize_grid_width' ) );

// set the height if its larger than 0
if( get_option_max( 'image_fullsize_grid_height') && get_option_max( 'image_fullsize_grid_height') > 0 ){
	$imgDimensions['height'] = get_option_max( 'image_fullsize_grid_height' );
}

$itemCaption = true;

?>
<?php 
// get the password protected login template part
if ( post_password_required() ) {
	get_template_part( 'includes/page', 'password.inc' );
} else {
?>
		<style type="text/css" scoped>
			/** Masonry Portfolio **/		
			.portfolio-fullsize-grid .portfolio-list li { 
				margin: 0 5px 5px 0;
				width: <?php get_option_max( 'image_fullsize_grid_width', true ) ?>px;
				visibility: hidden;
			}			
			<?php if($imgDimensions['width'] < 320){ ?>
				@media (max-width: 480px) {
					.portfolio-fullsize-grid #portfolioList li.item {
						width: 48.5%;	
					}
				}
			<?php } ?>			
		</style>		

		<div id="primary" class="portfolio-fullsize-grid">
			<div id="content" role="main">
				
				<header class="entry-header">
						
				<h1 class="page-title"><?php the_title(); ?></h1>
				<?php 
				// check if there is a excerpt
				if( max_get_the_excerpt() ){ 
				?>
				<h2 class="page-description"><?php max_get_the_excerpt(true) ?></h2>
				<?php } ?>
				
				</header><!-- .entry-header -->

				<?php /* -- added 2.0 -- */ ?>
				<?php the_content() ?>
				<?php /* -- end -- */ ?>
			
				<?php 
					// including the loop template gallery-loop.php
					get_template_part( 'includes/gallery', 'loop.inc' );
				?>				
			</div><!-- #content -->
		</div><!-- #container -->
		
<?php } ?>

	<script type="text/javascript"> 
		
		//<![CDATA[			
		jQuery(window).load(function(){			   
			
			var $iso_container = jQuery('#portfolioList');		
			
			// initialize Isotope		
			$iso_container.isotope({		
				resizable: false, // disable normal resizing
				gutterWidth: 8,
				itemSelector : '.portfolio-list li.item'				
			});
			
			// update columnWidth on window resize
			jQuery(window).smartresize(function(){
				$iso_container.isotope({
					gutterWidth: 8,
					itemSelector : '.portfolio-list li.item'
				});
			});
			
			$iso_container.css({ background: 'none' }).find('li.item').css({ visibility: 'visible' });
								
		});		
		//]]>
	</script>

<?php get_footer(); ?>