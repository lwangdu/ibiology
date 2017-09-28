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
    'id'          => 'sidebar_talks_filter',
    'name'        => __( 'Talks Filter', 'ibiology' ),
    'description' => __( 'Used on Explore page, maybe others?', 'ibiology' ),
) );

genesis_register_sidebar( array(
    'id'          => 'sidebar_talks',
    'name'        => __( 'Individual Talk', 'ibiology' ),
    'description' => __( 'This is the sidebar for an individual talk', 'ibiology' ),
) );

genesis_register_sidebar( array(
    'id'          => 'sidebar_search',
    'name'        => __( 'Search Results Page', 'ibiology' ),
    'description' => __( 'This is the sidebar for the Search Results Page', 'ibiology' ),
) );

genesis_register_sidebar( array(
    'id'           => 	'homeoage-1',
    'name'         => 	__( 'Homepage Feature 1', 'ibiology' ),
    'description'  => 	__( 'This is a home widget area that show on the front page', 'ibiology' ),
) );

// Register playlist widget area
genesis_register_sidebar( array(
    'id'            => 	'homepage-2',
    'name'          => 	__( 'Home Feature 2', 'ibiology' ),
    'description'   => 	__( 'This is a playlist  widget area that show on the front page', 'ibiology' ),
) );