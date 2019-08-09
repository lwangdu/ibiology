<?php
/**
 * Registration Form
 *
 * This template is used to display the registration form with [register_form] If the `id` attribute
 * is passed into the shortcode then register-single.php is used instead.
 * @link http://docs.restrictcontentpro.com/article/1597-registerform
 *
 * @package     iBiology
  */

global $rcp_options, $post, $rcp_levels_db, $rcp_register_form_atts;
$discount = ! empty( $_REQUEST['discount'] ) ? sanitize_text_field( $_REQUEST['discount'] ) : '';
?>

<?php if( is_user_logged_in() ) { ?>

    <h3 class="rcp_header">
		<?php echo apply_filters( 'rcp_registration_header_logged_in', $rcp_register_form_atts['logged_in_header'] ); ?>
    </h3>
<?php } else {?>
    <h3 class="rcp_header">
		<?php echo apply_filters( 'rcp_registration_header_logged_out', $rcp_register_form_atts['logged_out_header'] ); ?>
    </h3>
    <?php

// show any error messages after form submission
rcp_show_error_messages( 'register' ); ?>

<form id="rcp_registration_form" class="rcp_form" method="POST" action="<?php echo esc_url( rcp_get_current_url() ); ?>">

	<?php if( ! is_user_logged_in() ) { ?>

		<?php do_action( 'rcp_before_register_form_fields' ); ?>

		<fieldset class="rcp_user_fieldset">
			<p id="rcp_user_login_wrap">
				<label for="rcp_user_login"><?php echo apply_filters ( 'rcp_registration_username_label', __( 'Username', 'rcp' ) ); ?></label>
				<input name="rcp_user_login" id="rcp_user_login" class="required" type="text" <?php if( isset( $_POST['rcp_user_email'] ) ) { echo 'value="' . esc_attr( $_POST['rcp_user_email'] ) . '"'; } ?>/>
			</p>
			<p id="rcp_user_email_wrap">
				<label for="rcp_user_email"><?php echo apply_filters ( 'rcp_registration_email_label', __( 'Email', 'rcp' ) ); ?></label>
				<input name="rcp_user_email" id="rcp_user_email" class="required" type="text" <?php if( isset( $_POST['rcp_user_email'] ) ) { echo 'value="' . esc_attr( $_POST['rcp_user_email'] ) . '"'; } ?>/>
			</p>
			<p id="rcp_user_first_wrap">
				<label for="rcp_user_first"><?php echo apply_filters ( 'rcp_registration_firstname_label', __( 'First Name', 'rcp' ) ); ?></label>
				<input name="rcp_user_first" id="rcp_user_first" type="text" <?php if( isset( $_POST['rcp_user_first'] ) ) { echo 'value="' . esc_attr( $_POST['rcp_user_first'] ) . '"'; } ?>/>
			</p>
			<p id="rcp_user_last_wrap">
				<label for="rcp_user_last"><?php echo apply_filters ( 'rcp_registration_lastname_label', __( 'Last Name', 'rcp' ) ); ?></label>
				<input name="rcp_user_last" id="rcp_user_last" type="text" <?php if( isset( $_POST['rcp_user_last'] ) ) { echo 'value="' . esc_attr( $_POST['rcp_user_last'] ) . '"'; } ?>/>
			</p>
			<p id="rcp_password_wrap">
				<label for="rcp_password"><?php echo apply_filters ( 'rcp_registration_password_label', __( 'Password', 'rcp' ) ); ?></label>
				<input name="rcp_user_pass" id="rcp_password" class="required" type="password"/>
			</p>
			<p id="rcp_password_again_wrap">
				<label for="rcp_password_again"><?php echo apply_filters ( 'rcp_registration_password_again_label', __( 'Password Again', 'rcp' ) ); ?></label>
				<input name="rcp_user_pass_confirm" id="rcp_password_again" class="required" type="password"/>
			</p>

			<?php do_action( 'rcp_after_password_registration_field' ); ?>

		</fieldset>
	<?php } ?>

	<?php //do_action( 'rcp_before_subscription_form_fields' ); ?>

    <?php // This defaults the membership to Access level 0, but membership level id of 2 (a Community member, not an Educator) ?>
	<input type="hidden" name="rcp_level" value="2">

	<?php //do_action( 'rcp_after_register_form_fields', $levels ); ?>

	<?php if ( ! empty( $rcp_options['enable_terms'] ) ) : ?>
		<fieldset class="rcp_agree_to_terms_fieldset">
			<p id="rcp_agree_to_terms_wrap">
				<input type="checkbox" id="rcp_agree_to_terms" name="rcp_agree_to_terms" value="1">
				<label for="rcp_agree_to_terms">
					<?php
					if ( ! empty( $rcp_options['terms_link'] ) ) {
						echo '<a href="' . esc_url( $rcp_options['terms_link'] ) . '" target="_blank">';
					}

					if ( ! empty( $rcp_options['terms_label'] ) ) {
						echo $rcp_options['terms_label'];
					} else {
						_e( 'I agree to the terms and conditions', 'rcp' );
					}

					if ( ! empty( $rcp_options['terms_link'] ) ) {
						echo '</a>';
					}
					?>
				</label>
			</p>
		</fieldset>
	<?php endif; ?>

	<?php if ( ! empty( $rcp_options['enable_privacy_policy'] ) ) : ?>
		<fieldset class="rcp_agree_to_privacy_policy_fieldset">
			<p id="rcp_agree_to_privacy_policy_wrap">
				<input type="checkbox" id="rcp_agree_to_privacy_policy" name="rcp_agree_to_privacy_policy" value="1">
				<label for="rcp_agree_to_privacy_policy">
					<?php
					if ( ! empty( $rcp_options['privacy_policy_link'] ) ) {
						echo '<a href="' . esc_url( $rcp_options['privacy_policy_link'] ) . '" target="_blank">';
					}

					if ( ! empty( $rcp_options['privacy_policy_label'] ) ) {
						echo $rcp_options['privacy_policy_label'];
					} else {
						_e( 'I agree to the privacy policy', 'rcp' );
					}

					if ( ! empty( $rcp_options['privacy_policy_link'] ) ) {
						echo '</a>';
					}
					?>
				</label>
			</p>
		</fieldset>
	<?php endif; ?>

	<?php do_action( 'rcp_before_registration_submit_field' ); ?>

	<p id="rcp_submit_wrap">
		<input type="hidden" name="rcp_register_nonce" value="<?php echo wp_create_nonce('rcp-register-nonce' ); ?>"/>
		<input type="submit" name="rcp_submit_registration" id="rcp_submit" class="rcp-button" value="<?php esc_attr_e( apply_filters ( 'rcp_registration_register_button', __( 'Register', 'rcp' ) ) ); ?>"/>
	</p>
</form>
<?php } // if user is logged in.