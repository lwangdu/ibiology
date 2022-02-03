<?php

/* Get the items on a playlist */

function ibio_playlist_items($playlist, $connected_type = 'playlist_to_talks', $audience = null, $orderby = 'date')
{

    $args = array(
        'post_type' => 'ibiology_talk',
        'connected_type' => $connected_type,
        'connected_items' => $playlist,
        'post_status' => 'publish',
        'nopaging' => true,
        'orderby' => 'date'
    );


    if (!empty($audience)) {
        $audience_query = array(
            'taxonomy' => 'audience',
            'field' => 'slug',
            'terms' => $audience
        );

        $args['tax_query'] = array($audience_query);
    }


    $items = new WP_Query($args);

    // loop through to get the item order.

    foreach ($items->posts as $t) {
        $playlist_order = p2p_get_meta($t->p2p_id, 'order', true);
        $t->menu_order = intval($playlist_order);
    }

    usort($items->posts, 'ibio_compare_playlist_posts');

    wp_reset_query();

    return $items;
}

/*


    @param: $orderby = 'date', 'next', 'menu_order'

*/

function ibio_talks_playlist($playlist = null, $maxitems = 0, $audience = null, $current_talk = 0, $style = 'grid', $orderby = '')
{


    $start = $current_talk;

    if ($orderby == 'date') $start = 0;

    if (!$playlist) {
        $playlist = get_queried_object();
    }


    $talks = ibio_playlist_items($playlist, 'playlist_to_talks',  $audience, $orderby);

    $skipped_talks = array();
    $prev_tak = null;

    if ($talks->have_posts()) {
        $counter = 0;
        echo "<ul class='talks $style'>";
        foreach ($talks->posts as $t) {
            if ($maxitems > 0 && $counter >= $maxitems) break;
            // should we start displaying items?
            global $post;
            $post = null;
            if ($start > 0 && $t->ID != $start) {
                $skipped_talks[] = $t; // save them just in case we have to show some of the beginning talks.
                $prev_talk = $t;
                continue;
            } else if ($start > 0 && $t->ID == $start) {
                $start = 0;
                if ($orderby === 'menu_order') {
                    if ($prev_talk) {
                        $post = $prev_talk; // show a link to the previous post.
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            } else {
                $post = $t;
            }

            setup_postdata($post);
            //get_template_part( 'parts/list-talk');
            ibio_get_template_part('shared/list', 'talk');
            $counter++;
        }
        // loop back around to the beginning and show a few more talks
        if ($maxitems > $counter) {
            $talks->rewind_posts();
            while ($talks->have_posts() &&  $maxitems > $counter) {
                $talks->the_post();
                //get_template_part( 'parts/list-talk');
                ibio_get_template_part('shared/list', 'talk');
                $counter++;
            }
        }
        echo '</ul>';
    }

    $sessions = ibio_playlist_items($playlist, 'playlist_to_session',  $audience);
    if ($sessions->have_posts()) {
        $counter = 0;
        echo "<ul class='sessions $style'>";
        foreach ($sessions->posts as $t) {
            if ($maxitems > 0 && $counter >= $maxitems) break;
            if ($start > 0 && $t->ID != $start) {
                continue;
            } else if ($start > 0 && $t->ID == $start) {
                $start = 0;
                continue;
            }
            global $post;
            $post = $t;
            setup_postdata($post);
            //get_template_part( 'parts/list-talk');
            ibio_get_template_part('shared/list', 'talk');
            $counter++;
        }
        echo '</ul>';
    }

    wp_reset_postdata();
}

function ibio_talks_playlist_expanded($playlist = null, $maxitems = 0, $orderby = '', $group_by = '')
{


    $start = 0;

    if (!$playlist) {
        $playlist = get_queried_object();
    }


    global $talks, $sessions;
    $talks = ibio_playlist_items($playlist, 'playlist_to_talks', $orderby);
    $sessions = ibio_playlist_items($playlist, 'playlist_to_session');



    if ($talks->have_posts()) {

        if ($group_by === 'category') {
            // bucket the talks into categories
            $grouped_posts = array('none ', array());
            $categories = array();

            //echo  "Talks: " . count( $talks->posts ) . "<br/>";

            usort($talks->posts, function ($a, $b) {
                return $a->post_title <=> $b->post_title;
            });

            foreach ($talks->posts as $p) {

                $cats = get_the_category($p->ID);

                if (empty($cats)) {
                    $grouped_posts['none'][] = $p;
                    //echo " <br/>Found a post with no category<br/>";
                    continue;
                } else if (count($cats) > 1) {
                    $primary_cat = get_post_meta($post->ID, '_yoast_wpseo_primary_category', true);

                    if (empty($primary_cat)) {
                        $primary_cat = array_shift($cats);
                    } else {
                        $primary_cat = get_term($primary_cat, 'category');
                    }
                } else {
                    $primary_cat = array_shift($cats);
                }


                if (isset($categories[$primary_cat->slug])) {
                    $grouped_posts[$primary_cat->slug][] = $p;
                } else {
                    $categories[$primary_cat->slug] = $primary_cat;
                    $grouped_posts[$primary_cat->slug] = array($p);
                }
            }

            usort($categories, function ($a, $b) {
                return $a->name > $b->name;
            });


            foreach ($categories as $cat) {
                echo "<h2 id='{$cat->slug}'>{$cat->name}</h2>";
                echo "<ul class='big_card_layout'>";

                foreach ($grouped_posts[$cat->slug] as $p) {
                    global $post;
                    $post = $p;
                    setup_postdata($post);
                    ibio_get_template_part("shared/list", 'talk-with-resources');
                }

                echo "</ul>";
            }
        } else {
            echo "<ul class='big_card_layout'>";

            while ($talks->have_posts()) {
                global $post;
                $talks->the_post();
                ibio_get_template_part("shared/list", 'talk-with-resources');
            }


            echo "</ul>";
        }
    }

    if ($sessions->have_posts()) {
        ibio_get_template_part("shared/expanded", "talks-table");
    }


    wp_reset_postdata();
}


function ibio_compare_playlist_posts($a, $b)
{

    if (!isset($a->menu_order) || !isset($b->menu_order)) return 0;
    if ($a->menu_order == $b->menu_order) return $b->post_date > $a->post_date;
    //if ($a->menu_order == $b->menu_order) return 0;

    // always sort zeroes at the end (things w/ an order supersede things w/out an order
    if ($a->menu_order == 0) return 1;
    if ($b->menu_order == 0) return -1;

    if ($a->menu_order > $b->menu_order) {
        return 1;
    } else if ($a->menu_order < $b->menu_order) {
        return -1;
    }
}


/* Playlist Shortcode.  Use when you want to show the contents of a playlist on a page or post */

add_shortcode('ibio_playlist', 'ibio_playlist_shortcode');
function ibio_playlist_shortcode($atts)
{

    $atts = shortcode_atts(array(
        'id' => null,
        'numtalks' => 4,
        'start_index' => 0,
        'audience' => null,
        'expanded' => false,
        'group_by' => null
    ), $atts, 'ibio_playlist');


    if (empty($atts['id'])) {
        return '<span style="color\:red">Please supply a playlist ID to retrieve talks</span>';
    }


    $playlist = get_post($atts['id']);
    if (empty($playlist) || $playlist->post_type != IBioPlaylist::$post_type) {
        return '<span style="color\:red">Please supply a valid playlist ID to retrieve talks</span>';
    }

    ob_start();
    if ($atts['expanded'] != true) {
        ibio_talks_playlist($playlist, $atts['numtalks'], $atts['audience']);
    } else {
        ibio_talks_playlist_expanded($playlist, $atts['numtalks'], $atts['audience'], $atts['group_by']);
    }

    $playlist = ob_get_clean();

    return $playlist;
}
