<?php

  // Sidebar for the single video.

  $playlist_talks = new WP_Query( array (
        'post_type' => IbioTalk::$post_type,
        'cat'       => $related_category,
        'posts_per_page'  => 4,
        'post__not_in'    => array( $talk->ID )
     ) );

  ?>
  
  <!-- <div class="widget"><h4 class="widgettitle">In this Playlist: Cell Biology</h4><ul class="related-by-category talks-list">
<li class="talk-list-item">Previous: 
<strong class="entry-title"><a href="/talks/microrna-biogenesis-and-regulation">microRNA Biogenesis and Regulation</a>
</strong></li>

<li class="talk-list-item">Next: 
<strong class="entry-title"><a href="/talks/microrna-biogenesis-and-regulation">microRNA Biogenesis and Regulation</a>
</strong></li>

</ul></div> -->
  
  <?
  
  
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
      echo '<div class="widget"><h4 class="widgettitle">Related Talks</h4>';
      echo '<ul class="related-by-category talks-list">';
      while ( $related_talks->have_posts() ) {
        $related_talks->the_post();
        get_template_part( 'parts/list-talk');
      }
      echo '</ul>';
      echo '</div>';
    }      
    
    wp_reset_query();
  }
  
  dynamic_sidebar( 'sidebar_talks' );