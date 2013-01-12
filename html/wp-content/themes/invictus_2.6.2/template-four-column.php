<?php
/**
 * Template Name: Portfolio 4 Columns
 *
 * @package WordPress
 * @subpackage Invictus
 * @since Invictus 1.0
 */

get_header(); 

wp_reset_query();

$meta = max_get_cutom_meta_array();

// set the image dimensions for this portfolio template
$imgDimensions  = array( 'width' => 380, 'height' => 285 );
$imgDimensions1x = array( 'width' => 300, 'height' => 225 );

$itemCaption = true;
$hideExcerpt = true;

?>
<div id="single-page" class="clearfix left-sidebar">

		<div id="primary" class="portfolio-four-columns" >
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
				
                <?php comments_template( '', true ); ?>
									
			</div><!-- #content -->
		</div><!-- #container -->

		<div id="sidebar">
			 <?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('sidebar-gallery-four') ) ?>
		</div>

</div>

<?php get_footer(); ?>
