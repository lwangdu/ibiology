<?php


// custom filter for the search sidebar

$s = isset( $_REQUEST['s']) ?  $_REQUEST['s'] : null;

$post_types = isset( $_REQUEST['post_type']) ?  $_REQUEST['post_type'] : array();

// check to see which post types are selected do we can check the right boxes.

$talks_sel = $speakers_sel = $playlists_sel = '';

if (in_array( IBioTalk::$post_type, $post_types) ) $talks_sel = 'checked';
if (in_array( IBioSpeaker::$post_type, $post_types) ) $speakers_sel = 'checked';
if (in_array( IBioPlaylist::$post_type, $post_types) ) $playlists_sel = 'checked';



?>

<div class="widget search">
    <h3 class="widget-title widgettitle">Modify Your Search</h3>
    <form class="search-form"  itemprop="potentialAction" itemscope="" itemtype="https://schema.org/SearchAction" method="get" role="search">
        <meta itemprop="target" content="<?php echo bloginfo('ur');?>/?s={s}">
        <label class="search-form-label screen-reader-text" for="searchform-side">Search this website</label>
        <input itemprop="query-input" name="s" id="searchform-side" placeholder="Search this website …" type="search" value="<?php echo $s; ?>">
        <p style="margin-top:20px; margin-bottom:4px;">Show only:
        <label ><input type="checkbox" name="post_type[]" value="<? echo IBioTalk::$post_type; ?>" <?php echo $talks_sel; ?> />Talks</label>
        <label><input type="checkbox" name="post_type[]" value="<? echo IBioSpeaker::$post_type; ?>"  <?php echo $speakers_sel; ?> />Speakers</label>
        <label><input type="checkbox" name="post_type[]" value="<? echo IBioPlaylist::$post_type; ?>"  <?php echo $playlists_sel; ?> />Playlists</label>
        </p>
        <input value="Search" type="submit">

    </form>


</div>
<?php

dynamic_sidebar( 'sidebar_search' );