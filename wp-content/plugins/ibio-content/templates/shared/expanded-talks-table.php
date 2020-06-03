<?php

// Template part for displaying an expanded list of sessions and talks with all their videos and teaching tools.
global $talks;
global $sessions;

// to keep us honest about how many table columns we are displaying
global $columns;
$columns = 7;

if ( is_active_sidebar( 'above-resource-tables' ) ){
    ?>
    <div class="resource-table-meta above-resource-table">
    <?php
    dynamic_sidebar('above-resource-tables' );
    ?>
    </div>
    <?php
}
?>

<table class="expanded-talks">
	<thead>
	<tr><th>Title</th>
		<th>Video</th>
		<th>Description</th>
		<th>Duration</th>
		<th>Video Downloads</th>
		<th>Transcript</th>
		<th class="restriced-access">PDF Resources<br/>(Educators Only)</th>
	</tr>
	</thead>

<?php

foreach($talks->posts as $t){
	global $post;
	$post = $t;

	setup_postdata($post);

	ibio_get_template_part( 'shared/expanded' , 'talks-table-row');

}

foreach($sessions->posts as $t){
	global $post;
	$post = $t;

	setup_postdata($post);

	ibio_get_template_part( 'shared/expanded' , 'talks-table-row');

}

?>
</table>

<?php

if ( is_active_sidebar( 'below-resource-tables' ) ){
	?>
    <div class="resource-table-meta below-resource-table">
		<?php
		dynamic_sidebar('below-resource-tables' );
		?>
    </div>
    <?php

}
