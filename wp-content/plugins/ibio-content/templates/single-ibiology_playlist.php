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
	ibio_get_template_part('shared/related', 'resources');
}

function ibio_talks_playlist(){

  $talks = new WP_Query(array(
    'post_type' => 'ibiology_talk',
    'connected_type' => 'playlist_to_talks',
    'connected_items' => get_queried_object(),
    'nopaging' => true
  ));
  if ( $talks->have_posts( ) ) {
  	echo '<ul class="talks grid">';
    while($talks->have_posts()){
    	$talks->the_post();
      get_template_part( 'parts/list-talk');
    }	
    echo '</ul>';
    wp_reset_query();
  }
}

/* -------------------  Page Rendering --------------------------*/

// force content-sidebar layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );


add_action('genesis_entry_header', 'ibio_playlist_info', 20);
add_action('genesis_after_entry', 'ibio_talks_playlist', 20);
add_action('genesis_after_entry', 'ibio_related_content', 21);

genesis();