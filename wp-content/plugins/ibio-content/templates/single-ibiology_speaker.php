<?php

global $acf_fields_helper;
$acf_fields_helper = new IBio_Fields_Display_Helper();

global $speaker_talks;

function ibio_speaker_info(){

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
	 echo '<span class="awards">';
	 foreach ( $awards as $a ){
	        if ( !empty( $awards_list[ 'choices' ][ $a ] ) ) {
                echo '<span>' . $awards_list['choices'][$a] . '</span> ';
            }
 		}
 	echo '</span>';
 }
 
 echo '</p>';
	
}

function ibio_speaker_image(){
    global $post;
	echo get_the_post_thumbnail($post->ID, 'square-thumb', array( 'class' => 'alignleft photo' ));
}


function ibio_talks_for_speaker(){
    // re-using the loop from search gets us some extra stuff we have to remove.
    remove_action('genesis_before_entry', 'ibio_speaker_image');
    remove_action('genesis_entry_header', 'ibio_speaker_info', 12);
    remove_action('genesis_after_entry', 'ibio_talks_for_speaker', 5);
    remove_action( 'genesis_entry_header', 'genesis_do_post_title');
    add_action('genesis_entry_header', 'ibio_talk_title_link');

    $speaker_talks = new WP_Query(array(
        'post_type' => 'ibiology_talk',
        'connected_type' => 'speaker_to_talk',
        'connected_items' => get_queried_object(),
        'nopaging' => true
    ));


    if ( $speaker_talks->have_posts() ) {
        echo "<section class='related-items speaker-talks'><h2>Talks with this Speaker</h2>";
        while($speaker_talks->have_posts()) {
            $speaker_talks->the_post();
            ibio_get_template_part( "shared/talk", "with-excerpt");
        }
        echo '</section>';
    }

    wp_reset_query();
}
function ibio_speaker_body_class($classes){
	$classes[] = 'speaker';
	return $classes;
}

function ibio_talk_title_link(){
    global $post;

    $title_tag = sprintf( "<h3><a href='%s'>%s</a></h3>", get_the_permalink(), $post->post_title );

    echo $title_tag;

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
add_action('genesis_after_loop', 'ibio_talks_for_speaker', 5);

add_action('genesis_after_loop', 'ibio_related_content', 15);

genesis();