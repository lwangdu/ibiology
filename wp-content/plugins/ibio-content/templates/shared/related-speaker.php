<?php

	// display the related speakers.  the global $talk_speaker needs to be filled in

global $talk_speaker;

if ( !empty(  $talk_speaker ) ){
    echo "<section class='speakers'><h2>Speaker Bio</h2>";
    foreach ($talk_speaker as $s){
        $url = get_post_permalink($s->ID);
        setup_postdata( $s );
        global $post;
        $post = $s;
        echo "<h3><a href='$url'>" . $s->post_title . "</a></h3>";
        echo "<figure class='alignleft photo'><a href='$url'>";
        the_post_thumbnail( 'square-thumb' );
        echo '</a></figure>';
        the_excerpt();
    }
    echo '</section>';

    wp_reset_postdata();
}