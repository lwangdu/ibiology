<?php
/**
 * Plugin Name: iBio Eduction Membership
 * Description: Customize the Restrict Content Pro registration / membership system
 * Version:     1.0.0
 * Author:      Anca Mosoiu & Lobsang Wangdu
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.  You may NOT assume that you can use any other
 * version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.
 *
 * @package    Registration Form
 * @since      1.0.0
 * @copyright  Copyright (c) 2019, Anca Mosoiu & Lobsang Wangdu
 * @license    GPL-2.0+
 */

// Plugin directory
define( 'IBIO_DIR' , plugin_dir_path( __FILE__ ) );

/* Functions that display fields */

add_action( 'rcp_after_password_registration_field', 'ibio_add_user_fields' );

add_action( 'rcp_edit_member_after', 'ibio_start_custom_field_section', 14);
add_action( 'rcp_edit_member_after', 'ibio_add_user_fields', 15 );
add_action( 'rcp_edit_member_after', 'ibio_end_custom_field_section', 16);

add_action( 'rcp_profile_editor_after', 'ibio_add_user_fields', 15 );

/* validation of fields and Error Processing   */

add_action( 'rcp_form_errors', 'ibio_validate_data_on_register', 10 );

/* Save new user fields */

add_action( 'rcp_form_processing', 'ibio_rcp_save_fields_on_register', 10, 2 );

/* Update existing member information  */

add_action( 'rcp_edit_member', 'ibio_rcp_save_fields_on_profile_save', 10 );
add_action( 'rcp_user_profile_updated', 'ibio_rcp_save_fields_on_profile_save', 10 );


add_filter( 'rcp_user_registration_data', 'ibio_rcp_user_registration_data', 20 );

/* Remove username field on RCP */
function ibio_rcp_user_registration_data( $user ) {
	rcp_errors()->remove( 'username_empty' );
	$user['login'] = $user['email'];
	return $user;
}

/**
 * Adds the custom fields to the registration form and profile editor
 *
 */
