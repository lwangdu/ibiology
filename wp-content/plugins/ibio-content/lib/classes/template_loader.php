<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Template Loader
 *
 * @class 		IBio_Template_Loader
 * @version		1.0
 * @package		IBiology
 * @category	Class
 * @author 		Tech Liminal
 * @description A template loader based on WooCommerce's excellent feature.
 *
 */
class IBio_Template_Loader {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_filter( 'template_include', array( __CLASS__, 'template_loader' ) );
	}

	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. LBLProfile looks for theme 
	 * overrides in /theme/profiles/ by default.
	 *
	 *
	 * @param mixed $template
	 * @return string
	 */
	public static function template_loader( $template ) {
	
		//error_log( "Template Loader- " . $template );
	
		$find = array( );
		$file = '';

		global $ibiology_content;

		if ( is_embed() ) {
			return $template;
		}

		$post_type = get_post_type();
		if ( $post_type == IBioTalk::$post_type 
					|| $post_type == IBioSpeaker::$post_type 
					|| $post_type == IBioPlaylist::$post_type 
					|| $post_type == IBioResource::$post_type
					|| $post_type == IBioLesson::$post_type ) {
        
      if ( is_single() ) {
        $file 	= 'single-'.$post_type.'.php';
        $find[] = $file;
        $find[] = $ibiology_content->template_path . $file;
      } else if ( is_archive() ) {
        $file 	= 'archive-'.$post_type.'.php';
        $find[] = $file;
        $find[] = $ibiology_content->template_path . $file;      
      }
    }
		if ( $file ) {
			$template = locate_template( array_unique( $find ) );
			if ( ! $template ) {
				$template = $ibiology_content->template_path . $file;
			}
		}

		return $template;
	}

}

IBio_Template_Loader::init();
