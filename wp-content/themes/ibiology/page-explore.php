<?php

// Template Name: Explore Page

function ibio_talks_filter_widget_area(){
    if ( is_active_sidebar( 'sidebar_talks_filter' )) {
        echo '<section class="facetwp-filters">';
        dynamic_sidebar('sidebar_talks_filter');
        echo '</section>';
    }
}

function ibio_explore_class( $classes ){
    $classes[] = 'explore';
    return $classes;
}

function ibio_explore_loop(){

    $args = array(
        'post_type' => IBioTalk::$post_type,
        'posts_per_page' => 48,
        'post_status' => 'publish',
	    'facetwp' => true
        );

    $talks = new WP_Query($args);

    ibio_facet_start( 'pagination' );
    echo '<ul class="grid">';
    while ($talks->have_posts()){
        $talks->the_post();
        //get_template_part( 'parts/list-talk');
        ibio_get_template_part( 'shared/list' , 'talk');
    }
    echo '</ul>';
    ibio_facet_end( 'pagination' );

    wp_reset_query();

}


/* -----------------  Page Rendering ---------------------- */

// force full width layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
add_filter( 'body_class' , 'ibio_explore_class');

//add_action( 'wp_head', 'ibio_content_archive_setup' );

remove_action('genesis_archive_title_descriptions', 'genesis_do_archive_headings_intro_text', 12);

add_action( 'genesis_after_entry', 'ibio_talks_filter_widget_area', 14);

add_action( 'genesis_after_loop', 'ibio_explore_loop', 12);


genesis();