<?php
/**
 * BuddyPress - Members Home
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div id="buddypress" >

	<?php do_action( 'bp_before_member_home_content' ); ?>

	<div id="item-header" role="complementary">

		<?php bp_get_template_part( 'members/single/member-header' ) ?>

	</div><!-- #item-header -->

    <div class="<?php echo ( boss_get_option( 'boss_layout_style' ) == 'boxed' && is_active_sidebar( 'profile' ) && bp_is_user() ) ? 'right-sidebar' : 'full-width'; ?>">
        <div id="item-main-content">
            <?php if(!bp_is_current_component('events') || ( bp_is_current_component('events') && 'profile' == bp_current_action() ) ): //show if not Events Manager page or My Profile of Events ?>
        <?php             
            echo '<div class="row" id="challenge-block">';            
            $author_id = bp_displayed_user_id();
            $args = array(
                'posts_per_page' => 10,              
                'post_status'      => 'publish',                
                'author'        =>  $author_id,
                'post_type'   => 'post',
                'category__in' => [278, 272, 279, 280, 274, 281, 282, 283, 284, 285],  
                //'meta_key' => 'anth_assignment_num',
                'orderby' => 'ID',   
                'order' => 'ASC',     
            );
            $challenge_posts = get_posts( $args );
            $challengesAnswered = count($challenge_posts);  //number of user posts returned

            //var_dump($challenge_posts[9]);

            //$challengeCategories = challenge_array_maker(273); //array of challenge category IDs -- should be 10
            $challengeCategories = [278, 272, 279, 280, 274, 281, 282, 283, 284, 285];   //hand sorted into the correct order. UGLY. I know.
            $challengeCategoryNum = count($challengeCategories);

            for ($i=0; $i < $challengeCategoryNum; $i++) { 
                $false_count = 1;
                  for ($c=0; $c < $challengesAnswered; $c++){
                    $post_id = $challenge_posts[$c]->ID;
                    $post_cat = wp_get_post_categories($post_id)[0];
                    if ($post_cat === $challengeCategories[$i]){
                            echo '<a href="'.get_the_permalink($post_id).'">';                           
                            echo '<div class="square bg img1" '; 
                            echo get_post_background_img ($post_id) .'>';
                            echo '<div class="table-cell" id="assg_'.($i).'">';
                            echo '<span class="assignment_no">'.($i+1).'</span>';
                            echo  '</div></div></a>';
                        } else {
                             $false_count++;
                             if ($false_count === ($challengesAnswered+1)) {
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
                
            <div id="item-nav">
                <div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
                    <ul id="nav-bar-filter">

                        <?php bp_get_displayed_user_nav(); ?>

                        <?php do_action( 'bp_member_options_nav' ); ?>

                    </ul>
                </div>
            </div><!-- #item-nav -->
            <?php endif; ?>

            <?php if(bp_current_component() != 'shop')
                echo '<div id="item-body" role="main">';
            ?>

        		<?php

        		/**
        		 * Fires before the display of member body content.
        		 *
        		 * @since 1.2.0
        		 */
        		do_action( 'bp_before_member_body' );

        		if ( bp_is_user_front() ) :
        			bp_displayed_user_front_template_part();

        		elseif ( bp_is_user_activity() ) :
        			bp_get_template_part( 'members/single/activity' );

        		elseif ( bp_is_user_blogs() ) :
        			bp_get_template_part( 'members/single/blogs'    );

        		elseif ( bp_is_user_friends() ) :
        			bp_get_template_part( 'members/single/friends'  );

        		elseif ( bp_is_user_groups() ) :
        			bp_get_template_part( 'members/single/groups'   );

        		elseif ( bp_is_user_messages() ) :
        			bp_get_template_part( 'members/single/messages' );

        		elseif ( bp_is_user_profile() ) :
        			bp_get_template_part( 'members/single/profile'  );

        		elseif ( bp_is_user_forums() ) :
        			bp_get_template_part( 'members/single/forums'   );

        		elseif ( bp_is_user_notifications() ) :
        			bp_get_template_part( 'members/single/notifications' );

        		elseif ( bp_is_user_settings() ) :
        			bp_get_template_part( 'members/single/settings' );

        		// If nothing sticks, load a generic template
        		else :
        			bp_get_template_part( 'members/single/plugins'  );

        		endif;

        		/**
        		 * Fires after the display of member body content.
        		 *
        		 * @since 1.2.0
        		 */
        		do_action( 'bp_after_member_body' ); ?>

            <?php if(bp_current_component() != 'shop')
                echo '</div><!-- #item-body -->';
            ?>

            <?php do_action( 'bp_after_member_home_content' ); ?>

        </div>
        <!-- /.item-main-content -->
        <?php
        // Boxed layout sidebar
        if ( boss_get_option( 'boss_layout_style' ) == 'boxed' ) {
            get_sidebar( 'buddypress' );
        }
        ?>
    </div>

</div><!-- #buddypress -->
