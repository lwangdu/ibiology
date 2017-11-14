<?php

global $acf_fields_helper;
$acf_fields_helper = new IBio_Fields_Display_Helper();

global $talk_speaker;

$talk_speakers = new WP_Query(array(
    'post_type' => 'ibiology_speaker',
    'connected_type' => 'speaker_to_session',
    'connected_items' => get_queried_object(),
    'nopaging' => true
  ));
if ( !empty($talk_speakers) && isset($talk_speakers->posts)) {
  $talk_speaker = $talk_speakers->posts;
}


function ibio_talks_info(){
	global $talk_speaker;
	
	
	// put the speaker info in the page title
	if ( ! empty ($talk_speaker) ) {
	  echo '<div class="entry-meta">With ';
  	foreach ($talk_speaker as $s){
	  	$url = get_post_permalink($s->ID);
		  echo "<a class='speaker-link' href='$url'>" . $s->post_title . "</a>";
  	}	
  	echo '</div>';
	}
}

function ibio_lecture_header(){
  echo "<h2>Overview</h2>";
}

function ibio_talks_videos(){


  // get the videos
  global $videos;
  $videos = get_field( 'videos' );
  if ( empty( $videos ) ) return;
  $qa = get_field( 'questions_answers_list' ); 
  global $current_video, $question_content;
  $counter = 1;
  $num_parts = count($videos);

	$current_video[ 'num_parts' ] =  $num_parts;
	$counter = 1;

  
  foreach ( $videos as $v ) {

    $current_video['video'] = $v;
    $current_video['counter'] = $counter;

    echo '<section class="row video">';

    ibio_get_template_part( 'shared/single', 'video' );

    echo "</section>";

    $question_content = array_shift($qa);
      if ( !empty($question_content) ) {
          echo '<section class="row">';
          if ( !empty( $question_content['questions'] ) ){
				echo "<h3>Discussion Questions</h3><div class='questions'>{$question_content['questions']}</div>";
			}


			if ( !empty( $question_content['answers'] ) ){
				echo "<h3>Answers</h3><div class='questions'>{$question_content['answers']}</div>";
			}
            echo '</section>';

	 		//ibio_get_template_part( 'shared/q-and-a' );
	 	}
    $counter++;
  }
}


function ibio_related_resources(){
	ibio_get_template_part( 'shared/related', 'resources' );
}

function ibio_disucssion_questions(){
	ibio_get_template_part( 'shared/qa', 'talk' );
}

function ibio_talk_sidebar(){
  get_sidebar( 'talk' );
}

/* -------------------  Page Rendering --------------------------*/

// force content-sidebar layout - comment out to enable control at the post type or post level
//add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );

// move the breadcrumbs
//remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
//add_action( 'genesis_after_header', 'genesis_do_breadcrumbs', 15 );


// clean up post info and post meta
add_action( 'genesis_header', 'ibio_setup_single');


add_action('genesis_entry_header', 'ibio_talks_info', 12);
add_action( 'genesis_entry_content', 'ibio_lecture_header', 6);
add_action('genesis_entry_content', 'ibio_ed_resources', 11);
add_action('genesis_entry_content', 'ibio_talks_videos', 12);
add_action('genesis_entry_content', 'ibio_disucssion_questions', 12);
add_action('genesis_entry_content', 'ibio_related_resources', 20);
add_action('genesis_entry_content', 'ibio_talks_speaker', 22);


remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action( 'genesis_sidebar', 'ibio_talk_sidebar' );

genesis();