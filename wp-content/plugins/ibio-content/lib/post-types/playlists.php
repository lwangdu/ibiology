<?php

/**
 * IBioPlaylist Class
 *
 * @class 		IBioPlaylist
 * @version		1.0
 * @package		IBiology
 * @category	Class
 * @author 		Tech Liminal
 * @description The Post Type that represents a Playlist for IBiology
 *
 */

class IBioPlaylist {

	public static $post_type = 'ibiology_playlist';
	private static $prefix = "ibio_";
	public static $field_names = array();


	public function __construct(){
		add_action( 'init', array(&$this, 'create_post_type'));	
		
		//add_action( 'init', array(&$this, 'create_taxonomies'));	
		
		//add_action( 'init', array(&$this, 'init_editor'));
		//add_action( 'init', array(&$this, 'init_acf_fields'));
		add_filter('admin_body_class', array( &$this, 'admin_body_class' ) );
		add_filter( 'enter_title_here', array( &$this,'default_title') );
	
		add_action( 'save_post', array( &$this, 'save_post' ), 10, 2 );
		//add_action( 'acf/save_post', array( &$this, 'acf_save_post' ), 10, 2 );

		
	}

	function create_post_type() {

		$supports = array(
			'thumbnail', 
			'title', 
			'revisions',
			'excerpt',
			'editor',
			'author',
			'genesis-cpt-archives-settings',
            'genesis-layouts'
		);

		register_post_type( self::$post_type,
			array(
				'labels'               => array(
					'name'                => _x( 'Playlists', 'Post Type General Name', 'ibiology' ),
					'singular_name'       => _x( 'Playlist', 'Post Type Singular Name', 'ibiology' ),
					'menu_name'           => __( 'Playlists', 'ibiology' ),
					'all_items'           => __( 'All Playlists', 'ibiology' ),
					'view_item'           => __( 'View Playlist', 'ibiology' ),
					'add_new_item'        => __( 'Add New Playlist', 'ibiology' ),
					'add_new'             => __( 'New Playlist', 'ibiology' ),
					'edit_item'           => __( 'Edit Playlist', 'ibiology' ),
					'update_item'         => __( 'Update Playlist', 'ibiology' ),
					'search_items'        => __( 'Search Playlists', 'ibiology' ),
					'not_found'           => __( 'No Playlists found', 'ibiology' ),
					'not_found_in_trash'  => __( 'No Playlists found in Trash', 'ibiology' ),
						),
				'label'               => __( 'Playlists', 'ibiology' ),
				'description'         => __( 'IBiology Playlist with one or more Videos', 'ibiology' ),
				'supports'						=> $supports,
				'taxonomies'          => array( 'post_tag', 'category' ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_rest'		  => true,
				'rest_base' 		  => '',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 3,
				'can_export'          => true,
				'has_archive'         => true,
				'query_var'           => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'rewrite'             => array('slug' => 'playlists', 'with_front' => false),
				'capability_type'			=> 'post',
				'map_meta_cap'				=> true,
				'menu_icon'						=> 'dashicons-images-alt'
					)
			);

		}

/* --------------------  TAXONOMIES ---------------------- */

	function create_taxonomies() {
		  
	
	}

	/* --------------------- Admin Functions --------------------------*/
	/* --------------------- Admin Functions --------------------------*/

	public function create_post ($args){

		$default = array(
			'comment_status' => 'closed', // 'closed' means no comments.
			'ping_status' => 'closed' , // 'closed' means pingbacks or trackbacks turned off
			'post_author' => 1, // The user ID number of the author.
			'post_status' => 'publish' , // Set the status of the new post. 
			'post_title' => 'TBD', // The title of your post.
			'post_type' =>  self::$post_type, // You may want to insert a regular post, page, link, a menu item or some custom post type
			'description'=> ''
		);

		$args = array_merge($default, $args);

		$post_id = wp_insert_post($args, true);
		
		if ( ! is_wp_error($post_id) && $post_id) {

			// insert the metadata

			$alch_fields = array();

			foreach (self::$field_names as $field){
				$meta_fieldname = self::$prefix . $field;

				$alch_fields[] = $meta_fieldname;

				if (isset($args[$field]) ){
					update_post_meta($post_id, $meta_fieldname, $args[$field]);
				}

			}

		}

		return $post_id;

	}

	public function init_acf_fields(){
	
		if( function_exists('acf_add_local_field_group') ):
			// TODO: Insert permanent ACF Fields Here
		endif;
		
	}

		/* -------------------- Editor Stuff -------------------------*/

	public function init_editor(){

		// Set up the editor columns to display relevant info

		/*add_filter("manage_profile_posts_columns", array( &$this, "editor_column_headings" ), 1000);
		add_action("manage_profile_posts_custom_column", array( &$this, "editor_column_contents" ),10, 2);
		add_filter( "manage_edit-profile_sortable_columns", array( &$this, "sortable_columns" ) );*/

	}


	/* -------------------- Save Hooks-------------------------*/

	
	// called when saving a post.
	
	public function save_post($post_id){

		// dont do this on auosave.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
		
		$post = get_post($post_id);

  	if (!isset($post->post_type) || self::$post_type != $post->post_type ) {
    	    return;
		}

    // Check permissions
    /*if ( self::$post_type == $_POST['post_type'] )
    {
      if ( !current_user_can( 'edit_' . self::$post_type, $post_id ) )
          return;
    }*/

		// unhook this function so it doesn't loop infinitely
		remove_action( 'save_post', array( &$this, 'save_post' ) );

		// do stuff that might trigger another Save

        // check and update the hide_playlists site option

        global $wpdb;

        $query = "select post_id from {$wpdb->postmeta} where meta_key='hide_on_archive' and meta_value like '%hide%'";

        $results = $wpdb->get_col( $query );

        $success = update_option('hidden_playlists', $results, true);

		//update the excerpt with the information in the playlist so we don't have to recalculate it.

		if ( function_exists( 'get_field' ) ) { // only do this if ACF is active

			global $post;

			// get the pieces that we need to put into the excerpt for use on the playlist archive page.

			$seo_description = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);

			// count the number of videos (database way, since I don't want to retrieve them all through a complicated query)

			$p2p_playlist_talks_query = "select count(*) as t from {$wpdb->p2p} where p2p_from = $post_id and p2p_type in ('playlist_to_talks', 'playlist_to_session')";
			$num_talks_result = $wpdb->get_results( $p2p_playlist_talks_query);
			if ( is_array( $num_talks_result ) ){
				$num_talks = array_shift($num_talks_result);
				$num_talks = $num_talks->t;
			} else {
				$num_talks = 0;
			}


			$durations = get_field('playlist_durations_description', $post_id);
			if ( !empty( $durations ) ) {
				$durations = "<p class='durations'>$durations</p>";
			}

			$audiences = wp_get_post_terms( $post_id, 'audience');
			$audiences = ibio_display_audiences($audiences, '');

			$educator_expanded_link = get_field('playlist_educators_link', $post_id);
			$educator_expanded_link_label = get_field ('playlist_educators_link_label', $post_id);
			if ( empty( $educator_expanded_link_label) ){
				$educator_expanded_link_label = "View Playlist";
			}

			// might need to move this to the display for the playlist - depends on how we intend to use it.
			if ( !empty($educator_expanded_link) ){
				$educator_expanded_link = "<div class='educators-more-link'><a href='$educator_expanded_link' class='button'>$educator_expanded_link_label</a></div>";
			}

			$post->post_excerpt = "<p class='description'>$seo_description</p><p class='num_talks'>$num_talks Talks</p>$durations $audiences";
			$post->post_excerpt .= "$educator_expanded_link";

			wp_update_post( $post );

		} 
		
		
		
		// done updating the excerpt

		// re-hook this function
		add_action( 'save_post', array( &$this, 'save_post' ), 10, 2  );
		
		
	}
	
