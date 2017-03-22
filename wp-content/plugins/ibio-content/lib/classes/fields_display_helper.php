<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Template Loader
 *
 * @class 		IBio_Fields_Display_Helper
 * @version		1.0
 * @package		IBiology
 * @category	Class
 * @author 		Tech Liminal
 * @description Use to display content from ACF Fields in a generic Way
 *
 */
class IBio_Fields_Display_Helper {

	public function show_field_group($field_group_id){
	
		$all_fields = acf_get_fields($field_group_id);

		var_dump($all_fields);
	
	}
	
}
