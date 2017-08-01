<?php

	// display the related speakers.  the global $talk_speaker needs to be filled in

	global $talk_speaker;
  
  if ( !empty(  $talk_speaker ) ){
  	echo '<section class="speakers">';
  	echo "<h2>Speaker Bio</h2>";
    foreach ($talk_speaker as $s){
      $url = get_post_permalink($s->ID);
      setup_postdata( $s );
      global $post;
      $post = $s;
      echo "<h3><a href='$url'>" . $s->post_title . "</a></h3>";
      echo "<a href='$url' class='alignleft'>";
      the_post_thumbnail( 'thumbnail' );
      echo '</a>';
      the_excerpt();
    }	
		
    wp_reset_postdata();
    echo '</section>';
  }
