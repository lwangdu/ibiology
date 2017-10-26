<?php

$primary_playlist =  get_field( 'primary_playlist' );

if ( !empty( $primary_playlist ) ){

	global $post;
	$url = get_post_permalink( $primary_playlist) ;

    echo "<div class='related-items'><h3 class='widgettitle'><a href='$url'>Playlist: {$primary_playlist->post_title}</a></h3>";
    ibio_talks_playlist( $primary_playlist, 4. null, $post->ID, 'filmstrip');
    echo "<a href='$url' class='more-link'>All Talks in {$primary_playlist->post_title}</a>";
    echo '</div>';


}