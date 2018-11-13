<?php

global $talk_speaker;

$talk_speakers = new WP_Query(array(
    'post_type' => 'ibiology_speaker',
    'connected_type' => 'speaker_to_talk',
    'connected_items' => get_queried_object(),
    'nopaging' => true
  ));
if ( !empty($talk_speakers) && isset($talk_speakers->posts)) {
  $talk_speaker = $talk_speakers->posts;
}


function ibio_talks_info(){
	global $talk_speaker;
	
	
	// put the speaker info in the page title
	if ( ! empty ($talk_speaker) ) {
	  echo '<div class="post-info">With: ';
  	foreach ($talk_speaker as $s){
	  	$url = get_post_permalink($s->ID);
		  echo "<a class='speaker-link' href='$url'>" . $s->post_title . "</a>";
  	}	
  	echo '</div>';
	}
}

function ibio_lecture_header(){
  echo "<h2>Talk Overview</h2>";
}

function ibio_talks_header(){

  // Breadcrumbs
  echo '<div class="page-header"><div class="wrap">';
  genesis_do_breadcrumbs();
  genesis_do_post_title();
  echo '</div></div>';  
  
  ibio_get_template_part( 'single-talk/videos', 'container' );
  
}

function ibio_disucssion_questions(){
	ibio_get_template_part( 'shared/qa', 'talk' );
}


function ibio_talk_sidebar(){
  get_sidebar( 'talk' );
}

// include the YouTube iFrame API code in the page.  We should probably do this w/ wp_enqueue_script but it's
// not possible to include it with async defined yet.

function ibio_youtube_api(){
    echo '<script src="https://www.youtube.com/iframe_api" type="text/javascript"></script>';
}

/* -------------------  Page Rendering --------------------------*/

// force content-sidebar layout
add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );

// add filter to make the_content into expandable sections.
//add_filter( 'the_content', 'ibio_expandable_section', 200, 1);

// move the breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );



add_action('genesis_after_header', 'ibio_talks_header', 30);

// clean up post info and post meta
add_action( 'genesis_header', 'ibio_setup_single');
remove_action( 'genesis_entry_header', 'genesis_do_post_title');
if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) {
	add_action('genesis_entry_content', 'ADDTOANY_SHARE_SAVE_KIT', 4, 0);
}
add_action( 'genesis_entry_content', 'ibio_lecture_header', 5);
add_action('genesis_entry_content', 'ibio_ed_resources', 11);
add_action('genesis_entry_content', 'ibio_disucssion_questions', 12);
add_action('genesis_entry_content', 'ibio_talks_speaker', 22);


remove_action( 'genesis_entry_footer', 'genesis_post_meta');

add_action('genesis_after_entry', 'ibio_related_content', 5);

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action( 'genesis_sidebar', 'ibio_talk_sidebar' );
add_action( 'wp_footer', 'ibio_youtube_api');

genesis();