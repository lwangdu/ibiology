<?php
/**
 * Theme customization
 *
 * @package		iBiology
 * @Authour  	Anca Mosoiu and Lobsang Wangdu
 * @Link 	 	https://www.yowangdu.com
 * @copyright 	Copyrigh (c) 2017, iBiology
 * @license 	GPL-2.0+
 */

//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );


// load child theme textdomain.
load_child_theme_textdomain( 'ibiology' );
define( 'CHILD_THEME_VERSION', '1.0' );

add_action( 'genesis_setup', 'ibiology_setup',15 );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );


/**
*
* Theme setup.
* Attach all of the site-wide functions to the correct hooks and filters. All the function themselves are defind below this setup function
*
* @since 1.0.0
*/

add_image_size('square-thumb', 300, 300, TRUE);

function ibiology_setup() {
	// Define them constants.
	define( 'CHILD_THEME_NAME', 'iBiology' );
	define( 'CHILD_THEME_URL', 'http://github.com/lwangdu/ibiology' );
	define( 'CHILD_THEME_VERSION', '0.1.0' );
	
	// Add HTML5 makup structure.
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption'  ) );
	
	//Add viwport meta ag for mobile browsers.
	add_theme_support( 'genesis-responsive-viewport' );
	
	//* Add support for custom header
  add_theme_support( 'custom-header', array(
    'width'           => 600,
    'height'          => 160,
    'header-selector' => '.site-title a',
    'header-text'     => false,
    'flex-height'     => true,
  ) );
	

	
	// Add them support for accessibility.
	add_theme_support( 'genesis-accessibility', array(
			'404-page',
			'drop-down-menu',
			'headings',
			'rems',
			'search-form',
			'skip-links',
		) );
	// Add them support for footer widgets
	add_theme_support( 'genesis-footer-widgets', 1 );
	
	// Unregister other site layouts
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	
	//* Unregister secondary sidebar
	unregister_sidebar( 'sidebar-alt' );
	
	// Add theme widget areas.
	include_once( get_stylesheet_directory() .'/includes/widget-areas.php' );
	
	
}

// Google font stylesheet
add_action( 'wp_enqueue_scripts', 'ibiology_enqueue_styles' );
function ibiology_enqueue_styles() {
	wp_enqueue_style( 'google-fonts','https://fonts.googleapis.com/css?family=Lato:400,700|Roboto:400,500' );
	wp_enqueue_style( 'ionicons', '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css', array(), CHILD_THEME_VERSION );
}


//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 1 );


/** 
 *
 *  Widget Areas
 */

genesis_register_sidebar( array(
	'id'          => 'sidebar_talks',
	'name'        => __( 'Individual Talk', 'metro' ),
	'description' => __( 'This is the sidebar for an individual talk', 'metro' ),
) );



/**
 * Global enqueues
 *
 */
//* Enqueue Scripts and Styles
add_action( 'wp_enqueue_scripts', 'ibiology_enqueue_scripts_styles' );
function ibiology_enqueue_scripts_styles() {

	wp_enqueue_script( 'ibiology-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );
	$output = array(
		'mainMenu' => __( 'Menu', 'ibiology' ),
		'subMenu'  => __( 'Menu', 'ibiology' ),
	);
	wp_localize_script( 'ibiology-responsive-menu', 'ibiologyL10n', $output );
	
	wp_enqueue_script( 'ibiology-content', get_stylesheet_directory_uri() . '/js/ibio-theme.js', array( 'jquery' ), '1.0.0', true );
	
}

//* Customize the footer credits
	add_filter('genesis_footer_creds_text', 'ibiology_footer_creds_filter');
	function ibiology_footer_creds_filter( $creds ) {
		$creds = '[footer_copyright] &middot; <a href="https://www.ibiology.org">iBiology</a> &middot; <a href="https://www.ibiology.org/about" title="About Us">About Us</a>';
		return $creds;
	}

if ( ! isset( $content_width ) ) {
	$content_width = 800;
}

/* ----------------  Content ---------------- */

// default behavior for excerpts

add_filter('excerpt_more', 'ibio_excerpt_more');
function ibio_excerpt_more(){
	return '...';
}

// add a "more" link to all excerpts.
add_filter('the_excerpt', 'ibio_add_more_link', 1, 2);
function ibio_add_more_link($excerpt){
	return $excerpt . ' <a class="morelink" href="'. get_permalink( get_the_ID() ) . '">[Read More]</a>';
}



// customize embed settings 
// YouTue URL'S_IRWXU
function ibio_youtube_embed($code){
	//error_log( '[ibio_youtube_embed With ' . $code );
	if(strpos($code, 'youtu.be') !== false || strpos($code, 'youtube.com') !== false){
		$return = preg_replace("@src=(['\"])?([^'\">\s]*)@", "src=$1$2&showinfo=0", $code);
		//error_log( '[ibio_youtube_embed return  ' . $return );
		return $return;
	}
	return $code;
}

add_filter('embed_handler_html', 'ibio_youtube_embed', 200);
add_filter('embed_oembed_html', 'ibio_youtube_embed', 200);

remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_before_loop', 'ibio_breadcrumbs');

function ibio_breadcrumbs(){
	if ( function_exists('yoast_breadcrumb') ) {
		yoast_breadcrumb('<p id="breadcrumbs">','</p>');
	} else{
	   genesis_do_breadcrumbs();
	}
}

// pull talks in category pages

add_action( 'pre_get_posts', 'ibio_category_page_talks' );

function ibio_category_page_talks( $query ) {
	
	$post_type = get_post_type();
	
	if ( !is_category() ) return;
	
  if( $query->is_main_query() ) {    
    /* $query->query_vars['orderby'] = 'name';
    $query->query_vars['order'] = 'ASC'; */
    $query->query_vars['post_type'] =  IBioTalk::$post_type;
  }

}
