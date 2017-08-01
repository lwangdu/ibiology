<?php

	// display the related resources for a post (talk, session, playlist, etc)
	

global $post;

$resources = get_field( 'educator_resources' );

if ( $resources ) {
	echo '<section class="related-resources"><h3>Educator Resources</h3>';
	echo $resources;
	echo '</section>';
}