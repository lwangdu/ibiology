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
		
		add_filter( 'rewrite_rules_array', array( &$this, 'rewrite_rules' ) );
	}


	/**
 	* Load all of the function files needed for producing the plugin. 
 	*/
	function load_files(){

		/* Post Types and Classes */
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/talks.php' );
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/course-session.php' );
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/speakers.php' );
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/playlists.php' );
		//include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/educator-resource.php' );
	
		/* Functions */
		include (  plugin_dir_path( __FILE__ ) . '/lib/functions/content.php' );
		include (  plugin_dir_path( __FILE__ ) . '/lib/functions/playlist.php' );
				
		/* Classes */
		require_once ( plugin_dir_path( __FILE__ ) . '/lib/classes/template_loader.php' );
		require_once ( plugin_dir_path( __FILE__ ) . '/lib/classes/fields_display_helper.php' );
	}


	function init_objects(){
		$this->speakers = new IBioSpeaker();
		$this->talks = new IBioTalk();
		$this->sessions = new IBioSession();
		$this->playlists = new IBioPlaylist();
		//$this->resources = new IBioResource();
		

	}

	/* Load styles and scripts for use in the admin interface */
	function load_admin_scripts(){
		wp_enqueue_style('ibio-admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css' );
		//wp_register_script( 'js-datatables', '//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js', array( 'jquery' ), '1.10.12', true);
		wp_enqueue_script( 'ibio-app', plugin_dir_url( __FILE__ ) . '/assets/js/ibio.js', array( 'jquery' ), '1.0.0' );
		//wp_enqueue_script( 'js-datatables');
		//wp_enqueue_style( 'js-datatables-css', '//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css');
	} 

	/* Load display scripts */
	function load_scripts(){
		wp_enqueue_script('bootstrap', plugin_dir_url( __FILE__ ) . '/assets/js/bootstrap.js', array( 'jquery'), '1.0.0' );
		wp_enqueue_script( 'ibio-app', plugin_dir_url( __FILE__ ) . '/assets/js/ibio.js', array( 'jquery' ), '1.0.0' );
		//wp_enqueue_style('bootstrap', plugin_dir_url( __FILE__ ) . '/assets/css/bootstrap.css' ); 
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
          'name' => 'speaker_to_session',
          'from' => IbioSpeaker::$post_type,
          'to' => IbioSession::$post_type,
          'cardinality' => 'many-to-many',
          'admin_column' => 'any',
          'title' => array('from' => "Sessions with this Speaker", 'to' => 'Speakers in this Session')
        ) );

          p2p_register_connection_type( array(
          'name' => 'playlist_to_talks',
          'from' => IbioPlaylist::$post_type,
          'to' => IbioTalk::$post_type,
          'cardinality' => 'many-to-many',
          'admin_column' => 'to',
          'fields' => array(
						'order' => array(
							'title' => 'Order',
							'type'	=> 'text'
						)
					),
          'title' => array('from' => "Talks on Playlist", 'to' => 'Playlists')
        ) );

          p2p_register_connection_type( array(
                'name' => 'playlist_to_session',
                'from' => IbioPlaylist::$post_type,
                'to' => IBioSession::$post_type,
                'cardinality' => 'many-to-many',
                'admin_column' => 'to',
                'fields' => array(
                    'order' => array(
                        'title' => 'Order',
                        'type'	=> 'text'
                    )
                ),
                'title' => array('from' => "Sessions on Playlist", 'to' => 'Playlists')
            ) );
        
                
      } else {
      	error_log('Posts 2 Posts is not loaded yet.');
      }
	}
	
	function create_taxonomies(){
    	$post_types = array ( 'post', 
    	                     IBioTalk::$post_type,
    	                     IBioSession::$post_type,
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

	// Rewrite rules for individual talks

	function rewrite_rules( $rules_array ) {
		$new = array();


		//get the list of categories and create a rewrite rules for each.
        // We get them in order by parent so that we can quickly build the array we need.
        $cats = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false, 'hierarchical' => true, 'fields' => 'all', 'orderby' => 'parent'));

        //$cats = get_categories( array( 'taxonomy' => 'category', 'hide_empty' => false, 'hierarchical' => true, 'fields' => 'all', 'orderby' => 'parent'));

        // extract the parents and build the array for generating the rules
        $cr = array();
        foreach ($cats as $c){
            if ( $c->parent == 0 ) {
                $cr[ $c->term_id ] = array( 'slug' => $c->slug, 'rewrite' => false ) ;
            } else {
                $cr[$c->term_id] = array( 'slug' => $cr[$c->parent]['slug'] . '/' . $c->slug, 'rewrite' => true);
            }
        }

        foreach ($cr as $c){
            if ($c['rewrite']) {
                $match = $c['slug'] . '/(.+)?$';
                $new[$match] = 'index.php?ibiology_talk=$matches[1]';
            }
        }

		//$new[ 'research-talks/cell-biology/(.+)?$']  = 'index.php?ibiology_talk=$matches[1]';

		//$new[ 'talks/([^/]+/(.+)/?$' ] = 'index.php?ibiology_talk=$matches[2]';
		//$new[ 'talks/
		
		return array_merge( $new, $rules_array );
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