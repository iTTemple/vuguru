<?php
/**
 * The template for displaying Tag Archive pages.
 *
 * @package WordPress
 * @subpackage invictus
 * @since invictus 1.0
 */

global $showSuperbgimage;
$showSuperbgimage = true;

get_header(); 

// set the image dimensions for this portfolio template
$imgDimensions = array( 'width' => 660, 'height' => 480 );

$substrExcerpt = 70;

$itemCaption = true;

$post_type = get_post_type();

// Only get gallery posts
if($post_type == "gallery"){
	$tag_posts = query_posts( array('tag'=> get_query_var('tag'), 'post_type' => 'gallery', 'paged' => $paged) );
}
// Only get blog posts
if($post_type == "post"){
	$tag_posts = query_posts( array('tag'=> get_query_var('tag'), 'post_type' => 'post', 'paged' => $paged) );
}

?>

<div id="single-page" class="clearfix left-sidebar">

		<div id="primary" class="portfolio-three-columns" >
			<div id="content" role="main">
				
				<header class="entry-header">
										
					<h1 class="page-title"><?php single_tag_title() ?></h1>
				
				</header><!-- .entry-header -->
				
				<?php 
					if($post_type == "gallery"){
						// including the loop template tag-loop.inc.php
						get_template_part( 'includes/tag', 'loop.inc');
					}
					
					if($post_type == "post"){
						// including the loop template blog-loop.inc.php
						get_template_part( 'includes/blog', 'loop.inc' );
					}
				?>
			
				
				</div><!-- #content -->
				
		</div><!-- #container -->

		<div id="sidebar">
			 <?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('sidebar-tag') ) ?>
		</div>

</div>

<?php get_footer(); ?>
