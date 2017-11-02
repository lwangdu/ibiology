<?php

$primary_playlist =  get_field( 'primary_playlist' );
$primary_playlist = get_post( $primary_playlist );

  if ( !empty( $primary_playlist ) ){

   	$url = get_post_permalink( $primary_playlist->ID );
   	
   	echo "<a href='$url' class='more-link'>Full Playlist: {$primary_playlist->post_title}</a>";
  }