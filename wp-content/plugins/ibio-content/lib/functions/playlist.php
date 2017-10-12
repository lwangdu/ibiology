<?php

/* Get the talks on a playlist */

function ibio_talks_playlist($playlist = null, $maxitems = 0, $audience = null){

    if ( !$playlist ){
        $playlist = get_queried_object();

    }

    $args = array(
        'post_type' => 'ibiology_talk',
        'connected_type' => 'playlist_to_talks',
        'connected_items' => $playlist,
        'post_status' => 'publish',
        'nopaging' => true
    );


    if ( !empty($audience) ) {
        error_log('shortcode audience not empty: ' . $audience);
        $audience_query = array(
            'taxonomy' => 'audience',
            'field' => 'slug',
            'terms' => $audience
        );

        $args['tax_query'] = array($audience_query);
    }


    $talks = new WP_Query($args);
    
    // loop through to get the item order.

    $ordered_talks = array();
    foreach($talks->posts as $t){
        $playlist_order = p2p_get_meta($t->p2p_id, 'order', true);
        $t->menu_order = intval($playlist_order);
    }

    usort( $talks->posts, 'ibio_compare_playlist_posts' );


    if ( $talks->have_posts( ) ) {
        $counter = 0;
        echo '<ul class="talks grid">';
        foreach($talks->posts as $t){
            if ( $maxitems > 0 && $counter >= $maxitems) break;
            global $post;
            $post = $t;
            setup_postdata($post);
            get_template_part( 'parts/list-talk');
            $counter++;
        }
        echo '</ul>';
    }
    wp_reset_query();
}

function ibio_compare_playlist_posts( $a, $b){

    if ( !isset($a->menu_order) || !isset($b->menu_order)) return 0;

    if ( $a->menu_order == $b->menu_order ) return 0;

    // always sort zeroes at the end (things w/ an order supersede things w/out an order
    if ( $a->menu_order == 0 ) return 1;
    if ( $b->menu_order == 0 ) return -1;

    if ( $a->menu_order > $b->menu_order) {
        return 1;
    } else if ( $a->menu_order < $b->menu_order) {
        return -1;
    }
}


/* Playlist Shortcode.  Use when you want to show the contents of a playlist on a page or post */

add_shortcode( 'ibio_playlist', 'ibio_playlist_shortcode');
function ibio_playlist_shortcode($atts){

    $atts = shortcode_atts( array (
        'id' => null,
        'numtalks' => 4,
        'start_index' => 0,
        'audience' => null
        ) , $atts, 'ibio_playlist');


    if (empty( $atts['id'])) {
        return '<span style="color\:red">Please supply a playlist ID to retrieve talks</span>';
    }


    $playlist = get_post($atts['id']);
    if ( empty($playlist) || $playlist->post_type != IBioPlaylist::$post_type ){
        return '<span style="color\:red">Please supply a valid playlist ID to retrieve talks</span>';
    }

    ob_start();

    ibio_talks_playlist( $playlist, $atts['numtalks'], $atts['audience'] );

    $playlist = ob_get_clean();

    return $playlist;


}