<?php

global $current_video;

// unpack the global data container
$v 			= $current_video['video'];
$counter 	= $current_video['counter'];
$num_parts 	= $current_video['num_parts'];

$title = isset( $v[ 'part_title' ] ) ?  esc_attr( $v[ 'part_title' ] ) : '';
if ( $num_parts > 1 && is_singular( IBioTalk::$post_type) ){
	$title = "Part $counter: " . $title;
}
$download = isset( $v[ 'video_download_url' ] ) ?  esc_url( $v[ 'video_download_url' ] ) : '';
$download_low_res = isset( $v[ 'video_download_url_low_res' ] ) ?  esc_url( $v[ 'video_download_url_low_res' ] ) : '';
$audio_download = isset( $v[ 'audio_download' ] ) ?  esc_url( $v[ 'audio_download' ] ) : '';
$video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';
$size = isset( $v[ 'download_size' ] ) ?  '<span class="size">' . esc_attr( $v[ 'download_size' ] ) . '</span>' : '';


// feature tabs.
$feature_tabs = array();

$helptext = 'title="Right-click to save media file directly."';

$length = isset( $v[ 'video_length' ] ) ?  '<span class="length">Duration: ' . esc_attr( $v[ 'video_length' ] ) . '</span>' : '';
if ( !empty($length) ) {
    $feature_tabs['duration'] = array(
        "tab_title" => null,
        "tab_content" => $length,
        "target"    => null,
        "tab_style" => 'inline'
    );
}


/*
 * if we have a high-res video to download, we can automatically assume there's a low-res version.
 *
 */
$download_link = !empty($download) ? "<a href='$download' target='_blank' download class='download hi-res' $helptext>Hi-Res</a>" : '';
$download_low_res = !empty( $download ) ? str_replace('hi.mp4', 'lo.mp4', $download) : 'null';
$download_low_res_link = !empty($download_low_res) ? "<a href='$download_low_res' target='_blank' download class='download lo-res' $helptext>Low-Res</a>" : '';
$audio_download_link = !empty($audio_download) ? "<a href='$audio_download' target='_blank' download class='download' $helptext>Audio</a>" : '';





if ( !empty( $download ) || !empty( $audio_download) ){

	$download_link_array = array( $download_link, $download_low_res_link, $audio_download_link);

	$media_downloads = "<ul class='dropdown-menu'>";
	foreach ( $download_link_array as $dl ){
		if ( !empty( $dl ) ) {

			$media_downloads .= "<li>$dl</li>";
		}
	}
	$media_downloads .= '</ul>';

		$feature_tabs['download-links'] = array(
			"tab_title" => 'Downloads',
			"tab_content" => $media_downloads,
			"target"    => 'video-part-downloads-' . $counter,
			"tab_style" => 'dropdown'
		);




}


$subtitle_downloads = !empty( $v[ 'download_subtitled_video'] ) ? $v[ 'download_subtitled_video' ] : null;
$subtitles = '';
if ( is_array( $subtitle_downloads ) ){

    $subtitles = "<ul class='dropdown-menu'>";
    foreach ( $subtitle_downloads as $d ){
        $subtitles .= "<li><a href='{$d['video_download_url']}' download $helptext class='download' target='_blank'>{$d['language']}</a></li>";
    }
    $subtitles .= '</ul>';

    $feature_tabs['subtitles'] = array(
        "tab_title" => 'Subtitles',
        "tab_content" => $subtitles,
        "target" => 'video-part-subtitles-' . $counter,
        "tab_style" => 'dropdown'
    );
}

$transcript = (isset( $v[ 'transcript' ] ) &&  strlen($v[ 'transcript' ]) > 1) ?   "<div class=\"scroll-pane\">{$v[ 'transcript' ]}</div>" : '';

if ($transcript){
    $feature_tabs['transcript'] = array(
        "tab_title" => 'Transcript',
        "tab_content" => $transcript,
        "target" => 'video-part-transcript-'. $counter,
        "tab_style" => 'toggle'
    );
}

// make the tab control.


/*
 *
'<span class="transcript toggle" data-toggle="video-part-transcript-'. $counter .'">View Transcript</span><div id="video-part-transcript-'. $counter .'" class="drawer" style="display:none">' .
*/


echo "<div class='single-video part-$counter'>";

if ( $num_parts > 1 || is_singular( IBioSession::$post_type )) {
    echo "<header><h2 class='title'>$title</h2>";
    if (is_singular( IBioSession::$post_type ) && !empty( $v['video_description'])) {
        echo "<div class='description'>{$v['video_description']}</div>";
    }
    echo "</header>";
}
echo '<div class="content">';

$embed = wp_oembed_get( $video_url , array( 'width' => 800 ) );
$vid_id = " id='vtframe-$counter'";

// attach the showinfo parameter to the oembed.
// rel=0 is show related videos from the same channel
// enablejsapi is for letting us control the playback through javascript (See ibio.js)
// see: https://developers.google.com/youtube/player_parameters for more details.
$embed = preg_replace( '/src="(.+)oembed"/', 'src="$1oembed&showinfo=0&enablejsapi=1&rel=0" '.$vid_id, $embed );

if ( $embed == false ){
	$embed = '<div class="empty-video">We were unable to retrieve the video</div>';
}

echo $embed;

echo '</div><div class="footer"><div class="row controls">';

// make the nav tabs for hi-res video, audio download, subtitles, and transcript.

if ( !empty( $feature_tabs ) ) {
    $tab_nav = '<ul class="nav nav-tabs" role="tablist" data-tabs="tabs">';
    $tab_contents = '<div class="tab-content">';
    foreach ( $feature_tabs as $key => $tab ) {
        if ( !$tab['tab_title']) {
            $button = "<li class=\"{$tab['tab_style']} $key\">{$tab['tab_content']}</li>";
            $pane = '';
        } else if ( $tab['tab_style'] == 'dropdown'){
            $button =  "<li role=\"presentation\" class=\"dropdown {$key}\">";
            $button .= "<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"> {$tab['tab_title']}</a>";
            $button .= $tab['tab_content'];
            $button .= "</li>";
            $pane = '';
        } else if ( $tab['tab_style'] == 'toggle'){
            $button =  "<li role=\"presentation\" class=\"dropdown {$key}\">";
            $button .= "<a class=\"dropdown-toggle toggle\" data-toggle=\"{$tab['target']}\" href=\"#\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"> {$tab['tab_title']}</a>";
            $button .= '</li>';
            $pane = "<div id=\"{$tab['target']}\" class=\"tab-pane {$key}\" tabindex=\"-1\" >{$tab['tab_content']}</div>";
        } else {
            $button = "<li class=\"{$tab['tab_style']} $key \"><a href=\"#\" data-target='{$tab['target']}'>{$tab['tab_title']}</a></li>";
            $pane = "<div id=\"{$tab['target']}\" class=\"tab-pane {$key}\" tabindex=\"-1\" >{$tab['tab_content']}</div>";
        }

        $tab_nav .= $button;
        $tab_contents .= $pane;
    }
    $tab_contents .= '</div>';
    $tav_nav = $tab_nav;

    echo $tab_nav;
    echo "</ul>";
    echo $tab_contents;
}

echo '</div></div></div>';
