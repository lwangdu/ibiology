<?php

	// shwow the related talks based on the selected category.  This can be applied
	// to any post type thtt has related resources.
	  
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
    	$category_info = get_term( $related_category, 'category');
      echo '<div class="related-talks related-items"> <h4 class="widgettitle">More Talks in '. $category_info->name .'</h4>';
      echo '<ul class="related-by-category talks-list filmstrip">';
      while ( $related_talks->have_posts() ) {
        $related_talks->the_post();
        get_template_part( 'parts/list-talk');
      }
      echo '</ul>';
      wp_reset_query();
      echo '<div class="footer">';
      ibio_get_template_part( 'shared/primary-related-category', 'link');
      echo '</div>';
      echo '</div>';
    }      
    

  }