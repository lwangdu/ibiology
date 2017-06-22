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

add_action( 'genesis_setup', 'ibiology_setup',15 );


/**
*
* Theme setup.
* Attach all of the site-wide functions to the correct hooks and filters. All the function themselves are defind below this setup function
*
* @since 1.0.0
*/
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
    'width'           => 1240,
    'height'          => 129,
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
	wp_enqueue_style( 'google-fonts','//fonts.googleapis.com/css?family=Roboto:400,400i,700,700i|Signika' );
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
	
	wp_enqueue_script( 'ibiology-content', get_stylesheet_directory_uri() . '/js/ibio.js', array( 'jquery' ), '1.0.0', true );
	
}

//* Customize the footer credits
	add_filter('genesis_footer_creds_text', 'ibiology_footer_creds_filter');
	function ibiology_footer_creds_filter( $creds ) {
		$creds = '[footer_copyright] &middot; <a href="https://www.ibiology.org">iBiology</a> &middot; <a href="https://www.ibiology.org/about" title="About Us">About Us</a>';
		return $creds;
	}



//* work around turning off cusotm fields
/*
if ( !defined( 'get_field' ) ){
  function get_field($field_name, $post_id = null, $format = false){
    if ( empty( $post_id ) ){
      global $post;
      if ( ! empty( $post ) ){
        $post_id = $post->ID;
      } 
    }
    
    return get_post_meta( $post_id, $field, true ); 
  }
}
*/

/* For SearchWP - until we disable the http protection */

function ibio_searchwp_basic_auth_creds() {
	
	// NOTE: this needs to be your HTTP BASIC AUTH login
	//
	//                 *** NOT *** your WordPress login
	//
	//
	$credentials = array( 
		'username' => 'iobiology2017', // the HTTP BASIC AUTH username
		'password' => 'ronvale'  // the HTTP BASIC AUTH password
	);
	
	return $credentials;
}

add_filter( 'searchwp_basic_auth_creds', 'ibio_searchwp_basic_auth_creds' );
