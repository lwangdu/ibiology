<?php

global $videos, $v, $counter;

$title = isset( $v[ 'part_title' ] ) ?  esc_attr( $v[ 'part_title' ] ) : '';
$length = isset( $v[ 'video_length' ] ) ?  '<span class="length">Duration: ' . esc_attr( $v[ 'video_length' ] ) . '</span>' : '';
$download = isset( $v[ 'video_download_url' ] ) ?  esc_url( $v[ 'video_download_url' ] ) : '';
$size = isset( $v[ 'download_size' ] ) ?  '<span class="size">' . esc_attr( $v[ 'download_size' ] ) . '</span>' : '';
$download_link = ! empty ( $download ) ? "<a href='$download'>Download $size</a>" : '';
$transcript = isset( $v[ 'transcript' ] ) ? '<span class="transcript toggle" data-toggle="video-part-transcript-'. $counter .'">View Transcript</span><div id="video-part-transcript-'. $counter .'" class="drawer" style="display:none">' .  $v[ 'transcript' ] . '</div>' : '';
$video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';
$video_thumbnail = isset( $v[ 'video_thumbnail' ] ) ? $v[ 'video_thumbnail' ] : '';

// video thumbnail is an array.  Let's grab the thumbnail size of this image.
if ( is_array( $video_thumbnail ) && isset( $video_thumbnail['sizes'] ) && isset( $video_thumbnail['sizes']['thumbnail'] ) ){
	$thumbs[ $counter ] = $video_thumbnail['sizes']['thumbnail']; 
}

echo "<div class='single-video part-$counter'>";
//echo "<header><h2 class='title'>$title</h2></header>";
echo "<div class='content'>";
$embed = wp_oembed_get( $video_url , array( 'height' => 475 ) );
// attach the showinfo parameter to the oembed.      
$embed = preg_replace( '/src="(.+)oembed"/', 'src="$1oembed&showinfo=0"', $embed );
echo $embed;

echo '</div><div class="footer"><div class="row controls">';
echo "<span class='video-length' data-length='$length'>$length</span>";
echo "<span class='video-part-download'><a href='$download'>Download Hi-Res</a></span>";
echo $transcript;
echo '</div></div></div>';
	