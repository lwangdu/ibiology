<?php

	// display the related resources for a post (talk, session, playlist, etc)
	

global $post;

$resources = get_field( 'related_resources' );

if ( $resources ) {
	echo '<section class="related-resources row"><h3>Related Resources</h3>';
	echo $resources;
	echo '</section>';
}