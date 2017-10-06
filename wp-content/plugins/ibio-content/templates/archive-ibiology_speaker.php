<?php

global $cur_letter;
$cur_letter = 'A';

// function to create the a-z listing at the top of the page.

function ibio_speakers_body_class( $classes ){
    $classes[] = 'speakers';
    return $classes;
}

function ibio_anchor_check(){
    global $cur_letter;

    // check the last name and see if it's a
    $last_name = strtoupper( get_field('last_name' ) );

    if ( $last_name ) {
       $letter = substr( $last_name, 0, 1);
       if ( $letter != $cur_letter){
           $cur_letter = $letter;
           $letter = strtolower( $letter );
           // output an anchor, update the current lette
            echo "<h2 id='$letter-anchor'>$cur_letter</h2>";
       }
    }

}

// force full width layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
add_filter( 'body_class', 'ibio_speakers_body_class');

add_action( 'wp_head', 'ibio_content_archive_setup' );
add_action( 'genesis_before_entry', 'ibio_anchor_check');


genesis();