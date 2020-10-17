<?php

/* Template for the resource catalog */

$args = array(
	'post_type' => array( IBioTalk::$post_type, IBioSession::$post_type),
	'post_status' => 'publish',
	'meta_key' => 'has_educator_resources',
	'posts_per_page' => -1

);


// Get all the speakers for the talks we'll need and put them in an array map

$speakers_query = 'select p2p.p2p_from as \'speaker\', p.post_title as \'name\', p2p.p2p_to as \'talk\' from wp_p2p as p2p, wp_posts p  where p2p.p2p_type in (\'speaker_to_talk\', \'speaker_to_session\') and p2p.p2p_from = p.ID;';
global $wpdb;
$speakers_results = $wpdb->get_results( $speakers_query );

$speakers_map = array();

foreach ($speakers_results as $r ){
	if ( isset( $speakers_map[ $r->talk] ) ){
		$speakers_map[ $r->talk][] = array( 'id' => $r->speaker, 'name' => $r->name );
	} else {
		$speakers_map[ $r->talk] = array( array( 'id' => $r->speaker, 'name' => $r->name ));
	}
}

// options for the pages that contain expanded talks.


$expanded_talk_url = ibio_get_resources_url( null );

//var_dump($speakers_map);

$talks = new WP_Query( $args );

if ($talks->have_posts()):
	?>

	<table class="expanded-talks catalog" style="width:96vw">
		<thead>
		<tr><th class="title">Title</th>
			<th class="video nosort">Video</th>
			<th class="linked-talk">Appears In</th>
			<th class="audience nosort">Audience</th>
			<th class="concepts">Concepts</th>
			<th class="speaker">Speaker(s)</th>
			<th class="resource-downloads nosort">Video Downloads</th>
			<th class="transcript nosort">Transcript</th>
			<th class="restricted-access nosort">PDF Resources<br/>(Educators Only)</th>
		</tr>
		</thead>

		<?php

		global $post;
		while( $talks->have_posts() ):
			$talks->the_post();

			$edu_permalink = $expanded_talk_url . $post->ID;
            $permalink = get_post_permalink( $post->ID );
			$part_permalink = '';

			$talk_type = $post->post_type === IBioTalk::$post_type ? 'Research Talk: ' : '';

			$helptext = 'title="Right-click to save media file directly."';

			/* Loop through all of the videos and display details about them */
			$videos = get_field( 'videos' );
			$talk_title = get_field('short_title');

			if ( !empty( $videos ) ) :
			$counter = 0;

			if ( $post->post_type === IBioTalk::$post_type  ) {
				$appears_in = "<a href='$edu_permalink' target='_blank'>$talk_title</a>";
			} else {

			    // get the playlist for this session.
			    global $wpdb;

			    $query = "select pm.meta_value as p from {$wpdb->postmeta} pm where pm.meta_key = 'educators_link_page_id' and pm.post_id = (select pp.p2p_from from {$wpdb->p2p} pp where pp.p2p_to = {$post->ID} and pp.p2p_type = 'playlist_to_session' limit 1)";
				$resource_page_results = $wpdb->get_results( $query);
				if ( is_array( $resource_page_results ) ){
					$playlist_page = array_shift($resource_page_results);
					$p_id = $playlist_page->p;
					$playlist = get_post($p_id);
					$permalink = get_permalink( $p_id );

				}

			    $appears_in = "<a href='$permalink' target='_blank' >{$playlist->post_title}</a><br/>$talk_title";
            }

			$speakers = '';
			if ( isset( $speakers_map[ $post->ID ] ) ){
				foreach ( $speakers_map[ $post->ID ] as $s ){
						$link = get_post_permalink( $s['id']  );
						$speakers .= "<a href='$link' class='speaker-link' target='_blank'>{$s['name']}</a> ";
				}
			}

			// get the educator resources content
				$resources = get_field( 'educator_resources' );

			foreach( $videos as $v ) :
				$counter++;
				$title = isset( $v[ 'part_title' ] ) ?  esc_attr( $v[ 'part_title' ] ) : '';
				if ( $post->post_type === IBioTalk::$post_type ){
					//$title = "Part $counter: $title";
					$part_permalink = "$permalink#part-$counter";
				} else {
				    $part_permalink = $permalink;
				    if ( isset( $v[ 'research_talk_link' ] ) ){
				        $part_permalink = $v[ 'research_talk_link' ];

                    }
                }
				$download = isset( $v[ 'video_download_url' ] ) ?  esc_url( $v[ 'video_download_url' ] ) : '';
				$audio_download = isset( $v[ 'audio_download' ] ) ?  esc_url( $v[ 'audio_download' ] ) : '';
				$video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';

				$video_description = '';
				if ( $post->post_type === IBioSession::$post_type ) {
					$video_description = isset( $v['video_description'] ) ? '<p class="part-description">' . $v['video_description'] . '</p>': '';
				}

				$concepts = isset( $v['video_concepts'] ) ? $v['video_concepts'] : '';

				$video_thumbnail = isset( $v[ 'video_thumbnail' ] ) ? $v[ 'video_thumbnail' ] : '';
				$video_thumbnail_img = '';
				if ( is_array( $video_thumbnail ) ){
					$img_src = $video_thumbnail['sizes']['thumbnail'];
					$video_thumbnail_img = "<a href='$part_permalink'><img src='$img_src' alt='$title' class=''></a>";
				}

				$size = isset( $v[ 'download_size' ] ) ?  '<span class="size">' . esc_attr( $v[ 'download_size' ] ) . '</span>' : '';
				$length = isset( $v[ 'video_length' ] ) ?  '<span class="length">' . esc_attr( $v[ 'video_length' ] ) . '</span>' : '';

				$download_link = !empty($download) ? "<a href='$download' target='_blank' download class='download hi-res' $helptext>Hi-Res</a>" : 'N/A';
				$download_low_res = '';
				if ( strpos($download_link, "hi.mp4") !== false  ) {
					$download_low_res =  str_replace('hi.mp4', 'lo.mp4', $download);
				}
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

				} else{
					$download = 'N/A';
				}

				$audiences = $v['target_audience'];
				$audience = ibio_display_audiences( $audiences, '' );

				if ( !empty( $v[ 'transcript' ] ) ){
					$transcript_link = ibio_transcript_link( $post->ID, $counter );
					$transcript = "<a href='$transcript_link' target='_blank'>View Transcript</a>";
				} else {
					$transcript = 'N/A';
				}



				?>
				<tr>
					<td class="title"><?php echo $title; ?></td>
					<td class="video"><?php echo $video_thumbnail_img;?><div class="watch-biology"><a href="<?php echo $part_permalink; ?>" target="_blank">Watch on iBiology</a></div>
					<div class="watch-youtube"><a href="<?php echo $video_url; ?>" target="_blank">Watch on YouTube</a>
						<?php  if ( !empty ($video_description) ) echo $video_description; ?></td>
					<td class="linked-talk"><?php echo $talk_type; ?><?php echo $appears_in; ?></td>
					<td class="audience"><?php echo $audience; ?></td>
					<td class="concepts"><?php echo $concepts; ?></td>
					<td class="speakers"><?php echo $speakers; ?></td>
					<td class="resource-downloads controls"><?php echo "$download_link $download_low_res_link $audio_download_link $subtitles ";?></td>
					<td class="transcript"><?php echo $transcript; ?></td>
					<td class="restricted-access"><?php echo $resources; ?></td>
				</tr>

				<?php
			endforeach; //foreach ( $videos as $v )
		endif; //( !empty( $videos )
		endwhile; // have_posts


		?>


	</table>
	<?php

endif; //have_posts