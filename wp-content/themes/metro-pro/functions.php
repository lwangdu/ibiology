<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'metro', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'metro' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Metro Pro Theme', 'metro' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/metro/' );
define( 'CHILD_THEME_VERSION', '2.0.1' );


//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue Google fonts
add_action( 'wp_enqueue_scripts', 'metro_google_fonts' );
function metro_google_fonts() {
	wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Oswald:400', array(), CHILD_THEME_VERSION );
}


//* Enqueue Backstretch script and prepare images for loading
add_action( 'wp_enqueue_scripts', 'metro_enqueue_scripts' );
function metro_enqueue_scripts() {

	//* Load scripts only if custom background is being used
	if ( ! get_background_image() )
		return;

	// DMcQ: I'm setting these to load in the footer to speed up page rendering
	$loadInFooter = true;
	wp_enqueue_script( 'metro-pro-backstretch', get_bloginfo( 'stylesheet_directory' ) . '/js/backstretch.js', array( 'jquery' ), '1.0.0', $loadInFooter );
	wp_enqueue_script( 'metro-pro-backstretch-set', get_bloginfo('stylesheet_directory').'/js/backstretch-set.js' , array( 'jquery', 'metro-pro-backstretch' ), '1.0.0', $loadInFooter );

	wp_localize_script( 'metro-pro-backstretch-set', 'BackStretchImg', array( 'src' => str_replace( 'http:', '', get_background_image() ) ) );

}

//* Add custom background callback for background color
function metro_background_callback() {

	if ( ! get_background_color() )
		return;

	printf( '<style>body { background-color: #%s; }</style>' . "\n", get_background_color() );

}

//* Add new image sizes
add_image_size( 'home-bottom', 150, 150, TRUE );
add_image_size( 'home-middle', 332, 190, TRUE );
add_image_size( 'home-top', 700, 400, TRUE );

//* Add support for custom background
add_theme_support( 'custom-background', array( 'wp-head-callback' => 'metro_background_callback' ) );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 1068,
	'height'          => 122,
	'header-selector' => '.site-title a',
	'header-text'     => false
) );

//* Add support for additional color style options
add_theme_support( 'genesis-style-selector', array(
	'metro-pro-blue'  => __( 'Blue', 'metro' ),
	'metro-pro-green' => __( 'Green', 'metro' ),
	'metro-pro-pink'  => __( 'Pink', 'metro' ),
	'metro-pro-red'   => __( 'Red', 'metro' ),
) );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 1 );


//* Reposition the secondary navigation
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before', 'genesis_do_subnav' );

//* Hooks after-entry widget area to single posts
add_action( 'genesis_entry_footer', 'metro_after_post'  ); 
function metro_after_post() {
	
     if ( ! is_singular( 'post' ) )
    	 return;

     genesis_widget_area( 'after-entry', array(
	 	'before' => '<div class="after-entry widget-area"><div class="wrap">',
	 	'after'  => '</div></div>',
   ) );

}

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'metro_remove_comment_form_allowed_tags' );
function metro_remove_comment_form_allowed_tags( $defaults ) {
	
	$defaults['comment_notes_after'] = '';
	return $defaults;

}

//* Reposition the footer widgets
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
add_action( 'genesis_after', 'genesis_footer_widget_areas' );

