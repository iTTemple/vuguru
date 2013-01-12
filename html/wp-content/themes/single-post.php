<?php
/**
 * Template for displaying the Blog Detail Page 
 *
 * @package WordPress
 * @subpackage Invictus
 * @since Invictus 1.0
 */

global $meta, $isPost, $post_meta, $isBlog;

$showSuperbgimage = true;
$isPost = true;
$isBlog = true;

//Get the post meta informations and store them in an array
$meta = max_get_cutom_meta_array(get_the_ID());

if(isset($meta[MAX_SHORTNAME.'_photo_slider_select'])) :
	
	/*-----------------------------------------------------------------------------------*/
	/*  Get Slides Slider JS if needed
	/*-----------------------------------------------------------------------------------*/
	if( $meta[MAX_SHORTNAME.'_photo_slider_select'] == 'slider-slides'){
		wp_enqueue_script('jquery-slides', get_template_directory_uri() .'/slider/slides/slides.min.jquery.js', 'jquery');
		wp_enqueue_style('slides-css', get_template_directory_uri().'/slider/slides/slider-slides.css', false, false);	
	}
	
	/*-----------------------------------------------------------------------------------*/
	/*  Get Nivo Slider JS if needed
	/*-----------------------------------------------------------------------------------*/
	
	if($meta[MAX_SHORTNAME.'_photo_slider_select'] == 'slider-nivo'){
		wp_enqueue_script('jquery-nivo', get_template_directory_uri() .'/slider/nivo/jquery.nivo.slider.js', 'jquery');
		wp_enqueue_style('nivo-css', get_template_directory_uri().'/slider/nivo/nivo-slider.css', false, false);	
	}
	
	/*-----------------------------------------------------------------------------------*/
	/*  Get Kwicks Slider JS if needed
	/*-----------------------------------------------------------------------------------*/
	if($meta[MAX_SHORTNAME.'_photo_slider_select'] == 'slider-kwicks'){
		wp_enqueue_script('jquery-kwicks', get_template_directory_uri() .'/slider/kwicks/jquery.kwicks.min.js', 'jquery');
		wp_enqueue_style('kwicks-css', get_template_directory_uri().'/slider/kwicks/kwicks-slider.css', false, false);	
	}

endif;

/*-----------------------------------------------------------------------------------*/
/*  Get JPlayer JS if needed
/*-----------------------------------------------------------------------------------*/
if( $meta[MAX_SHORTNAME.'_photo_item_type_value'] == 'selfhosted' || $meta[MAX_SHORTNAME.'_photo_item_type_value'] == 'embedded' )  {
	wp_enqueue_script('swobject', get_template_directory_uri() .'/js/swfobject.js', 'jquery');
	wp_enqueue_script('jwplayer', get_template_directory_uri() .'/js/jwplayer/jwplayer.js', 'jquery');
}


//get_header(); 
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns:fb="http://ogp.me/ns/fb#">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<!--  Mobile Viewport Fix
j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag
device-width : Occupy full width of the screen in its current orientation
initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width
-->
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<?php
$page_id = $wp_query->get_queried_object_id();