	// called from the acf front-end hook.
	
	public function acf_save_post($post_id){
		
		$this->save_post($post_id);
		
	}

	/* -------------------- Body Class -------------------------*/

	function admin_body_class($classes){

		global $post;

		if ((isset($post) && $post->post_type == self::$post_type) || (isset( $_REQUEST['post_type']) && self::$post_type == $_REQUEST['post_type']) ) {
			$classes .= ' ' . self::$post_type;
		}
		return $classes;
	}

	function default_title($title){
		if ( isset( $_REQUEST['post_type']) && self::$post_type == $_REQUEST['post_type'] ) {
			return "Long title of This Playlist"; 
		} else {
			return $title;
		}
		return $title;
	}

	/* --------------------  COLUMN HEADINGS ---------------------- */


	//this function display the columns headings
	public function editor_column_headings($columns) {

		$new_columns = array();
		foreach($columns as $label => $c) {
			if ($label == 'title'){
				$new_columns['post_id'] = __('ID' , 'ibiology');
			}
			if ($label == 'date') {
				continue;
			}
			$new_columns[$label] = $c;
		}

		return $new_columns;
	}

	// Display the custom column contents.
	public function editor_column_contents($column, $post_id) {
		
		global $post, $wpdb, $table_prefix;
		switch($column){
			case 'post_id':
				echo $post_id;
				break;

		case 'last_name' :
				$last_name = get_post_meta($post_id, 'last_name', true);
				echo $last_name;
				break; 
		}
	}

	function sortable_columns() {
		return array(
			'title'	=> 'title',
			"post_id"	=> "post_id",
		);
	}

 

	/* --------------------  FILTERS ---------------------- */



	/* --------------------  Check for stuff that needs to be done w/ the talks --------------- */
	function parse_request(){
	
	
	}

} // Finish class defintion