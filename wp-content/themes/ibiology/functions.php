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
define( 'CHILD_THEME_NAME', 'iBiology' );
define( 'CHILD_THEME_URL', 'http://github.com/lwangdu/ibiology' );


ibiology_setup();

// Add theme widget areas.
require_once( get_stylesheet_directory() .'/includes/widget-areas.php' );

// add other functions
require_once( get_stylesheet_directory() .'/includes/breadcrumbs.php' );



/**
*
* Theme setup.
* Attach all of the site-wide functions to the correct hooks and filters.
*
* @since 1.0.0
*/


function ibiology_setup() {

	// Add HTML5 makup structure.
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption'  ) );
	
	//Add viwport meta ag for mobile browsers.
	add_theme_support( 'genesis-responsive-viewport' );

    add_image_size('square-thumb', 300, 300, TRUE);

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
	add_theme_support( 'genesis-footer-widgets', 2 );
	
	// Unregister other site layouts
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	
	//* Unregister secondary sidebar
	unregister_sidebar( 'sidebar-alt' );
	
}


/**
 *
 *  Global Enqueues
 */

//* Enqueue Scripts and Styles
add_action( 'wp_enqueue_scripts', 'ibiology_enqueue_scripts_styles' );
function ibiology_enqueue_scripts_styles()
{

    wp_enqueue_script('ibiology-responsive-menu', get_stylesheet_directory_uri() . '/assets/js/responsive-menu.js', array('jquery'), '1.0.0', true);
    $output = array(
        'mainMenu' => __('Menu', 'ibiology'),
        'subMenu' => __('Menu', 'ibiology'),
    );
    wp_localize_script('ibiology-responsive-menu', 'ibiologyL10n', $output);

    wp_enqueue_script('ibiology-content', get_stylesheet_directory_uri() . '/assets/js/ibio-theme.js', array('jquery'), '1.0.0', true);

    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Lato:400,700|Roboto:400,500');
    wp_enqueue_style('font-awesome', get_stylesheet_directory_uri() . '/assets/css/font-awesome.min.css');

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
	return $excerpt . ' <a class="more-link" href="'. get_permalink( get_the_ID() ) . '">Read More</a>';
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


// pull talks in category pages -- 

add_action( 'pre_get_posts', 'ibio_prepare_query' );

function ibio_prepare_query( $query ) {
	
	$post_type = get_post_type();
	
	if ( !$query->is_main_query() ) return;

    /*if ( is_page('explore') ){
        error_log('page template: Explore Page');
        unset ( $query->query_vars['pagename']);
        $query->query_vars['post_type'] =  IBioTalk::$post_type;
        $query->query_vars['posts_per_page'] = 50;
        error_log( serialize ($query->query_vars ));
        return;
    }*/

	if ( is_post_type_archive(IBioPlaylist::$post_type) ){
		$query->query_vars['orderby'] = 'name';
		$query->query_vars['order'] = 'ASC'; 
		$query->query_vars['posts_per_page'] = -1;
		return;
	}	else if ( is_post_type_archive(IBioSpeaker::$post_type) ){
		$query->query_vars['orderby'] = 'meta_value';
		$query->query_vars['meta_key'] = 'last_name';
		$query->query_vars['order'] = 'ASC'; 
		$query->query_vars['posts_per_page'] = -1;
		return;
	}
	
	if ( is_category() ) {
		/* $query->query_vars['orderby'] = 'name';
		$query->query_vars['order'] = 'ASC'; */
		$query->query_vars['post_type'] =  IBioTalk::$post_type;
        //$query->query_vars['posts_per_page'] = -1;
		return;
	}

}


// for filtering display posts shortcode items

function ibio_display_posts_with_short_title( $output, $original_atts, $image, $title, $date, $excerpt, $inner_wrapper, $content, $class ) {
 
	// Create a new title
	$short_title = get_field( 'short_title' );
	if ( $short_title > '' ) {
		$title = $short_title;
	}
	
	$url = get_the_permalink();	
	$title = "<h3 class='entry-title'><a href='$url'>$title</a></h3>";	
	// Now let's rebuild the output
	$output = '<' . $inner_wrapper . ' class="' . implode( ' ', $class ) . '">' . $image . $title . $date . $author . $excerpt . $content . '</' . $inner_wrapper . '>';
 
	// Finally we'll return the modified output
	return $output;
}
add_filter( 'display_posts_shortcode_output', 'ibio_display_posts_with_short_title', 10, 9 );


// FacetWP Sort options
function ibio_facetwp_sort_options( $options, $params ) {
    error_log('facet options '.  serialize($options ));
    error_log ('parameters: '. serialize($params) );
    $options['date_recorded'] = array(
        'label' => 'Date Recorded',
        'query_args' => array(
            'orderby' => 'meta_value', // sort by numerical custom field
            'meta_key' => 'recorded_date', // required when sorting by custom fields
            'order' => 'DESC', // descending order
        )

    );
    $options['alpha-shortaz'] = array(
        'label' => 'By Short Title (A-Z)',
        'query_args' => array(
            'orderby' => 'meta_value', // sort by numerical custom field
            'meta_key' => 'short_title', // required when sorting by custom fields
            'order' => 'ASC', // descending order
        )
    );
    $options['alpha-shortza'] = array(
        'label' => 'By Short Title (Z-A)',
        'query_args' => array(
            'orderby' => 'meta_value', // sort by numerical custom field
            'meta_key' => 'short_title', // required when sorting by custom fields
            'order' => 'DESC', // descending order
        )
    );
    return $options;
}

add_filter( 'facetwp_sort_options', 'ibio_facetwp_sort_options', 10, 2 );