//* Reposition the footer
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );
add_action( 'genesis_after', 'genesis_footer_markup_open', 11 );
add_action( 'genesis_after', 'genesis_do_footer', 12 );
add_action( 'genesis_after', 'genesis_footer_markup_close', 13 );

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'home-top',
	'name'        => __( 'Home - Top', 'metro' ),
	'description' => __( 'This is the top section of the homepage.', 'metro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-middle-left',
	'name'        => __( 'Home - Middle Left', 'metro' ),
	'description' => __( 'This is the middle left section of the homepage.', 'metro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-middle-right',
	'name'        => __( 'Home - Middle Right', 'metro' ),
	'description' => __( 'This is the middle right section of the homepage.', 'metro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-bottom',
	'name'        => __( 'Home - Bottom', 'metro' ),
	'description' => __( 'This is the bottom section of the homepage.', 'metro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'after-entry',
	'name'        => __( 'After Entry', 'metro' ),
	'description' => __( 'This is the after entry section.', 'metro' ),
) );


//* New code start here

// add to child theme functions.php after the call to init.php and before the closing tag (if it exists)
add_action('genesis_before_content', 'wpp_relevanssi_did_you_mean', 5);
function wpp_relevanssi_did_you_mean() {
	if ( is_search() ) {
		if (function_exists('relevanssi_didyoumean')) {
			relevanssi_didyoumean(get_search_query(), "<div id='didyoumean' style='font-size:20px; padding-bottom:10px;'>Did you mean: ", "</div>", 5);
		}
	}
}


/* DMcQ: Nov 13, 2014 -- adding these lines to try to get rid of second og:img tag inserted on video pages */
add_filter('wpseo_pre_analysis_post_content', 'mysite_opengraph_content');
function mysite_opengraph_content($val) {
    return '';
}
/* .DMcQ */

// Hide post title

add_filter( 'be_title_toggle_post_types', 'be_types' );
function be_types( $types ) {
	$types[] = 'post';
	return $types;
}



function create_custom_taxonomies()
{
    register_taxonomy('length', 'post', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Length',
            'singlur_name' => 'length',
            'all_items' => 'All Lengths',
            'edit_item' => 'Edit Length',
            'view_item' => 'View Length',
            'update_item' => 'Update Length',
            'add_new_item' => 'Add New Length',
            'new_item_name' => 'New Length Name',
            'parent_item' => 'Parent Length',
            'parent_item_colon' => "Parent Length: ",
            'search_items' => 'Search Lengths',
            'popular_items' => 'Populuar Lengths',
            'separate_items_with_commas' => 'Separate lengths with commas',
            'add_or_remove_items' => 'Add or remove lengths',
            'choose_from_most_used' => 'Choose from most used lengths',
            'not_found' => 'No lengths found.',
            'menu_name' => 'Length',
        ),
        'rewrite' => array(
            'slug' => 'length',
            'hierarchical' => true,
        )
    ));
    
    register_taxonomy('level', 'post', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Level',
            'singlur_name' => 'level',
            'all_items' => 'All Levels',
            'edit_item' => 'Edit Level',
            'view_item' => 'View Level',
            'update_item' => 'Update Level',
            'add_new_item' => 'Add New Level',
            'new_item_name' => 'New Level Name',
            'parent_item' => 'Parent Level',
            'parent_item_colon' => "Parent Level: ",
            'search_items' => 'Search Levels',
            'popular_items' => 'Populuar Levels',
            'separate_items_with_commas' => 'Separate levels with commas',
            'add_or_remove_items' => 'Add or remove levels',
            'choose_from_most_used' => 'Choose from most used levels',
            'not_found' => 'No levels found.',
            'menu_name' => 'Level',
        ),
        'rewrite' => array(
            'slug' => 'level',
            'hierarchical' => true,
        )
        
        
    ));
    
    register_taxonomy('topics', 'post', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Topics',
            'singlur_name' => 'topic',
            'all_items' => 'All Topic',
            'edit_item' => 'Edit Topics',
            'view_item' => 'View Topics',
            'update_item' => 'Update Topics',
            'add_new_item' => 'Add New Topic',
            'new_item_name' => 'New Topic Name',
            'parent_item' => 'Parent Topic',
            'parent_item_colon' => "Parent Topic: ",
            'search_items' => 'Search Topics',
            'popular_items' => 'Populuar Topics',
            'separate_items_with_commas' => 'Separate topics with commas',
            'add_or_remove_items' => 'Add or remove topics',
            'choose_from_most_used' => 'Choose from most used topics',
            'not_found' => 'No topics found.',
            'menu_name' => 'Topics',
        ),
        'rewrite' => array(
            'slug' => 'topics',
            'hierarchical' => true,
        )
        
        
    ));
    
    register_taxonomy('English Subtitles', 'post', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'English Subtitles',
            'singlur_name' => 'English Subtitles',
            'all_items' => 'All English Subtitles',
            'edit_item' => 'Edit English Subtitles',
            'view_item' => 'View English Subtitles',
            'update_item' => 'Update English Subtitles',
            'add_new_item' => 'Add New English Subtitle',
            'new_item_name' => 'New English Subtitle Name',
            'parent_item' => 'Parent English Subtitle',
            'parent_item_colon' => "Parent English Subtitle: ",
            'search_items' => 'Search English Subtitles',
            'popular_items' => 'Populuar English Subtitles',
            'separate_items_with_commas' => 'Separate english subtitles with commas',
            'add_or_remove_items' => 'Add or remove english subtitles',
            'choose_from_most_used' => 'Choose from most used english subtitles',
            'not_found' => 'No english subtitles found.',
            'menu_name' => 'English Subtitles',
        ),
        'rewrite' => array(
            'slug' => 'english-subtitles',
            'hierarchical' => true,
        )
        
        
    ));
    
    register_taxonomy('educator resources', 'post', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Educator Resources',
            'singlur_name' => 'Educator Resource',
            'all_items' => 'All Educator Resources',
            'edit_item' => 'Edit Educator Resources',
            'view_item' => 'View Educator Resources',
            'update_item' => 'Update Educator Resources',
            'add_new_item' => 'Add New Educator Resources',
            'new_item_name' => 'New Educator Resournce Name',
            'parent_item' => 'Parent Educator Resource',
            'parent_item_colon' => "Parent Educator Resourc: ",
            'search_items' => 'Search Educator Resources',
            'popular_items' => 'Populuar Educator Resources',
            'separate_items_with_commas' => 'Separate educator resources with commas',
            'add_or_remove_items' => 'Add or remove educator resources',
            'choose_from_most_used' => 'Choose from most used educator resources',
            'not_found' => 'No educator resources found',
            'menu_name' => 'Educator Resources',
        ),
        'rewrite' => array(
            'slug' => 'educator-resources',
            'hierarchical' => true,
        )
        
        
    ));
    
    
    
}

