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
	'home_welcome' 		=>is_active_sidebar( 'home-welcome' ),
	'call_to_action' 	=>is_active_sidebar( 'call-to-action' ),
	);
// Return early if no sidebars are active.
if ( ! in_array( true, $home_sidebars )) {
	return;
}
	
// Add home welcome area if "home welcom" widget area is active.
if ( $home_sidebars['home_welcome']) {
	add_action( 'genesis_after_header', 'ibiology_add_home_welcome' );

}
	
		
// Add home call to action area if "Call to action" widget area is active.
if ( $home_sidebars['call_to_action']) {
	add_action( 'genesis_after_header', 'ibiology_add_call_to_action');
	}
	
}
/**
* Display content for the "Home Welcome" section.
*
* @since 1.0.0
*/
function ibiology_add_home_welcome() {
	genesis_widget_area('home-welcome',
		array(
			'before' 	=>'<div class="home-welcome"><div class="wrap">',
			'after'		=> '</div></div>',
		)
	);
	
}

/**
* Display content for the "Call to Action" section.
*
* @since 1.0.0
*/
function ibiology_add_call_to_action() {
	genesis_widget_area('call-to-action',
		array(
			'before' 	=>'<div class="call-to-action"><div class="wrap">',
			'after'		=> '</div></div>',
		)
	);
	
}
genesis();