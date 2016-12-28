<?php
/**
 * The template used for anth101 loops
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<header class="entry-header <?php if(is_search()){ echo 'page-header'; }?>">
			<h1 class="entry-title <?php if(is_search()){ echo 'main-title'; }?>"><?php the_title(); ?></h1>
		</header>

		<div class="entry-content">
			<?php the_content(); ?>
			<!--NEW LOOP -->
			<?php
			  // set up or arguments for our custom query
 			  $the_category = get_post_custom_values('anth_cat_search')[0];

			  $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
			  $query_args = array(
			    'post_type' => 'post',
			    'category_name' => $the_category,
			    'posts_per_page' => 12,
			    'paged' => $paged
			  );
			  // create a new instance of WP_Query
			  $the_query = new WP_Query( $query_args );
			?>

			<div class="flex-container">
				<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); // run the loop ?>
				    <div class="flex-item">
					  <article>	
					  <a href="<?php the_permalink(); ?>">
					  	<div class="anth-thumb" <?php echo anth_thumb_background();?>>				  						  	
					  </a>		
					  	<a href="<?php the_permalink(); ?>">
					    	<h2><?php echo the_title(); ?></h2>
					    </a>
					  </div>  
					    
					    <div class="anth-author">
					    	<?php the_author();?>
					    </div>
					    <div class="anth-excerpt">
					      <?php the_excerpt(); ?>			     
					    </div>
					    <div class="anth-fav">
					    	<?php echo get_simple_likes_button(get_the_id()) . sap_get_bookmark_button() ; ?>
					    	<?php echo crunchify_social_sharing_buttons($post);?>	
					    </div>
					  </article>
					</div>	  
				<?php endwhile; ?>
			</div>	

			<?php if ($the_query->max_num_pages > 1) { // check if the max number of pages is greater than 1  ?>
			  <nav class="anth-prev-next-posts">
			    <div class="anth-posts-link">
			      <?php echo get_next_posts_link( 'More Challenges', $the_query->max_num_pages ); // display older posts link ?>
			    </div>
			    <div class="anth-posts-link">
			      <?php echo get_previous_posts_link( 'Previous challenges' ); // display newer posts link ?>
			    </div>
			  </nav>
			<?php } ?>


			<?php else: ?>
			  <article>
			    <h1>Sorry...</h1>
			    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
			  </article>
			<?php endif; ?>
			  


			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'boss' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->

	</article><!-- #post -->