add_action('init', 'create_custom_taxonomies', 0);




remove_action('genesis_entry_content', 'genesis_do_post_content');
add_action( 'genesis_entry_content', 'metro_pro_post' );
/**
 * Echo the post content.
 *
 * On single posts or pages it echoes the full content, and optionally the
 * trackback string if they are enabled. On single pages, also adds the edit
 * link after the content.
 *
 * Elsewhere it displays either the excerpt, limited content, or full content.
 *
 * Pagination links are included at the end, if needed.
 *
 * @since 1.1.0
 *
 * @uses genesis_get_option() Get theme setting value
 * @uses the_content_limit() Limited content
 */
function metro_pro_post() {

	global $post;
 
    if ( is_singular() ) {
        
		
		//DMcQ 3/2/15 - adding div for sharethis 
		if ( is_front_page() ==false){
			echo '<div class="addthis_sharing_toolbox"></div><br/>';
		}
		
        the_content();
		
		if ( is_front_page() ==false){
			echo '<br/><div class="addthis_sharing_toolbox"></div>';
		}

        if ( is_single() && 'open' == get_option( 'default_ping_status' ) && post_type_supports( $post->post_type, 'trackbacks' ) ) {
            echo '<!--';
            trackback_rdf();
            echo '-->' . "\n";
        }

        if ( is_page() && apply_filters( 'genesis_edit_post_link', true ) )
            edit_post_link( __( '(Edit)', 'genesis' ), '', '' );
    }
    elseif ( 'excerpts' == genesis_get_option( 'content_archive' ) ) {
        
		if (is_category('blog')){
			echo '<div >';
			echo '<div class="blogPostThumb">';
			the_post_thumbnail();
			echo '</div>';
			echo '<div class="blogPostExcerpt">';
			the_excerpt();
			echo '</div>';
			echo '<div style="clear:both;"></div>';
			echo '</div>';
		} else {
		
            $shortdesc = get_post_meta($post->ID, '_yoast_wpseo_metadesc', true);
            $shortdesc = wp_trim_words( $shortdesc, 70 );
			
            $duration = get_post_meta($post->ID, 'duration', true);
        
            $talk_type_string = wp_get_post_terms($post->ID, 'length', array('field'=>'name'));
        
            $talk_type = explode('(', trim($talk_type_string[0] -> name));
            
            echo '<b>Description: </b>' . $shortdesc . '<br/>' ;
			
			echo '<div class="searchItemPropsGroup">';
			echo '<div class="searchItemProps">';
            echo 'Length: ' . $duration . '<br/>' ;
            echo 'Type: ' . $talk_type[0] . '<br/>' ;
            
			 $eng_sub = wp_get_post_terms($post->ID, 'English Subtitles');
			(empty($eng_sub)) ? $eng_sub = "no" : $eng_sub = "yes";
			
			 $edu_res = wp_get_post_terms($post->ID, 'educator resources');
			(empty($edu_res)) ? $edu_res = "no" : $edu_res = "yes";
			
			$topic_string = wp_get_post_terms($post->ID, 'topics', array('field'=>'name'));  
			if($topic_string!="")
			{    
            	$topicArr = explode('(', trim($topic_string[0] -> name));
				$topicName = ucfirst ($topicArr[0]);
			}
			else
			{
				$topicName = "";
			}
			
			         
            echo 'Topic: ' . $topicName . '<br/>' ;
			echo '</div>';
			echo '<div class="searchItemProps">';
            echo 'English Subtitles: ' . $eng_sub . '<br/>' ;
            echo 'Educator Resources: ' . $edu_res . '<br/>' ;
			echo '</div>';
			echo '</div>';
		}
    }
    else {
        if ( genesis_get_option( 'content_archive_limit' ) )
            the_content_limit( (int) genesis_get_option( 'content_archive_limit' ), __( '[Read more...]', 'genesis' ) );
        else
            the_content( __( '[Read more...]', 'genesis' ) );
    }

    wp_link_pages( array( 'before' => '<p class="pages">' . __( 'Pages:', 'genesis' ), 'after' => '</p>' ) );

}

