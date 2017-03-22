<?php

global $acf_fields_helper;
$acf_fields_helper = new IBio_Fields_Display_Helper();

global $talk_speaker;

function ibio_talks_info(){
	global $talk_speaker;
	
	$talk_speakers = new WP_Query(array(
			'post_type' => 'ibiology_speaker',
			'connected_type' => 'speaker_to_talk',
			'connected_items' => get_queried_object(),
  		'nopaging' => true
		));
	
	
}

function ibio_talks_videos(){
	global $acf_fields_helper;
	$acf_fields_helper->show_field_group(32361);
}

function ibio_related_content(){
	global $acf_fields_helper;
	$acf_fields_helper->show_field_group(32376);
}

function ibio_talks_speaker(){
	echo "<h2>Speaker</h2>";
	
	echo "<pre>";
	var_dump($talk_speakers);
	echo "</pre>";

}

/* -------------------  Page Rendering --------------------------*/

add_action('genesis_entry_header', 'ibio_talks_info', 20);

add_action('genesis_loop', 'ibio_talks_videos', 2);
add_action('genesis_loop', 'ibio_related_content', 15);
add_action('genesis_loop', 'ibio_talks_speaker', 20);
genesis();