<?php 

	// Category Archive Template.  Will default to pulling in Research Talks
	



/* -------------------  Page Rendering --------------------------*/
//* Force full width content layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

//filter the default query to apply the post_type.

genesis();
