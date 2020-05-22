<?php

	// a template for showing a single talk for educators.  Includes the title, description, speaker, and a table with educator resources

global $post; // the current talk


$description = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true);
$speakers_list = ibio_get_speaker_list($post);

$resources = get_field( 'educator_resources' );

?>

<section>
	<div class="two-thirds first">
		<p><?php echo $description; ?></p>
		<p><?php echo $speakers_list; ?></p>
	</div>
	<figure class="one-third">
		<?php echo get_the_post_thumbnail($post, 'large');?>
	</figure>
</section>

<table class="expanded-talks">
	<thead>
	<tr><th>Title</th>
		<th>Video</th>
		<th>Description</th>
		<th>Duration</th>
		<th>Video Downloads</th>
		<th>Transcript</th>
		<th class="restriced-access">Materials</th>
	</tr>
	</thead>

	<?php ibio_get_template_part('shared/expanded', 'talks-table-row-parts');?>

	<tr class="resources"><td colspan="7"><strong>Educator Resources for this talk: </strong><?php echo $resources;?></td></tr>

</table>

