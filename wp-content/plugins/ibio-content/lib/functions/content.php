<?php

  /* Functions related to content */

//* content filters

function ibio_content_archive_setup(){
	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs');
	add_action( 'genesis_before_loop', 'ibio_breadcrumbs', 8);
  
  add_action( 'body_class', 'ibio_grid_body_class' );
  add_filter( 'genesis_post_title_text', 'ibio_talk_short_title' );
  remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
  // move the title below the image
  remove_action( 'genesis_entry_header', 'genesis_do_post_title');
  add_action( 'genesis_entry_footer', 'genesis_do_post_title');
  remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
  remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
}

// removes the hooks for post meta and post info on pages where this is called.
function ibio_setup_single(){
    remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
    remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
}

function ibio_talk_short_title($title){

  global $post;
  $short_title = get_field( 'short_title' );
  $new_title = empty( $short_title ) ? $title : $short_title;
  return $new_title;
}

function ibio_grid_body_class($classes){
  $classes[] = 'grid-listing';
  return $classes;
}

function ibio_ed_resources(){
	ibio_get_template_part( 'shared/related-resources', 'educator' );
	
}

// show the "related content" items, usually grouped together after a talk.
function ibio_related_content(){

    if (is_singular(IBioTalk::$post_type)){

        $primary_related_category = get_field('related_talks');
        $primary_related_playlist = get_field( 'primary_playlist');
        $videos = get_field( 'videos');

        if (count($videos) > 1){
            if ( $primary_related_playlist ){
                ibio_get_template_part('shared/primary', 'playlist');

            } else {
                ibio_get_template_part( 'shared/related', 'talks-by-category' );
            }
        } else {
            if ( empty( $primary_related_category ) ){
                ibio_get_template_part('shared/primary', 'playlist');
            } else {
                ibio_get_template_part( 'shared/related', 'talks-by-category' );
            }

        }

        ibio_get_template_part( 'shared/related', 'resources' );
    } else {

        ibio_get_template_part( 'shared/related', 'talks-by-category' );
        ibio_get_template_part( 'shared/related', 'resources' );
        //ibio_get_template_part('shared/primary', 'playlist');
    }


}

/*
 * ibio_expandable_section
 * @param $content Input content
 * @returns the content wrapped in HTML to create an expandable section later.
*/
function ibio_expandable_section( $content )
{
    return '<div class="expandable">' . $content . '</div>';
}

/*******  FACET-WP items ****************/

function ibio_facet_start( $feature = null ){

    echo '<div class="facetwp-template"> ';

    if ( $feature === 'pagination' ) {
        echo '<div class="flex-row archive-pagination"> <div class="count-summary left"> Showing &nbsp;' . do_shortcode('[facetwp counts="true"]') . ' &nbsp;talks.   </div>';
        echo '<div> <div class="big"> Display:  &nbsp;' . do_shortcode('[facetwp per_page="true"]') . '</div>';
        echo do_shortcode('[facetwp pager="true"]');
        echo '</div></div>'; // toolbar
    }


}

function ibio_facet_end( $feature = null ){
    // pagination
    if ( $feature === 'pagination' ) {
        echo '<div class="flex-row">';
        echo do_shortcode('[facetwp pager="true"]');
        echo '</div>';
    }
    echo '<div class="facetwp-overlay" style="display:none;"></div></div><!--Facet Container -->';
}

// convert a number of minutes into a nice display hh:mm:ss
function ibio_pretty_duration( $total_duration ){
    $hours = floor($total_duration / 3600 );
    $total_duration -= $hours * 3600;
    $minutes = floor($total_duration / 60 );
    $seconds = $total_duration % 60;

    return sprintf ("%d:%02d:%02d", $hours, $minutes, $seconds);
}

// display a list of audiences
// takes in an array of terms
function ibio_display_audiences( $audiences ){
    $audience = '';
    if (!empty($audiences) && is_array($audiences)) {
        $audience .= '<div>Audience: <ul class="audiences">';
        foreach ($audiences as $a) {
            $audience .= "<li title='{$a->name}' class='audience {$a->slug}'><span>{$a->name}</span></li> ";
        }
        $audience .= '</ul></div>';
    }

    return $audience;
}

// output a list of speakers for a talk, with links to their respective pages.
function ibio_talk_speakers_list( $label = null ){
    global $talk_speaker;
    if ( empty ($talk_speaker)  ) return;

    $num =  count( $talk_speaker );

    if ( $num > 1 ){
        $heading = 'Speakers';
    } else {
        $heading = "Speaker";
    }

    echo "<div class='row'>$heading: ";
    $out = array();

    foreach ( $talk_speaker as $t ){
        $surl = get_post_permalink( $t->ID) ;
        $out[] =  "<a href='$surl' title='{$t->post_title}'>{$t->post_title}</a>";
    }

    echo implode( ', ', $out);
    echo '</div>';
}

function ibio_talks_speaker(){
    ibio_get_template_part( 'shared/related', 'speaker' );
}