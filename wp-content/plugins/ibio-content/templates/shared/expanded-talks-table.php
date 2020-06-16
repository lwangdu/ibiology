<?php

// Template part for displaying an expanded list of sessions and talks with all their videos and teaching tools.
global $talks;
global $sessions;

// to keep us honest about how many table columns we are displaying
global $columns;
$columns = 8;

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
	<tr><th class="title">Title</th>
		<th class="video">Video</th>
        <th class="part-description">Description</th>
        <th class="concepts">Concepts</th>
		<th class="duration">Duration</th>
		<th class="resource-downloads">Video Downloads</th>
		<th class="transcript">Transcript</th>
		<th class="restricted-access">PDF Resources<br/>(Educators Only)</th>
	</tr>
	</thead>

<?php

if (!empty ( $talks ) ) {

	foreach ( $talks->posts as $t ) {
		global $post;
		$post = $t;

		setup_postdata( $post );

		ibio_get_template_part( 'shared/expanded', 'talks-table-row' );

	}
}

if (!empty ( $sessions ) ) {
	foreach ( $sessions->posts as $t ) {
		global $post;
		$post = $t;

		setup_postdata( $post );

		ibio_get_template_part( 'shared/expanded', 'talks-table-row' );

	}
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
