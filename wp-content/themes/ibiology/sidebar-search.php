<?php


// custom filter for the search sidebar

$s = isset( $_REQUEST['s']) ?  $_REQUEST['s'] : null;

?>

<div class="widget">
    <form class="search-form" itemprop="potentialAction" itemscope="" itemtype="https://schema.org/SearchAction" method="get" action="http://www.ibiology.dev/" role="search"><meta itemprop="target" content="http://www.ibiology.dev/?s={s}"><label class="search-form-label screen-reader-text" for="searchform-59dfa24b4e46c1.47178120">Search this website</label>
        <input itemprop="query-input" name="s" id="searchform-side" placeholder="Search this website …" type="search">

        <label for 'post_type'><input type="checkbox" name="post_type[]" value="<? echo IBioTalk::$post_type; ?>" />Talks<br/>
        <input type="checkbox" name="post_type[]" value="<? echo IBioSpeaker::$post_type; ?>" />Speakers<br/>
        <input type="checkbox" name="post_type[]" value="<? echo IBioSpeaker::$post_type; ?>" />Speakers<br/>
        <input type="checkbox" name="post_type[]" value="<? echo IBioSpeaker::$post_type; ?>" />Speakers<br/>

        <input value="Search" type="submit"></form>


</div>

dynamic_sidebar( 'sidebar_search' );