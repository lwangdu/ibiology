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
	 * Templates are in the 'templates' folder. IBio_Template_Loader looks for theme 
	 * overrides in the theme's folder
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
		error_log( '[template_loader] post_type = ' . serialize($post_type) );
		if ( $post_type == IBioTalk::$post_type || $post_type == IBioSpeaker::$post_type || $post_type == IBioPlaylist::$post_type || $post_type == IBioSession::$post_type ) {      
      if ( is_single() ) {
        $file 	= 'single-'.$post_type.'.php';
        $find[] = $file;
      } else if ( is_archive() ) {
        $file 	= 'archive-'.$post_type.'.php';
        $find[] = $file;    
			}
    }  /*else if ( is_category() && !$post_type) {
      	error_log('default category');
      	$file 	= 'archive-ibiology_talk.php';
        $find[] = $file;
      }*/
    
    //error_log('[template_loader] File options: ' . serialize($find) );
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

/**
 * Get template part (for templates of iBio types).
 *
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 */
function ibio_get_template_part( $slug, $name = '' ) {

		error_log("[ibio_get_template_part] slug: $slug ; name: $name ");

		global $ibiology_content;
		
    $template = '';

    // Look in yourtheme/slug-name.php and yourtheme/ibiology/slug-name.php
    if ( $name  ) {
        $template = locate_template( array( "{$slug}-{$name}.php", $ibiology_content->template_path_slug . "/{$slug}-{$name}.php" ) );
    }

    // Get default slug-name.php
    if ( ! $template && $name && file_exists( $ibiology_content->template_path  . "/{$slug}-{$name}.php" ) ) {
        $template = $ibiology_content->template_path  . "/{$slug}-{$name}.php";
    }

    // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/ibiology/slug.php
    if ( ! $template ) {
        $template = locate_template( array( "{$slug}.php",  $ibiology_content->template_path_slug . "/{$slug}.php" ) );
    }

		error_log("[ibio_get_template_part] template: $template");

    if ( $template ) {
        load_template( $template, false );
    }
}
