<?php
// Show the related talks based on the selected category.
// Use this in the single talk page when there's only one video 

$related_category = intval( get_field( 'related_talks' ) );

if ( !empty( $related_category ) ){

	$talk = get_queried_object();  
	
	
	$related_talks = new WP_Query( array (
			'post_type' => IbioTalk::$post_type,
			'cat'       => $related_category,
			'posts_per_page'  => 3,
			'post__not_in'    => array( $talk->ID ),
            'orderby' => 'rand'
		) );
		
	if ( $related_talks->have_posts() ) {
		$category_info = get_term( $related_category, 'category');
		echo '<div class="related-items row"><header>More Talks in '. $category_info->name .'</header>';
		echo '<ul class="related-by-category talks-list stack">';
		while ( $related_talks->have_posts() ) {
			$related_talks->the_post();
			get_template_part( 'parts/list-talk');
		}
		echo '</ul>';
		echo '<div style="clear:both"></div>';
		echo '</div>';
		wp_reset_query();
	}      
	

}