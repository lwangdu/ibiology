<?php

global $acf_fields_helper;
$acf_fields_helper = new IBio_Fields_Display_Helper();

global $speaker_talks;

function ibio_speaker_info(){
	global $speaker_talks;
	
	$posts = new WP_Query(array(
			'post_type' => 'ibiology_talk',
			'connected_type' => 'playlist_to_talks',
			'connected_items' => get_queried_object(),
  		'nopaging' => true
		));
	$speaker_talks = $posts->posts;
	
}

function ibio_speaker_details(){
	global $acf_fields_helper;
	$acf_fields_helper->show_field_group(32397);
}

function ibio_related_content(){
	global $acf_fields_helper;
	echo "<h2>Related Information</h2>";
	$acf_fields_helper->show_field_group(32376);
}

function ibio_talks_playlist(){
	global $speaker_talks;
	echo "<h2>Talks with this Speaker</h2>";
	
	foreach ($speaker_talks as $s){
		$url = get_post_permalink($s->ID);
		echo "<h3><a href='$url'>" . $s->post_title . "</a></h3>" . get_the_post_thumbnail($s->ID, 'thubmanil');
	}	

}

/* -------------------  Page Rendering --------------------------*/

add_action('genesis_entry_header', 'ibio_speaker_info', 20);
add_action('genesis_entry_content', 'ibio_speaker_details', 15);
add_action('genesis_entry_content', 'ibio_talks_speaker', 20);
add_action('genesis_entry_content', 'ibio_related_content', 21);

genesis();