// Check for WordPress SEO Plugin by Yoast
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
if( !is_plugin_active('wordpress-seo/wp-seo.php') ){
	?>
<title>
<?php
	
		// set the page variable on frontpage diffrent than on other pages
		if(is_front_page()){
			$paged = (get_query_var('page')) ? get_query_var('page') : 1;
		}else{
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}
	
		/*
		 * Print the <title> tag based on what is being viewed.
		 */
		 
		wp_title( '|', true, 'right' );
	
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo sprintf( __( 'Page %s', 'invictus' ), max( $paged, $page ) ) . ' | ';
	
		// Add the blog name.
		bloginfo( 'name' );
	
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";
	
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s', 'invictus' ), max( $paged, $page ) );
	
	?>
</title>
<?php 
	// check if facebook open graph meta tags should be shown
	if( !is_home() && $isPost === true ){	?>
<!--open graph meta tags-->
<meta property="og:title" content="<?php echo get_the_title() ?>" />
<meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>" />
<meta property="og:url" content="<?php echo get_permalink() ?>" />
<meta property="og:locale" content="<?php get_option_max('post_social_language', true) ?>" />
<?php
	if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { 
		$_og_imgURL = max_get_image_path($page_id, 'og-image');
	?>
<meta property="og:image" content="<?php echo $_og_imgURL; ?>" />
<meta property="og:image:type" content="image/jpeg" />
<?php } ?>
<meta property="og:type" content="article" />
<?php if( max_get_the_excerpt() ){ ?>
<meta property="og:description" content='<?php strip_tags(max_get_the_excerpt(true)) ?>' />
<?php }else{ ?>
<meta property="og:description" content='<?php echo get_bloginfo( 'description', 'display' ) ?>' />
<?php 
		}
	}	
}?>
<?php if(get_option_max('social_fb_admins') != ""){ ?>
<meta property="fb:admins" content="<?php get_option_max('social_fb_admins',true) ?>" />
<?php } ?>
<?php if(get_option_max('social_fb_appid') != ""){ ?>
<meta property="fb:app_id" content="<?php get_option_max('social_fb_appid',true) ?>" />
<?php } ?>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<!-- Get the Main Style CSS -->
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<!-- Get the Theme Style CSS -->
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/<?php get_option_max('color_main',true) ?>.css" />
<!-- Google Font API include -->
<?php max_get_google_font_html(); ?>
<?php
// get infinite scroll if needed
if(get_post_meta(get_the_ID(), MAX_SHORTNAME.'_page_infinite_scroll', true) == 'true'){
	wp_enqueue_script('infinitescroll');
}
?>
<?php // Main Color styles ?>
<style type="text/css">

	<?php $rgba_1 = max_HexToRGB( get_option_max( 'color_main_typo' ) ) ?>
	
	a:link, a:visited { color: <?php get_option_max( 'color_main_link' , true) ?> }	
	
	nav#navigation li.menu-item a:hover, nav#navigation li.menu-item a:hover { color: <?php get_option_max( 'color_nav_link_hover' , true) ?> }
	nav#navigation .sfHover ul.sub-menu a:hover, nav#navigation .sfHover ul.sub-menu a:active  { color: <?php get_option_max( 'color_pulldown_link_hover' , true) ?> }	
	
	#site-title,
	nav#navigation ul a:hover,
	nav#navigation ul li.sfHover a,
	nav#navigation ul li.current-cat a,
	nav#navigation ul li.current_page_item a,
	nav#navigation ul li.current-menu-item a,
	nav#navigation ul li.current-page-ancestor a,
	nav#navigation ul li.current-menu-parent a,
	nav#navigation ul li.current-menu-ancestor a,
	#colophon,
	#thumbnails .pulldown-items a.activeslide { 
		border-color: <?php get_option_max( 'color_main_typo' , true) ?>;
	}
	
	#thumbnails .scroll-link,
	#fullsizeTimer,
	.blog .date-badge,
	.tag .date-badge,
	.pagination a:hover,
	.pagination span.current,
	#showtitle .imagetitle,
	#anchorTop,
	.portfolio-fullsize-scroller .scroll-bar .ui-slider-handle { 
		background-color: <?php get_option_max( 'color_main_typo' , true) ?>;
	}	
	
	#expander, #toggleThumbs { 
		background-color: <?php echo get_option_max( 'color_main_typo' ) ?>;
		background-color: rgba(<?php echo $rgba_1['r'] ?>,<?php echo $rgba_1['g'] ?>,<?php echo $rgba_1['b'] ?>, 0.75); 
	}
	nav#navigation ul ul { 
		background-color: <?php echo get_option_max( 'color_main_typo' ) ?>;
		background-color: rgba(<?php echo $rgba_1['r'] ?>,<?php echo $rgba_1['g'] ?>,<?php echo $rgba_1['b'] ?>, 0.9); 
	}

	nav#navigation ul ul li {
		border-color: rgba(255, 255, 255, 0.5); 		
	}

</style>
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/ie.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/ie_<?php get_option_max('color_main',true) ?>.css" />
<style type="text/css">
	#expander,
	#toggleThumbs,
	nav#navigation ul ul { background-color: <?php get_option_max( 'color_main_typo' , true) ?>; }
