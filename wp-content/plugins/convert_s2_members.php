<?php
/**
 * @package iIbiology
 * @version 1.0
 */
/*
Plugin Name: Convert S2 Members
Description: Convert S2 member users into RCP members
Author: Anca Mosoiu
Version: 0.1
Author URI: http://ibiology.org
*/


/* -----------   Activate / Deactivate  ------------- */
register_activation_hook(__FILE__, 'ibio_convert_members');
add_action('activate_plugin', 'ibio_convert_members');

// load the users and convert them from s2 to RCP by mapping their usermeta
function ibio_convert_members(){

	$args = array(
		'role'         => 's2member_level1',
		'role__in'     => array(),
		'meta_key'     => 'wp_s2member_custom_fields',
		'orderby'      => 'login',
		'order'        => 'ASC',
		'offset'       => '',
		'search'       => '',
		'number'       => '25',
		'count_total'  => false,
		'fields'       => 'all',
		'who'          => '',
	);
	$users = get_users( $args );

	// example custom fields
	/* a:6:{s:11:"institution";s:23:"University of Salamanca";s:7:"country";s:5:"Spain";s:16:"institution_type";
	s:4:"Grad";s:16:"used_vids_before";s:3:"Yes";s:22:"planning_on_using_vids";s:3:"Yes";s:15:"institution_url";
	s:95:"https://lazarillo.usal.es/nportal/components/directorioPersonal/detalle.jsp?tipo=PDI&uid=u39624";}
	*/

	$fields = array('wp_s2member_paid_registration_times', 'wp_s2member_custom_fields', );

	foreach($users as $u) {
		$meta = get_user_meta($u->ID, 'wp_s2member_custom_fields', true);
		if ( isset($meta['institution']) ){
			update_user_meta( $u->ID,'ibio_institution', $meta['institution'] );
		}
		if ( isset($meta['country']) ){
			update_user_meta( $u->ID,'country', $meta['country'] );
		}
		if ( isset($meta['institution_url']) ){
			update_user_meta( $u->ID,'ibio_educator_proof_url', $meta['institution_url'] );
		}
		if ( isset($meta['institution_type']) ){

			update_user_meta( $u->ID,'ibio_teaching_level', $meta['institution_type'] );
		}
		if ( isset($meta['used_vids_before']) ){
			update_user_meta( $u->ID,'ibio_uses_videos', $meta['used_vids_before'] );
		}
		if ( isset($meta['planning_on_using_vids']) ){
			update_user_meta( $u->ID,'ibio_videos_planned_use', $meta['planning_on_using_vids'] );
		}

		update_user_meta( $u->ID, '_old_wp_s2member_custom_fields', $meta);
		delete_user_meta( $u->ID, 'wp_s2member_custom_fields', $meta);

		// add a new rcp customer
		$customer_id = rcp_add_customer( array(
			'user_id' => $u->ID,
			//'date_registered' => '2019-01-01 12:00:00'
		) );

		//add their membership
		$membership_id = rcp_add_membership( array(
			'customer_id' => $customer_id,
			'object_id' => 1,
			'status' => 'active'
		) );
	}


}

