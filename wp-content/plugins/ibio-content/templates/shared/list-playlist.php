<?php
/**
 * list-playlist.php
 * Description: Displays a list of playlists
 * Created : anca
 */


global $list_playlists;
// Display each playlist w/ its excerpt.  This is used in search results
// and on the single speaker page.

remove_filter( 'the_excerpt', 'wpautop' );
remove_filter('the_excerpt', 'ibio_add_more_link', 1, 2);

// From the genesis default loop.
//do_action( 'genesis_before_entry' );
printf( '<article %s>', genesis_attr( 'entry' ) );
do_action( 'genesis_entry_header' );
do_action( 'genesis_before_entry_content' );
printf( '<div %s>', genesis_attr( 'entry-content' ) );
//do_action( 'genesis_entry_content' );
the_excerpt();

echo '</div>';
echo '</article>';
//do_action( 'genesis_after_entry' );
