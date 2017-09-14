<?php

/**
 * IBioTalk Class
 *
 * @class 		IBioTalk
 * @version		1.0
 * @package		IBiology
 * @category	Class
 * @author 		Tech Liminal
 * @description The Post Type that represents a Talk for IBiology
 *
 */

class IBioTalk {

	public static $post_type = 'ibiology_talk';
	private static $prefix = "ibio_";

	// use for any required field names.  These will be saved with the $prefix value
	public static $field_names = array();
	private $field_helper;


	public function __construct(){
		
		add_action( 'init', array(&$this, 'create_post_type'));	
		
		//add_action( 'init', array(&$this, 'create_taxonomies'));	
		
		//add_action( 'init', array(&$this, 'init_editor'));
		//add_action( 'init', array(&$this, 'init_acf_fields'));
		add_filter('admin_body_class', array( &$this, 'admin_body_class' ) );
		add_filter( 'enter_title_here', array( &$this,'default_title') );
	
		add_action( 'save_post', array( &$this, 'save_post' ), 100, 2 );
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
			'comments'
		);

		register_post_type( self::$post_type,
			array(
				'labels'               => array(
					'name'                => _x( 'Talks', 'Post Type General Name', 'ibiology' ),
					'singular_name'       => _x( 'Talk', 'Post Type Singular Name', 'ibiology' ),
					'menu_name'           => __( 'Talks/Videos', 'ibiology' ),
					'all_items'           => __( 'All Talks', 'ibiology' ),
					'view_item'           => __( 'View Talk', 'ibiology' ),
					'add_new_item'        => __( 'Add New Talk', 'ibiology' ),
					'add_new'             => __( 'New Talk', 'ibiology' ),
					'edit_item'           => __( 'Edit Talk', 'ibiology' ),
					'update_item'         => __( 'Update Talk', 'ibiology' ),
					'search_items'        => __( 'Search Talks', 'ibiology' ),
					'not_found'           => __( 'No Talks found', 'ibiology' ),
					'not_found_in_trash'  => __( 'No Talks found in Trash', 'ibiology' ),
						),
				'label'               => __( 'Talks', 'ibiology' ),
				'description'         => __( 'IBiology Talk with one or more Videos', 'ibiology' ),
				'supports'						=> $supports,
				'taxonomies'          => array( 'post_tag', 'category' ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 3,
				'can_export'          => true,
				'has_archive'         => true,
				'query_var'           => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'rewrite'             => array('slug' => 'talks', 'with_front' => true),
				'capability_type'			=> 'post',
				'map_meta_cap'				=> true,
				'menu_icon'						=> 'dashicons-video-alt2'
					)
			);

		}

/* --------------------  TAXONOMIES ---------------------- */

	function create_taxonomies() {
		  
	
	}

	/* --------------------- Admin Functions --------------------------*/

	public function create_post ($args){

		$author = wp_get_current_user();
		$author_id = isset($author->ID) ? $author->ID : 0;
		
		$default = array(
			'comment_status' => 'closed', // 'closed' means no comments.
			'ping_status' => 'closed' , // 'closed' means pingbacks or trackbacks turned off
			'post_author' => $author_id, // The user ID number of the author.
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
						error_log( $post->post_type);

    	    return;
		}
		
		error_log('Safe_post filred Post');


		// unhook this function so it doesn't loop infinitely
		remove_action( 'save_post', array( &$this, 'save_post' ), 100 );

		// do stuff that might trigger another Save


		// grab the parts and put them in the excerpt.
		
		if ( function_exists( 'get_field' ) ){
			$videos = get_field( 'videos' );
			$seo_description = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
			$post->post_excerpt = '';
			
			ob_start();
			
			if ( !empty( $seo_description ) ){
				echo $seo_description;
			}
			
			
			//error_log('Got Video and Count') ;
			
			if ( is_array( $videos ) && count( $videos ) > 1 ){
				// we have a multi-part talk;
				echo '<ul class="videos-list row">';
				$counter = 1;
				foreach( $videos as $v ){
					$title = isset( $v[ 'part_title' ] ) ?  esc_attr( $v[ 'part_title' ] ) : '';
					$title = "Part $counter: " . $title;
					
					$video_thumbnail = isset( $v[ 'video_thumbnail' ] ) ? $v[ 'video_thumbnail' ] : '';
					 // video thumbnail is an array.  Let's grab the thumbnail size of this image.
					if ( is_array( $video_thumbnail ) && isset( $video_thumbnail['sizes'] ) && isset( $video_thumbnail['sizes']['thumbnail'] ) ){
						$thumbnail_src = $video_thumbnail['sizes']['thumbnail']; 
						$thumb = "<img src='$thumbnail_src' alt='$title'/>";
					} else {
						$thumbnail_html = ''; 
					}
		      $audiences = $v['target_audience'];
    		  $audience = '';
      		if ( !empty($audiences) && is_array($audiences) ){
						$audience .= '<br/>Audience: <ul class="audiences">';
						foreach( $audiences as $a ){
							$audience .= "<li class='audience {$a->slug}'><span>{$a->name}</span></li> ";
						}
						$audience .= '</ul>';
					}
					
    			echo "<li class='part-$counter' data-select='part-$counter'><figure>$thumb</figure>$title ";
    
    			
      	  $counter++;
      
    		}
    		echo '</ul>';
				
			} else {
			
				$post->post_excerpt = "single part talk";		
			}
			
			$post->post_excerpt = ob_get_contents();
			error_log($post->post_excerpt);
			
			ob_end_clean();
			
			wp_update_post( $post );
		}
		

		// re-hook this function
		add_action( 'save_post', array( &$this, 'save_post' ), 100, 2  );
		
		
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
			return "Long title of This Talk"; 
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
	
	/* --------------------  Data Retrieval Components ---------------------- */

	function get_videos(){
		
	}


} // Finish class defintion