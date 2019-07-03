<?php
/**
 * Show a post header w/ an excerpt
 * User: anca
 * Date: 7/2/19
 * Time: 6:41 PM
 */

// Display each talk w/ its excerpt.  This is used in search results
// and on the single speaker page.
remove_filter( 'the_excerpt', 'wpautop' );
remove_filter('the_excerpt', 'ibio_add_more_link', 1);
add_filter( 'post_class', function($classes) { $classes[] = 'search-results'; return $classes; } );
printf( '<article %s>', genesis_attr( 'entry') );

?>
<h3><a href="<?php the_permalink()?>"><?php the_title(); ?></a></h3>
<?php

printf( '<div %s>', genesis_attr( 'entry-content' ) );
the_excerpt();

echo '</article>';

