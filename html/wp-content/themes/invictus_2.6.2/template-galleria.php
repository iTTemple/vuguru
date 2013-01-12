<?php
/**
 * Template Name: Portfolio Galleria Template
 *
 * @package WordPress
 * @subpackage Photonova
 * @since Photonova 1.0
 */

global $body_class_array, $meta;

wp_enqueue_script('jquery-galleria', get_template_directory_uri() .'/js/galleria/galleria-1.2.5.min.js', 'jquery');
wp_enqueue_style('galleria-css', get_template_directory_uri().'/js/galleria/galleria.classic.css', false, false);	

/*-----------------------------------------------------------------------------------*/
/*  Main Settings and variables
/*-----------------------------------------------------------------------------------*/
$meta = max_get_cutom_meta_array();

$meta[MAX_SHORTNAME."_page_gallery_fullwidth"] = false;

get_header();

$galleryPosts = max_query_term_posts( 9999 , $meta['max_select_gallery'], 'gallery', $meta['max_gallery_order'], GALLERY_TAXONOMY, $meta['max_gallery_sort'] );	

?>

<?php 
// get the password protected login template part
if ( post_password_required() ) {
	get_template_part( 'includes/page', 'password.inc' );
} else {
?>

<div id="single-page" class="clearfix left-sidebar">

		<div id="primary" class="hfeed">
			
			<div id="content" role="main">
				
				<div <?php if(!is_front_page() && !is_Home()) the_post(); post_class('entry-header'); ?> id="post-<?php the_ID(); ?>">				
								

					<header class="clearfix">						
						<h1 class="page-title"><?php the_title(); ?></h1>
						<?php 
						// check if there is a excerpt
						if( max_get_the_excerpt() ){ 
						?>
						<h2 class="page-description"><?php max_get_the_excerpt(true) ?></h2>
						<?php } ?>				
					</header><!-- .entry-header -->
							
								
					<?php 
					if (have_posts()) : 
						
						// open galleria container
						$gal_output = '<div id="galleria" class="clearfix';
											
						$gal_output .= '">';
						
						while (have_posts()) {
							// get the post									
							the_post();
							
							// get image url
							$_imgUrl_temp = max_get_post_image_url( get_the_ID(), 'large' );							
							$_imgUrl_big = $_imgUrl_temp[0];								
																				
							// get the url for showing the poster url
							$_poster_url = max_get_custom_image_url(get_post_thumbnail_ID(), get_the_ID(), 100, 100, "c" );
							
							// get post custom meta
							$_post_meta = max_get_cutom_meta_array(get_the_ID());						
							// attach image to output
							$gal_output .= '<a href="'. $_imgUrl_big .'">';
							$gal_output .= '<img title="' . get_the_title() . '"';

							if(!empty($_post_meta[MAX_SHORTNAME.'_caption_text'])) :
								$gal_output .= ' alt="' . $_post_meta[MAX_SHORTNAME.'_caption_text'] . '"';
							else: 
								$gal_output .= ' alt="' . get_the_title() . '"';
							endif;
							
							$gal_output .= ' src="' . $_poster_url . '">
										</a>';
						}
						// close galleria container
						$gal_output .= '</div>';				
						wp_reset_query();
					endif;

					echo $gal_output; 
					?>					

					<?php if($post->post_content != "") : ?>
					<p>&nbsp;</p><br  />
					<div class="clearfix">
					<?php the_content() ?>
					</div>
					<?php endif; ?>
					
					<?php comments_template( '', true ); ?>
										
				</div><!-- #post -->		

			</div><!-- #content -->
		</div><!-- #container -->

		<div id="sidebar">
			 <?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('sidebar-gallery-galleria') ) ?>
		</div>

</div>

<script type="text/javascript">
//<![CDATA[
jQuery(function(){

	// Initialize Galleria
	Galleria.loadTheme('http://aino.github.com/galleria/demos/categories/themes/classic/galleria.classic.min.js');

	Galleria.run('#galleria', {
		responsive: true,
		<?php if( $meta[MAX_SHORTNAME.'_page_galleria_autoplay'] == 'true' ){ ?>
		autoplay: <?php echo $meta[MAX_SHORTNAME.'_page_galleria_autoplay']; ?>,
		<?php } ?>
		height: 0.75,
		lightbox: true,
		imageCrop: '<?php echo $meta[MAX_SHORTNAME.'_page_galleria_crop'] ?>'
	});
	
});		
//]]>	
</script>
<?php } ?>
<?php get_footer(); ?>