<?php

  $related_category = intval( get_field( 'related_talks' ) );
  
  if ( $related_category > 0  ){
   	$cat_info = get_term( $related_category, 'category' );
   	$url = get_term_link( $related_category, 'category' );
   	
   	echo "<a href='$url' class='more-link'>All Talks in {$cat_info->name}</a>";
  }