add_filter( 'the_content_more_link', 'sp_read_more_link' );
function sp_read_more_link() {
	return '<a class="more-link" href="' . get_permalink() . '">[Continue Reading]</a>';
}


remove_action('genesis_post_title', 'genesis_do_post_title');
add_action( 'genesis_entry_header', 'metro_pro_do_post_title' );
/**
 * Echo the title of a post.
 *
 * The genesis_post_title_text filter is applied on the text of the title, while
 * the genesis_post_title_output filter is applied on the echoed markup.
 *
 * @since 1.1.0
 *
 * @return null Returns early if the length of the title string is zero
 */
function metro_pro_do_post_title() {
	
    global $post;

    //$title = apply_filters( 'genesis_post_title_text', get_the_title() );
    $title = get_post_meta($post->ID, '_genesis_title', true);
    $title = get_post_meta($post->ID, '_genesis_title', true) ? get_post_meta($post->ID, '_genesis_title', true) : get_post_meta($post->ID, '_yoast_wpseo_title', true);
            
    $title = $title ? $title : get_the_title();

    if ( 0 == strlen( $title ) )
        return;

    if ( is_singular() )
        $title = sprintf( '<h1 class="entry-title"></h1>', $title );
    elseif ( apply_filters( 'genesis_link_post_title', true ) )
        $title = sprintf( '<h4 class="entry-title"><a href="%s" title="%s" rel="bookmark">%s</a></h2>', get_permalink(), the_title_attribute( 'echo=0' ), apply_filters( 'genesis_post_title_text', $title ) );
    else
        $title = sprintf( '<h2 class="entry-title">%s</h2>', $title );

    echo apply_filters( 'genesis_post_title_output', "$title \n" );

}


