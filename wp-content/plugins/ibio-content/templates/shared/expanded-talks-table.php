<?php

// Template part for displaying an expanded list of sessions and talks with all their videos and teaching tools.
global $talks;
global $sessions;

// to keep us honest about how many table columns we are displaying
global $columns;
$columns = 7;


?>

<table class="expanded-talks">
	<thead>
	<tr><th>Title</th>
		<th>Video</th>
		<th>Description</th>
		<th>Speaker</th>
		<th>Video Downloads</th>
		<th>Transcript</th>
		<th class="restriced-access">Materials</th>
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
