<?php
// Show the related talks based on the selected playlist.
// Use this in the single talk page when there's only one video

$primary_playlist = ( get_field( 'primary_playlist' ) );
$talk = get_queried_object();

if ( !empty( $primary_playlist ) ){


    echo '<div class="related-items row"><header>More Talks in '. $primary_playlist->post_title .'</header>';

    ibio_talks_playlist($primary_playlist, 3, null, $talk->ID, 'stack', 'next');

    $url = get_permalink($primary_playlist);

    echo "<div class='row'><a href='$url' class='more-link'>All Talks in {$primary_playlist->post_title}</a></div>";

    return;

}