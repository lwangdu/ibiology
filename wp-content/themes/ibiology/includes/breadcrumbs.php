<?php

// Functions having to do with Breadcrumbs.  So many!

//remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
//add_action( 'genesis_before_loop', 'ibio_breadcrumbs');

function ibio_breadcrumbs(){

    genesis_do_breadcrumbs();

    /*if ( function_exists('yoast_breadcrumb') ) {
        yoast_breadcrumb('<p id="breadcrumbs">','</p>');
    } else{
        genesis_do_breadcrumbs();
    }*/
}

add_filter ('genesis_breadcrumb_link', 'ibio_breadcrumb_links', 10, 5);
function ibio_breadcrumb_links($link, $url, $title, $content, $args){
    return $link;
}

add_filter( 'genesis_breadcrumb_args', 'ibio_breadcrumb_args');
function ibio_breadcrumb_args($args){

    $args['sep'] = '<span aria-label="breadcrumb separator"> &raquo; </span>';

    $args['heirarchial_categories'] = 1;
    $args['labels']['prefix'] = '';
    $args['labels']['author'] = '';
    $args['labels']['category'] = ''; // Genesis 1.6 and later
    $args['labels']['tag'] = 'All content tagged with ';
    $args['labels']['date'] = '';
    $args['labels']['search'] = 'Searching for ';
    $args['labels']['tax'] = '';
    $args['labels']['post_type'] = 'All ';
    $args['labels']['404'] = 'Not found: ';
    return $args;
}

// Crumbs for archive pages
add_filter( 'genesis_archive_crumb', 'ibio_archive_crumb', 10, 2);
function ibio_archive_crumb($crumb, $args){
    $foo = $crumb;
    return $crumb;
}


add_filter('genesis_cpt_crumb', 'ibio_content_crumb', 10, 2);
function ibio_content_crumb($crumb, $args){

    $itemprop_item = genesis_html5() ? ' itemprop="item"' : '';
    $itemprop_name = genesis_html5() ? ' itemprop="name"' : '';

    $current_post = get_queried_object();
    $link = $crumb;

    // Talks
    switch ($current_post->post_type){
        case IBioTalk::$post_type:
            // get the categories for the talk
            $cats = get_the_category( $current_post->ID );

            if ( empty($cats) || is_wp_error( $cats ) ){
                $page_parent = get_post_meta( $current_post->ID, 'parent_page', true);
                $page = get_post( $page_parent );
                if ( empty($page)) return $link;

                $url = get_permalink( $page );
                $link = sprintf( '<a href="%s"%s><span%s>%s</span></a>', esc_attr( $url ), $itemprop_item, $itemprop_name, esc_html( $page->post_title ) );

                while ($page->post_parent){
                    $page = get_post( $page->post_parent );
                    $url = get_permalink( $page );
                    $link = sprintf( '<a href="%s"%s><span%s>%s</span></a>%s%s', esc_attr( $url ), $itemprop_item, $itemprop_name, esc_html( $page->post_title ), $args['sep'], $link );
                }

            } else {
                // more than one means getting the primary one
                $primary_cat = get_post_meta( $current_post->ID, '_yoast_wpseo_primary_category', true);

                if ( empty($primary_cat) ) {
                    $primary_cat = array_shift( $cats );
                } else {
                    $primary_cat = get_term( $primary_cat, 'category');
                }
                $url = get_term_link( $primary_cat, 'category');

                // sometimes the yoast primary category is no longer there... So the URL will be a problem.

                if ( !is_wp_error($url)) {

                    $link = sprintf('<a href="%s"%s><span%s>%s</span></a>', $url, $itemprop_item, $itemprop_name, esc_html($primary_cat->name));

                    while ($primary_cat->parent) {
                        $primary_cat = get_term($primary_cat->parent, 'category');
                        $url = get_term_link($primary_cat, 'category');
                        $link = sprintf('<a href="%s"%s><span%s>%s</span></a>%s%s', esc_attr($url), $itemprop_item, $itemprop_name, esc_html($primary_cat->name), $args['sep'], $link);
                    }
                }
            }

    }

    return $link;
}