<?php

$acf_fields_helper = new IBio_Fields_Display_Helper();


function ibio_talks_info(){
	
}

funciton ibio_talks_videos(){
		$acf_fields_helper->show_field_group(32361);
}

function ibio_related_content(){
	$acf_fields_helper->show_field_group(32376);
}

/* -------------------  Page Rendering --------------------------*/

add_action('genesis_entry_header', 'ibio_talks_info', 20);

add_action('genesis_loop', 'ibio_talks_videos', 2);
add_action('genesis_loop', 'ibio_related_content', 20);


genesis();