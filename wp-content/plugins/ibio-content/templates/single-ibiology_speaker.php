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
	
	// get the speaker affiliation and awards.
	
 $affiliation = get_field( 'affiliation' );
 $awards = get_field( 'awards' );
 
 if ( empty( $affiliation ) && empty( $awards ) ) return;
 
 echo '<p class="entry-meta">';
 
 if (!empty( $affiliation ) ){
 	echo "<span class='affiliation'>$affiliation</span><br/>";
 }
 
 if (!empty( $awards ) && is_array($awards) ){ 
	$awards_list = get_field_object( 'awards' );
	 echo '<span class="awards">Awards: ';
	 foreach ( $awards as $a ){
 			echo '<span>' . $awards_list['choices'][$a] . '</span> ';
 		}
 	echo '</span>';
 }
 
 echo '</p>';
	
}

function ibio_speaker_image(){
	echo get_the_post_thumbnail($s->ID, 'square-thumb', array( 'class' => 'alignleft photo' ));
}

function ibio_talks_speaker(){
	global $speaker_talks;
	if (!empty( $speaker_talks )){
		global $post;	
		echo "<section class='related-items narrow column alignright'><h2 class='widget-title'>Talks with this Speaker</h2>";
		echo '<ul class="related-talks talks-list stack">';
		foreach($speaker_talks as $post) {
			setup_postdata($post);
			get_template_part( 'parts/list-talk');
		}
		echo '</ul>';
		wp_reset_query();
		echo '</section>';
	}

}

function ibio_speaker_body_class($classes){
	$classes[] = 'speaker';
	return $classes;
}

/* -------------------  Page Rendering --------------------------*/

add_filter( 'body_class', 'ibio_speaker_body_class');
// force content-sidebar layout
//add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );

// clean up post info and post meta
add_action( 'genesis_header', 'ibio_setup_single');


add_action('genesis_before_entry', 'ibio_speaker_image');
add_action('genesis_entry_header', 'ibio_speaker_info', 12);
//add_action('genesis_entry_content', 'ibio_speaker_details', 15);
//add_action('genesis_entry_content', 'ibio_talks_speaker', 9);
add_action('genesis_sidebar', 'ibio_talks_speaker', 9);

add_action('genesis_after_entry', 'ibio_related_content', 5);

genesis();