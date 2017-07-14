<?php
/**
 * iBiology in.
 *
 * This file adds the speakers page template to the iBiology Theme.
 *
 * @package iBiology
 * @author  Lobsang Wangdu
 * @license GPL-2.0+
 * @link    http://www.ibiology/
 */

//* Template Name: FAQs 

//custom hooks below here...

// Just an example.
remove_action('genesis_loop', 'genesis_do_loop');
/**
 * Example function that replaces the default loop with a custom loop querying 'PostType' CPT.
 * Remove this function (along with the remove action hook) to show default page content.
 * Or feel free to update the $args to make it work for you.
*/

add_action('genesis_loop', 'faq_loop');
function faq_loop() {

if ( have_rows( 'faqs' ) ):

	while ( have_rows( 'faqs' ) ) : the_row(); 
		the_sub_field( 'question' ); 
		the_sub_field( 'answer' );
	endwhile;
else :
	
endif;
	
	
 } 

//* Run the Genesis loop
genesis(); 
