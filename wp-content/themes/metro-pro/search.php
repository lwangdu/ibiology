<?php

/* 	DMcQ - Feb 17, 2016 
	This search.php is customized to provide more advanced features for searching, including switching
	between list and grid, adding filters, and so on.	
*/


/* Add some divs to scope our styles */
function begin_ibio_search_page_div(){
	echo "<div class='ibio-search'>";	
}
function end_ibio_search_page_div(){
	echo "</div> <!-- / .ibio-search -->";	
}
add_filter('genesis_before_content_sidebar_wrap','begin_ibio_search_page_div');
add_filter('genesis_after_content_sidebar_wrap','end_ibio_search_page_div');


/* Make sure search results are full-width.*/

function make_search_results_full_width() {
	global $wp_query;   
    if( $wp_query->is_search) {
        return 'full-width-content';
    }
}
add_filter( 'genesis_site_layout', 'make_search_results_full_width' );


/* Write out search controls */

add_action('genesis_before_content', 'create_search_controls');
function create_search_controls(){	
	the_widget('ibio_searchpage_widget'); 
}	


/* Write the search results header.*/

add_action( 'genesis_before_loop', 'metro_do_search_title' );
function metro_do_search_title() {
	
	global $post;	
	
	$title = sprintf( '<h1 style=“margin-bottom:20px” class="archive-title">%s %s</h1>', apply_filters( 'metro_search_title_text', __( 'Search Results for:', 'metro' ) ), get_search_query() );
		
	$resultsFormat = get_query_var('ib_results_format', 'list');

	echo "<div class='ibio-search-results-header'>";	
	echo "<div class='toggle-display-format switch-field'>";
	echo '<input type="radio" id="toggle_list" name="ib_results_format" value="list" onclick="submit()" ' . (($resultsFormat=='list') ? 'checked' : '') . ' />';
    echo '<label for="toggle_list"><i class="fa fa-list"></i></label>';
   	echo '<input type="radio" id="toggle_grid" name="ib_results_format" value="grid" onclick="submit()" ' . (($resultsFormat=='grid') ? 'checked' : '') . ' />';
    echo '<label for="toggle_grid"><i class="fa fa-th"></i></label>';
	echo "</div>"; 
	echo apply_filters( 'metro_search_title_output', $title );
	echo "</div>"; 
	echo "</form>";
}


/* Setup div to style grid results */

add_action( 'loop_start', 'add_grid_div' );
add_action( 'loop_end', 'end_grid_div' );
function add_grid_div(){
	$resultsFormat = get_query_var('ib_results_format', 'list');
	if ($resultsFormat=='grid'){
		echo "<div class='ibio-search-results-grid'>";
	}
}
function end_grid_div(){
	$resultsFormat = get_query_var('ib_results_format', 'list');
	if ($resultsFormat=='grid'){
		echo "</div> <!-- / .search-results-grid -->";
	}
}



/* Get rid of standard stuff so we can write search results */
remove_action('genesis_entry_header', 'metro_pro_do_post_title');
remove_action('genesis_entry_content', 'metro_pro_post');

remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_content', 'ibio_do_search_loop' );
function ibio_do_search_loop(){
	
	//values for list format should be 'list' or 'grid' ... TODO: way to formalize this flag better, e.g. static vars

	global $post;	
	$resultsFormat = get_query_var('ib_results_format', 'list');
	
	if ($resultsFormat == "grid"){
		writeGridResult();		
	} else {
		writeListResult();
	}

	wp_link_pages( array( 'before' => '<p class="pages">' . __( 'Pages:', 'genesis' ), 'after' => '</p>' ) );
	
}

function get_item_title(){
	global $post; 
    $title = get_post_meta($post->ID, '_genesis_title', true) ? get_post_meta($post->ID, '_genesis_title', true) : get_post_meta($post->ID, '_yoast_wpseo_title', true);
            
    $title = $title ? $title : get_the_title();
	$title = sprintf( '<a href="%s" title="%s" rel="bookmark">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), apply_filters( 'genesis_post_title_text', $title ) );
	return $title;
}

function get_item_short_description() {	
	global $post;	
	// Collect variables
	$shortdesc = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true);
	$shortdesc = wp_trim_words( $shortdesc, 70 );
	return $shortdesc;
}

function get_talk_type(){
	
	global $post;	
	
	$talk_type_string = wp_get_post_terms($post->ID, 'length', array('field'=>'name'));
	$talk_type = explode('(', trim($talk_type_string[0] -> name));
	
	return $talk_type[0];
}


