<?php

$primary_playlist =  get_field( 'primary_playlist' );

if ( !empty( $primary_playlist ) ){

	$talks = new WP_Query(array(
		'post_type' => 'ibiology_talk',
		'connected_type' => 'playlist_to_talks',
		'connected_items' => $primary_playlist->ID,
		'nopaging' => true,
		'post__not_in' => array(get_queried_object())
	));
	
	if ( $talks->have_posts() ){
		echo "<div class='widget'><h3 class='widgettitle'>Playlist: {$primary_playlist->post_title}</h3>";
		echo '<ul class="related_playlist related_items grid col-2">';
		while( $talks->have_posts() ){
			$talks->the_post();
			if ( $post->ID == get_queried_object() ) continue;
			$url = get_post_permalink();
			echo "<li class='item'><a href='$url'>". get_the_post_thumbnail($post->ID, 'thubmanil') . $post->post_title . "</a></li>" ;
		}
		
		echo '</ul></div>';
	}
	
	wp_reset_query();


}