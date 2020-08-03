<?php

	// display the related resources for a post (talk, session, playlist, etc)
	

global $post;

$resources = get_field( 'related_resources' );

	echo '<section class="related-resources row">';

if ( $resources ) {
	if ( !is_singular( IBioSession::$post_type ) ){
		echo '<h3>Related Resources</h3>';
	}
	echo $resources;
	echo '</section>';
}
// This collaborators and credits section.
$collaborators_credits = get_field( 'collaborators_credits' );

	echo '<section class="collaborators_credits row">';

if ( $collaborators_credits ) {
	if ( !is_singular( IBioSession::$post_type ) ){
		echo '<h3>Credits</h3>';
	}
	echo $collaborators_credits;
	echo '</section>';
}