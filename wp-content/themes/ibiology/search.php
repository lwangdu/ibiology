<?php

	// Search template
	
	
// Set up the post excerpt with or without featured image, depending on the kind of
// post it is.
	
function ibio_setup_result(){

    // remove postmeta and postinfo on all search results.
    remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
    remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

    add_action( 'genesis_entry_header', 'ibio_post_type_label', 6);

    add_action( 'genesis_entry_content', 'genesis_do_post_image' , 8 );


    global $post;
	if ( $post->post_type == IBioTalk::$post_type ){
        remove_action( 'genesis_entry_content', 'genesis_do_post_image' , 8 );
	}  // should we do this for sessions?
}
	
function ibio_search_sidebar(){
    get_sidebar( 'search');
}

function ibio_post_type_label(){
    global $post;

    $type = get_post_type_object($post->post_type);
    $labels = $type->labels;

    ?>

    <aside class="post-type-label"><?php echo $labels->singular_name; ?></aside>

    <?php

}



/**
 * This is a custom loop which contains search results.
 *
 * It outputs basic wrapping HTML and displays images
 * based on user's search queries.
 *
 * based on: https://gist.github.com/robneu/6789517
 *
 * @global $post
 * @uses   prefix_searchwp_init to instantiate the SearchWP class.
 * @since  1.0.0
 */
function ibio_search_loop() {

    // Return early if SearchWP is disabled.
    if ( ! class_exists( 'SearchWP' ) ) {
        echo __('Search engine has not been fully initialized.  Please activate and configure SearchWP. In the meantime, here are some results.', 'ibio');
        genesis_do_loop();
        return;
    }

    global $post;

    $counter = 0;

    $query = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
    $page  = isset( $_REQUEST['page'] ) ? absint( $_REQUEST['page'] ) : 1;
    $post_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : null;



    // Do nothing if no search has been performed.
    if ( empty( $query ) ) {
        return;
    }

    // perform the search

    $args = array(
        's' => $query,
        'page' => $page
    );

    if ($post_type) {
        if (!is_array($post_type)){
            $post_type = array( $post_type );
        }
        $args['post_type'] = $post_type;
    }

    $results = new SWP_Query( $args );

    // Display a message if there are no results.
    if ( empty( $results->posts ) ) {
        echo "<p>No result were found for <span class='search_term'>$query</span>.</p>";
        return;
    } else {
        echo "<p><span class='results_count'>{$results->found_posts}</span> results for <span class='search_term'>$query</span>.</p>";
    }

    // Display pagination.
    //ibio_searchwp_pagination( $results, $page );

    echo'<div class="searchwp-results">';

    // Display the search results.
    foreach ( $results->posts as $post ) {
        // Make sure post template tags work correctly.
        setup_postdata( $post );

        $counter++;

        ibio_get_template_part( "shared/talk", "with-excerpt");

    }

    wp_reset_postdata();
    wp_reset_query();

    echo'</div>';

    // Display pagination.
    ibio_searchwp_pagination( $results, $page );
}

/**
 * Pagination for a Search WP search loop.
 *
 * This outputs basic wrapping HTML and displays pagination links for the user
 * to navigate between pages of search results.
 *
 * @global $post
 * @uses   prefix_searchwp_init to instantiate the SearchWP class.
 * @param  $query the user's search query
 * @param  $page the current page number
 * @since  1.1.0
 */

function ibio_searchwp_pagination( $query, $page ) {

    // set up pagination
    $prev = $page > 1 ? $page - 1 : false;
    $next = $page < $query->max_num_pages  ? $page + 1 : false;
    // Set the nav link for reuse.
    $nav_link = get_bloginfo('url') . '?s=' . urlencode( $query->s ) . '&amp;page=';
    ?>
    <!-- begin pagination -->
    <div class="archive-pagination pagination">
        <div class="pagination-count"><?php echo "Page $page of " .$query->max_num_pages ; ?> </div>
        <?php if( $next ) : ?>
            <div class="pagination-next alignright">
                <a href="<?php echo $nav_link . $next; ?>"><?php _e( 'Next Page &raquo;', 'textdomain' ); ?></a>
            </div>
        <?php endif; ?>
        <?php if( $prev ) : ?>
            <div class="pagination-prev alignright">
                <a href="<?php echo $nav_link . $prev; ?>"><?php _e( '&laquo; Previous Page', 'textdomain' ); ?></a>
            </div>
        <?php endif; ?>
    </div>
    <!-- end pagination -->
    <?php
}

// Put some information about the search results here
function ibio_search_results_heading(){
    echo "<div class='archive-description'>";
    if (isset( $_REQUEST['s'] ) ) {
        $term = stripslashes($_REQUEST['s']);
        echo "<h1 class='archive-title'>Search Results</h1>";
    } else {
        echo "<h1 class='archive-title'>Search</h1>";
    }
    echo "</div>";
}





/* ------------------------  Page Rendering -----------------*/

add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );

// disable automated paragraph in excerpts.
remove_filter( 'the_excerpt', 'wpautop' );

add_action( 'genesis_entry_header', 'ibio_setup_result', 5);

remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description');
add_action ('genesis_loop', 'ibio_search_results_heading', 8);

remove_action( 'genesis_loop', 'genesis_do_loop');
add_action( 'genesis_loop', 'ibio_search_loop');

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action( 'genesis_sidebar', 'ibio_search_sidebar' );
genesis();
