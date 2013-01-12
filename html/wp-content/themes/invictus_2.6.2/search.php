<?php
/**
 * Page to show search results
 * @package WordPress
 * @subpackage Invictus
 */

global $paged, $showSuperbgimage;

$showSuperbgimage = true;

get_header(); ?>

<div id="single-page" class="clearfix blog left-sidebar">

		<div id="primary">
		
			<header <?php post_class('entry-header'); ?> id="post-<?php the_ID(); ?>" >
			
				<h1 class="page-title"><?php printf( __( 'Search Results for &quot;%s&quot;', 'invictus' ), '' . get_search_query() . '' ); ?></h1>
				<h2 class="page-description">&nbsp;</h2>
				
			</header>		
		
			<div id="content" role="main">
			<?php 
				// including the content template search-loop.inc.php
				get_template_part( 'includes/search', 'loop.inc' );
			?>
			</div><!-- #content -->
			
		</div><!-- #primary -->

		<div id="sidebar">
			 <?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('sidebar-search-result') ) ?>		
		</div>

</div>
<?php get_footer(); ?>