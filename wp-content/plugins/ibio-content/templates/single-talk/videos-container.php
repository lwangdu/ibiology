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

    $container_class = $num_parts > 1 ? " multi-part" : " single-part";

    echo "<div class='videos-container $container_class'>";

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
      $audience = ibio_display_audiences( $audiences );

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

      ibio_talk_speakers_list();

      global $post;

      $total_duration = get_post_meta( $post->ID, 'total_duration', true );
      echo "<div class='duration row'>Total Duration: " . ibio_pretty_duration( $total_duration ) . '</div>';

    } else {
      /* single part talk */
        echo '<header>This Talk</header>';

        ibio_talk_speakers_list();

       $this_talk = get_queried_object();
       
       $audience_list = wp_get_post_terms( $this_talk->ID, 'audience' );
       
       echo ibio_display_audiences( $audience_list );

    }



    $translations = get_field( 'talks_in_other_languages' );
    $languages = '';
    if ( is_array( $translations ) ) {
      $languages .= '<div class="row"><span class="label" data-toggle="translations">Watch in:</span>';
      $languages .= '<ul class="translations horizontal">';
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
      $date_recorded = "<div class='date-recorded row'>Recorded: $month $year</div>";
    }

    echo $date_recorded;
    echo $languages;
    
    // add a jump link to the Educator resoruces if we have them.
    
    $resources = get_field( 'educator_resources' );
    
    if ( !empty( $resources ) ){
    	$resources_page = get_option( 'ibio_teaching_tools_resource_page');
    	if ( $resources_page) {
    		$resources_url = get_permalink( $resources_page );
	    } else {
		    $resources_url = '';
	    }

	    if ( strpos($resources_url, '?') ){
    		$resources_url .= "&tid={$post->ID}";
	    } else {
		    $resources_url .= "?tid={$post->ID}";
	    }

    	echo "<a href='$resources_url'>Educator Resources for this Talk</a>";
    }
    
    echo '<div class="row">';
    $primary_related_category = get_field('related_talks');
    $primary_playlist = get_field( 'primary_playlist' );

    if ( $num_parts == 1 ){
        if ( $primary_playlist ) {
            ibio_get_template_part( 'single-talk/related-playlist', 'sidebar');
        } else {
            ibio_get_template_part( 'single-talk/related-talks', 'sidebar' );
            ibio_get_template_part( 'shared/primary-related-category', 'link' );
        }

    } else {
        if ( $primary_related_category ) {
            ibio_get_template_part('shared/primary-related-category', 'link');
        } else {
            ibio_get_template_part('shared/primary-related-playlist', 'link');
        }

    }

    echo '</div>';
    
    echo '</div>';     
  echo '</div></section>';
}
