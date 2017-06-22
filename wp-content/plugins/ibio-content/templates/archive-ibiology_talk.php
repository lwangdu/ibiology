<?php

// force full width layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

add_action( 'wp_head', 'ibio_content_archive_setup' );

genesis();