function writeGridResult(){
	
	global $post;
	
	//collect metadata about search item result
	$title =  get_item_title();
	$duration = get_post_meta($post->ID, 'duration', true);
	$talk_type = get_talk_type();
	$speaker = get_the_term_list( $post->ID, 'speakers', 'Speaker: ', ', ' );
	
	$eng_sub = wp_get_post_terms($post->ID, 'English Subtitles');
	(empty($eng_sub)) ? $eng_sub = "no" : $eng_sub = "yes";
	
	$edu_res = wp_get_post_terms($post->ID, 'educator resources');
	(empty($edu_res)) ? $edu_res = "no" : $edu_res = "yes";
	
	$topics = wp_get_post_terms($post->ID, 'topics', array('field'=>'name'));  
	
	/*
	if($topics!="")
	{    
		$topicArr = explode('(', trim($topics[0] -> name));
		$topic_name = ucfirst ($topicArr[0]);
	}
	*/
	
	if($topics!="")
	{    
	
		//set the topic name with the first one there, but we might update it below...
		//$topicArr = explode('(', trim($topics[0] -> name));	//what is this for?
		$topic_name = ucfirst(trim($topics[0]->name));
		
		// make sure to show the "right" topic if more than one
		// exists for this post *and* the user searched on a particular topic
		if (count($topics)>1){
			$p_topic_id  = get_query_var('p_topics');	
			if (!empty($p_topic_id) && $p_topic_id!="-1"){		
				$topic_search_term = get_term_by( 'id', $p_topic_id, 'topics' );
				if (!empty($topic_search_term)){
					$topic_search_term_name = $topic_search_term->name; 
					foreach ( $topics as $term ) {
						if($topic_search_term_name == $term->name){							
							$topic_name = ucfirst(trim($topic_search_term_name));
							break;
						}
					}					
				}							
			} 
		}	
	}
	
	
	if ($talk_type==""){
		$talk_type="(none)";
	}
	if ($topic_name==""){
		$topic_name = "(none)";
	}
		
	echo '<div class="vidBoxHolder">';
	
	$link = sprintf ('<a href="%s" title="%s" rel="bookmark">', get_permalink(), the_title_attribute( 'echo=0' ));
	echo '<div class="vidBox">';	
	echo $link;
	echo '<div class="vidThumb">';
	if ( has_post_thumbnail() ){
		echo get_the_post_thumbnail( $post->ID );
	} else {
		echo '<img src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/default-video-thumb.png" alt="No video image available" title="iBiology Logo" />';
	}	
	
	echo '</div><!--/.vidThumb-->';	
	echo '</a>';
	echo '<div class="vidTitle">' . $title . '</div>';
	echo '<div class="vidDetails">';	
	/* uncomment this when we're ready to turn on speaker custom tax
	if ($speaker!=""){
		echo 'Speaker: ' . $speaker . '<br/>';	
	}
	*/
	
	echo '<div>Length: ' . $duration . '</div>' ;
	echo '<div>Type: ' . $talk_type . '</div>' ;
	echo '<div>Topic: ' . $topic_name . '</div>' ;	
	echo '<div>English Subtitles: ' . $eng_sub . '</div>' ;
	echo '<div>Educator Resources: ' . $edu_res . '</div>' ;
	echo '</div><!-- ./vidDetails -->';
	echo '</div><!-- ./vidBox -->';
	echo '</div><!-- ./vidBoxHolder -->';
	
}


function writeListResult(){
	global $post;
	
	//collect metadata about search item result
	$title =  get_item_title();
	$shortdesc = get_item_short_description();
	$duration = get_post_meta($post->ID, 'duration', true);
	$talk_type = get_talk_type();
	
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
				
	echo '<b>' . $title . '</b><br/>';		
	echo '<b>Description: </b>' . $shortdesc . '<br/>' ;
	
	echo '<div class="searchItemPropsGroup">';
	
	echo '<div class="searchItemProps">';
	echo 'Length: ' . $duration . '<br/>' ;
	echo 'Type: ' . $talk_type . '<br/>' ;
	echo 'Topic: ' . $topicName . '<br/>' ;
	echo '</div>';
	
	echo '<div class="searchItemProps">';
	echo 'English Subtitles: ' . $eng_sub . '<br/>' ;
	echo 'Educator Resources: ' . $edu_res . '<br/>' ;
	echo '</div>';
	
	echo '</div>';
}


genesis();