<?php

	// Template part for displaying a list of talks with a short title and permalink.
	

global $post;

global $post;

$short_title = get_field( 'short_title' );

$title = empty( $short_title ) ? $post->post_title : $short_title;


?>

<li class='talk-list-item'>
<strong class="entry-title"><a href="<?php the_permalink(); ?>"><?php echo $title; ?></a>
<figure class="post-image"><?php the_post_thumbnail(); ?></figure>

</li>