<?php
/**
 * The loop that displays search posts.
 *
 * @package WordPress
 * @subpackage Invictus
 * @since Invictus 1.0
 */

$post_meta = max_get_cutom_meta_array($post->ID);

			if (have_posts()) : while (have_posts()) : the_post();
			?>
			
							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
								
								<div class="rel">
								
									<div class="date-badge"><?php echo get_the_date("d") ?><span><?php echo get_the_date("M") ?></span></div>
									
									<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="title="<?php echo htmlspecialchars(get_the_excerpt()) ?>""><?php the_title() ?></a></h2>
									
									<?php 
									if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { 
																	
										// get the imgUrl for showing the post image
										$imgUrl = max_get_custom_image_url(get_post_thumbnail_ID(), get_the_ID(), 660, 220, $post_meta[MAX_SHORTNAME.'_photo_cropping_direction_value'] );
																	
									?>
									
									<div class="entry-image">
										<a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
											<img src="<?php echo $imgUrl ?>" height="220" width="660" class="fade-image" alt="<?php the_title() ?>" title="<?php the_title() ?>" />
										</a>				
									</div>
										
									<?php } ?>
					
									<div class="clearfix">
									
										<div class="clearfix entry-meta">
											<ul>
												<?php								
													// get entry categories
													$post_categories = wp_get_post_categories( $post->ID );
													$cat_list = array();
													foreach ( $post_categories as $c ) {
														$cat = get_category( $c );
														$cat_list[] .= $cat->name;
													}						
													
													$cat_list = implode(', ', $cat_list);
													
													// get comment count
													$comments_count = get_comments_number();
													$comment_string = $comments_count == 1 ? __( 'Comment' , 'invictus' )  : __( 'Comments' , 'invictus' );
													
													printf( __( '<li>By <span>%1$s</span></li>', 'invictus'), get_the_author() );	
													printf( __( '<li>Posted on <span>%1$s</span></li>', 'invictus'), get_the_date() );									
												?>
											</ul>
										</div><!-- .entry-meta -->
				
										<div class="entry-content">							
										<?php 
										// check if blog should be shown full 
										if( get_option_max('general_show_fullblog') == 'true'){
											the_content();
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
										<br />	
										<p class="read-more"><a href="<?php the_permalink() ?>" title="<?php echo __( 'Continue Reading...', 'invictus' ) ?>"><?php echo __( 'Continue Reading...', 'invictus' ) ?></a></p>							
										<?php } ?>
									</footer><!-- .entry-meta -->
								
								</div>
								
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