</style>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
// get infinite scroll if needed
if(get_post_meta(get_the_ID(), MAX_SHORTNAME.'_page_infinite_scroll', true) == 'true'){
	wp_enqueue_script('infinitescroll');
}
?>
<?php wp_head(); ?>
</head>
<?php
the_post();

// Check for full width blog detail page
if(get_option_max('general_show_fullblog_details') == 'true'){
	$post_sidebar = 'no-sidebar';
	$post_width = MAX_FULL_WIDTH;
}else{
	$post_sidebar = 'left-sidebar';
	$post_width = MAX_CONTENT_WIDTH;
}

?>
<?php
if(!empty($_REQUEST['request']) && $_REQUEST['request']=='ajax'){ ?>
<style type="text/css">
 .top_height iframe
{

	   margin-left: 50px;
}
</style>
<div id="single-page" class="clearfix blog <?php echo $post_sidebar ?> top_height" >
  <div id="primary">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <header class="entry-header" style="padding-top:0px;">
        <h1 class="entry-title" style=" display:block">
          <?php the_title(); ?>
        </h1>
        
	<p style="text-align:center">
		<?php if(!empty($_REQUEST['request'])) {
		 if( $meta[MAX_SHORTNAME.'_photo_item_type_value'] == 'selfhosted' || 
						$meta[MAX_SHORTNAME.'_photo_item_type_value'] == 'youtube_embed' || 
						$meta[MAX_SHORTNAME.'_photo_item_type_value'] == "vimeo_embed" )
					{
						get_template_part( 'includes/post', 'video.inc' ); }}?></p>
      </header>
      <!-- .entry-header -->
      <!-- #content -->
    </article>
  </div>
  <!-- #primary -->
</div>

<?php }?>



