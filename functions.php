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


//doing the background image inline CSS
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


//Direct author links to BuddyPress profile links  
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


//SOCIAL SHARING 
function crunchify_social_sharing_buttons($post) {
    global $post;
    // Get current page URL 
    $crunchifyURL = urlencode(get_the_permalink($post));
 
    // Get current page title
    $crunchifyTitle = str_replace( ' ', '%20', get_the_title($post));

    // Get Post Thumbnail for pinterest
    $crunchifyThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'full' );

    // Construct sharing URL without using any script
    $twitterURL = 'https://twitter.com/intent/tweet?text='.$crunchifyTitle.'&amp;url='.$crunchifyURL;
    $facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$crunchifyURL;
    $googleURL = 'https://plus.google.com/share?url='.$crunchifyURL;
    $linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url='.$crunchifyURL.'&amp;title='.$crunchifyTitle;
    $pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$crunchifyURL.'&amp;media='.$crunchifyThumbnail[0].'&amp;description='.$crunchifyTitle;
    
    $content = '';
    // Add sharing button at the end of page/page content
    $content .= '<!-- Crunchify.com social sharing. Get your copy here: http://crunchify.me/1VIxAsz -->';
    $content .= '<div class="anth-social"><div class="anth-share-header">Share via  </div>';
    $content .= '<a class="anth-link crunchify-twitter" href="' . $twitterURL .'" target="_blank"><i class="fa fa-twitter"></i></a>';
    $content .= '<a class="anth-link crunchify-facebook" href="'. $facebookURL .'" target="_blank"><i class="fa fa-facebook-official"></i></a>';
    $content .= '<a class="anth-link crunchify-googleplus" href="'.$googleURL.'" target="_blank"><i class="fa fa-google"></i></a>';
    $content .= '<a class="anth-link crunchify-linkedin" href="'.$linkedInURL.'" target="_blank"><i class="fa fa-linkedin"></i></a>';
    $content .= '<a class="anth-link crunchify-pinterest" href="'.$pinterestURL.'" target="_blank"><i class="fa fa-pinterest"></i></a>';
    $content .= '</div>';
    
    return $content;
 
};

//BUDDYPRESS GROUPS MODIFICATIONS
/**
 * The bp_is_active( 'groups' ) check is recommended, to prevent problems 
 * during upgrade or when the Groups component is disabled
 */
if ( bp_is_active( 'groups' ) ) :
 
class Group_Extension_Anth extends BP_Group_Extension {
    /**
     * Your __construct() method will contain configuration options for 
     * your extension, and will pass them to parent::init()
     */
    function __construct() {
        $args = array(
            'slug' => 'group-anth-posts',
            'name' => 'Clan Posts',
        );
        parent::init( $args );
    }
 
    /**
     * display() contains the markup that will be displayed on the main 
     * plugin tab
     */
    function display( $group_id = NULL ) {
        $group_id = bp_get_group_id();
        require('buddypress/groups/single/anth-blog.php'); 
    }
 
    /**
     * settings_screen() is the catch-all method for displaying the content 
     * of the edit, create, and Dashboard admin panels
     */
    function settings_screen( $group_id = NULL ) {
        $setting = groups_get_groupmeta( $group_id, 'group_extension_anth_setting' );
 
        ?>
        Save your plugin setting here: <input type="text" name="group_extension_anth_setting" value="<?php echo esc_attr( $setting ) ?>" />
        <?php
    }
 
    /**
     * settings_sceren_save() contains the catch-all logic for saving 
     * settings from the edit, create, and Dashboard admin panels
     */
    function settings_screen_save( $group_id = NULL ) {
        $setting = '';
 
        if ( isset( $_POST['group_extension_anth_setting'] ) ) {
            $setting = $_POST['group_extension_anth_setting'];
        }
 
        groups_update_groupmeta( $group_id, 'group_extension_anth_setting', $setting );
    }
}
bp_register_group_extension( 'group_extension_anth' );
 
endif; // if ( bp_is_active( 'groups' ) )

?>