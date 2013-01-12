<?php
/**
 * @package WordPress
 * @subpackage Invictus
 */
 
global $wp_query, $page, $paged, $showSuperbgimage, $main_homepage, $isFullsizeFlickr, $isPost, $isBlog;
 
?><!DOCTYPE html>
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
	<title><?php
	
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
	// Check for always fit on homepage or fullsize gallery template
	$fit_images = "";
		
	if ( get_option_max('fullsize_fit_always')  == "true" || get_post_meta($page_id, 'max_show_page_fullsize_fit', TRUE) == "true" ){ 
		$fit_images = "fit-images";
	}
	
	if( get_option_max('fullsize_fit_always')  == "false" && get_post_meta($page_id, 'max_show_page_fullsize_fit', TRUE) == "true" ){ 
		$fit_images = "fit-images";
	}
	
	if( get_option_max('fullsize_fit_always')  == "true" && get_post_meta($page_id, 'max_show_page_fullsize_fit', TRUE) == "false" ){ 
		$fit_images = "";
	}
		
	// Fullwidth blog option chekc
	$blog_class = "";
	if( $isBlog && get_option_max('general_show_fullblog_details') == 'true' ){
		$blog_class = "blog-fullwidth";
	}
	
?>

<body <?php body_class(array($fit_images, $blog_class, get_option_max('color_main',false).'-theme')); ?>>

<div id="anchorTop"><a href="#"><?php _e('Back to top', MAX_SHORTNAME); ?></a></div>

<?php 
// check if we must show superbgimage expander
if ( $main_homepage || $isFullsizeGallery || $isFullsizeFlickr === true || get_post_meta($page_id, 'max_show_page_fullsize', true) == 'true' || get_post_meta($page_id, MAX_SHORTNAME.'_show_post_fullsize_value', true) == 'true' ){ 
?>
	<a href="#" id="expander" class="slide-up">
		<span>Hide Content</span>
	</a>
<?php } ?>

<?php // Check for show fullsize background overlay ?>
<?php if( $main_homepage === true && get_option_max( 'homepage_show_fullsize_overlay' ) == 'true' && !$isFullsizeFlickr ){ ?>
<div id="scanlines" class="overlay-<?php get_option_max( 'fullsize_overlay_pattern' , true ) ?>"></div>		
<?php } ?>
<?php if ( ( !$main_homepage && get_option_max( 'general_show_fullsize_overlay' ) == 'true' && !$isFullsizeFlickr ) || ( get_option_max('flickr_scanlines') == 'true' && $isFullsizeFlickr === true ) ) { ?>
<div id="scanlines" class="overlay-<?php get_option_max( 'fullsize_overlay_pattern' , true ) ?>"></div>	
<?php } ?>

<div id="page">

	<?php 
	// get the custom logo if needed
	if( get_option_max('custom_logo_value') == "" ) {
		$logo_url = get_template_directory_uri().'/css/'. get_option_max('color_main') .'/bg-logo.png';		
	}else{
		$logo_url = get_option_max('custom_logo_value');
	}
	?>
	
	<header id="branding" role="banner" class="clearfix<?php if(  get_option_max('custom_logo_blank') == 'true' ) echo ' blank-logo"'; ?>">

		<hgroup class="navbar">
			<h1 id="site-title" class="clearfix">
				<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<img src="<?php echo $logo_url ?>" alt="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>" />
				</a>
			</h1>			
		</hgroup>
	
		<?php if( $main_homepage === true ){ ?>
			<?php 
			// Check if Welcome teaser should be shown
			if ( get_option_max('homepage_show_welcome_teaser') == 'true' ) { 
			
			$welcome = stripslashes( get_option_max('homepage_welcome_teaser') );
			
			?>			
			<div id="welcomeTeaser"><span class="inner"><?php echo $welcome ?></span></div>
		<?php 	} ?>				
		<?php } ?>	
		
		<nav id="navigation" role="navigation" class="clearfix">
			
			<div id="navHolder">
				<h1 class="section-heading"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php _e( 'Main menu', 'invictus' ); ?></a></h1>
				<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'invictus' ); ?>"><?php _e( 'Skip to content', 'invictus' ); ?></a></div>
				<?php if ( has_nav_menu( 'primary' ) ) { /* if menu location 'primary-menu' exists then use custom menu */ ?>                        
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'sf-menu', 'container' => '', 'walker' => new menu_walker() ) ); ?>
				<?php } else { /* else use wp_list_pages */?>
				<ul class="sf-menu">
					<?php wp_list_pages(); ?>
				</ul>
				<?php } ?>
			</div>						
							
		</nav><!-- #navigation -->
			
	</header><!-- #branding -->

	<div id="main" class="clearfix zIndex<?php $_fade_in = get_option_max('general_fadein_content') > 0 ? ' fadein-content' : ""; echo $_fade_in; ?>">