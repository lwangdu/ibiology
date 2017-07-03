<?php

global $videos;

  if ( is_array( $videos ) ) {
    echo '<section class="videos row"><div class="wrap">';
   
    
      $counter = 1; // count the parts;
      $titles = array();
  
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
        $transcript = isset( $v[ 'transcript' ] ) ? '<span class="transcript toggle" data-toggle="video-part-transcript-'. $counter .'">View Transcript</span><div id="video-part-transcript-'. $counter .'" class="" style="display:none">' . esc_html( $v[ 'transcript' ] ) . '</div>' : '';
        $video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';
        
        
        echo "<div class='single-video part-$counter'><h2 class='title'>$title</h2><div class='content'>";
        echo wp_oembed_get( $video_url , array( 'height' => 475 ) );
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
          $tabs .= "<li class='part-$i' data-select='part-$i'><figure></figure>$title</li>";
        }
        $tabs .= "</ul>";
        echo $tabs;
      }  
      $translations = get_field( 'translations' );
      $languages = '';
      if ( is_array( $translations ) ) {
        $languages .= '<span class="toggle" data-toggle="translations">Translated Versions</span>';
        $languages .= '<ul class="translations" style="display:none">';
        foreach( $translations as $t )  {
          $url = get_permalink( $t->translated_talk );
          $languages = "<li><a href='$url'>{$t->language}</a></li>";
        }
        $languages .= "</ul>";
      }
        
      $date_recorded = '';
      $month = get_field( 'date_recorded_month' );
      $year = get_field( 'date_recorded_year' );

      if ( !empty( $year ) ){
        $date_recorded = "<div class='date-recorded'>Recorded: $month $year</div>";
      }
  
      echo $date_recorded;
      echo $languages;
      
      echo '</div>';     
    echo '</div></section>';
  } 