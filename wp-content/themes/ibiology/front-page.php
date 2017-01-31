<?php

/**
 * Front page Template
 *
 * @package		iBiology
 * @Authour  	Anca Mosoiu and Lobsang Wangdu
 * @Link 	 	https://www.yowangdu.com
 * @copyright 	Copyrigh (c) 2017, Lobsang Wangdu
 * @license 	GPL-2.0+
 */

add_action( 'genesis_meta', 'ibiology_home_page_setup' );

/**
* Set up the home layout by conditionlly loading section when widgets.
* are active
*
* @since 1.0.0
*/
function ibiology_home_page_setup() {
	$home_sidebars = array(
	'new_talk' 		=>is_active_sidebar( 'new-talk' ),
	'playlist' 		=>is_active_sidebar( 'playlist' ),
	);
// Return early if no sidebars are active.
if ( ! in_array( true, $home_sidebars )) {
	return;
}
	
// Add home welcome area if "home welcom" widget area is active.
if ( $home_sidebars['new_talk']) {
	add_action( 'genesis_after_header', 'ibiology_add_new_talk' );

}
	
		
// Add home call to action area if "Call to action" widget area is active.
if ( $home_sidebars['playlist']) {
	add_action( 'genesis_after_header', 'ibiology_add_playlist');
	}
	
}
/**
* Display content for the "Home new talk" section.
*
* @since 1.0.0
*/
function ibiology_add_new_talk() {
	genesis_widget_area('new-talk',
		array(
			'before' 	=>'<div class="new-talk"><div class="wrap">',
			'after'		=> '</div></div>',
		)
	);
	
}

/**
* Display content for the "Playlist" section.
*
* @since 1.0.0
*/
function ibiology_add_playlist() {
	echo "<div class='wrap'><p>Welcome!!!</p></div>";
	genesis_widget_area('playlist',
		array(
			'before' 	=>'<div class="playlist"><div class="wrap">',
			'after'		=> '</div></div>',
		)
	);
	
}
genesis();