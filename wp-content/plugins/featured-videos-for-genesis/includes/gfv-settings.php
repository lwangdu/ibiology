<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}

// Hook for adding admin menus
add_action( 'admin_menu', 'gfv_add_settings' );
// Hook for adding settings
add_action( 'admin_init', 'register_gfv_settings' );
function gfv_add_settings() {
	add_submenu_page( 'genesis', __( 'Genesis Featured Video', 'genesis-featured-video' ), __( 'Featured Video', 'genesis-featured-video' ), 'manage_options', 'gfv-settings', 'gfv_main_settings' );
}

function register_gfv_settings() { // whitelist options
  register_setting( 'gfv_option_group', 'gfv_thumb_width' );
  register_setting( 'gfv_option_group', 'gfv_thumb_height' );
  register_setting( 'gfv_option_group', 'gfv_thumb_position' );
}
// Create Featured Video page
function gfv_main_settings() {
	$width_var = 'gfv_thumb_width';
	$height_var = 'gfv_thumb_height';
	$position_var = 'gfv_thumb_position';

  $width = absint( get_option( $width_var ) );
  $height = absint( get_option( $height_var ) );
  $position = esc_attr( get_option( $position_var ) );
	?>
  <div class="wrap">
    <?php if( isset($_GET['settings-updated']) ) { ?>
			<div id="message" class="updated">
				<p><strong><?php _e( 'Settings saved.', 'genesis-featured-video' ); ?></strong></p>
			</div>
		<?php } ?>
    <h2> <?php _e( 'Genesis Featured Video', 'genesis-featured-video' ); ?></h2>
    <?php _e( 'YouTube: To avoid distorting the video dimensions, keep an aspect ratio of 16:9. Examples: 640x360, 560x315, 300x169.'); ?>
      <form method="post" action="options.php">
         <?php
         wp_nonce_field( 'update-options' );
         settings_fields( 'gfv_option_group' );
         do_settings_sections( 'gfv_option_group' );
         ?>
         <table class="form-table">
          <tr valign="top">
          <th scope="row"><?php _e( 'Video Width', 'genesis-featured-video' ); ?></th>
          <td><input type="number" name="<?php echo $width_var; ?>" value="<?php echo $width; ?>" /></td>
          </tr>

          <tr valign="top">
          <th scope="row"><?php _e( 'Video Height', 'genesis-featured-video' ); ?></th>
          <td><input type="number" name="<?php echo $height_var; ?>" value="<?php echo $height; ?>" /></td>
          </tr>

          <tr valign="top">
          <th scope="row"><?php _e( 'Video Position', 'genesis-featured-video' ); ?></th>
            <td>
              <select name="<?php echo $position_var; ?>">
                <option value="none" <?php if( ( '' || 'none' ) == $position ) { echo 'selected'; } ?> ><?php _e( 'None', 'genesis-featured-video' ); ?></option>
                <option value="left" <?php if( 'left' == $position ) { echo 'selected'; } ?> ><?php _e( 'Left', 'genesis-featured-video' ); ?></option>
                <option value="right" <?php if( 'right' == $position ) { echo 'selected'; } ?> ><?php _e( 'Right', 'genesis-featured-video' ); ?></option>
              </select>
            </td>
          </tr>
        </table>
        <input type="hidden" name="action" value="update" />
         <?php
          submit_button( __( 'Save Settings', 'genesis-featured-video' ), 'primary' );
         ?>
      </form>
  </div>
  <?php
}

//Save footer information
function save_gfv_settings(){
  // check the nonce, update the option etc...
  if( isset( $_POST['update-options'] ) && wp_verify_nonce( 'update-options' ) ) {
    if( isset( $_POST['gfv_thumb_width'] ) ) {
      $safevalue = preg_replace("/[^0-9]/", "", $_POST['gfv_thumb_width']);
      update_option( 'gfv_thumb_width', $safevalue );
    }
    if( isset( $_POST['gfv_thumb_height'] ) ) {
      $safevalue = preg_replace("/[^0-9]/", "", $_POST['gfv_thumb_height']);
      update_option( 'gfv_thumb_height', $safevalue );
    }
    if( isset( $_POST['gfv_thumb_position'] ) ) {
      $valid = array( 'left', 'right', 'middle' );
      if ( in_array( $_POST['gfv_thumb_position'], $valid) ) {
        $safevalue = $_POST['gfv_thumb_position'];
      } else {
        $safevalue = 'left';
      }
      update_option( 'gfv_thumb_position', $safevalue );
    }
  }
}
add_action( 'admin_init', 'save_gfv_settings', 10 );
