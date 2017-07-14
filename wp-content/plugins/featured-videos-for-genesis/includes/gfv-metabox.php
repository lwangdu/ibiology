<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
add_action( 'admin_init', 'gfv_add_meta_boxes', 1 );
function gfv_add_meta_boxes() {
  $post_types = gvf_get_post_types();
  foreach( $post_types as $post_type ) {
	  add_meta_box( 'gfv-video', 'Genesis Featured Video', 'gfv_meta_box_display', $post_type, 'side', 'default' );
  }
}

function gfv_meta_box_display() {
  global $post;
  $url = get_post_meta( $post->ID, '_gfv_video_url', true ); ?>
  <div style="width:100%;">
    <label for="_gfv_video_url"><strong><?php _e( 'Video URL','genesis-featured-video' ); ?></strong></label><br />
    <input type="text" name="_gfv_video_url" value="<?php echo $url; ?>" />
  </div>
  <?php
}
add_action( 'save_post', 'gfv_save_video_url' );
function gfv_save_video_url( $post_id ) {
  $fields = array( '_gfv_video_url' );
  foreach ( $fields as $field ) {
    if ( array_key_exists( $field, $_POST ) ) {
      $value = $_POST[$field];
      if ( $field == '_gfv_video_url' ) {
        $safeinput = esc_url_raw( $value );
      }
      update_post_meta( $post_id, $field, $safeinput );
    }
  }
}
