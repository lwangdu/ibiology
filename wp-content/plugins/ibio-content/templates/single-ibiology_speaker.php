<?php

global $acf_fields_helper;
$acf_fields_helper = new IBio_Fields_Display_Helper();

global $speaker_talks;

function ibio_speaker_info(){
	global $speaker_talks;
	
	$posts = new WP_Query(array(
			'post_type' => 'ibiology_talk',
			'connected_type' => 'speaker_to_talk',
			'connected_items' => get_queried_object(),
  		'nopaging' => true
		));
	$speaker_talks = $posts->posts;
	
	echo get_the_post_thumbnail($s->ID, 'square-thumb', array( 'class' => 'alignleft' ));
	
}

function ibio_speaker_details(){
	global $acf_fields_helper;
	$acf_fields_helper->show_field_group(32397);
}


function ibio_talks_speaker(){
	global $speaker_talks;
	if (!empty( $speaker_talks )){
	
		echo "<section class='related-items row'><h2>Talks with this Speaker</h2>";
	
		foreach ($speaker_talks as $s){
			$url = get_post_permalink($s->ID);
			echo "<div class='entry'><h3><a href='$url'>" . $s->post_title . "</a></h3>" . get_the_post_thumbnail($s->ID, 'thumbnail', array( 'class' => 'alignleft' ) );
			echo $s->post_excerpt . '</div>';
		}	
		echo '</section>';
	}

}

/* -------------------  Page Rendering --------------------------*/

add_action('genesis_entry_header', 'ibio_speaker_info', 20);
add_action('genesis_entry_content', 'ibio_speaker_details', 15);
add_action('genesis_entry_content', 'ibio_talks_speaker', 20);
add_action('genesis_entry_content', 'ibio_related_content', 21);

genesis();