<?php

global $acf_fields_helper;
$acf_fields_helper = new IBio_Fields_Display_Helper();

global $talk_speaker;

$talk_speakers = new WP_Query(array(
    'post_type' => 'ibiology_speaker',
    'connected_type' => 'speaker_to_session',
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
  echo "<h2>Session Overview</h2>";
}

function ibio_talks_videos(){

  // Breadcrumbs
  echo '<aside class="breadcrumbs-strip"><div class="wrap">';
   genesis_do_breadcrumbs();
  echo '</div></aside>';  

  // get the videos
  global $videos;
  $videos = get_field( 'videos' );
  if ( empty( $videos ) ) return;
  
 foreach ( $videos as $v ) {   
 		ibio_get_template_part( 'shared/q-and-a' );
    ibio_get_template_part( 'shared/single', 'video' );
  }
}

function ibio_related_content(){
	$resources = get_field( 'related_resources' );
  
  if( !empty( $resources ) ){
    	echo "<h2>Related Resources</h2>";
      echo $resources;
  }	

	get_template_part("parts/related-talks-by-category");
	
}

function ibio_talks_speaker(){
	global $talk_speaker;
  
  if ( !empty(  $talk_speaker ) ){
  	echo "<h2>Speaker Bio</h2>";
    foreach ($talk_speaker as $s){
      $url = get_post_permalink($s->ID);
      setup_postdata( $s );
      global $post;
      $post = $s;
      echo "<h3><a href='$url'>" . $s->post_title . "</a></h3>";
      echo "<a href='$url' class='alignleft'>";
      the_post_thumbnail( 'thumbnail' );
      echo '</a>';
      the_excerpt();
    }	

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
//add_action( 'genesis_after_header', 'genesis_do_breadcrumbs', 15 );

remove_action( 'genesis_entry_header', 'genesis_post_info');
remove_action( 'genesis_entry_footer', 'genesis_post_meta');
add_action('genesis_after_header', 'ibio_talks_videos', 30);
  
//add_action('genesis_entry_header', 'ibio_talks_info', 20);
add_action( 'genesis_entry_content', 'ibio_lecture_header', 5);
add_action('genesis_entry_content', 'ibio_talks_speaker', 22);
add_action('genesis_entry_content', 'ibio_related_content', 24);

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action( 'genesis_sidebar', 'ibio_talk_sidebar' );

genesis();