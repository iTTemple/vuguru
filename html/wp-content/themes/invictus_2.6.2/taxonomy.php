<?php
/**
 * The template for displaying Photo pages.
 *
 * @package WordPress
 * @subpackage Invictus
 * @since Invictus 1.0
 */

$showSuperbgimage = true;

get_header(); 

// Get the current term by the slug
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

$args = array(
	'taxonomy' => GALLERY_TAXONOMY,
	'term' => $term->slug,
	'post_type' => 'gallery',
	'posts_per_page' => -1,
	'ignore_sticky_posts'=> 1,
	'orderby'=> 'post_date',
	'order'=> 'ASC',
	'paged' => $paged
);
$myQuery = null;
$myQuery = new WP_Query($args);

// set the image dimensions for this portfolio template
$imgDimensions = array( 'width' => 320, 'height' => 226 );

$itemCaption = true;

?>

<div id="single-page" class="clearfix left-sidebar">

		<div id="primary" class="portfolio-three-columns" >
			<div id="content" role="main">
				
				<header class="entry-header">
						
					<h1 class="page-title"><?php echo $term->name; ?></h1>
					<?php	if ( ! empty(  $term->description ) ) {
							echo '<h2 class="page-description">' . $term->description . '</h2>'; 
						}
					?>
				
				</header><!-- .entry-header -->
				
				<?php 
					// including the loop template gallery-loop.php
					get_template_part( 'includes/gallery', 'loop.inc' );
				?>

			</div><!-- #content -->
		</div><!-- #container -->
		
		<div id="sidebar">
			 <?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('sidebar-gallery-taxonomy') ) ?>		
		</div>
		
</div>
<?php get_footer(); ?>
