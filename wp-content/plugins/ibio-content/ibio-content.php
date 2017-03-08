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
	
	public $template_path;
	
	function __construct(){
		
		$this->template_path = plugin_dir_path( __FILE__ ) . 'templates/';
		
		$this->load_files();
		$this->init_objects();
		
		

		add_action('admin_enqueue_scripts', array( &$this, 'load_admin_scripts' ));

		add_action( 'wp_enqueue_scripts', array( &$this, 'load_scripts' ) );
	}


	/**
 	* Load all of the function files needed for producing the plugin. 
 	*/
	function load_files(){

		/* Post Types and Classes */
		include (  plugin_dir_path( __FILE__ ) . '/lib/post-types/talks.php' );
	
		/* Functions */
		require_once ( plugin_dir_path( __FILE__ ) . '/lib/functions/templates.php' );
		
		/* Classes */
		require_once ( plugin_dir_path( __FILE__ ) . '/lib/classes/template_loader.php' );
	}


	function init_objects(){

		global $lbl_profiles;
		$lbl_profiles = new LBLStaffProfile();
		$lbl_profiles->init();
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

}

global $lbl_profiles_plugin;
$lbl_profiles_plugin = new LBLProfilePlugin();

/* -----------   Activate / Deactivate  ------------- */
register_activation_hook(__FILE__, 'tl_profiles_activate');
register_deactivation_hook(__FILE__, 'tl_profiles_deactivate');

function tl_profiles_activate(){
	flush_rewrite_rules();
}

function tl_profiles_deactivate(){

}