function ibio_add_user_fields($user_id = 0) {

	$institution = $country = '';
	if ( ! empty( $user_id ) ) {
		$institution = get_user_meta( $user_id, 'ibio_institution', true );
		$country     = get_user_meta( $user_id, 'country', true );
	}

	?>
    <p>
        <label for="ibio_institution"><?php _e( 'Your institution *', 'ibiology' ); ?></label>
        <input name="ibio_institution" id="ibio_institution" type="text"
               value="<?php echo esc_attr( $institution ); ?>"/>
    </p>
    <p>
        <label for="country"><?php _e( 'Your country *', 'ibiology' ); ?></label>
        <input name="country" id="country" type="text" value="<?php echo esc_attr( $country ); ?>"/>
    </p>

	<?php

    // Teaching Level
	$teach = '';
	if ( ! empty( $user_id ) ) {
	    $teach = get_user_meta( $user_id, 'ibio_teaching_level', true );
	}

	?>
    <p>
        <label for="ibio_teaching_level"><?php _e( 'What type of student do you primarily teach? *', 'ibiology' ); ?></label>
        <select id="ibio_teaching_level" name="ibio_teaching_level">
            <option value="">Select one</option>
            <option value="elementary" <?php selected( $teach, 'elementary' ); ?>><?php _e( 'Elementary or Middle School Students', 'ibiology' ); ?></option>
            <option value="high_school" <?php selected( $teach, 'high_school' ); ?>><?php _e( 'High School Students', 'ibiology' ); ?></option>
            <option value="community_college" <?php selected( $teach, 'community_college' ); ?>><?php _e( 'Community College or Technical School Students', 'ibiology' ); ?></option>
            <option value="undergraduate" <?php selected( $teach, 'undergraduate' ); ?>><?php _e( '4-year Undergraduate College Students', 'ibiology' ); ?></option>
            <option value="masters" <?php selected( $teach, 'masters' ); ?>><?php _e( 'Masters Students', 'ibiology' ); ?></option>
            <option value="graduate" <?php selected( $teach, 'graduate' ); ?>><?php _e( 'Graduate (PhD)/Professional Students', 'ibiology' ); ?></option>
            <option value="other" <?php selected( $teach, 'other' ); ?>><?php _e( 'Other', 'ibiology' ); ?></option>
        </select>
    </p>

	<?php
	// End Teaching level

    // Used Videos in Classroom?

	$ibio_videos = '';

	if ( !empty( $user_id ) ) {
		$ibio_videos = get_user_meta( $user_id, 'ibio_uses_videos', true );
	}
	?>
    <p>
		<?php _e( 'Have you already used iBio videos in the classroom? * ', 'ibiology' ); ?>
    </p>
    <p>
        <label for="rcp_ibio_videos_yes">
            <input name="ibio_uses_videos" id="rcp_ibio_videos_yes" type="radio" value="yes" <?php checked( $ibio_videos, 'yes' ); ?>/>
			<?php _e( 'Yes', 'ibiology' ); ?>
        </label>
        <br/>

        <label for="rcp_ibio_videos_no">
            <input name="ibio_uses_videos" id="rcp_ibio_videos_no" type="radio" value="no" <?php checked( $ibio_videos, 'no' ); ?>/>
			<?php _e( 'No', 'ibiology' ); ?>
        </label>

    </p>

	<?php
    // End: Used Videos in Classroom?

	//  How they Used Videos in Classroom?
	$description = '';
	if ( !empty( $user_id ) ) {
		$description = get_user_meta( $user_id, 'ibio_video_use_info', true );
	}

	?>
    <p>
        <label for="ibio_video_use_info"><?php _e( 'If yes, could you tell us which videos you used, and how and why you used them?', 'ibiology' ); ?></label>
        <textarea id="ibio_video_use_info" name="ibio_video_use_info"><?php echo esc_textarea( $description ); ?></textarea>
    </p>

	<?php
	// End: How they Used Videos in Classroom?


	// How they plan to use  Videos in Classroom?
	$ibio_videos2 = '';
	if ( !empty( $user_id ) ){
		$ibio_videos2 = get_user_meta( $user_id, 'ibio_videos_planned_use', true );
    }
	?>
    <p>
		<?php _e( 'Are you planning to use iBio videos in the classroom? *', 'ibiology' ); ?>
    </p>
    <p>
        <label for="rcp_ibio_videos2_yes">
            <input name="ibio_videos_planned_use" id="rcp_ibio_videos2_yes" type="radio" value="yes" <?php checked( $ibio_videos2, 'yes' ); ?>/>
			<?php _e( 'Yes', 'ibiology' ); ?>
        </label>
        <br/>

        <label for="rcp_ibio_videos2_no">
            <input name="ibio_videos_planned_use" id="rcp_ibio_videos2_no" type="radio" value="no" <?php checked( $ibio_videos2, 'no' ); ?>/>
			<?php _e( 'No', 'ibiology' ); ?>
        </label>
        <br/>

        <label for="rcp_ibio_videos2_maybe">
            <input name="ibio_videos_planned_use" id="rcp_ibio_videos2_maybe" type="radio" value="maybe" <?php checked( $ibio_videos2, 'maybe' ); ?>/>
			<?php _e( 'Maybe', 'ibiology' ); ?>
        </label>

    </p>

	<?php
	// End: How they plan to use  Videos in Classroom?

	// Validate their affiliation
	$website_url = '';
    if ( !empty( $user_id ) ) {
	    $website_url = get_user_meta( $user_id, 'ibio_educator_proof_url', true );
    }

	?>
    <h4>Validate your institutional affiliation</h4>
    <p class="description">Our goal is to ensure that students are not accessing the educator only resources so you can use it in your classroom. Please refer us to an official webpage showing your name and affiliation so we can verify that you are an educator (Note: Links to your institution homepage will not work for registration. Also, please ensure the URL you provide is not password protected). If this is not possible, please send us an email at <a href="mailto:info@ibiology.org">info@ibiology.org</a>.

        <input type="url" id="ibio_educator_proof_url" name="ibio_educator_proof_url" placeholder="https://" value="<?php echo esc_attr( $website_url ); ?>"/>
    </p>

	<?php
    // End: Validate their affiliation

    // end: RCP Form Customizations

}

/* Because the "edit member" screen uses a table, we need to wrap our custom fields into a table row */
function ibio_start_custom_field_section (){
    ?>
    <tr> <td colspan="2"><h3>Educator Custom Fields</h3>
    <?php
}

function ibio_end_custom_field_section(){
    ?>
    </td></tr>
    <?php
}


/* ----------------------------------  Validation on Register  ------------------------------- */

