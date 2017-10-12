<?php

// Functions having to do with Breadcrumbs.  So many!

//remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
//add_action( 'genesis_before_loop', 'ibio_breadcrumbs');

function ibio_breadcrumbs(){
    if ( function_exists('yoast_breadcrumb') ) {
        yoast_breadcrumb('<p id="breadcrumbs">','</p>');
    } else{
        genesis_do_breadcrumbs();
    }
}

add_filter ('genesis_breadcrumb_link', 'ibio_breadcrumb_links', 10, 5);
function ibio_breadcrumb_links($link, $url, $title, $content, $args){
    error_log("[Building Breadcrumbs] $link " . serialize ($args));
    return $link;
}

add_filter( 'genesis_breadcrumb_args', 'ibio_breadcrumb_args');
function ibio_breadcrumb_args($args){

    $args['sep'] = '<span aria-label="breadcrumb separator"> | </span>';

    $args['heirarchial_categories'] = 0;
    $args['labels']['prefix'] = '';
    $args['labels']['author'] = '';
    $args['labels']['category'] = ''; // Genesis 1.6 and later
    $args['labels']['tag'] = 'All content tagged with ';
    $args['labels']['date'] = '';
    $args['labels']['search'] = 'Search for ';
    $args['labels']['tax'] = '';
    $args['labels']['post_type'] = 'All ';
    $args['labels']['404'] = 'Not found: ';
    return $args;
}

// Crumbs for archive pages
add_filter( 'genesis_archive_crumb', 'ibio_archive_crumb', 10, 2);
function ibio_archive_crumb($crumb, $args){
    return $crumb . serialize($args);
}


add_filter('genesis_cpt_crumb', 'ibio_content_crumb', 10, 2);
function ibio_content_crumb($crumb, $args){

    $itemprop_item = genesis_html5() ? ' itemprop="item"' : '';
    $itemprop_name = genesis_html5() ? ' itemprop="name"' : '';

    $current_post = get_queried_object();
    // Talks
    switch ($current_post->post_type){
        case IBioTalk::$post_type:
            $categories= wp_get_post_categories($current_post->ID);
            $main_cat = array_shift($categories);
            $main_cat = get_term($main_cat, 'category');
            // assume the first one is primary;
            $category_link = get_term_link($main_cat->term_id, 'category');
            $link = sprintf( '<a href="%s"%s><span%s>%s</span></a>', esc_attr( $category_link ), $itemprop_item, $itemprop_name, $main_cat->name );

    }

    return $link;
}