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
$download = isset( $v[ 'video_download_url' ] ) ?  esc_url( $v[ 'video_download_url' ] ) : '';
$video_url = isset( $v[ 'video_url' ] ) ? esc_html( $v[ 'video_url' ] ) : '';
$size = isset( $v[ 'download_size' ] ) ?  '<span class="size">' . esc_attr( $v[ 'download_size' ] ) . '</span>' : '';


// feature tabs.
$feature_tabs = array();

$length = isset( $v[ 'video_length' ] ) ?  '<span class="length">Duration: ' . esc_attr( $v[ 'video_length' ] ) . '</span>' : '';
if ( !empty($length) ) {
    $feature_tabs['duration'] = array(
        "tab_title" => null,
        "tab_content" => $length,
        "target"    => null,
        "tab_style" => 'inline'
    );
}

$download_link = !empty($download) ? "<span class='video-part-download'><a href='$download' target='_blank' class='download'>Hi-Res</a></span>" : '';
if ( !empty($download_link)){
    $feature_tabs['download'] = array(
        "tab_title" => null,
        "tab_content" => $download_link,
        "target"    => null,
        "tab_style" => 'inline'
    );
}

$subtitle_downloads = !empty( $v[ 'download_subtitled_video'] ) ? $v[ 'download_subtitled_video' ] : null;
$subtitles = '';
if ( is_array( $subtitle_downloads ) ){

    $subtitles = "<ul class='dropdown-menu'>";
    foreach ( $subtitle_downloads as $d ){
        $subtitles .= "<li><a href='{$d['video_download_url']}' download  class='download' target='_blank'>{$d['language']}</a></li>";
    }
    $subtitles .= '</ul>';

    $feature_tabs['subtitles'] = array(
        "tab_title" => 'Subtitles',
        "tab_content" => $subtitles,
        "target" => 'video-part-subtitles-' . $counter,
        "tab_style" => 'dropdown'
    );
}

$transcript = (isset( $v[ 'transcript' ] ) &&  strlen($v[ 'transcript' ]) > 1) ?   $v[ 'transcript' ] : '';

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

// make the nav tabs for hi-red download, subtitles, and transcript.

if ( !empty( $feature_tabs ) ) {
    $tab_nav = '<ul class="nav nav-tabs" role="tablist" data-tabs="tabs">';
    $tab_contents = '<div class="tab-content">';
    foreach ( $feature_tabs as $key => $tab ) {
        if ( !$tab['tab_title']) {
            $button = "<li class=\"{$tab['tab_style']} $key\">{$tab['tab_content']}</li>";
            $pane = '';
        } else if ( $tab['tab_style'] == 'dropdown'){
            $button =  "<li role=\"presentation\" class=\"dropdown\">";
            $button .= "<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"> {$tab['tab_title']} <span class=\"caret\"></span></a>";
            $button .= $tab['tab_content'];
            $button .= "</li>";
            $pane = '';

        } else {
            $button = "<li class=\"{$tab['tab_style']} $key \"><a href=\"#{$tab['target']}\" data-toggle=\"tab\">{$tab['tab_title']}<span class=\"caret\"></span></a></li>";
            $pane = "<div id=\"{$tab['target']}\" class=\"tab-pane\">{$tab['tab_content']}</div>";
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
