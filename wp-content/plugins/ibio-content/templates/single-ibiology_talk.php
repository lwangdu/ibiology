<?php

global $acf_fields_helper;
$acf_fields_helper = new IBio_Fields_Display_Helper();

global $talk_speaker;

function ibio_talks_info(){
	global $talk_speaker;
	
	$talk_speakers = new WP_Query(array(
			'post_type' => 'ibiology_speaker',
			'connected_type' => 'speaker_to_talk',
			'connected_items' => get_queried_object(),
  		'nopaging' => true
		));
	
	$talk_speaker = $talk_speakers->posts;
	
	// put the speaker info in the page title
	if ( ! empty ($talk_speaker) ) {
	  echo '<div class="post-info">With :';
  	foreach ($talk_speaker as $s){
	  	$url = get_post_permalink($s->ID);
		  echo "<a class='speaker-link' href='$url'>" . $s->post_title . "</a>";
  	}	
  	echo '</div>';
	}
}

function ibio_talks_videos(){

  // get the videos
  
  $videos = get_field( 'videos' );
  if ( is_array( $videos ) ) {
    echo '<section class="videos row"><div class="wrap">';
    genesis_do_breadcrumbs();
    
      $counter = 1; // count the parts;
      $titles = array();
  
      $num_parts = count( $videos );
      
      foreach( $videos as $v ){
        $title = isset( $v[ 'part_title' ] ) ?  esc_attr( $v[ 'part_title' ] ) : '';
        if ( $num_parts > 1 ){
          $title = "Part $counter: " . $title;
        }
        $titles[ $counter ] = $title;
        $length = isset( $v[ 'video_length' ] ) ?  '<span class="length">' . esc_attr( $v[ 'video_length' ] ) . '</span>' : '';
        $download = isset( $v[ 'video_download_url' ] ) ?  esc_url( $v[ 'video_download_url' ] ) : '';
        $size = isset( $v[ 'download_size' ] ) ?  '<span class="size">' . esc_attr( $v[ 'download_size' ] ) . '</span>' : '';
        $download_link = ! empty ( $download ) ? "<a href='$download'>Download $size</a>" : '';
        $transcript = isset( $v[ 'transcript' ] ) ? '<div class="transcript"><div class="toggle">View Transcript</div><div class="content" style="display:none">' . esc_html( $v[ 'transcript' ] ) . '</div></div>' : '';
        $video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';
        echo "<div class='single-video part-$counter'><h2 class='title'>$title</h2><div class='content'>";
        echo wp_oembed_get( $video_url , array( 'width' => '800' ) );
        echo '</div><div class="footer">';
        echo "<span class='video-length' data-length='$length'>$length</span>";
        echo "<span class='video-part-download'><a href='$download'>Download Hi-Res</a></span>";
        echo "<span class='video-part-transcript-toggle' data-part='$counter'>Transcript</span>";
        echo "<div id='video-part-transcript-$counter' class='video-part-transcript' style='display:none'>$transcript</div>";
        echo '</div></div>';
        $counter++;
        
      }
      
      if ( $num_parts > 1 ){
        $tabs = '<ul class="videos-nav">';
        for( $i = 1 ; $i < $counter ; $i++ ){
          $title =  $titles[ $i ];
          $tabs .= "<li class='part-$i' data-select='part-$i'>$title</li>";
        }
        $tabs .= "</ul>";
        echo $tabs;
      }
            
    echo '</wrap></section>';
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
      echo "<h3><a href='$url'>" . $s->post_title . "</a></h3>" . $s->post_content;
    }	

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

add_action('genesis_after_header', 'ibio_talks_videos', 30);
  
add_action('genesis_entry_header', 'ibio_talks_info', 20);

add_action('genesis_entry_content', 'ibio_talks_speaker', 22);
add_action('genesis_entry_content', 'ibio_related_content', 24);

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action( 'genesis_sidebar', 'ibio_talk_sidebar' );

genesis();