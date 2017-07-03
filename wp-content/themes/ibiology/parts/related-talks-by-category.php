<?php

	// shwow the related talks based on the selected category
	  
  $related_category = intval( get_field( 'related_talks' ) );
  
  if ( !empty( $related_category ) ){
  
    $talk = get_queried_object();  
    
    //var_dump( $talk->ID );
    
    $related_talks = new WP_Query( array (
        'post_type' => IbioTalk::$post_type,
        'cat'       => $related_category,
        'posts_per_page'  => 4,
        'post__not_in'    => array( $talk->ID )
      ) );
      
    if ( $related_talks->have_posts() ) {
      echo '<h4 class="widgettitle">Related Talks</h4>';
      echo '<ul class="related-by-category talks-list grid">';
      while ( $related_talks->have_posts() ) {
        $related_talks->the_post();
        get_template_part( 'parts/list-talk');
      }
      echo '</ul>';
      echo '</div>';
    }      
    
    wp_reset_query();
  }