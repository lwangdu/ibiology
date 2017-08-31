<?php

global $current_video;

// unpack the global data container
$v 					= $current_video['video'];
$counter 		= $current_video['counter'];
$num_parts 	= $current_video['num_parts'];

$title = isset( $v[ 'part_title' ] ) ?  esc_attr( $v[ 'part_title' ] ) : '';
if ( $num_parts > 1 ){
	$title = "Part $counter: " . $title;
}
$length = isset( $v[ 'video_length' ] ) ?  '<span class="length">Duration: ' . esc_attr( $v[ 'video_length' ] ) . '</span>' : '';
$download = isset( $v[ 'video_download_url' ] ) ?  esc_url( $v[ 'video_download_url' ] ) : '';
$download_link = !empty($download) ? "<span class='video-part-download'><a href='$download' target='_blank'>Hi-Res</a></span>" : '';

$size = isset( $v[ 'download_size' ] ) ?  '<span class="size">' . esc_attr( $v[ 'download_size' ] ) . '</span>' : '';
$transcript = (isset( $v[ 'transcript' ] ) &&  strlen($v[ 'transcript' ]) > 1) ? '<span class="transcript toggle" data-toggle="video-part-transcript-'. $counter .'">View Transcript</span><div id="video-part-transcript-'. $counter .'" class="drawer" style="display:none">' .  $v[ 'transcript' ] . '</div>' : '';
$video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';

$subtitle_downloads = !empty( $v[ 'download_subtitled_video'] ) ? $v[ 'download_subtitled_video' ] : null;

$subtitles = '';
if ( is_array( $subtitle_downloads ) ){
	$subtitles = "<span class='toggle subtitles' data-toggle='subtitle-downloads-$counter'>Subtitled Version</span><div id='subtitle-downloads-$counter' class='drawer' style='display:none'><ul>";
	foreach ( $subtitle_downloads as $d ){
		$subtitles .= "<li><a href='{$d['video_download_url']}'>{$d['language']}</a></li>";
	}    
	$subtitles .= '</ul></div>';
} 

if ( $download_link || $subtitles ){
	$download_label = 'Download: ' ;
} else {
	$download_lael = '';
}

echo "<div class='single-video part-$counter'><header><h2 class='title'>$title</h2></header><div class='content'>";
$embed = wp_oembed_get( $video_url , array( 'width' => 800 ) );

// attach the showinfo parameter to the oembed.      
$embed = preg_replace( '/src="(.+)oembed"/', 'src="$1oembed&showinfo=0"', $embed );

if ( $embed == false ){
	$embed = '<div class="empty-video">We were unable to retrieve the video</div>';
}

echo $embed;

echo '</div><div class="footer"><div class="row controls">';
echo "<span class='video-length' data-length='$length'>$length</span>";
echo $download_label;
echo $download_link;
echo $subtitles;
echo $transcript;
echo '</div></div></div>';
