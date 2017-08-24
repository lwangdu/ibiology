<?php

global $acf_fields_helper;
$acf_fields_helper = new IBio_Fields_Display_Helper();

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
  ibio_breadcrumbs();
  genesis_do_post_title();
  echo '</div></div>';  
  
  ibio_get_template_part( 'single-talk/videos', 'container' );
  
}

function ibio_disucssion_questions(){
	ibio_get_template_part( 'shared/qa', 'talk' );
}


function ibio_talks_speaker(){
	global $talk_speaker;
  
  if ( !empty(  $talk_speaker ) ){
  	echo "<section class='speakers'><h2>Speaker Bio</h2>";
    foreach ($talk_speaker as $s){
      $url = get_post_permalink($s->ID);
      setup_postdata( $s );
      global $post;
      $post = $s;
      echo "<h3><a href='$url'>" . $s->post_title . "</a></h3>";
      echo "<figure class='alignleft photo'><a href='$url'>";
      the_post_thumbnail( 'square-thumb' );
      echo '</a></figure>';
      the_excerpt();  
    }	
    echo '</section>';

    wp_reset_postdata();
  }
}

function ibio_talk_sidebar(){
  get_sidebar( 'talk' );
}

/* -------------------  Page Rendering --------------------------*/

// force content-sidebar layout
add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );

// move the breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_before_loop', 'ibio_breadcrumbs');


add_action('genesis_after_header', 'ibio_talks_header', 30);

remove_action( 'genesis_entry_header', 'genesis_post_info');
remove_action( 'genesis_entry_header', 'genesis_do_post_title', 10 );

add_action( 'genesis_entry_content', 'ibio_lecture_header', 5);
add_action('genesis_entry_content', 'ibio_ed_resources', 11);
add_action('genesis_entry_content', 'ibio_disucssion_questions', 12);
add_action('genesis_entry_content', 'ibio_talks_speaker', 22);


remove_action( 'genesis_entry_footer', 'genesis_post_meta');

add_action('genesis_after_entry', 'ibio_related_content', 5);

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action( 'genesis_sidebar', 'ibio_talk_sidebar' );

genesis();