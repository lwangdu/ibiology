<?php

global $current_video;

// unpack the global data container
$v 			= $current_video['video'];
$counter 	= $current_video['counter'];
$num_parts 	= $current_video['num_parts'];

$title = isset( $v[ 'part_title' ] ) ?  esc_attr( $v[ 'part_title' ] ) : '';
if ( $num_parts > 1 ){
	$title = "Part $counter: " . $title;
}
$length = isset( $v[ 'video_length' ] ) ?  '<span class="length">Duration: ' . esc_attr( $v[ 'video_length' ] ) . '</span>' : '';
$download = isset( $v[ 'video_download_url' ] ) ?  esc_url( $v[ 'video_download_url' ] ) : '';
$video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';
$size = isset( $v[ 'download_size' ] ) ?  '<span class="size">' . esc_attr( $v[ 'download_size' ] ) . '</span>' : '';


// feature tabs.
$feature_tabs = array();

$download_link = !empty($download) ? "<span class='video-part-download'><a href='$download' target='_blank' class='download'>Hi-Res</a></span>" : '';
if ( !empty($download_link)){
    $feature_tabs['download'] = array(
        "tab_title" => null,
        "tab_content" => $subtitles,
        "target"    => null,
        "tab_style" => 'inline'
    );
}

$subtitle_downloads = !empty( $v[ 'download_subtitled_video'] ) ? $v[ 'download_subtitled_video' ] : null;
$subtitles = '';
if ( is_array( $subtitle_downloads ) ){

    $subtitles = "<ul'>";
    foreach ( $subtitle_downloads as $d ){
        $subtitles .= "<li><a href='{$d['video_download_url']}' download  class='download' target='_blank'>{$d['language']}</a></li>";
    }
    $subtitles .= '</ul></div>';

    $feature_tabs['subtitles'] = array(
        "tab_title" => 'Subtitles',
        "tab_content" => $subtitles,
        "target" => 'video-part-subtitles-' . $counter,
        "tab_style" => 'download'
    );
}

$transcript = (isset( $v[ 'transcript' ] ) &&  strlen($v[ 'transcript' ]) > 1) ?   $v[ 'transcript' ] : '';

if ($transcript){
    $feature_tabs['transcript'] = array(
        "tab_title" => 'Subtitles',
        "tab_content" => $transcript,
        "target" => 'video-part-transcript-'. $counter,
        "tab_style" => 'download'
    );
}

// make the tab control.


/*
 *
'<span class="transcript toggle" data-toggle="video-part-transcript-'. $counter .'">View Transcript</span><div id="video-part-transcript-'. $counter .'" class="drawer" style="display:none">' .
*/


echo "<div class='single-video part-$counter'>";

if ( $num_parts > 1) {
    echo "<header><h2 class='title'>$title</h2></header>";
}
echo '<div class="content">';

$embed = wp_oembed_get( $video_url , array( 'width' => 800 ) );
$vid_id = " id='vtframe-$counter'";

// attach the showinfo parameter to the oembed.      
$embed = preg_replace( '/src="(.+)oembed"/', 'src="$1oembed&showinfo=0&enablejsapi=1" '.$vid_id, $embed );

if ( $embed == false ){
	$embed = '<div class="empty-video">We were unable to retrieve the video</div>';
}

echo $embed;

echo '</div><div class="footer"><div class="row controls">';
echo "<div class='video-length' data-length='$length'>$length</div>";

// make the nav tabs for hi-red download, subtitles, and transcript.

if ( !empty( $feature_tabs ) ) {
    $tab_nav = '<ul class="nav nav-tabs" role="tablist">';
    $tab_contents = '<div class="tab-content">';
    foreach ( $feature_tabs as $key => $tab ) {

    }
    $tab_contents .= '</div>';
    $tav_nav .='</ul>';
}

echo '</div></div></div>';