function ibio_validate_data_on_register( $posted ) {
	/*
	if ( is_user_logged_in() ) {
		return;
	}
	*/

    // Validate their institution and country

    if ( empty( $posted['ibio_institution'] ) ) {
		    rcp_errors()->add( 'invalid_institution', __( 'Please enter your institution', 'ibiology'), 'register' );
    }

	if ( empty( $posted['country'] ) ) {
		rcp_errors()->add( 'invalid_country', __( 'Please tell us in which country you are located', 'ibiology'), 'register' );
	}


	// Validate Teaching Level
	$available_choices = array(
		'elementary',
		'high_school',
		'community_college',
		'undergraduate',
		'masters',
		'graduate',
		'other'
	);
	// Add an error message if the submitted option isn't one of our valid choices.
	if ( ! in_array( $posted['ibio_teaching_level'], $available_choices ) ) {
		rcp_errors()->add( 'invalid_teach', __( 'Please select the level of students you teach', 'ibiology' ), 'register' );
	}

	// Validate the Current use of Videos (required field)
	$available_choices = array(
		'yes',
		'no'
	);
	// Add an error message if the submitted option isn't one of our valid choices.
	if ( ! in_array( $posted['ibio_uses_videos'], $available_choices ) ) {
		rcp_errors()->add( 'invalid_ibio_videos', __( 'Please select whether or not you use videos in the classroom', 'ibiology' ), 'register' );
	}

	// Validate the Planned use of Videos (required field)
	$available_choices = array(
		'yes',
		'no',
		'maybe'
	);
	// Add an error message if the submitted option isn't one of our valid choices.
	if ( ! in_array( $posted['ibio_videos_planned_use'], $available_choices ) ) {
		rcp_errors()->add( 'invalid_ibio_planned_videos', __( 'Please select whether you plan to use videos in the classroom', 'ibiology' ), 'register' );
	}

	// Validate that they provided a URL
	if ( empty( $posted['ibio_educator_proof_url'] ) ) {
		rcp_errors()->add( 'invalid_website_url', __( 'Please enter your institution website URL', 'ibiology'), 'register' );
	}

}

/* ----------------------------------  Saving  and updating user meta fields ------------------------------- */

/**
 * Stores the information submitted during registration.
 */
function ibio_rcp_save_fields_on_register( $posted, $user_id ) {

	if ( ! empty( $posted['country'] ) ) {
		update_user_meta( $user_id, 'country', wp_filter_nohtml_kses( $posted['country'] ) );
	}

	if ( ! empty( $posted['ibio_institution'] ) ) {
		update_user_meta( $user_id, 'ibio_institution', wp_filter_nohtml_kses( $posted['ibio_institution'] ) );
	}

	if ( ! empty( $posted['ibio_video_use_info'] ) ) {
		update_user_meta( $user_id, 'ibio_video_use_info', wp_filter_nohtml_kses( $posted['ibio_video_use_info'] ) );
	}
	if ( ! empty( $posted['ibio_teaching_level'] ) ) {
		update_user_meta( $user_id, 'ibio_teaching_level', sanitize_text_field( $posted['ibio_teaching_level'] ) );
	}
	if ( ! empty( $posted['ibio_uses_videos'] ) ) {
		update_user_meta( $user_id, 'ibio_uses_videos', sanitize_text_field( $posted['ibio_uses_videos'] ) );
	}
	if ( ! empty( $posted['ibio_videos_planned_use'] ) ) {
		update_user_meta( $user_id, 'ibio_videos_planned_use', sanitize_text_field( $posted['ibio_videos_planned_use'] ) );
	}
	if ( ! empty( $posted['ibio_educator_proof_url'] ) ) {
		update_user_meta( $user_id, 'ibio_educator_proof_url', esc_url_raw( $posted['ibio_educator_proof_url'] ) );
	}
}


/**
 * Stores the information submitted during profile update.
 */
