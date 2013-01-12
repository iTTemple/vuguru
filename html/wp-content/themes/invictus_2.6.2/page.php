<?php
/**
 * @package WordPress
 * @subpackage Invictus
 */

get_header(); ?>

	<div id="single-page" class="clearfix left-sidebar">
	
		<div id="primary">
			<div id="content" role="main">
	
				<?php the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				
			</div><!-- #content -->
		</div><!-- #primary -->
		
		<div id="sidebar"></div>
		
	</div>
	
<?php get_footer(); ?>