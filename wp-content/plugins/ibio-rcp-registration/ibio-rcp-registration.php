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


/* Remove username field on RCP */
function ibio_rcp_user_registration_data( $user ) {
	rcp_errors()->remove( 'username_empty' );
	$user['login'] = $user['email'];
	return $user;
}

add_filter( 'rcp_user_registration_data', 'ibio_rcp_user_registration_data' );

/**
 * Adds the custom fields to the registration form and profile editor
 *
 */
function ibio_add_user_fields() {

	$institution = get_user_meta( get_current_user_id(), 'ibio_institution', true );
	$country   = get_user_meta( get_current_user_id(), 'ibio_country', true );
	?>
	<p>
		<label for="ibio_institution"><?php _e( 'Your institution', 'rcp' ); ?></label>
		<input name="ibio_institution" id="ibio_institution" type="text" value="<?php echo esc_attr( $institution ); ?>"/>
	</p>
	<p>
		<label for="country"><?php _e( 'Your country *', 'rcp' ); ?></label>
		<input name="country" id="country" type="text" value="<?php echo esc_attr( $country ); ?>"/>
	</p>
	<?php
}
add_action( 'rcp_after_password_registration_field', 'ibio_add_user_fields' );
add_action( 'rcp_profile_editor_after', 'ibio_add_user_fields' );

/**
 * Adds the custom fields to the member edit screen
 *
 */
