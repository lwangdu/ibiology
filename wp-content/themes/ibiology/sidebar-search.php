<?php


// custom filter for the search sidebar

$s = isset( $_REQUEST['s']) ?  $_REQUEST['s'] : null;

?>

<div class="widget">
    <form class="search-form"  itemprop="potentialAction" itemscope="" itemtype="https://schema.org/SearchAction" method="get" role="search">
        <meta itemprop="target" content="<?php echo bloginfo('ur');?>/?s={s}">
        <label class="search-form-label screen-reader-text" for="searchform-side">Search this website</label>
        <input itemprop="query-input" name="s" id="searchform-side" placeholder="Search this website …" type="search" value="<?php echo $s; ?>">

        <label for 'post_type'><input type="checkbox" name="post_type[]" value="<? echo IBioTalk::$post_type; ?>" />Talks<br/>
        <input type="checkbox" name="post_type[]" value="<? echo IBioSpeaker::$post_type; ?>" />Speakers<br/>
        <input type="checkbox" name="post_type[]" value="<? echo IBioPlaylist::$post_type; ?>" />Playlists<br/>
        <input type="checkbox" name="post_type[]" value="<? echo IBioSession::$post_type; ?>" />Flipped Course Sessions<br/>
        <input type="checkbox" name="post_type[]" value="post" />Blog Posts<br/>

        <input value="Search" type="submit">

    </form>


</div>
<?php

dynamic_sidebar( 'sidebar_search' );