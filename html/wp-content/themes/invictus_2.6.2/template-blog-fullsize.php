<?php
/**
 * Template Name: Blog Fullsize Template
 *
 * @package WordPress
 * @subpackage Invictus
 * @since Invictus 1.0
 */

global $page_tpl, $paged;

$showSuperbgimage = true ;

get_header(); 

wp_reset_query();

// get the categories and query the posts
$blog_categories = get_post_meta(get_the_ID(), MAX_SHORTNAME.'_page_blog_category', true);

if( is_array($blog_categories) ) :
	$blog_posts = query_posts( array('posts_per_page' => get_option('posts_per_page'), 'paged' => $paged, 'cat' => implode(",", $blog_categories) ) );
endif;

$custom_fields = get_post_custom_values('_wp_page_template', $post->ID);
$page_tpl = $custom_fields[0];

?>

<div id="single-page" class="clearfix blog">

		<div id="primary" class="template-fullsize">

			<header <?php post_class('entry-header'); ?> id="post-<?php the_ID(); ?>" >
			
				<h1 class="page-title"><?php the_title(); ?> </h1>
				<?php 
				// check if there is a excerpt
				if( max_get_the_excerpt() ){ 
				?>
				<h2 class="page-description"><?php max_get_the_excerpt(true) ?></h2>
				<?php } ?>
					
			</header>

			<div id="content" role="main" class="hfeed">
			
			<?php /* -- added 2.0 -- */ ?>
			<?php the_content() ?>
			<?php /* -- end -- */ ?>
			
			<?php
			if ( !post_password_required() ) :
			
				if( is_array($blog_categories) ) :
			
					// including the loop template blog-loop.php
					get_template_part( 'includes/blog', 'loop.inc' );
					
				else : ?>
				
					<article id="post-not-found" class="<?php post_class(); ?> hentry clearfix">
						<header>
							<h2 class="entry-title" style="padding-left: 0"><?php _e("Oops, Blog categories not found!", MAX_SHORTNAME); ?></h2>
						</header>
						<section>
							<p><?php _e("Uh Oh. Something is missing. Try double checking things. Please make sure, you have some categories blog selected.", MAX_SHORTNAME); ?></p>
						</section>
						<footer>
							<p><?php _e("This is the error message in the template-blog.php template and can be fixed in the WordPress page options for this template.", MAX_SHORTNAME); ?></p>
						</footer>
					</article>
				
				<?php					
					
				endif;
			endif;
			?>							
							
			</div><!-- #content -->	
			
		</div><!-- #primary -->

</div>
<?php get_footer(); ?>
