<?php
/**
 * Template Name: Talk Transcript
 */

function ibio_transcript_body_class($classes){
	$classes[] = 'transcript';
	return $classes;
}

// put the featured image of the transcript page into the header
function ibio_featured_image(){
	if ( has_post_thumbnail ( get_queried_object_id() ) ){
		echo get_the_post_thumbnail( get_queried_object_id(), 'full');
	}
}

function ibio_display_talk_transcript(){
	$talk_to_display = null;

	if ( isset( $_GET['tid'] ) && is_numeric( $_GET['tid'] ) ) {
		$talk_to_display = get_post($_GET['tid']);
	}

	if (  isset( $_GET['part'] ) && is_numeric( $_GET['part'] ) ) {
		$part_to_display = $_GET['part'];
	} else {
		$part_to_display = 1;
	}

	// get out if there's no valid talk to display for a transcript

	$talk = get_post($talk_to_display);

	if ( empty( $talk_to_display ) || is_a($talk, 'WP_Error') ) return;

	$parts = get_field( 'videos', $talk->ID );

	//var_dump ($parts);

	if (is_array($parts) ){
		$part = $parts[ $part_to_display - 1  ];
		//var_dump($part);

	} else {
		$part = array('title' => 'no title', 'transcript' => 'no transcript' );
	}

	$transcript_field_name = 'videos_' . ($part_to_display-1) . '_transcript';
	$transcript = get_post_meta( $talk->ID, $transcript_field_name, true);

	?>
	<h1><?php echo $talk->post_title; ?></h1>
	<h2>Transcript of Part <?php echo $part_to_display; ?>: <?php echo $part['part_title']; ?></h2>
	<pre><?php echo $transcript; ?></pre>
<?php

}



// full width page
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
add_filter( 'body_class','ibio_transcript_body_class' );

//remove_action('genesis_entry_header', 'genesis_do_post_title');

//add_filter( 'genesis_build_crumbs', 'ibio_dynamic_post_crumb', 20, 2);

remove_action( 'genesis_header', 'genesis_do_header');
add_action( 'genesis_header', 'ibio_featured_image' );
remove_action( 'genesis_after_header',  'genesis_do_nav');
remove_action( 'genesis_after_header',  'genesis_do_subnav');
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs');

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'ibio_display_talk_transcript');

remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas');
genesis();