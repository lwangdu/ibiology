<?php

	/* Template for single ibiology podcast. Mostly so we have a sidebar.  */



// force full width layout
add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );
genesis();