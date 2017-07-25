<?php

global $videos;

if ( is_array( $videos ) ) {
  echo '<section class="videos row"><div class="wrap">';
 
    global $talk_speaker;
 
    if ( isset( $talk_speaker) ){
      foreach( $talk_speaker as $s ){
        $speakers .= "<span class='speaker-name'>{$s->post_title}</span>";
      }
    }
    
    $counter = 1; // count the parts;
    $titles = array();
    $thumbs = array();

    $num_parts = count( $videos );
    echo '<div class="videos-container">';
    foreach( $videos as $v ){
      $title = isset( $v[ 'part_title' ] ) ?  esc_attr( $v[ 'part_title' ] ) : '';
      if ( $num_parts > 1 ){
        $title = "Part $counter: " . $title;
      }
      $titles[ $counter ] = $title;
      $length = isset( $v[ 'video_length' ] ) ?  '<span class="length">Duration: ' . esc_attr( $v[ 'video_length' ] ) . '</span>' : '';
      $download = isset( $v[ 'video_download_url' ] ) ?  esc_url( $v[ 'video_download_url' ] ) : '';
      $size = isset( $v[ 'download_size' ] ) ?  '<span class="size">' . esc_attr( $v[ 'download_size' ] ) . '</span>' : '';
      $download_link = ! empty ( $download ) ? "<a href='$download'>Download $size</a>" : '';
      $transcript = isset( $v[ 'transcript' ] ) ? '<span class="transcript toggle" data-toggle="video-part-transcript-'. $counter .'">View Transcript</span><div id="video-part-transcript-'. $counter .'" class="drawer" style="display:none">' .  $v[ 'transcript' ] . '</div>' : '';
      $video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';
      $video_thumbnail = isset( $v[ 'video_thumbnail' ] ) ? $v[ 'video_thumbnail' ] : '';
      
      // video thumbnail is an array.  Let's grab the thumbnail size of this image.
      if ( is_array( $video_thumbnail ) && isset( $video_thumbnail['sizes'] ) && isset( $video_thumbnail['sizes']['thumbnail'] ) ){
        $thumbs[ $counter ] = $video_thumbnail['sizes']['thumbnail']; 
      }
      
      echo "<div class='single-video part-$counter'><header><h2 class='title'>$title</h2>$speakers</header><div class='content'>";
      $embed = wp_oembed_get( $video_url , array( 'height' => 475 ) );
      // attach the showinfo parameter to the oembed.      
      $embed = preg_replace( '/src="(.+)oembed"/', 'src="$1oembed&showinfo=0"', $embed );
      echo $embed;
      
      echo '</div><div class="footer"><div class="row controls">';
      echo "<span class='video-length' data-length='$length'>$length</span>";
      echo "<span class='video-part-download'><a href='$download'>Download Hi-Res</a></span>";
      echo $transcript;
      echo '</div></div></div>';
      $counter++;
      
    }
    echo '</div>';
    
    echo '<div class="videos-info">';
    
    
    if ( $num_parts > 1 ){
      echo '<header>Videos in this Talk</header>';
      $tabs = '<ul class="videos-nav">';
      for( $i = 1 ; $i < $counter ; $i++ ){
        $title =  $titles[ $i ];
        $thumb = isset( $thumbs[ $i] ) ? '<img src="'. $thumbs[ $i] .'" alt="' . $title . '"/>' : '';
        
        $tabs .= "<li class='part-$i' data-select='part-$i'><figure>$thumb</figure>$title</li>";
      }
      $tabs .= "</ul>";
      echo $tabs;
    }  
    $translations = get_field( 'translations' );
    $languages = '';
    if ( is_array( $translations ) ) {
      $languages .= '<div class="row"><span class="toggle" data-toggle="translations">Translated Versions</span>';
      $languages .= '<ul class="translations">';
      foreach( $translations as $t )  {
        $url = get_permalink( $t[ 'translated_talk'] );
        $languages .= "<li><a href='$url'>{$t['language']}</a></li>";
      }
      $languages .= "</ul></div>";
    }
      
    $date_recorded = '';
    $month_field = get_field_object( 'date_recorded_month' );
    // get the label for the month, rather than the number.
    // error_log( serialize( $month_field['choices'] ) );
    
    $month = $month_field['choices'][$month_field[ 'value' ] ];

    $year = get_field( 'date_recorded_year' );

    if ( !empty( $year ) ){
      $date_recorded = "<div class='date-recorded row'>Recorded:$month $year</div>";
    }

    echo $date_recorded;
    echo $languages;
    
    echo '<div class="row">';
    get_template_part('parts/primary-related-category-link');
    echo '</div>';
    
    echo '</div>';     
  echo '</div></section>';
} 