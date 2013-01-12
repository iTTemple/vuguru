<?php
/**
 * The loop that displays blog posts.
 *
 * @package WordPress
 * @subpackage Invictus
 * @since Invictus 1.0
 */

global $paged, $more, $page_tpl, $post_meta;

$isPost = true;
$more = 0;

if (have_posts()) : while (have_posts()) : the_post();

//Get the post meta informations and store them in an array
$post_meta = max_get_cutom_meta_array($post->ID);

?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
					
					<div class="rel">
					
						<div class="date-badge"><?php echo get_the_date("d") ?><span><?php echo get_the_date("M") ?></span></div>
						
						<h2 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo htmlspecialchars(get_the_excerpt()) ?>"><?php the_title() ?></a></h2>
						
						<?php 						
						// show the videos and sliders in blog posts or not
						$show_compact = get_option_max('blog_show_compact');						
						if(	empty($show_compact) ):													
							$show_compact = 'false';							 
						endif;
																		
						/*-----------------------------------------------------------------------------------*/
						/*  Get the needed Slider Template if a slider is selected
						/*-----------------------------------------------------------------------------------*/
						if( isset($post_meta[MAX_SHORTNAME.'_photo_slider_select']) && $post_meta[MAX_SHORTNAME.'_photo_slider_select'] != "" && $post_meta[MAX_SHORTNAME.'_photo_slider_select'] != "none" && $post_meta[MAX_SHORTNAME.'_photo_item_type_value'] == "none" && $show_compact == 'false' ){
							// strip of "slider-"							
							if($post_meta[MAX_SHORTNAME.'_photo_slider_select'] != "slider-kwicks"){
								$slider_tpl = split("-", $post_meta[MAX_SHORTNAME.'_photo_slider_select'] );	
								get_template_part( 'includes/slider', $slider_tpl[1].'.inc' );
							}
														
							/*-----------------------------------------------------------------------------------*/
							/*  Get Slides Slider JS if needed
							/*-----------------------------------------------------------------------------------*/
							if( $post_meta[MAX_SHORTNAME.'_photo_slider_select'] == 'slider-slides'){ ?>								
								<script src="<?php echo get_template_directory_uri() ?>/slider/slides/slides.min.jquery.js"></script>
								<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri() ?>/slider/slides/slider-slides.css" />
							<?php
							}
							
							/*-----------------------------------------------------------------------------------*/
							/*  Get Nivo Slider JS if needed
							/*-----------------------------------------------------------------------------------*/
							
							if($post_meta[MAX_SHORTNAME.'_photo_slider_select'] == 'slider-nivo'){ ?>
								<script src="<?php echo get_template_directory_uri() ?>/slider/nivo/jquery.nivo.slider.js"></script>
								<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri() ?>/slider/nivo/nivo-slider.css" />
							<?php
							}							
																					
						}

						/*-----------------------------------------------------------------------------------*/
						/*  Get the needed Video Template if a video is attached
						/*-----------------------------------------------------------------------------------*/						
						if($post_meta[MAX_SHORTNAME.'_photo_item_type_value'] != 'none' && $show_compact == 'false' ){
														
							get_template_part( 'includes/post', 'video.inc' );									
							 
							// Get the JW Player javascript files
							if( $post_meta[MAX_SHORTNAME.'_photo_item_type_value'] == 'selfhosted'){ ?>
								<script src="<?php echo get_template_directory_uri() ?>/js/swfobject.js"></script>
								<script src="<?php echo get_template_directory_uri() ?>/js/jwplayer/jwplayer.js"></script>
							<?php 
							}
							
						}
												
					/*-----------------------------------------------------------------------------------*/
					/* No video or slider attached, so get the featured image
					/*-----------------------------------------------------------------------------------*/						
					if( ( $post_meta[MAX_SHORTNAME.'_photo_item_type_value'] == 'none' && ( $post_meta[MAX_SHORTNAME.'_photo_slider_select'] == 'none' || $post_meta[MAX_SHORTNAME.'_photo_slider_select'] == "slider-kwicks" ) ) || $show_compact == 'true' ){
						
						if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { 
						
							// Get the thumbnail
							$imgID = get_post_thumbnail_id();  
							$imgUrl = max_get_image_path($post->ID, 'full');
	
							$img_w = 940;
	
							// Check if images should be cropped
							$img_h = false;
							$timb_img_height = false;
						
							if(get_option_max( 'image_blog_original_ratio' ) != 'true' ) {
								$img_h = 300;
								$timb_img_height = 'height="300"';
							}						
						
							?>
							<div class="entry-image">												
								<a href="<?php the_permalink() ?>" title="<?php echo htmlspecialchars(get_the_excerpt()) ?>">
									<?php 
									// get the imgUrl for showing the post image
									$imgUrl = max_get_custom_image_url(get_post_thumbnail_ID(), get_the_ID(), $img_w, $img_h, $post_meta[MAX_SHORTNAME.'_photo_cropping_direction_value']); 
									?>								
									<img src="<?php echo $imgUrl ?>" <?php echo $timb_img_height ?> width="<?php echo $img_w ?>" class="fade-image<?php if( get_option_max('image_show_fade') != "true") { echo(" no-hover"); } ?>" alt="<?php the_title() ?>" title="<?php echo htmlspecialchars(get_the_excerpt()) ?>" />
								</a>									
							</div>
						
						<?php } ?>
						
					<?php } ?>
					</div>					
	
					<div class="clearfix">
					
						<div class="clearfix entry-meta entry-meta-head">
							<ul>
								<?php
								
									// get entry categories
									$post_categories = wp_get_post_categories( $post->ID );
									$cat_list = array();
									foreach ( $post_categories as $c ) {
										$cat = get_category( $c );
										$cat_list[] .= '<a href="'. get_category_link( $c ) .'">'. $cat->name .'</a>';
									}						
											
									$cat_list = implode(', ', $cat_list);																						
																					
									printf( __( '<li>By <span class="vcard author"><span class="fn">%1$s</span><span class="role">Author</span></span>&nbsp;/</li> ', MAX_SHORTNAME), get_the_author() );
									printf( __( '<li><span class="published">%1$s</span>&nbsp;/</li>', MAX_SHORTNAME), get_the_time(get_option('date_format')) );
									printf( __( '<li>In <span>%1$s</span>&nbsp;/</li>', MAX_SHORTNAME), substr($cat_list, 0) );
									printf( __( '<li class="last-update">Last Update <span class="updated">%1$s</span>&nbsp;/</li>', MAX_SHORTNAME), get_the_time(get_option('date_format')) );
									if ('open' == $post->comment_status) :
										echo '<li class="cnt-comment"><a href="#comments-holder"><span class="icon"></span>';									
										comments_number( 'No Comments', '1 Comment', '% Comments' );
										echo '</a></li>';
									endif;
								?>
							</ul>
						</div><!-- .entry-meta -->

						<div class="entry-content">						
						<?php 
						// check if blog should be shown full 									
						if( get_option_max('general_show_fullblog') == 'true'){
							the_content('<p class="read-more">' . __( 'Continue Reading...', 'invictus' ) . '</p>',FALSE,'');
						}else{
							the_excerpt();
						}						
						?>
						</div><!-- .entry-content -->	
											
					</div>
				
					<footer>
						<?php 
						// check if blog should be shown full 
						if( get_option_max('general_show_fullblog') != 'true'){						
						?>					
						<p class="read-more"><a href="<?php the_permalink() ?>" title="<?php echo __( 'Continue Reading...', 'invictus' ) ?>"><?php echo __( 'Continue Reading...', 'invictus' ) ?></a></p>							
						<?php } ?>
					</footer><!-- .entry-meta -->
					
				</article><!-- #post-<?php the_ID(); ?> -->
				
				<?php echo do_shortcode('[hr]') ?>


<?php endwhile; ?>
<?php else : ?>
<h2>No Entries Found</h2>
<?php endif; ?>						

<?php /* Display navigation to next/previous pages when applicable */ ?>
	<?php if (function_exists("max_pagination")) {
		max_pagination();
	} ?>
