<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Template Loader
 *
 * @class 		LBLProfile_Template_Loader
 * @version		1.0
 * @package		LBLProfile
 * @category	Class
 * @author 		Tech Liminal
 * @description A template loader based on WooCommerce's excellent feature.
 *
 */
class LBLProfile_Template_Loader {

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

		global $lbl_profiles_plugin;

		if ( is_embed() ) {
			return $template;
		}

		if ( is_single() && get_post_type() == LBLStaffProfile::$post_type ) {

			$file 	= 'single-staff_page.php';
			$find[] = $file;
			$find[] = $lbl_profiles_plugin->template_path . $file;


		} elseif ( is_post_type_archive( 'staff_page' )  ) {

			$file 	= 'archive-staff_page.php';
			$find[] = $file;
			$find[] = $lbl_profiles_plugin->template_path . $file;

		}

		if ( $file ) {
			$template       = locate_template( array_unique( $find ) );
			if ( ! $template ) {
				$template = $lbl_profiles_plugin->template_path . $file;
			}
		}

		return $template;
	}

}

LBLProfile_Template_Loader::init();
