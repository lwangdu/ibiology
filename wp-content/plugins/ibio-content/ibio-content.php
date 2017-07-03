<?php

/**
 * Plugin Name: IBiology Content Types
 * Plugin URI: https://ibiology.org/ibio-content
 * Description: Create and manage iBiology content types - Talks/Videos, Speakers, Playlists
 * Version: 1.0
 * Author: Anca Mosoiu
 * Author URI: http://techliminal.com
 * License: GPL2
 */

defined('ABSPATH') or die('No direct access');

/* Add Chrome PHP class for PHP debugging */
if( WP_DEBUG ) {

}

class IBioContent{
	
	
	public $talks;
	public $speakers;
	public $playlists;
	
	public $template_path;
	public $template_path_slug;
	
	function __construct(){
		
		$this->template_path = plugin_dir_path( __FILE__ ) . 'templates/';
		$this->template_path_slug = 'ibiology';
		$this->load_files();
		$this->init_objects();
		
		

		add_action('admin_enqueue_scripts', array( &$this, 'load_admin_scripts' ));
		add_action( 'wp_enqueue_scripts', array( &$this, 'load_scripts' ) );
		add_action('wp_loaded', array(&$this, 'create_connection_types'), 10);
		add_action ('init', array(&$this, 'create_taxonomies' ), 10 );
	}


	/**
 	* Load all of the function files needed for producing the plugin. 
 	*/
	function load_files(){

		/* Post Types and Classes */
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/talks.php' );
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/course-lesson.php' );
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/speakers.php' );
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/playlists.php' );
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/educator-resource.php' );
	
		/* Functions */
		include (  plugin_dir_path( __FILE__ ) . '/lib/functions/content.php' );
		
		/* Classes */
		require_once ( plugin_dir_path( __FILE__ ) . '/lib/classes/template_loader.php' );
		require_once ( plugin_dir_path( __FILE__ ) . '/lib/classes/fields_display_helper.php' );
	}


	function init_objects(){
		$this->speakers = new IBioSpeaker();
		$this->talks = new IBioTalk();
		$this->lessons = new IBioLesson();
		$this->playlists = new IBioPlaylist();
		$this->resources = new IBioResource();
		

	}

	/* Load styles and scripts for use in the admin interface */
	function load_admin_scripts(){
		wp_enqueue_style('profile-admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css' );
		wp_register_script( 'js-datatables', '//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js', array( 'jquery' ), '1.10.12', true);
		wp_enqueue_script( 'lbl-profiles', plugin_dir_url( __FILE__ ) . '/assets/js/profiles.js', array( 'jquery' , 'js-datatables' ), '1.0.0' );
		wp_enqueue_script( 'js-datatables');
		wp_enqueue_style( 'js-datatables-css', '//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css');
	} 

	/* Load display scripts */
	function load_scripts(){

	}
	
	/* Create the Post2Posts connection types we will be using */
	function create_connection_types(){
		if ( function_exists( 'p2p_register_connection_type' ) ){
        p2p_register_connection_type( array(
          'name' => 'speaker_to_talk',
          'from' => IbioSpeaker::$post_type,
          'to' => IbioTalk::$post_type,
          'cardinality' => 'many-to-many',
          'admin_column' => 'any',
          'title' => array('from' => "Talks for this Speaker", 'to' => 'Speakers in this Talk')
        ) );
        
          p2p_register_connection_type( array(
          'name' => 'speaker_to_lesson',
          'from' => IbioSpeaker::$post_type,
          'to' => IbioLesson::$post_type,
          'cardinality' => 'many-to-many',
          'admin_column' => 'any',
          'title' => array('from' => "Lessons with this Speaker", 'to' => 'Speakers in this Lesson')
        ) );
        
				p2p_register_connection_type( array(
          'name' => 'playlist_to_talks',
          'from' => IbioPlaylist::$post_type,
          'to' => IbioTalk::$post_type,
          'cardinality' => 'many-to-many',
          'admin_column' => 'any',
          'title' => array('from' => "Talks on Playlist", 'to' => 'Playlists')
        ) );
        
        /*p2p_register_connection_type( array(
          'name' => 'tool_to_talk',
          'from' => IbioPlaylist::$post_type,
          'to' => IbioTalk::$post_type,
          'cardinality' => 'one-to-one',
          'admin_column' => 'any',
          'title' => array('from' => "Teaching Tools for This Talk", 'to' => 'Talk')
        ) ); */
        
				/* p2p_register_connection_type( array(
          'name' => 'playlist_to_talks',
          'from' => IbioPlaylist::$post_type,
          'to' => IbioTalk::$post_type,
          'cardinality' => 'one-to-many',
          'admin_column' => 'any',
          'title' => array('from' => "Talks on Playlist", 'to' => 'Playlists')
        ) );*/
                
      } else {
      	error_log('Posts 2 Posts is not loaded yet.');
      }
	}
	
	function create_taxonomies(){
	    /* register_taxonomy('length', 'post', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Length',
            'singlur_name' => 'length',
            'all_items' => 'All Lengths',
            'edit_item' => 'Edit Length',
            'view_item' => 'View Length',
            'update_item' => 'Update Length',
            'add_new_item' => 'Add New Length',
            'new_item_name' => 'New Length Name',
            'parent_item' => 'Parent Length',
            'parent_item_colon' => "Parent Length: ",
            'search_items' => 'Search Lengths',
            'popular_items' => 'Populuar Lengths',
            'separate_items_with_commas' => 'Separate lengths with commas',
            'add_or_remove_items' => 'Add or remove lengths',
            'choose_from_most_used' => 'Choose from most used lengths',
            'not_found' => 'No lengths found.',
            'menu_name' => 'Length',
        ),
        'rewrite' => array(
            'slug' => 'length',
            'hierarchical' => true,
        )
    ));*/
    	
    	$post_types = array ( 'post', 
    	                     IBioTalk::$post_type,
    	                     IBioLesson::$post_type,
    	                     IBioPlaylist::$post_type );
    	                     
	    register_taxonomy('audience', $post_types, array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Audience',
            'singlur_name' => 'audience',
            'all_items' => 'All Audiences',
            'edit_item' => 'Edit Audience',
            'view_item' => 'View Audience',
            'update_item' => 'Update Audience',
            'add_new_item' => 'Add New Audience',
            'new_item_name' => 'New Audience Name',
            'parent_item' => 'Parent Audience',
            'parent_item_colon' => "Parent Audience: ",
            'search_items' => 'Search Audiencea',
            'popular_items' => 'Populuar Audiences',
            'separate_items_with_commas' => 'Separate audiences with commas',
            'add_or_remove_items' => 'Add or remove audiences',
            'choose_from_most_used' => 'Choose from most used audiences',
            'not_found' => 'No audiences found.',
            'menu_name' => 'Audience',
        ),
        'rewrite' => array(
            'slug' => 'audience',
            'hierarchical' => true,
        )
    ));
    
    
	}

}

global $ibiology_content;
$ibiology_content = new IBioContent();

/* -----------   Activate / Deactivate  ------------- */
register_activation_hook(__FILE__, 'tl_profiles_activate');
register_deactivation_hook(__FILE__, 'tl_profiles_deactivate');

function tl_profiles_activate(){
	flush_rewrite_rules();
}

function tl_profiles_deactivate(){

}