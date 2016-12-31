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

//* Register home welocme widget area
genesis_register_sidebar( array(
'id'           => 	'home-welcome',
'name'         => 	__( 'Home Welcome', 'ibiology' ),
'description'  => 	__( 'This is a home widget area that show on the front page', 'ibiology' ),
) );
	
// Register call to action widget area
genesis_register_sidebar( array(
'id'            => 	'call-to-action',
'name'          => 	__( 'Call to Action', 'ibiology' ),
'description'   => 	__( 'This is a call to action  widget area that show on the front page', 'ibiology' ),
) );