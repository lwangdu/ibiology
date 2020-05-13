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

// figure out which relationship to use for getting the connected speakers - speaker_to_session or speaker_to_talk
if ( $post->post_type === IBioSession::$post_type) {
    $connection_type = 'speaker_to_session';
} else {
	$connection_type = 'speaker_to_talk';
}

// Get the Speakers for this talk/session
$talk_speakers = new WP_Query(array(
	'post_type' => 'ibiology_speaker',
	'connected_type' => $connection_type,  // we have to check separately for talks vs. sessions
	'orderby' => 'meta_value',
	'meta_key' => 'last_name',
	'order' => 'ASC',
	'connected_items' => $post,
	'nopaging' => true
));

//var_dump( $talk_speakers);

if ( !empty($talk_speakers) && isset($talk_speakers->posts)) {
	$talk_speaker = $talk_speakers->posts;
}

    // Get the list of speakers with links to their pages
$speaker_content = '';
	if ( ! empty ($talk_speaker) ) {
		$out = array();
		foreach ( $talk_speaker as $t ){
			$surl = get_post_permalink( $t->ID) ;
			$out[] =  "<a href='$surl' class='speaker-link' title='{$t->post_title}'>{$t->post_title}</a>";
		}
        $speaker_content = "With: ";
		$speaker_content .=  implode( ', ', $out);

	}



    /* Show the single row that shows the name and description of the talk or session. */

?>
    <tr><th colspan="<?php echo ($columns - 1) ; ?>" class="section-row">
            <span class="session-title"><a href="<?php echo $permalink;?>"><?php echo $post->post_title; ?></a></span>
            <p class="description"><?php echo $description; ?></p>
            <p class="speakers-list"><?php echo $speaker_content;?></p>
        </th>
        <td><?php echo $resources;?></td>
        </tr>
<?php

/* Loop through all of the videos and display details about them */
$videos = get_field( 'videos' );

if ( !empty( $videos ) ) :
	foreach( $videos as $v ) :
		$title = isset( $v[ 'part_title' ] ) ?  esc_attr( $v[ 'part_title' ] ) : '';
		$download = isset( $v[ 'video_download_url' ] ) ?  esc_url( $v[ 'video_download_url' ] ) : '';
		$audio_download = isset( $v[ 'audio_download' ] ) ?  esc_url( $v[ 'audio_download' ] ) : '';
		$video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';

		$video_thumbnail = isset( $v[ 'video_thumbnail' ] ) ? $v[ 'video_thumbnail' ] : '';
		$video_thumbnail_img = '';
		if ( is_array( $video_thumbnail ) ){
		    $img_src = $video_thumbnail['sizes']['thumbnail'];
			$video_thumbnail_img = "<a href='$permalink'><img src='$img_src' alt='$title' class=''></a>";
        }

		$size = isset( $v[ 'download_size' ] ) ?  '<span class="size">' . esc_attr( $v[ 'download_size' ] ) . '</span>' : '';
		$length = isset( $v[ 'video_length' ] ) ?  '<span class="length">' . esc_attr( $v[ 'video_length' ] ) . '</span>' : '';

		$download_link = !empty($download) ? "<button class='download-link'><a href='$download' target='_blank' download class='download hi-res' $helptext>Hi-Res</a>" : '';
		$download_low_res = !empty( $download ) ? str_replace('hi.mp4', 'lo.mp4', $download) : 'null';
		$download_low_res_link = !empty($download_low_res) ? "<a href='$download_low_res' target='_blank' download class='download lo-res' $helptext>Low-Res</a>" : '';
		$audio_download_link = !empty($audio_download) ? "<a href='$audio_download' target='_blank' download class='download' $helptext>Audio Only</a>" : '';


		$subtitle_downloads = !empty( $v[ 'download_subtitled_video'] ) ? $v[ 'download_subtitled_video' ] : null;
		$subtitles = '';
		if ( is_array( $subtitle_downloads ) ) {

			$subtitles = "Download with subtitles in: <ul class='downloads-list horizontal'>";
			foreach ( $subtitle_downloads as $d ) {
				$subtitles .= "<li><a href='{$d['video_download_url']}' download class='download' target='_blank'>{$d['language']}</a></li>";
			}
			$subtitles .= '</ul>';

		}
		$transcript = (isset( $v[ 'transcript' ] ) &&  strlen($v[ 'transcript' ]) > 1) ?   "Transcript available and coming soon" : '';


		?>
        <tr>
            <td><?php echo $title; ?></td>
            <td><?php echo $video_thumbnail_img;?><a href="<?php echo $video_url; ?>">YouTube</a></td>
            <td>N/A</td>
            <td><?php echo $length; ?></td>
            <td class="controls">
                <?php echo "$download_link $download_low_res_link $audio_download_link $subtitles ";?>

            </td>
            <td><?php echo $transcript; ?></td>
            <td class="restriced-access"></td>
        </tr>

		<?php
	endforeach; //foreach ( $videos as $v )
endif; //( !empty( $videos )
