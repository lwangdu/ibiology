<?php

	// Template part for displaying a list of talks with a short title and resources link.
	

global $post;

$short_title = get_field( 'short_title' );

$title = empty( $short_title ) ? $post->post_title : $short_title;
$description = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true);

$parts = get_field('videos' );

if ( is_array( $parts) ){
    $num_parts = count( $parts );
} else {
    $num_parts = 0;
}

$resources_url = ibio_get_resources_url( $post->ID );

$audience_list = wp_get_post_terms( $post->ID, 'audience' );



?>

<li class='list-item item'>
<figure class="post-image"><a href="<?php echo $resources_url ?>"><?php the_post_thumbnail(); ?></a></figure>
<h3 class="entry-title"><a href="<?php echo $resources_url ?>"><?php echo $title; ?></a></h3>
    <p class="excerpt"><?php echo $description; ?></p>
    <p class="icon-video"> <?php echo $num_parts; ?> videos </p>
    <?php echo ibio_display_audiences( $audience_list, '' ); ?>
    <div class="item-footer"><a class="button" href="<?php echo $resources_url?>">View Seminar Resources</a></div>
</li>