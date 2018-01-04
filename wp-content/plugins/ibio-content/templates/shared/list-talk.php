<?php

	// Template part for displaying a list of talks with a short title and permalink.
	

global $post;

$short_title = get_field( 'short_title' );

$title = empty( $short_title ) ? $post->post_title : $short_title;

$sequence_num = $post->menu_order;

?>

<li class='talk-list-item item' data-order="<?php echo $sequence_num; ?>">
<figure class="post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a></figure>
<span class="entry-title"><a href="<?php the_permalink(); ?>"><?php echo $title; ?></a></span>
</li>