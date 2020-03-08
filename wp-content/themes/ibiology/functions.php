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

// Add gutenberg suport
add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );

/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
    require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

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

	add_post_type_support('ibiology_podcasts', 'genesis-archive-layouts');
	add_post_type_support('page', 'excerpt');
}


/**
 *
 *  Global Enqueues
 */

//* Enqueue Scripts and Styles
add_action( 'wp_enqueue_scripts', 'ibiology_enqueue_scripts_styles' );
function ibiology_enqueue_scripts_styles()
{

    wp_enqueue_script('ibiology-responsive-menu', get_stylesheet_directory_uri() . '/assets/js/responsive-menu.js', array('jquery'), '1.1.0', true);
    $output = array(
        'mainMenu' => __('Menu', 'ibiology'),
        'subMenu' => __('Menu', 'ibiology'),
    );
    wp_localize_script('ibiology-responsive-menu', 'ibiologyL10n', $output);

    wp_enqueue_script('ibiology-content', get_stylesheet_directory_uri() . '/assets/js/ibio-theme.js', array('jquery'), '1.1.0', true);

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
    if ( !is_search()) {
        return $excerpt . ' <a class="more-link" href="' . get_permalink(get_the_ID()) . '">Continue Reading </a>';
    } else {
        return $excerpt;
    }
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

add_action( 'pre_get_posts', 'ibio_prepare_query', 100 );

function ibio_prepare_query( $query ) {
	
	$post_type = get_post_type();
	
	if ( !$query->is_main_query() ) return;

	//error_log( serialize($query ) );

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
		// prevent RCP from blowing up our hidden playlists
		$query->query_vars['suppress_filters'] = true;

        $hidden = get_option('hidden_playlists');
        if ( is_array($hidden) ) {
        	if ( !empty( $query->query_vars['post__not_in'] ) && is_array( $query->query_vars['post__not_in'] ) ){
        		$hidden = array_merge( $query->query_vars['post__not_in'], $hidden );
	        }
            $query->query_vars['post__not_in'] = $hidden;
        }

		return;
	}	else if ( is_post_type_archive(IBioSpeaker::$post_type) ){
		$query->query_vars['orderby'] = 'meta_value';
		$query->query_vars['meta_key'] = 'last_name';
		$query->query_vars['order'] = 'ASC'; 
		$query->query_vars['posts_per_page'] = -1;
		return;
	}
	
	if ( is_category() && ( !is_category('blog') && !is_category('podcasts') ) ) {
		/* $query->query_vars['orderby'] = 'name';
		$query->query_vars['order'] = 'ASC'; */
		$query->query_vars['post_type'] =  IBioTalk::$post_type;
        //$query->query_vars['posts_per_page'] = -1;
		return;
	}

}

/* ------------------------  Display Posts Section -----------------------------*/

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
	$output = '<' . $inner_wrapper . ' class="' . implode( ' ', $class ) . '">' . $image . $title . $date . $excerpt . $content . '</' . $inner_wrapper . '>';
 
	// Finally we'll return the modified output
	return $output;
}

add_filter( 'display_posts_shortcode_output', 'ibio_display_posts_with_short_title', 10, 9 );


/**
 * Exclude displayed posts from DPS query
 *
 */
function ibio_dps_exclude_displayed_posts( $args ) {
    global $_genesis_displayed_ids;
    $args['post__not_in'] = !empty( $args['post__not_in'] ) ? array_merge( $args['post__not_in'], $_genesis_displayed_ids ) : $_genesis_displayed_ids;
    return $args;
}
add_filter( 'display_posts_shortcode_args', 'ibio_dps_exclude_displayed_posts' );
/**
 * Add DPS posts to exclusion list
 *
 */
function ibio_dps_add_posts_to_exclusion_list( $output ) {
    global $_genesis_displayed_ids;
    $_genesis_displayed_ids[] = get_the_ID();
    return $output;
}
add_filter( 'display_posts_shortcode_output', 'ibio_dps_add_posts_to_exclusion_list' );


