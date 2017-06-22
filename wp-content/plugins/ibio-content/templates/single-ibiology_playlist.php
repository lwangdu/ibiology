<?php

global $acf_fields_helper;
$acf_fields_helper = new IBio_Fields_Display_Helper();

global $talks;

function ibio_playlist_talks() {
  global $talks;
  
  if ( $posts->have_post() ) {
    $talks = $posts->posts;
  } else {
    $talks = null;
  }
  return $talks;
}

function ibio_playlist_info(){
	global $talks;
	
	if (! empty( $talks ) && is_array( $talks ) ){
	  echo '<div class="post-info">' . count( $talks ) . ' talks</div>';
	} 
}

function ibio_playlist_details(){
	global $acf_fields_helper;
	$acf_fields_helper->show_field_group(32397);
}

function ibio_related_content(){
	global $acf_fields_helper;
	echo "<h2>Related Information</h2>";
	$acf_fields_helper->show_field_group(32376);
}

function ibio_talks_playlist(){

  $talks = new WP_Query(array(
    'post_type' => 'ibiology_talk',
    'connected_type' => 'playlist_to_talks',
    'connected_items' => get_queried_object(),
    'nopaging' => true
  ));
  if ( $talks->have_posts( ) ) {
    foreach ( $talks->posts as $s){
      $url = get_post_permalink($s->ID);
      echo "<h3><a href='$url'>" . $s->post_title . "</a></h3>" . get_the_post_thumbnail($s->ID, 'thubmanil');
    }	
  }
}

/* -------------------  Page Rendering --------------------------*/

add_action('genesis_entry_header', 'ibio_playlist_info', 20);
add_action('genesis_entry_content', 'ibio_talks_playlist', 20);
add_action('genesis_entry_content', 'ibio_related_content', 21);

genesis();