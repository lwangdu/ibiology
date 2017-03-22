<?php

global $acf_fields_helper;
$acf_fields_helper = new IBio_Fields_Display_Helper();


function ibio_talks_info(){
	
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
	
}

/* -------------------  Page Rendering --------------------------*/

add_action('genesis_entry_header', 'ibio_talks_info', 20);

add_action('genesis_loop', 'ibio_talks_videos', 2);
add_action('genesis_loop', 'ibio_related_content', 20);


genesis();