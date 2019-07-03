<?php

	/* Template for single ibiology podcast. Mostly so we have a sidebar.  */

	function ibio_podcast_related_items(){
		//$post = get_queried_object();
		global $post;

		$post = get_field( 'related_talk' );

		if ( !empty( $post)  ){
			setup_postdata( $post );

			/* echo '<ul>';
			ibio_get_template_part( 'parts/list', 'talk');
			echo '</ul>';
			*/

			echo "<h2>View the full talk on our website</h2>";
			get_template_part( 'parts/post' , 'with-excerpt');

			wp_reset_postdata();


		}


		global $talk_speaker;
		$talk_speaker = get_field( 'related_speakers' );

		ibio_get_template_part( 'shared/related', 'speaker' );


	}


// force full width layout
add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );
add_action( 'genesis_after_loop', 'ibio_podcast_related_items');
genesis();