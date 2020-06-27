<?php

// Template part for displaying an expanded list of sessions and talks with all their videos and teaching tools.
global $post;

// to keep us honest about how many table columns we are displaying
// this is set in the expanded-talks-table.php file
global $columns;

$description = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true);
$permalink = get_post_permalink( $post );
// educator resources is an HTML field that contains text and links.
$resources = get_field( 'educator_resources' );

$speaker_content = ibio_get_speaker_list( $post );


    /* Show the single row that shows the name and description of the talk or session. */

?>
    <tr class="section-row"><th colspan="<?php echo ($columns - 1) ; ?>">
            <span class="session-title"><?php echo $post->post_title; ?></span>
            <p class="description"><?php echo $description; ?></p>
            <p class="speakers-list"><?php echo $speaker_content;?></p>
            <p class="session-link">Share this session with your students:<a href="<?php echo $permalink;?>" target="_blank">Link</a>.</p>
        </th>
        <td ><?php echo $resources;?></td>
        </tr>
<?php

ibio_get_template_part('shared/expanded', 'talks-table-row-parts');