function ibio_add_member_edit_fields( $user_id = 0 ) {

	$institution = get_user_meta( $user_id, 'rcp_institution', true );
	$country   = get_user_meta( $user_id, 'rcp_country', true );
	?>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_institution"><?php _e( 'institution', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="rcp_institution" id="rcp_institution" type="text" value="<?php echo esc_attr( $institution ); ?>"/>
			<p class="description"><?php _e( 'The member\'s institution', 'rcp' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_country"><?php _e( 'Country', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="rcp_country" id="rcp_country" type="text" value="<?php echo esc_attr( $country ); ?>"/>
			<p class="description"><?php _e( 'The member\'s country', 'rcp' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'rcp_edit_member_after', 'ibio_add_member_edit_fields' );



/**
 * Adds a custom select field to the registration form and profile editor.
 */
function ibio_rcp_add_select_field() {
	$teach = get_user_meta( get_current_user_id(), 'rcp_teach', true );
	?>
	<p>
		<label for="rcp_teach"><?php _e( 'What type of student do you primarily teach? *', 'rcp' ); ?></label>
		<select id="rcp_teach" name="rcp_teach">
			<option value="friend" <?php selected( $teach, 'elementary'); ?>><?php _e( 'Elementary or Middle School Students', 'rcp' ); ?></option>
			<option value="search" <?php selected( $teach, 'high_school'); ?>><?php _e( 'High School Students', 'rcp' ); ?></option>
			<option value="social" <?php selected( $teach, 'community_college'); ?>><?php _e( 'Community College or Technical School Students', 'rcp' ); ?></option>
			<option value="other" <?php selected( $teach, 'undergraduate'); ?>><?php _e( '4-year Undergraduate College Students', 'rcp' ); ?></option>
			<option value="other" <?php selected( $teach, 'masters'); ?>><?php _e( 'Masters Students', 'rcp' ); ?></option>
			<option value="other" <?php selected( $teach, 'graduate'); ?>><?php _e( 'Graduate (PhD)/Professional Students', 'rcp' ); ?></option>
			<option value="other" <?php selected( $teach, 'other'); ?>><?php _e( 'Other', 'rcp' ); ?></option>
		</select>
	</p>

	<?php
}
add_action( 'rcp_after_password_registration_field', 'ibio_rcp_add_select_field' );
add_action( 'rcp_profile_editor_after', 'ibio_rcp_add_select_field' );
/**
 * Adds the custom select field to the member edit screen.
 */
function ibio_rcp_add_select_member_edit_field( $user_id = 0 ) {
	$referrer = get_user_meta( $user_id, 'rcp_teach', true );
	?>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_teach"><?php _e( 'What type of student do you primarily teach?', 'rcp' ); ?></label>
		</th>
		<td>
			<select id="rcp_teach" name="rcp_teach">
				<option value="friend" <?php selected( $teach, 'elementary'); ?>><?php _e( 'Elementary or Middle School Students', 'rcp' ); ?></option>
				<option value="search" <?php selected( $teach, 'high_school'); ?>><?php _e( 'High School Students', 'rcp' ); ?></option>
				<option value="social" <?php selected( $teach, 'community_college'); ?>><?php _e( 'Community College or Technical School Students', 'rcp' ); ?></option>
				<option value="other" <?php selected( $teach, 'undergraduate'); ?>><?php _e( '4-year Undergraduate College Students', 'rcp' ); ?></option>
				<option value="other" <?php selected( $teach, 'masters'); ?>><?php _e( 'Masters Students', 'rcp' ); ?></option>
				<option value="other" <?php selected( $teach, 'graduate'); ?>><?php _e( 'Graduate (PhD)/Professional Students', 'rcp' ); ?></option>
				<option value="other" <?php selected( $teach, 'other'); ?>><?php _e( 'Other', 'rcp' ); ?></option>
			</select>
		</td>
	</tr>
	<?php
}
add_action( 'rcp_edit_member_after', 'ibio_rcp_add_select_member_edit_field' );
/**
 * Determines if there are problems with the registration data submitted.
 */
function ibio_rcp_validate_select_on_register( $posted ) {
	if ( is_user_logged_in() ) {
		return;
	}

	// List all the available options that can be selected.
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
	if ( ! in_array( $posted['rcp_teach'], $available_choices ) ) {
		rcp_errors()->add( 'invalid_teach', __( 'Please select a valid list', 'rcp' ), 'register' );
	}
}
add_action( 'rcp_form_errors', 'ibio_rcp_validate_select_on_register', 10 );
/**
 * Stores the information submitted during registration.
 */
function ibio_rcp_save_select_field_on_register( $posted, $user_id ) {
	if ( ! empty( $posted['rcp_teach'] ) ) {
		update_user_meta( $user_id, 'rcp_teach', sanitize_text_field( $posted['rcp_teach'] ) );
	}
}
add_action( 'rcp_form_processing', 'ibio_rcp_save_select_field_on_register', 10, 2 );
/**
 * Stores the information submitted during profile update.
 */
function ibio_rcp_save_select_field_on_profile_save( $user_id ) {

	// List all the available options that can be selected.
	$available_choices = array(
		'elementary',
		'high_school',
		'community_college',
		'undergraduate',
		'masters',
		'graduate',
		'other'
	);
	if ( isset( $_POST['rcp_teach'] ) && in_array( $_POST['rcp_teach'], $available_choices ) ) {
		update_user_meta( $user_id, 'rcp_teach', sanitize_text_field( $_POST['rcp_teach'] ) );
	}
}
add_action( 'rcp_user_profile_updated', 'ibio_rcp_save_select_field_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'ibio_rcp_save_select_field_on_profile_save', 10 );





/**
 * Adds a custom radio button fields to the registration form and profile editor.
Have you already used iBio videos in the classroom?
 */
function ibio_rcp_add_radio_fields() {
	$ibio_videos = get_user_meta( get_current_user_id(), 'rcp_ibio_videos', true );
	?>
	<p>
		<?php _e( 'Have you already used iBio videos in the classroom? * ', 'rcp' ); ?>
	</p>
	<p>
		<label for="rcp_ibio_videos_yes">
			<input name="rcp_ibio_videos" id="rcp_ibio_videos_yes" type="radio" value="yes" <?php checked( $ibio_videos, 'yes' ); ?>/>
			<?php _e( 'Yes', 'rcp' ); ?>
		</label>
		<br/>

		<label for="rcp_ibio_videos_no">
			<input name="rcp_ibio_videos" id="rcp_ibio_videos_no" type="radio" value="no" <?php checked( $ibio_videos, 'no' ); ?>/>
			<?php _e( 'No', 'rcp' ); ?>
		</label>

	</p>

	<?php
}
add_action( 'rcp_after_password_registration_field', 'ibio_rcp_add_radio_fields' );
add_action( 'rcp_profile_editor_after', 'ibio_rcp_add_radio_fields' );
/**
 * Adds the custom radio button fields to the member edit screen.
 */
function ibio_rcp_add_radio_member_edit_fields( $user_id = 0 ) {
	$ibio_videos = get_user_meta( $user_id, 'rcp_ibio_videos', true );
	?>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_ibio_videos"><?php _e( 'Have you already used iBio videos in the classroom?', 'rcp' ); ?></label>
		</th>
		<td>
			<label for="rcp_ibio_videos_yes">
				<input name="rcp_ibio_videos" id="rcp_ibio_videos_yes" type="radio" value="yes" <?php checked( $ibio_videos, 'yes' ); ?>/>
				<?php _e( 'Yes', 'rcp' ); ?>
			</label>
			<br/>

			<label for="rcp_ibio_videos_no">
				<input name="rcp_ibio_videos" id="rcp_ibio_videos_no" type="radio" value="no" <?php checked( $ibio_videos, 'no' ); ?>/>
				<?php _e( 'No', 'rcp' ); ?>
			</label>

		</td>
	</tr>
	<?php
}
add_action( 'rcp_edit_member_after', 'ibio_rcp_add_radio_member_edit_fields' );
/**
 * Determines if there are problems with the registration data submitted.
 * Remove this code if you want the radio button to be optional.
 */
function ibio_rcp_validate_radio_on_register( $posted ) {
	if ( is_user_logged_in() ) {
		return;
	}
	// List all the available options that can be selected.
	$available_choices = array(
		'yes',
		'no'
	);
	// Add an error message if the submitted option isn't one of our valid choices.
	if ( ! in_array( $posted['rcp_ibio_videos'], $available_choices ) ) {
		rcp_errors()->add( 'invalid_ibio_videos', __( 'Please select a valid box', 'rcp' ), 'register' );
	}
}
add_action( 'rcp_form_errors', 'ibio_rcp_validate_radio_on_register', 10 );
/**
 * Stores the information submitted during registration.
 */
function ibio_rcp_save_radio_field_on_register( $posted, $user_id ) {
	if ( ! empty( $posted['rcp_ibio_videos'] ) ) {
		update_user_meta( $user_id, 'rcp_ibio_videos', sanitize_text_field( $posted['rcp_ibio_videos'] ) );
	}
}
add_action( 'rcp_form_processing', 'ibio_rcp_save_radio_field_on_register', 10, 2 );
/**
 * Stores the information submitted during profile update.
 */
function ibio_rcp_save_radio_field_on_profile_save( $user_id ) {

	// List all the available options that can be selected.
	$available_choices = array(
		'yes',
		'no'
	);
	if ( isset( $_POST['rcp_ibio_videos'] ) && in_array( $_POST['rcp_ibio_videos'], $available_choices ) ) {
		update_user_meta( $user_id, 'rcp_ibio_videos', sanitize_text_field( $_POST['rcp_ibio_videos'] ) );
	}
}
add_action( 'rcp_user_profile_updated', 'ibio_rcp_save_radio_field_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'ibio_rcp_save_radio_field_on_profile_save', 10 );




/**
 * Adds a custom textarea field to the registration form and profile editor. If use videos yes or no.
 */
function ibio_rcp_add_textarea_field() {
	$description = get_user_meta( get_current_user_id(), 'rcp_customer_description', true );
	?>
	<p>
		<label for="rcp_customer_description"><?php _e( 'If yes, could you tell us which videos you used, and how and why you used them?', 'rcp' ); ?></label>
		<textarea id="rcp_customer_description" name="rcp_customer_description"><?php echo esc_textarea( $description ); ?></textarea>
	</p>

	<?php
}
add_action( 'rcp_after_password_registration_field', 'ibio_rcp_add_textarea_field' );
add_action( 'rcp_profile_editor_after', 'ibio_rcp_add_textarea_field' );
/**
 * Adds the custom textarea field to the member edit screen.
 */
function ibio_rcp_add_textarea_member_edit_field( $user_id = 0 ) {
	$description = get_user_meta( $user_id, 'rcp_customer_description', true );
	?>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_customer_description"><?php _e( 'If yes, could you tell us which videos you used, and how and why you used them?', 'rcp' ); ?></label>
		</th>
		<td>
			<textarea id="rcp_customer_description" name="rcp_customer_description" class="large-text" rows="10" cols="30"><?php echo esc_textarea( $description ); ?></textarea>
		</td>
	</tr>
	<?php
}
add_action( 'rcp_edit_member_after', 'ibio_rcp_add_textarea_member_edit_field' );



/**
 * Stores the information submitted during registration.
 */
function ibio_rcp_save_textarea_field_on_register( $posted, $user_id ) {
	if ( ! empty( $posted['rcp_customer_description'] ) ) {
		update_user_meta( $user_id, 'rcp_customer_description', wp_filter_nohtml_kses( $posted['rcp_customer_description'] ) );
	}
}
add_action( 'rcp_form_processing', 'ibio_rcp_save_textarea_field_on_register', 10, 2 );
/**
 * Stores the information submitted during profile update.
 */
function ibio_rcp_save_textarea_field_on_profile_save( $user_id ) {
	if ( ! empty( $_POST['rcp_customer_description'] ) ) {
		update_user_meta( $user_id, 'rcp_customer_description', wp_filter_nohtml_kses( $_POST['rcp_customer_description'] ) );
	}
}
add_action( 'rcp_user_profile_updated', 'ibio_rcp_save_textarea_field_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'ibio_rcp_save_textarea_field_on_profile_save', 10 );




/**
 * Adds a custom radio button fields to the registration form and profile editor.
Are you planning to use iBio videos in the classroom? *
 */
function ibio2_rcp_add_radio_fields() {
	$ibio_videos2 = get_user_meta( get_current_user_id(), 'rcp_ibio_videos2', true );
	?>
	<p>
		<?php _e( 'Are you planning to use iBio videos in the classroom? *', 'rcp' ); ?>
	</p>
	<p>
		<label for="rcp_ibio_videos2_yes">
			<input name="rcp_ibio_videos2" id="rcp_ibio_videos2_yes" type="radio" value="yes" <?php checked( $ibio_videos, 'yes' ); ?>/>
			<?php _e( 'Yes', 'rcp' ); ?>
		</label>
		<br/>

		<label for="rcp_ibio_videos2_no">
			<input name="rcp_ibio_videos2" id="rcp_ibio_videos2_no" type="radio" value="no" <?php checked( $ibio_videos2, 'no' ); ?>/>
			<?php _e( 'No', 'rcp' ); ?>
		</label>
		<br/>

		<label for="rcp_ibio_videos2_maybe">
			<input name="rcp_ibio_videos2" id="rcp_ibio_videos2_maybe" type="radio" value="maybe" <?php checked( $ibio_videos2, 'maybe' ); ?>/>
			<?php _e( 'Maybe', 'rcp' ); ?>
		</label>

	</p>

	<?php
}
add_action( 'rcp_after_password_registration_field', 'ibio2_rcp_add_radio_fields' );
add_action( 'rcp_profile_editor_after', 'ibio2_rcp_add_radio_fields' );
/**
 * Adds the custom radio button fields to the member edit screen.
 */
function ibio2_rcp_add_radio_member_edit_fields( $user_id = 0 ) {
	$ibio_videos2 = get_user_meta( $user_id, 'rcp_ibio_videos2', true );
	?>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_ibio_videos2"><?php _e( 'iBio videos in the classroom?', 'rcp' ); ?></label>
		</th>
		<td>
			<label for="rcp_ibio_videos2_yes">
				<input name="rcp_ibio_videos2" id="rcp_ibio_videos2_yes" type="radio" value="yes" <?php checked( $ibio_videos, 'yes' ); ?>/>
				<?php _e( 'Yes', 'rcp' ); ?>
			</label>
			<br/>

			<label for="rcp_ibio_videos2_no">
				<input name="rcp_ibio_videos2" id="rcp_ibio_videos2_no" type="radio" value="no" <?php checked( $ibio_videos2, 'no' ); ?>/>
				<?php _e( 'No', 'rcp' ); ?>
			</label>
			<br/>

			<label for="rcp_ibio_videos2_maybe">
				<input name="rcp_ibio_videos2" id="rcp_ibio_videos2_maybe" type="radio" value="maybe" <?php checked( $ibio_videos2, 'maybe' ); ?>/>
				<?php _e( 'Maybe', 'rcp' ); ?>
			</label>
		</td>
	</tr>
	<?php
}
add_action( 'rcp_edit_member_after', 'ibio2_rcp_add_radio_member_edit_fields' );
/**
 * Determines if there are problems with the registration data submitted.
 * Remove this code if you want the radio button to be optional.
 */
function ibio2_rcp_validate_radio_on_register( $posted ) {
	if ( is_user_logged_in() ) {
		return;
	}
	// List all the available options that can be selected.
	$available_choices = array(
		'yes',
		'no',
		'maybe'
	);
	// Add an error message if the submitted option isn't one of our valid choices.
	if ( ! in_array( $posted['rcp_ibio_videos'], $available_choices ) ) {
		rcp_errors()->add( 'invalid_ibio_videos', __( 'Please select a valid box', 'rcp' ), 'register' );
	}
}
add_action( 'rcp_form_errors', 'ibio2_rcp_validate_radio_on_register', 10 );
/**
 * Stores the information submitted during registration.
 */
function ibio2_rcp_save_radio_field_on_register( $posted, $user_id ) {
	if ( ! empty( $posted['rcp_ibio_videos2'] ) ) {
		update_user_meta( $user_id, 'rcp_ibio_videos2', sanitize_text_field( $posted['rcp_ibio_videos2'] ) );
	}
}
add_action( 'rcp_form_processing', 'ibio2_rcp_save_radio_field_on_register', 10, 2 );
/**
 * Stores the information submitted during profile update.
 */
function ibio2_rcp_save_radio_field_on_profile_save( $user_id ) {

	// List all the available options that can be selected.
	$available_choices = array(
		'yes',
		'no',
		'maybe'
	);
	if ( isset( $_POST['rcp_ibio_videos2'] ) && in_array( $_POST['rcp_ibio_videos2'], $available_choices ) ) {
		update_user_meta( $user_id, 'rcp_ibio_videos2', sanitize_text_field( $_POST['rcp_ibio_videos2'] ) );
	}
}
add_action( 'rcp_user_profile_updated', 'ibio2_rcp_save_radio_field_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'ibio2_rcp_save_radio_field_on_profile_save', 10 );







/**
 * Adds a custom URL field to the registration form and profile editor.
 */

function ibio_rcp_add_url_field() {
	$website_url = get_user_meta( get_current_user_id(), 'rcp_website_url', true );
	?>
	<p>
		<label for="rcp_website_url"><?php _e( 'Please refer us to an official webpage showing your name and affiliation so we can verify that you are an educator (note: links to your institution homepage will not work for registration, also please ensure the URL you provide is not password protected). If this is not possible, please send us an email at info@ibiology.org. Our goal is to ensure that students are not accessing the educator only resources so you can use it in your classroom. ', 'rcp' ); ?></label>
		<input type="url" id="rcp_website_url" name="rcp_website_url" placeholder="https://" value="<?php echo esc_attr( $website_url ); ?>"/>
	</p>

	<?php
}
add_action( 'rcp_after_password_registration_field', 'ibio_rcp_add_url_field' );
add_action( 'rcp_profile_editor_after', 'ibio_rcp_add_url_field' );

/**  * Adds the custom URL field to the member edit screen.  */
function ibio_rcp_add_url_member_edit_field( $user_id = 0 ) { $website_url = get_user_meta( $user_id, 'rcp_website_url', true ); ?> <tr valign="top">
	<th scope="row" valign="top">
		<label for="rcp_website_url"><?php _e( 'Please refer us to an official webpage showing your name and affiliation so we can
verify that you are an educator (note: links to your institution homepage will not work for registration, also please ensure the URL you provide is not
password protected). If this is not possible, please send us an email at info@ibiology.org. Our goal is to ensure that students are not accessing the
educator only resources so you can use it in your classroom. *', 'rcp' );
			?></label>
	</th>
	<td><input type="url" id="rcp_website_url" name="rcp_website_url" placeholder="https://" value="<?php echo esc_attr( $website_url ); ?>"/></td>
</tr>
<?php }
add_action( 'rcp_edit_member_after', 'ibio_rcp_add_url_member_edit_field' );

/** * Determines if there are problems with the registration data submitted. *
Remove this code if you want the URL field to be optional.  */

function ibio_rcp_validate_url_on_register( $posted ) {
	if ( is_user_logged_in() ) {
		return;     }
	if ( empty( $posted['rcp_website_url'] ) ) {
		rcp_errors()->add( 'invalid_website_url', __( 'Please enter your institution website URL', 'rcp'), 'register' );
	} } add_action( 'rcp_form_errors', 'ibio_rcp_validate_url_on_register', 10 );

/**  * Stores the information submitted during registration.  */
function ibio_rcp_save_url_field_on_register( $posted, $user_id ) {
	if ( ! empty( $posted['rcp_ibio_educator'] ) ) {
		update_user_meta( $user_id, 'rcp_ibio_educator', esc_url_raw( $posted['rcp_ibio_educator'] ) );
	} }
add_action( 'rcp_form_processing', 'ibio_rcp_save_url_field_on_register', 10,2 );


/**  * Stores the information submitted during profile update.  */
function ibio_rcp_save_url_field_on_profile_save( $user_id ) {
	if ( ! empty($_POST['rcp_ibio_educator'] ) ) {
		update_user_meta( $user_id, 'rcp_ibio_educator', esc_url_raw( $_POST['rcp_ibio_educator'] ) );
	} }
add_action( 'rcp_user_profile_updated', 'ibio_rcp_save_url_field_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'ibio_rcp_save_url_field_on_profile_save', 10 );
