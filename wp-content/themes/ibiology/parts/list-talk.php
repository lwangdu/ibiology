<?php

	// Template part for displaying a list of talks with a short title and permalink.
	

global $post;

?>

<li class='talk-list-item'>
<strong class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a>


</li>