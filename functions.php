<?php
/**
 * @package Boss Child Theme
 * The parent theme functions are located at /boss/buddyboss-inc/theme-functions.php
 * Add your own functions in this file.
 */

/**
 * Sets up theme defaults
 *
 * @since Boss Child Theme 1.0.0
 */
function boss_child_theme_setup()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   * Read more at: http://www.buddyboss.com/tutorials/language-translations/
   */

  // Translate text from the PARENT theme.
  load_theme_textdomain( 'boss', get_stylesheet_directory() . '/languages' );

  // Translate text from the CHILD theme only.
  // Change 'boss' instances in all child theme files to 'boss_child_theme'.
  // load_theme_textdomain( 'boss_child_theme', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'boss_child_theme_setup' );

/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function boss_child_theme_scripts_styles()
{
  /**
   * Scripts and Styles loaded by the parent theme can be unloaded if needed
   * using wp_deregister_script or wp_deregister_style.
   *
   * See the WordPress Codex for more information about those functions:
   * http://codex.wordpress.org/Function_Reference/wp_deregister_script
   * http://codex.wordpress.org/Function_Reference/wp_deregister_style
   **/


  /*
   * Styles
   */
  wp_enqueue_style( 'boss-child-custom', get_stylesheet_directory_uri().'/css/custom.css' );
}
add_action( 'wp_enqueue_scripts', 'boss_child_theme_scripts_styles', 9999 );
function wpdocs_custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );


/****************************** CUSTOM FUNCTIONS ******************************/

// Add your own custom functions here

function challenge_array_maker ($parent) {
                //get the categories form the Student Submissions parent category and put them in an array
                $challenge_names = get_categories( array( 'child_of' => $parent,'hide_empty' => false, 'orderby' => 'slug' ) );                
                $challenge_array =[];
                $challenge_cat_id_array =[];
                for($i = 0; $i <count($challenge_names); $i++) {
                    //echo $challenge_names[$i]->cat_name . '<br>';
                    //echo $challenge_names[$i]->cat_ID . '<br>';                
                    array_push($challenge_array, $challenge_names[$i]->cat_name);
                    array_push($challenge_cat_id_array, $challenge_names[$i]->cat_ID);
                }

                //$combine = array_combine($challenge_cat_id_array, $challenge_array);
                return $challenge_cat_id_array;
            }

function get_assignment_category_number($post_id){
  $cats = get_the_category($post_id);  
  $name = $cats[0]->name;
  $findme   = '.';
  $pos = strpos($name, $findme);
  $number = intval(substr($name,0,$pos));
  if ($number){
  return $number;
}
}

function get_post_background_img ($post) {
  if ( $thumbnail_id = get_post_thumbnail_id($post) ) {
        if ( $image_src = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' ) )
            printf( ' style="background-image: url(%s);"', $image_src[0] );     
    }
}            

/*
add_action( 'save_post', 'add_assignment_id_metafield' );

function add_assignment_id_metafield($post_id) {
           global $post;
          $post_id = $post->ID;
          $assignment_num = intval(get_assignment_category_number($post));
        // Check if the custom field has a value.
            update_post_meta($post_id,'anth_assignment_num', $assignment_num);    
         }
         */

// hook the translation filters
add_filter(  'gettext',  'change_group_to_tribe'  );
add_filter(  'ngettext',  'change_group_to_tribe'  );

function change_group_to_tribe( $translated ) {
  $translated = str_ireplace(  'Group',  'Clan',  $translated );  // ireplace is PHP5 only
  return $translated;
}  


add_filter(  'gettext',  'change_groups_to_tribes'  );
add_filter(  'ngettext',  'change_groups_to_tribes'  );

function change_groups_to_tribes( $translated ) {
  $translated = str_ireplace(  'Groups',  'Clans',  $translated );  // ireplace is PHP5 only
  return $translated;
} 

//INFINITE SCROLLING

//revisit bill erickson's post . . . 


//special ANTH pages

function anth_cat_search_add_meta_box() {
 
  $screens = array('post');
  foreach ( $screens as $screen ) {
 
    add_meta_box(
    'anth_cat_search_category',
    __( 'Choose the Category', 'anthsearch' ),
    'anth_cat_search_category_callback',
    $screen,
      'normal',
      'high'
     );
    }
}

function anth_thumb_background(){
    global $post; 

    $image_id=get_post_thumbnail_id($post);
    $image_url = wp_get_attachment_image_src($image_id,'thumbnail');
    $image_url=$image_url[0];

    //$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID, 'medium'), array( 5600,1000 ), false, '' )[0];
    if ($image_url){ 
       return 'style="background-image: url(' . $image_url .'); background-size: cover;"';
    } else {
    return 'style="background-color:#efefef"';
    }
  }
function ra_add_author_filter() {
          add_filter( 'author_link', 'ra_bp_filter_author' );
  }       
  add_action( 'wp_head', 'ra_add_author_filter' );
  
  function ra_bp_filter_author( $content ) {
          if( defined( 'BP_MEMBERS_SLUG' ) ) {
                  if( is_multisite() ) {
                          $member_url = network_home_url( BP_MEMBERS_SLUG );
                          if( !is_subdomain_install() && is_main_site() )
                                  $extra = '/blog';
                          else
                                  $extra = '';
  
                          $blog_url = get_option( 'siteurl' ) . $extra . '/author';
                          return str_replace( $blog_url, $member_url, $content );
                  }
                  return preg_replace( '|/author(/[^/]+)/?$|', '/' . BP_MEMBERS_SLUG . '$1' . '/profile/', $content );
          }
          return $content;
  }

?>