<?php

$primary_playlist =  get_field( 'primary_playlist' );

if ( !empty( $primary_playlist ) ){

	global $post;

	$talks = new WP_Query(array(
		'post_type' => 'ibiology_talk',
		'connected_type' => 'playlist_to_talks',
		'connected_items' => $primary_playlist->ID,
		//'post__not_in' => array($post->ID),
		'posts_per_page' => 3
	));


	if ( $talks->have_posts() ){
		echo "<div class='related-items'><h3 class='widgettitle'>Playlist: {$primary_playlist->post_title}</h3>";
		echo '<div class="filmstrip-wrapper"><ul class="related_playlist related_items filmstrip">';
		while( $talks->have_posts() ){
			$talks->the_post();
			get_template_part('parts/list', 'talk');
			
		}
        echo '</ul>';
		// link to the plalist
		$url = get_post_permalink($primary_playlist);
		echo "<a href='$url' class='more-link'>All Talks in {$primary_playlist->post_title}</a>";
  		echo '</div>';
	}
	
	wp_reset_query();

}