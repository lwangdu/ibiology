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
        $length = isset( $v[ 'video_length' ] ) ?  '<span class="length">' . esc_attr( $v[ 'video_length' ] ) . '</span>' : '';
        $download = isset( $v[ 'video_download_url' ] ) ?  esc_url( $v[ 'video_download_url' ] ) : '';
        $size = isset( $v[ 'download_size' ] ) ?  '<span class="size">' . esc_attr( $v[ 'download_size' ] ) . '</span>' : '';
        $download_link = ! empty ( $download ) ? "<a href='$download'>Download $size</a>" : '';
        $transcript = isset( $v[ 'transcript' ] ) ? '<div class="transcript"><div class="toggle">View Transcript</div><div class="content" style="display:none">' . esc_html( $v[ 'transcript' ] ) . '</div></div>' : '';
        $video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';
        echo "<div class='single-video part-$counter'><h2 class='title'>$title</h2><div class='content'>";
        echo wp_oembed_get( $video_url , array( 'height' => '450px' ) );
        echo '</div><div class="footer">';
        echo "<span class='video-length' data-length='$length'>$length</span>";
        echo "<span class='video-part-download'><a href='$download'>Download Hi-Res</a></span>";
        echo "<span class='video-part-transcript-toggle' data-part='$counter'>Transcript</span>";
        echo "<div id='video-part-transcript-$counter' class='video-part-transcript' style='display:none'>$transcript</div>";
        echo '</div></div>';
        $counter++;
        
      }
      echo '</div>';
      
      if ( $num_parts > 1 ){
        $tabs = '<ul class="videos-nav">';
        for( $i = 1 ; $i < $counter ; $i++ ){
          $title =  $titles[ $i ];
          $tabs .= "<li class='part-$i' data-select='part-$i'><figure></figure>$title</li>";
        }
        $tabs .= "</ul>";
        echo $tabs;
      }
            
    echo '</wrap></section>';
  } 