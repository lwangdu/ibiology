<?php

function ibio_talks_playlist(){

    $talks = new WP_Query(array(
        'post_type' => 'ibiology_talk',
        'connected_type' => 'playlist_to_talks',
        'connected_items' => get_queried_object(),
        'nopaging' => true
    ));

    // loop through to get the item order.

    $ordered_talks = array();
    foreach($talks->posts as $t){
        $playlist_order = p2p_get_meta($t->p2p_id, 'order', true);
        $t->menu_order = intval($playlist_order);
    }

    usort( $talks->posts, 'ibio_compare_playlist_posts' );

    if ( $talks->have_posts( ) ) {
        echo '<ul class="talks grid">';
        foreach($talks->posts as $t){
            global $post;
            $post = $t;
            setup_postdata($post);
            get_template_part( 'parts/list-talk');
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