function ibio_rcp_save_fields_on_profile_save( $user_id ) {


	if ( ! empty( $_POST['country'] ) ) {
		update_user_meta( $user_id, 'country', wp_filter_nohtml_kses( $_POST['country'] ) );
	}

	if ( ! empty( $_POST['ibio_institution'] ) ) {
		update_user_meta( $user_id, 'ibio_institution', wp_filter_nohtml_kses( $_POST['ibio_institution'] ) );
	}

	// Save the teaching levels
	$available_choices = array(
		'elementary',
		'high_school',
		'community_college',
		'undergraduate',
		'masters',
		'graduate',
		'other'
	);
	if ( isset( $_POST['ibio_teaching_level'] ) && in_array( $_POST['ibio_teaching_level'], $available_choices ) ) {
		update_user_meta( $user_id, 'ibio_teaching_level', sanitize_text_field( $_POST['ibio_teaching_level'] ) );
	}

	// Save whether they use videos
	$available_choices = array(
		'yes',
		'no'
	);
	if ( isset( $_POST['ibio_uses_videos'] ) && in_array( $_POST['ibio_uses_videos'], $available_choices ) ) {
		update_user_meta( $user_id, 'ibio_uses_videos', sanitize_text_field( $_POST['ibio_uses_videos'] ) );
	}

	// Save how they use videos
	if ( ! empty( $_POST['ibio_video_use_info'] ) ) {
		update_user_meta( $user_id, 'ibio_video_use_info', wp_filter_nohtml_kses( $_POST['ibio_video_use_info'] ) );
	}

	// Save whether they plan to use videos
	$available_choices = array(
		'yes',
		'no',
		'maybe'
	);
	if ( isset( $_POST['ibio_videos_planned_use'] ) && in_array( $_POST['ibio_videos_planned_use'], $available_choices ) ) {
		update_user_meta( $user_id, 'ibio_videos_planned_use', sanitize_text_field( $_POST['ibio_videos_planned_use'] ) );
	}

	// save they educator URL

	if ( ! empty($_POST['ibio_educator_proof_url'] ) ) {
		update_user_meta( $user_id, 'ibio_educator_proof_url', esc_url_raw( $_POST['ibio_educator_proof_url'] ) );
	}

}

/* ----------------------------------  Email Template and Callbacks ------------------------------- */


///  Add the ibio custom fields to the user email that goes out.
function ibio_rcp_email_template_tags( $email_tags ) {
	$email_tags[] = array(
		'tag'         => 'country',
		'description' => __( 'Educator Proof URL' ),
		'function'    => 'ibio_country_usermeta_callback'
	);
	$email_tags[] = array(
		'tag'         => 'ibio_educator_proof_url',
		'description' => __( 'Educator Proof URL' ),
		'function'    => 'ibio_educator_proof_url_usermeta_callback'
	);
	$email_tags[] = array(
		'tag'         => 'ibio_teaching_level',
		'description' => __( 'Registration Survey Fields' ),
		'function'    => 'ibio_teaching_level_usermeta_callback'
	);
	$email_tags[] = array(
		'tag'         => 'ibio_uses_videos',
		'description' => __( 'Registration Survey Fields' ),
		'function'    => 'ibio_uses_videos_usermeta_callback'
	);

	$email_tags[] = array(
		'tag'         => 'ibio_video_use_info',
		'description' => __( 'Registration Survey Fields' ),
		'function'    => 'ibio_video_use_info_usermeta_callback'
	);

	$email_tags[] = array(
		'tag'         => 'ibio_institution',
		'description' => __( 'Registration Survey Fields' ),
		'function'    => 'ibio_institution_usermeta_callback'
	);
	$email_tags[] = array(
		'tag'         => 'ibio_videos_planned_use',
		'description' => __( 'Registration Survey Fields' ),
		'function'    => 'ibio_videos_planned_use_usermeta_callback'
	);


	return $email_tags;
}


add_filter( 'rcp_email_template_tags', 'ibio_rcp_email_template_tags' );

///callbacks
function ibio_country_usermeta_callback( $user_id = 0, $payment_id = 0, $tag = '' ) {
	$user_meta = get_user_meta( $user_id, 'country', true );

	return $user_meta;
}

function ibio_educator_proof_url_usermeta_callback( $user_id = 0, $payment_id = 0, $tag = '' ) {
	$user_meta = get_user_meta( $user_id, 'ibio_educator_proof_url', true );

	return $user_meta;
}

function ibio_teaching_level_usermeta_callback( $user_id = 0, $payment_id = 0, $tag = '' ) {
	$my_user_meta = get_user_meta( $user_id, 'ibio_teaching_level', true );

	return $my_user_meta;
}

function ibio_uses_videos_usermeta_callback( $user_id = 0, $payment_id = 0, $tag = '' ) {
	$my_user_meta = get_user_meta( $user_id, 'ibio_uses_videos', true );

	return $my_user_meta;
}

function ibio_video_use_info_usermeta_callback( $user_id = 0, $payment_id = 0, $tag = '' ) {
	$my_user_meta = get_user_meta( $user_id, 'ibio_video_use_info', true );

	return $my_user_meta;
}

function ibio_institution_usermeta_callback( $user_id = 0, $payment_id = 0, $tag = '' ) {
	$my_user_meta = get_user_meta( $user_id, 'ibio_institution', true );

	return $my_user_meta;
}

function ibio_videos_planned_use_usermeta_callback( $user_id = 0, $payment_id = 0, $tag = '' ) {
	$my_user_meta = get_user_meta( $user_id, 'ibio_videos_planned_use', true );

	return $my_user_meta;
}