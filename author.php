<?php
/**
 * The template for displaying Author Archive pages.
 *
 * Used to display archive-type pages for posts by an author.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

get_header(); ?>
<?php if ( is_active_sidebar('sidebar') ) : ?>
	<div class="page-right-sidebar">
<?php else : ?>
	<div class="page-full-width">
<?php endif; ?>
    
	<section id="primary" class="site-content">
		<div id="content" role="main">
       
        <header class="archive-header page-header">
            <h1 class="archive-title main-title">
               <?php printf( __( 'Author Archives: %s', 'boss' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?>
            </h1>
        </header><!-- .archive-header -->
        
		<?php if ( have_posts() ) : ?>
           
            <?php
			// If a user has filled out their description, show a bio on their entries.
			if ( get_the_author_meta( 'description' ) ) : ?>
			<div class="author-info table">
				<div class="author-avatar table-cell">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'buddyboss_author_bio_avatar_size', 60 ) ); ?>
				</div><!-- .author-avatar -->
				<div class="author-description table-cell">
					<h2><?php printf( __( 'About %s', 'boss' ), get_the_author() ); ?></h2>
					<p><?php the_author_meta( 'description' ); ?></p>
				</div><!-- .author-description	-->
			</div><!-- .author-info -->
			<?php endif; ?>

            <!-- ASSIGNMENTS -->
<h2>Challenges</h2>
 <?php             
            echo '<div class="row" id="challenge-block">';            

            $args = array(
              'numberposts' => 10,
              'author'        =>  $author_id=$post->post_author,
              'post_type'   => 'post',
              'category__in' => challenge_array_maker(273),  
               'meta_key' => 'anth_assignment_num',
                'orderby' => 'meta_value_num',   
                'order' => 'ASC'     
            );
             
            $challenge_posts = get_posts( $args );
            $challengesAnswered = count($challenge_posts);  //number of user posts returned

            $challengeCategories = challenge_array_maker(273); //array of challenge category IDs -- should be 10
            $challengeCategoryNum = count($challengeCategories);


            
            for ($i=0; $i < $challengeCategoryNum; $i++) {
                $false_count = 1;
                //echo '<h2>' . challenge_array_maker(293)[$i] . '</h2>';
                for ($c=1; $c < $challengesAnswered; $c++){                       
                    $post_id = $challenge_posts[$c]->ID; 

                    if (wp_get_post_categories ($post_id)[0] === challenge_array_maker(273)[$i]){
                            echo '<a href="'.get_the_permalink($post_id).'">';                           
                            echo '<div class="square bg img1" '; 
                            echo get_post_background_img ($post_id) .'>';
                            echo '<div class="table-cell" id="assg_'.($i).'">';
                            echo '<span class="assignment_no">'.($i+1).'</span>';
                            echo  '</div></div></a>';
                    } else {       
                            $false_count++;
                        if ($false_count === $challengesAnswered) {
                            echo '<a href="http://anth101.com/challenge' . ($i+1) . '">';                            
                            echo '<div class="square bg img1">';
                            echo '<div class="table-cell" id="assg_'.($i+1).'">';
                            echo '<span class="assignment_no">'.($i+1).'</span>';
                            echo  '</div></div></a>';
                        }


                    }          

                }    
            }
            echo '</div>';
            ?>

<!-- END ASSIGNEMNTS-->            


			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<div class="pagination-below">
				<?php buddyboss_pagination(); ?>
			</div>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

    <?php if ( is_active_sidebar('sidebar') ) : 
        get_sidebar('sidebar'); 
    endif; ?>
    </div>
<?php get_footer(); ?>