remove_action('genesis_before_post_content', 'genesis_post_info');
add_filter( 'metro_pro	_post_info', 'do_shortcode', 20 );
add_action( 'genesis_entry_footer', 'metro_pro_post_info' );
/**
 * Echo the post info (byline) under the post title.
 *
 * Doesn't do post info on pages.
 *
 * The post info makes use of several shortcodes by default, and the whole
 * output is filtered via genesis_post_info before echoing.
 *
 * @since 0.2.3
 *
 * @global stdClass $post Post object
 * @return null Returns early if on a page
 */
function metro_pro_post_info() {

    global $post;

    if ( 'page' == get_post_type( $post->ID ) )
        return;
if(is_singular()){
    $post_info = '[post_date]';
    printf( '<div class="post-info">%s</div>', apply_filters( 'genesis_post_info', $post_info ) );
}
}


add_filter( 'metro_post_meta', 'do_shortcode', 20 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
add_action('genesis_entry_footer', 'metro_pro_post_meta');
/**
 * Echo the post meta after the post content.
 *
 * Doesn't do post meta on pages.
 *
 * The post info makes use of a couple of shortcodes by default, and the whole
 * output is filtered via genesis_post_meta before echoing.
 *
 * @since 0.2.3
 *
 * @global stdClass $post Post object
 * @return null Returns early if on a page
 */
function metro_pro_post_meta() {

    global $post;

    if ( 'page' == get_post_type( $post->ID ) ){
        return;
	}
	if(is_singular()){
   	 	$post_meta = '[post_categories] [post_tags]';
    	printf( '<div class="post-meta">%s</div>', apply_filters( 'genesis_post_meta', $post_meta ) );
	}
}


//function that excludes posts of the category Teaching Tools for Seminars
add_filter('relevanssi_hits_filter', 'exclude_ttfs');
function exclude_ttfs($hits) {
	
    $included_posts = array();
    foreach($hits[0] as $hit){
        $ttfsterm = false;
        foreach(get_the_category($hit->ID) as $cat){
            if($cat->cat_ID != 123) {
                $ttfsterm = true;
                break;
            }
        }
        if($ttfsterm){
            array_push($included_posts, $hit);
        }
    }
    $hits[0] = $included_posts;
    return $hits;
   
}

//function that excludes posts with Assessments in post title
add_filter('relevanssi_hits_filter', 'exclude_assessments');
function exclude_assessments($hits){
    $non_assessments = array();
    foreach($hits[0] as $hit){
        $ttitle = get_the_title($hit);
        if(strpos($ttitle, 'Assessments') !== false){
            continue;
        }else{
            array_push($non_assessments, $hit);
        }
    }
    $hits[0] = $non_assessments;
    return $hits;
    
}

//function to exclude Course Materials posts from results
add_filter('relevanssi_hits_filter', 'exclude_course_mats');
function exclude_course_mats($hits){
    $non_course_mats = array();
    foreach($hits[0] as $hit){
        $ttitle = get_the_title($hit);
        if(strpos($ttitle, 'Course Materials: Week') !== false){
            continue;
        }else{
            array_push($non_course_mats, $hit);
        }    
    }
    $hits[0] = $non_course_mats;
    return $hits;
    
}

//fucntion to exlude discovery and answers posts
add_filter('relevanssi_hits_filter', 'exclude_discov_answers');
function exclude_discov_answers($hits){
    $non_disc_aswr = array();
    foreach($hits[0] as $hit){
        $ttitle = get_the_title($hit);
        if(strpos($ttitle, 'Discovery Talk Questions and Answers') !== false){
            continue;
        }else{
            array_push($non_disc_aswr, $hit);
        }    
    }
    $hits[0] = $non_disc_aswr;
    return $hits;

}

add_filter( 'xmlrpc_methods', function( $methods ) {
unset( $methods['pingback.ping'] );
return $methods;
} ); 

// Content width
global $content_width;
$content_width = apply_filters( 'content_width', 600, 430, 850 );

add_filter( 'embed_defaults', 'wps_embed_defaults' );
/**
 * Changes the default embed sizes based on site layout
 * via filtering wp_embed_defaults()
 *
 * @author Travis Smith
 *
 * @param array height and width keys, values can be string/int
 * @return array height and width keys and values
 */
function wps_embed_defaults( $defaults ) {  
 
    switch ( genesis_site_layout() ) {
 
        case 'full-width-content':
            $defaults = array( 'width'  => 1068, 'height' => 600 );
            break;
        case 'content-sidebar':
        case 'sidebar-content':
            $defaults = array( 'width'  => 700, 'height' => 400 );
            break;
        case 'content-sidebar-sidebar':
        case 'sidebar-content-sidebar':
        case 'sidebar-sidebar-content':
            $defaults = array( 'width'  => 430, 'height' => 300 );
            break;
        default:
            break;
    }
    return $defaults; 
 
}

/* IBIO SEARCH PAGE UPGRADES */
/* DMcQ: Adding functions there to help support an image-grid search with filters. 
	At some point this page will support closeable tags indicating current filters, similar to how TED works.*/

/* Adding 'grid' format for searches */
add_filter('query_vars', 'insert_ib_results_format');

function insert_ib_results_format($vars){
   $vars[]= "ib_results_format";
   return $vars;
}


/* IBIO OPTIMIZATIONS */
/* DMcQ: Updates to help optimize how page loads resources */
add_filter("sidebar_login_js_in_footer", "load_sidebar_login_js_in_footer");
function load_sidebar_login_js_in_footer(){
	return true; //moves js to footer.
}


/* SEARCH PAGE COOKIES */
/* Check if user has selected list/grid before, and 
   if so use that setting. If there's no setting,
   use the current selection if it exists.
*/
add_action( 'pre_get_posts', 'init_search_cookie' );
function init_search_cookie($query) {
	
	if ($query->is_main_query() && is_search()){
		$resultsFmtCookie = 'ib_results_format';
		$fmtQueryVar = 'ib_results_format';
		$defaultFmt = 'list';
		$fmt = get_query_var($fmtQueryVar);
		if ($fmt==null){
			$cookieFmt = $_COOKIE[$resultsFmtCookie];
			if ($cookieFmt==null){
				$fmt = $defaultFmt;
			} else {
				$fmt = $cookieFmt;
			}
			$query->set($fmtQueryVar, $fmt);
		}
		setcookie( $resultsFmtCookie, $fmt, 30 * DAYS_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}
	
}

/* Search page helper javascript */

/* Add in a special script to hold some search-page specific scripts */
function ibio_helper_scripts() {
	wp_enqueue_script(
		'ibio-search-helper',
		get_bloginfo( 'stylesheet_directory' ) . '/js/ibio-search-helper.js',
		array('jquery'),
		null,
		true
	);
}
add_action('wp_enqueue_scripts', 'ibio_helper_scripts');



/* DMCQ: TESTING OUT GTM-BASED TRACKING OF YOUTUBE EVENTS */

/* 	DMcQ: Adding in required params for youtube API */
/*
add_filter( "oembed_result", "dmcq_add_youtube_params", 10, 3 );
function dmcq_add_youtube_params( $return, $url, $data ) {
	if ( false !== strpos( $return, "youtube.com" ) ) {
	  return str_replace( "feature=oembed", "feature=oembed&enablejsapi=1&origin=" . site_url(), $return );
	} else {
	  return $return;
	}
}
*/


/* DMcQ: Inject Google Tag Manager code - June 24 2016 */

/* We're injecting GTM directly rather than using gtm4wp, as we want a simpler implementation
   and more manual configuration of how GTM is working within WP.
   
   Set this action to priority 1 so it comes *right* after the header (and before genesis nav)
   
 */

add_action('genesis_before', 'google_tag_manager', 1);

function google_tag_manager() { ?>
    <!-- DMCQ : Google Tag Manager -->
    <noscript>
        <iframe src="//www.googletagmanager.com/ns.html?id=GTM-M5BZTX"
    height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <script>
	(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-M5BZTX');
    </script>
    <!-- End Google Tag Manager -->
<?php
}












