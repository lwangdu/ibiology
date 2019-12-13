<?php

	// display the related resources for a post (talk, session, playlist, etc)
	

global $post;

$resources = get_field( 'educator_resources' );

if ( $resources ) {
	echo '<section id="educator-resources" class="related-resources row"><h3>Educator Resources for this Talk</h3>';
	echo $resources;
	echo '</section>';
}