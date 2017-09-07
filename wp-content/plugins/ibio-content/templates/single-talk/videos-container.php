<?php

$videos = get_field( 'videos' );

if ( empty( $videos ) ) return;

if ( is_array( $videos ) ) {
  echo '<section class="videos row"><div class="wrap">';
     
    $titles = array();
    $thumbs = array();
    $num_parts = count( $videos ); // count the parts;    
    global $current_video;
    $current_video[ 'num_parts' ] =  $num_parts;
    $current_video[ 'total_duration' ] = 0;
    $counter = 1;
    $part_audiences = array();
    
    echo '<div class="videos-container">';
    foreach( $videos as $v ){
      $current_video['video'] = $v;
      $current_video['counter'] = $counter;

      $title = isset( $v[ 'part_title' ] ) ?  esc_attr( $v[ 'part_title' ] ) : '';
      if ( $num_parts > 1 ){
        $title = "Part $counter: " . $title;
      }      
      $titles[ $counter ] = $title;

      $video_thumbnail = isset( $v[ 'video_thumbnail' ] ) ? $v[ 'video_thumbnail' ] : '';
      // video thumbnail is an array.  Let's grab the thumbnail size of this image.
      if ( is_array( $video_thumbnail ) && isset( $video_thumbnail['sizes'] ) && isset( $video_thumbnail['sizes']['thumbnail'] ) ){
        $thumbs[ $counter ] = $video_thumbnail['sizes']['thumbnail']; 
      }
      ibio_get_template_part('shared/single', 'video');
     
     
      $audiences = $v['target_audience'];
      $audience = '';
      if ( !empty($audiences) && is_array($audiences) ){
        $audience .= '<br/>Audience: <ul class="audiences">';
        foreach( $audiences as $a ){
          $audience .= "<li class='audience {$a->slug}'><span>{$a->name}</span></li> ";
        }
        $audience .= '</ul>';
      }

      $part_audiences[ $counter ] = $audience;
      
      $counter++;
      
    }
    echo '</div>';
    
    echo '<div class="videos-info">';


    /* Videos in this talk */
    
    if ( $num_parts > 1 ){
      /* multi-part talk */
      echo '<header>Videos in this Talk</header>';
      $tabs = '<ul class="videos-nav">';
      for( $i = 1 ; $i < $counter ; $i++ ){
        $title =  $titles[ $i ];
        $thumb = isset( $thumbs[ $i] ) ? '<img src="'. $thumbs[ $i] .'" alt="' . $title . '"/>' : '';
        
        $tabs .= "<li class='part-$i' data-select='part-$i'><figure>$thumb</figure>$title ";
        $tabs .=  $part_audiences[ $i ] . '</li>';
        
      }
      $tabs .= "</ul>";
      echo $tabs;
      
      echo '<div class="duration row">Total Duration: 01:00:20</div>';

    } else {
      /* single part talk */
       echo '<header>This Talk</header>';  
       
       $this_talk = get_queried_object();
       
       $audience_list = wp_get_post_terms( $this_talk->ID, 'audience' );
       
       if ( !empty($audience_list) && is_array( $audience_list ) ){
        echo '<div class="row">Audience: <ul class="audiences">';
        foreach ( $audience_list as $a ){
           echo "<li class='audience {$a->slug}'><span>{$a->name}</span></li> ";
        }
        echo "</ul></div>";       
       }
          
    }
    
    $translations = get_field( 'talks_in_other_languages' );
    $languages = '';
    if ( is_array( $translations ) ) {
      $languages .= '<div class="row"><span class="toggle" data-toggle="translations">Watch in:</span>';
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
    $month = $month_field['choices'][$month_field[ 'value' ] ];
    $year = get_field( 'date_recorded_year' );

    if ( !empty( $year ) ){
      $date_recorded = "<div class='date-recorded row'>Recorded:$month $year</div>";
    }

    echo $date_recorded;
    echo $languages;
    
    echo '<div class="row">';
    if ( $num_parts == 1 ){
      ibio_get_template_part( 'single-talk/related-talks', 'sidebar' );
    }
    echo '</div><div class="row">';
    ibio_get_template_part( 'shared/primary-related-category', 'link' );
    echo '</div>';
    
    echo '</div>';     
  echo '</div></section>';
} 