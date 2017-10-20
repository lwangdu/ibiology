<?php
  
  // Add some information for site admins and content migration folks.
  
  
  if ( current_user_can( 'edit_post' ) and WP_DEBUG ){
    $post = get_queried_object();
    $migration_status = get_field( 'migration_status' );
    $original_url = get_field( 'original_url' );
    $has_educator_resources = get_post_meta( $post->ID, 'has_educator_resources', true);
    $date_recorded = get_post_meta( $post->ID, 'recorded_date', true);
    $total_duration = get_post_meta( $post->ID, 'total_duration', true);
    $subtitles = get_post_meta( $post->ID, 'subtitle_language', false);

    
    echo "<div class='admin-info'><div class='widgettitle'>Migration Information</div><ul>";
    echo "<li>Post ID: {$post->ID}</li>";
    echo "<li>Publish Date: {$post->post_date}</li>";
    echo "<li>Migration Status: $migration_status </li>";

    echo "<li>For Educators: $has_educator_resources</li>";
    echo "<li>Total Duration: $total_duration minutes</li>";
    echo "<li>Date Recorded for Sort: $date_recorded</li>";

    echo "<li>Subtitles: " . serialize($subtitles) . "</li>";

    if ( empty( $original_url ) ){
      echo "<li>Original URL not entered.<a href='https://www.ibiology.org/?p={$post->ID}'>Maybe this is it?</a></li>";
    } else {
      echo "<li><a href='$original_url'>View on current site</a></li>";
    }
    
    echo '</ul></div>';
  }

  dynamic_sidebar( 'sidebar_talks' );