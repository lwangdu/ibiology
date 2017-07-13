<?php
  
  // Add some information for site admins and content migration folks.
  
  
  if ( current_user_can( 'edit_post' ) ){
    $post = get_queried_object();
    $migration_status = get_field( 'migration_status' );
    $original_url = get_field( 'original_url' );
    
    echo "<div class='admin-info'><div class='widgettitle'>Migration Information</div><ul>";
    echo "<li>Post ID: {$post->ID}</li>";
    echo "<li>Publish Date: {$post->post_date}</li>";
    echo "<li>Migration Status: $migration_status </li>";
    if ( empty( $original_url ) ){
      echo "<li>Original URL not entered.<a href='https://www.ibiology.org/?p={$post->ID}'>Maybe this is it?</a></li>";
    } else {
      echo "<li><a href='$original_url'>View on current site</a></li>";
    }
    
    echo '</ul></div>';
  }
  
  dynamic_sidebar( 'sidebar_talks' );