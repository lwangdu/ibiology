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

$resources_page = get_option( 'ibio_teaching_tools_resource_page');
if ( $resources_page) {
	$resources_url = get_permalink( $resources_page );
} else {
	$resources_url = '';
}
if ( strpos($resources_url, '?') ){
	$resources_url .= "&tid={$post->ID}";
} else {
	$resources_url .= "?tid={$post->ID}";
}
?>

<li class='talk-list-item item'>
<figure class="post-image"><a href="<?php echo $resources_url ?>"><?php the_post_thumbnail(); ?></a></figure>
<h3 class="entry-title"><a href="<?php echo $resources_url ?>"><?php echo $title; ?></a></h3>
    <p class="description"><?php echo $description; ?></p>
    <p class="icon-video"> <?php echo $num_parts; ?> videos </p>
    <a class="button" href="<?php echo $resources_url?>">View Seminar Resources</a>
</li>