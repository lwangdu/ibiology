<?php

/*
 * Display the individual video parts in rows.  Assumes the table is formatted and started.
 */

global $post;
$permalink = get_post_permalink( $post->ID );
$part_permalink = $permalink;

$helptext = 'title="Right-click to save media file directly."';

/* Loop through all of the videos and display details about them */
$videos = get_field( 'videos' );

if ( !empty( $videos ) ) :
	$counter = 0;
	foreach( $videos as $v ) :
		$counter++;
		$title = isset( $v[ 'part_title' ] ) ?  esc_attr( $v[ 'part_title' ] ) : '';
		if ( $post->post_type === IBioTalk::$post_type ){
			$title = "Part $counter: $title";
			$part_permalink = "$permalink#part-$counter";
		}
		$download = isset( $v[ 'video_download_url' ] ) ?  esc_url( $v[ 'video_download_url' ] ) : '';
		$audio_download = isset( $v[ 'audio_download' ] ) ?  esc_url( $v[ 'audio_download' ] ) : '';
		$video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';

		$video_description = isset( $v['video_description'] ) ? $v['video_description'] : '';
		$concepts = isset( $v['video_concepts'] ) ? $v['video_concepts'] : '';

		$video_thumbnail = isset( $v[ 'video_thumbnail' ] ) ? $v[ 'video_thumbnail' ] : '';
		$video_thumbnail_img = '';
		if ( is_array( $video_thumbnail ) ){
			$img_src = $video_thumbnail['sizes']['thumbnail'];
			$video_thumbnail_img = "<a href='$part_permalink'><img src='$img_src' alt='$title' class=''></a>";
		}

		$size = isset( $v[ 'download_size' ] ) ?  '<span class="size">' . esc_attr( $v[ 'download_size' ] ) . '</span>' : '';
		$length = isset( $v[ 'video_length' ] ) ?  '<span class="length">' . esc_attr( $v[ 'video_length' ] ) . '</span>' : '';

		$download_link = !empty($download) ? "<a href='$download' target='_blank' download class='download hi-res' $helptext>Hi-Res</a>" : '';
		$download_low_res = !empty( $download ) ? str_replace('hi.mp4', 'lo.mp4', $download) : 'null';
		$download_low_res_link = !empty($download_low_res) ? "<a href='$download_low_res' target='_blank' download class='download lo-res' $helptext>Low-Res</a>" : '';
		$audio_download_link = !empty($audio_download) ? "<a href='$audio_download' target='_blank' download class='download' $helptext>Audio Only</a>" : '';


		$subtitle_downloads = !empty( $v[ 'download_subtitled_video'] ) ? $v[ 'download_subtitled_video' ] : null;
		$subtitles = '';
		if ( is_array( $subtitle_downloads ) ) {

			$subtitles = "<p class='subtitle-downloads'>Subtitled:</p> <ul class='downloads-list'>";
			foreach ( $subtitle_downloads as $d ) {
				$subtitles .= "<li><a href='{$d['video_download_url']}' download class='download' target='_blank'>{$d['language']}</a></li>";
			}
			$subtitles .= '</ul>';

		}

		if ( !empty( $v[ 'transcript' ] ) ){
		    $transcript_link = ibio_transcript_link( $post->ID, $counter );
		    $transcript = "<a href='$transcript_link' target='_blank'>View/Download Transcript</a>";
        } else {
		    $transcript = 'N/A';
        }



		?>
		<tr>
			<td class="title"><?php echo $title; ?></td>
			<td class="video"><?php echo $video_thumbnail_img;?><a href="<?php echo $part_permalink; ?>" target="_blank">Watch on iBiology</a>
            <br/><a href="<?php echo $video_url; ?>" target="_blank">Watch on YouTube</a></td>
            <td class="concepts"><?php echo $concepts; ?></td>
			<td class="part-description"><?php echo $video_description; ?></td>
			<td class="duration"><?php echo $length; ?></td>
			<td class="resource-downloads controls">
				<?php echo "$download_link $download_low_res_link $audio_download_link $subtitles ";?>

			</td>
			<td class="transcript"><?php echo $transcript; ?></td>
			<td class="restriced-access"></td>
		</tr>

		<?php
	endforeach; //foreach ( $videos as $v )
endif; //( !empty( $videos )
