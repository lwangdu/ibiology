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

class IBioContentPlugin{
	
	
	public $talks;
	public $speakers;
	public $playlists;
	
	public $template_path;
	
	function __construct(){
		
		$this->template_path = plugin_dir_path( __FILE__ ) . 'templates/';
		
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
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/speakers.php' );
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/playlists.php' );
	
		/* Functions */
		
		/* Classes */
		require_once ( plugin_dir_path( __FILE__ ) . '/lib/classes/template_loader.php' );
		require_once ( plugin_dir_path( __FILE__ ) . '/lib/classes/fields_display_helper.php' );
	}


	function init_objects(){
		$this->speakers = new IBioSpeaker();
		$this->talks = new IBioTalk();
		$this->playlists = new IBioPlaylist();
		

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
          'name' => 'playlist_to_talks',
          'from' => IbioPlaylist::$post_type,
          'to' => IbioTalk::$post_type,
          'cardinality' => 'one-to-many',
          'admin_column' => 'any',
          'title' => array('from' => "Talks on Playlist", 'to' => 'Playlists')
        ) );
      } else {
      	error_log('Posts 2 Posts is not loaded yet.');
      }
	}
	
	function create_taxonomies(){
	    register_taxonomy('length', 'post', array(
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
    ));
    
    register_taxonomy('level', 'post', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Level / Site Area',
            'singlur_name' => 'level',
            'all_items' => 'All Levels',
            'edit_item' => 'Edit Level',
            'view_item' => 'View Level',
            'update_item' => 'Update Level',
            'add_new_item' => 'Add New Level',
            'new_item_name' => 'New Level Name',
            'parent_item' => 'Parent Level',
            'parent_item_colon' => "Parent Level: ",
            'search_items' => 'Search Levels',
            'popular_items' => 'Populuar Levels',
            'separate_items_with_commas' => 'Separate levels with commas',
            'add_or_remove_items' => 'Add or remove levels',
            'choose_from_most_used' => 'Choose from most used levels',
            'not_found' => 'No levels found.',
            'menu_name' => 'Level',
        ),
        'rewrite' => array(
            'slug' => 'level',
            'hierarchical' => true,
        )
        
        
    ));
    
    register_taxonomy('topics', 'post', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Topics',
            'singlur_name' => 'topic',
            'all_items' => 'All Topic',
            'edit_item' => 'Edit Topics',
            'view_item' => 'View Topics',
            'update_item' => 'Update Topics',
            'add_new_item' => 'Add New Topic',
            'new_item_name' => 'New Topic Name',
            'parent_item' => 'Parent Topic',
            'parent_item_colon' => "Parent Topic: ",
            'search_items' => 'Search Topics',
            'popular_items' => 'Populuar Topics',
            'separate_items_with_commas' => 'Separate topics with commas',
            'add_or_remove_items' => 'Add or remove topics',
            'choose_from_most_used' => 'Choose from most used topics',
            'not_found' => 'No topics found.',
            'menu_name' => 'Topics',
        ),
        'rewrite' => array(
            'slug' => 'topics',
            'hierarchical' => true,
        )
        
        
    ));
    
    register_taxonomy('English Subtitles', 'post', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'English Subtitles',
            'singlur_name' => 'English Subtitles',
            'all_items' => 'All English Subtitles',
            'edit_item' => 'Edit English Subtitles',
            'view_item' => 'View English Subtitles',
            'update_item' => 'Update English Subtitles',
            'add_new_item' => 'Add New English Subtitle',
            'new_item_name' => 'New English Subtitle Name',
            'parent_item' => 'Parent English Subtitle',
            'parent_item_colon' => "Parent English Subtitle: ",
            'search_items' => 'Search English Subtitles',
            'popular_items' => 'Populuar English Subtitles',
            'separate_items_with_commas' => 'Separate english subtitles with commas',
            'add_or_remove_items' => 'Add or remove english subtitles',
            'choose_from_most_used' => 'Choose from most used english subtitles',
            'not_found' => 'No english subtitles found.',
            'menu_name' => 'English Subtitles',
        ),
        'rewrite' => array(
            'slug' => 'english-subtitles',
            'hierarchical' => true,
        )
        
        
    ));
    
    register_taxonomy('educator resources', 'post', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Educator Resources',
            'singlur_name' => 'Educator Resource',
            'all_items' => 'All Educator Resources',
            'edit_item' => 'Edit Educator Resources',
            'view_item' => 'View Educator Resources',
            'update_item' => 'Update Educator Resources',
            'add_new_item' => 'Add New Educator Resources',
            'new_item_name' => 'New Educator Resournce Name',
            'parent_item' => 'Parent Educator Resource',
            'parent_item_colon' => "Parent Educator Resourc: ",
            'search_items' => 'Search Educator Resources',
            'popular_items' => 'Populuar Educator Resources',
            'separate_items_with_commas' => 'Separate educator resources with commas',
            'add_or_remove_items' => 'Add or remove educator resources',
            'choose_from_most_used' => 'Choose from most used educator resources',
            'not_found' => 'No educator resources found',
            'menu_name' => 'Educator Resources',
        ),
        'rewrite' => array(
            'slug' => 'educator-resources',
            'hierarchical' => true,
        )
        
        
    ));
    

	}

}

global $ibiology_content;
$ibiology_content = new IBioContentPlugin();

/* -----------   Activate / Deactivate  ------------- */
register_activation_hook(__FILE__, 'tl_profiles_activate');
register_deactivation_hook(__FILE__, 'tl_profiles_deactivate');

function tl_profiles_activate(){
	flush_rewrite_rules();
}

function tl_profiles_deactivate(){

}