<div id="single-page" class="clearfix blog <?php echo $post_sidebar ?> ">
  <div id="primary">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <header class="entry-header"  style="display:none">
        <h1 class="entry-title">
          <?php the_title(); ?>
        </h1>

        <?php if ( !post_password_required() ) : ?>
        <div class="clearfix entry-meta entry-meta-head show_large" <?php if(!empty($_REQUEST['request'])){?> style="display:none"<?php }?>  >
          <!--<ul>
							<?php
							
								// get entry categories
								$post_categories = wp_get_post_categories( $post->ID );
          $cat_list = array();
          foreach ( $post_categories as $c ) {
          $cat = get_category( $c );
          $cat_list[] .= '<a href="'. get_category_link( $c ) .'">'. $cat->name .'</a>';
          }						
          
          $cat_list = implode(', ', $cat_list);						
          
          printf( __( '
          <li>By <span class="vcard author"><span class="fn">%1$s</span><span class="role">Author</span></span>&nbsp;/</li>
          ', MAX_SHORTNAME), get_the_author() );
          printf( __( '
          <li><span class="published">%1$s</span>&nbsp;/</li>
          ', MAX_SHORTNAME), get_the_time(get_option('date_format')) );
          printf( __( '
          <li>In <span>%1$s</span>&nbsp;/</li>
          ', MAX_SHORTNAME), substr($cat_list, 0) );
          printf( __( '
          <li class="last-update">Last Update <span class="updated">%1$s</span>&nbsp;/</li>
          ', MAX_SHORTNAME), get_the_time(get_option('date_format')) );
          if ('open' == $post->comment_status) :
          echo '
          <li class="cnt-comment"><a href="#comments-holder"><span class="icon"></span>';									
            comments_number( 'No Comments', '1 Comment', '% Comments' );
            echo '</a></li>
          ';
          endif;
          ?>
          </ul>
          -->
          <!-- Entry nav -->
          <ul class="nav-posts " >
            <?php if(get_previous_post()) {?>
            <li class="nav-previous tooltip" title="<?php _e('Previous post', MAX_SHORTNAME) ?>">
              <?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '', 'Previous post link', MAX_SHORTNAME ) . '</span> %title' ); ?>
            </li>
            <?php } ?>
            <?php if(get_next_post()) {?>
            <li class="nav-next tooltip" title="<?php _e('Next post', MAX_SHORTNAME) ?>">
              <?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '', 'Next post link', MAX_SHORTNAME ) . '</span>' ); ?>
            </li>
            <?php } ?>
          </ul>
        </div>
        <!-- .entry-meta -->
        <?php endif; ?>
      </header>
      <!-- .entry-header -->

      <div id="content" role="main" >
	  <?php if(!empty($_REQUEST['request'])){ ?>
	

<?php }?>
<?php
if(!empty($_REQUEST['request']) && $_REQUEST['request']=='ajax'){?>
<h2 class="entry-description hide_small">
          <?php the_content(); ?>
        </h2>
		<?php }?>
			  
		<div class="show_large" <?php if(!empty($_REQUEST['request'])){?> style="display:none"<?php }?>>
        <?php if ( !post_password_required() ) : ?>
        <?php 					
					/*-----------------------------------------------------------------------------------*/
					/*  Get the needed Slider Template if a slider is selected
					/*-----------------------------------------------------------------------------------*/
					if( isset($meta[MAX_SHORTNAME.'_photo_slider_select']) && $meta[MAX_SHORTNAME.'_photo_slider_select'] != "" && $meta[MAX_SHORTNAME.'_photo_slider_select'] != "none" && $meta[MAX_SHORTNAME.'_photo_item_type_value'] == "none" ){
						// strip of "slider-"
						$slider_tpl = split("-", $meta[MAX_SHORTNAME.'_photo_slider_select'] );	
						get_template_part( 'includes/slider', $slider_tpl[1].'.inc' );
					}
					/*-----------------------------------------------------------------------------------*/
					/*  Get the needed Image or Video
					/*-----------------------------------------------------------------------------------*/
					else if( $meta[MAX_SHORTNAME.'_photo_item_type_value'] == 'selfhosted' || 
						$meta[MAX_SHORTNAME.'_photo_item_type_value'] == 'youtube_embed' || 
						$meta[MAX_SHORTNAME.'_photo_item_type_value'] == "vimeo_embed" )
					{

						
					}else{										
					
						if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { 
						
							// Get the thumbnail
							$imgID = get_post_thumbnail_id();  
							$imgUrl = max_get_image_path($post->ID, 'full');
	
							// Check if images should be cropped
							$timb_height ='';
							$timb_img_height = '';
						
							if(get_option_max( 'image_blog_original_ratio' ) != 'true' ) {
								$timb_height = 250;
								$timb_img_height = 'height="250"';
							}
							
							// get the imgUrl for showing the post image
							$imgUrl = max_get_custom_image_url(get_post_thumbnail_ID(), get_the_ID(), $post_width, $timb_height, get_cropping_direction( $meta[MAX_SHORTNAME.'_photo_cropping_direction_value'] ) );												
						
						?>
        <div class="entry-image"> <a href="<?php echo $imgUrl; ?>" data-link="<?php echo get_permalink($post_id) ?>" class="scroll-link" style="display: block;" data-rel="prettyPhoto" title="<?php echo get_the_excerpt() ?>"> <img src="<?php echo $imgUrl; ?>" class="fade-image<?php if( get_option_max('image_show_fade') != "true") { echo(" no-hover"); } ?>" alt="<?php the_title() ?>" title="<?php the_title() ?>" /> </a> </div>
        <?php }
					} ?>
        <?php endif; ?>
        <div class="clearfix">
          <?php if ( !post_password_required() ) : ?>
          <?php 
							// including the loop template social-share.inc.php
							get_template_part( 'includes/social', 'share.inc' );
						?>
          <?php endif; ?>
          <div class="entry-content">
            <?php the_content(); ?>
          </div>
          <!-- .entry-content -->
        </div>
        <?php if ( !post_password_required() ) : ?>
        <?php
					// Check if author should be shown
					if ( get_option_max('general_show_author') == "true" ) 
						echo do_shortcode("[authorbox]")
				?>
        <?php 
					echo do_shortcode("[related_posts]") 
				?>
        <?php comments_template( '', true ); ?>
        <div class="pagination hidden">
          <p>
            <?php posts_nav_link(' '); ?>
          </p>
        </div>
        <?php endif; ?>
      </div>
	  </div>
      <!-- #content -->
    </article>
  </div>
  <!-- #primary -->
  <?php if ( !post_password_required() && get_option_max('general_show_fullblog_details') == 'false' ) : ?>
  <div id="sidebar">
    <?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('sidebar-blog-post') ) ?>
  </div>
  <?php endif; ?>
</div>
<?php //get_footer(); ?>
