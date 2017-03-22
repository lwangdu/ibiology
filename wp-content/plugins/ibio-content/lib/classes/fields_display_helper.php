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
		
		$fieldnames = array();
	
		// get the regular beamline fields
		foreach($all_fields as $f){
			$fieldnames[] = $f['name'];
		}	
		
		// show the fields that are in a table form	
		$fields = get_field_objects();

		if( $fields )
		{
			echo '<table class="beamline-detail">';
	
			foreach($fieldnames as $fn){
				if (isset($fields[$fn])){
					$field =  $fields[$fn];
					if (empty( $field['value'] ) ) continue;
				
					echo '<tr>';
					echo '<th>' . $field['label'] . '</th>';
				
					if ($field['type'] == 'file'){
						$file_parts = $field['value'];
						echo '<td><a href="' . $file_parts['url'] . '"><img class="file-icon" src="' . $file_parts['icon'] . '" alt=""/> ' . $file_parts['filename'] . '</a></td>';
					} else if ($field['type'] == 'number'){
						echo beamline_number_field($field['value']);
					} else if ($field['type'] == 'url'){
						echo '<td><a href="'.$field['value'] . '">' . $field['value'] . '</a></td>';
					} else  if (is_array($field['value'])){
						$disc = '<ul>';
						foreach($field['value'] as $d){
							$disc .= "<li>$d</li>";
						}	
						$disc .= '</ul>';				
						echo '<td>' . $disc . '</td>';
					} else {
						echo '<td>' . $field['value']. '</td>';
					}	
				echo '</tr>';
				}		
			}
			
			echo "</table>";
			
		}
		
		echo "<pre>";
		var_dump($all_fields);
		echo "</pre>";	
	}
	
}