/* ------------------------  FACETWP Section -----------------------------*/
// FacetWP Sort options
function ibio_facetwp_sort_options( $options, $params ) {

    $options['date_desc'] = array(
        'label' => 'Date Recorded (Newest)',
        'query_args' => array(
            'orderby' => 'meta_value', // sort by numerical custom field
            'meta_key' => 'recorded_date', // required when sorting by custom fields
            'order' => 'DESC', // descending order
        )

    );
    $options['date_asc'] = array(
        'label' => 'Date Recorded (Oldest)',
        'query_args' => array(
            'orderby' => 'meta_value', // sort by numerical custom field
            'meta_key' => 'recorded_date', // required when sorting by custom fields
            'order' => 'ASC', // descending order
        )

    );
    /* $options['title_asc'] = array(
        'label' => 'By Title (A-Z)',
        'query_args' => array(
            'orderby' => 'meta_value', // sort by numerical custom field
            'meta_key' => 'short_title', // required when sorting by custom fields
            'order' => 'ASC', // descending order
        )
    );
    $options['title_desc'] = array(
        'label' => 'By Title (Z-A)',
        'query_args' => array(
            'orderby' => 'meta_value', // sort by numerical custom field
            'meta_key' => 'short_title', // required when sorting by custom fields
            'order' => 'DESC', // descending order
        )
    );*/

    // hide the sort by title options
    unset ( $options['title_asc'] );
    unset ( $options['title_desc'] );

    $options['duration-s'] = array(
        'label' => 'Duration (shortest first)',
        'query_args' => array(
            'orderby' => 'meta_value_num', // sort by numerical custom field
            'meta_key' => 'total_duration', // required when sorting by custom fields
            'order' => 'ASC', // descending order
        )
    );
    return $options;
}

add_filter( 'facetwp_sort_options', 'ibio_facetwp_sort_options', 10, 2 );

add_filter( 'facetwp_facet_dropdown_show_counts', '__return_false' );

// change the indexing for facetwp so that it saves duration groups instead of numbers.
add_filter( 'facetwp_index_row', 'ibio_index_facet_duration', 10, 2);
function ibio_index_facet_duration( $params, $class ){
    // bail if it's not a duration
    if ( $params[ 'facet_name' ] != 'duration') return $params;

    $duration = $params['facet_value'];

    if ($duration < ( 15 * 60)) {
        $params[ 'facet_value'] = '15';
        $params[ 'facet_display_value'] = "0 - 15 minutes";

    } else if ( $duration < ( 30 * 60) ) {
        $params[ 'facet_value'] = '30';
        $params[ 'facet_display_value'] = "15 - 30 minutes";

    } else {
        $params[ 'facet_value'] = '60';
        $params[ 'facet_display_value'] = "30+ minutes";
    }

    return $params;

}


function ibio_facetwp_pager_html( $output, $params ) {
	$page = (int) $params['page'];
	$per_page = (int) $params['per_page'];
	$total_rows = (int) $params['total_rows'];
	$total_pages = (int) $params['total_pages'];

	$output = '';

	// Only show pagination when > 1 page
	if ( 1 < $total_pages ) {

		if ( 1 < $page ) {
			$output .= '<a class="facetwp-page previous-page" data-page="'. ($page - 1) .'">« Previous Page</a>';
		}
		if ( 1 < ( $page - 10 ) ) {
			$output .= '<a class="facetwp-page" data-page="' . ($page - 10) . '"> ... ' . ($page - 10) . '</a>';
		}
		for ( $i = 2; $i > 0; $i-- ) {
			if ( 0 < ( $page - $i ) ) {
				$output .= '<a class="facetwp-page" data-page="' . ($page - $i) . '">' . ($page - $i) . '</a>';
			}
		}

		// Current page
		$output .= '<a class="facetwp-page active" data-page="' . $page . '">' . $page . '</a>';

		for ( $i = 1; $i <= 2; $i++ ) {
			if ( $total_pages >= ( $page + $i ) ) {
				$output .= '<a class="facetwp-page" data-page="' . ($page + $i) . '">' . ($page + $i) . '</a>';
			}
		}
		if ( $total_pages > ( $page + 10 ) ) {
			$output .= '<a class="facetwp-page" data-page="' . ($page + 10) . '"> ... ' . ($page + 10) . '</a>';
		}
		if ( $page < $total_pages ) {
			$output .= '<a class="facetwp-page next-page" data-page="' . ($page + 1) . '">Next Page »</a>';
		}
	}

	return $output;
}

add_filter( 'facetwp_pager_html', 'ibio_facetwp_pager_html', 10, 2 );

// Hide Gravity Form field labels when using placeholders
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );


// Yoast Filters

add_filter( 'wpseo_schema_website', 'ibio_yoast_website_schema', 10, 1 );
function ibio_yoast_website_schema( $graph_piece ) {

	if ( is_array( $graph_piece ) && !empty( $graph_piece['potentialAction'] ) ) {
		$graph_piece['potentialAction']['target'] = site_url() . '/search-ibiology/?q={q}';
		$graph_piece['potentialAction']['query-input'] = 'required name=q';
	}


	return $graph_piece;

}

//Add pagination for arhive page
add_action( 'pre_get_posts', 'ibio_cpt_archive_items' );
function ibio_cpt_archive_items( $query ) {
if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'iBiology Podcast' ) ) {
		$query->set( 'posts_per_page', '48' );
	}

}
