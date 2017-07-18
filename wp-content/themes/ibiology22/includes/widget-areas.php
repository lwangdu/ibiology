<?php
/**
 * Theme Register widget areas
 *
 * @package		iBiology
 * @Authour  	Anca Mosoiu and Lobsang Wangdu
 * @Link 	 	https://www.yowangdu.com
 * @copyright 	Copyright (c) 2017, Lobsang Wangdu
 * @license 	GPL-2.0+
 */

//* Register home new talk widget area
genesis_register_sidebar( array(
'id'           => 	'new-talk',
'name'         => 	__( 'New talk', 'ibiology' ),
'description'  => 	__( 'This is a home widget area that show on the front page', 'ibiology' ),
) );
	
// Register playlist widget area
genesis_register_sidebar( array(
'id'            => 	'playlist',
'name'          => 	__( 'Playlist', 'ibiology' ),
'description'   => 	__( 'This is a playlist  widget area that show on the front page', 'ibiology' ),
) );