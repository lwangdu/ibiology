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
		$repeaters = array();
	
		// get the regular beamline fields
		foreach($all_fields as $f){
			$fieldnames[] = $f['name'];
			
			// if it's a repeater field, fill it out in the repeaters array
			if ($f['type'] == 'repeater'){
				$fields = array();
				foreach ($f['sub_fields'] as $s){
					$fields[] = $s['name'];
				}
			$repeaters[$f['name']] = $fields;
			}
			
			
			
		}	
		

		/*echo "<h2>Repeaters</h2>";
		var_dump($repeaters);
		*/
		// show the fields that are in a table form	
		$fields = get_field_objects();

		if( $fields )
		{
			echo '<table class="subfield-detail">';
	
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
						echo $field['value'];
					} else if ($field['type'] == 'repeater' ){
						$subfields = $field['value'];
						echo "<td>";
						//var_dump($repeaters[$field['name']]);
						foreach($subfields as $sf){
							foreach($repeaters[$field['name']] as $sfn){
								switch ($sfn){
									case "video_url":
										echo wp_oembed_get($sf[$sfn]) . "<Br/>";
										break;
									case "transcript":
										echo '<div class="su-accordion"><div class="su-spoiler su-spoiler-style-default su-spoiler-icon-plus su-spoiler-closed">';
										echo '<div class="su-spoiler-title"><span class="su-spoiler-icon"></span>Expand Transcript</div><div class="su-spoiler-content su-clearfix">';
										echo  $sf[$sfn];
										echo '</div></div></div>';
										break;
									default:
										echo "<strong> $sfn :</strong>" . $sf[$sfn] . "<Br/>";
								}
							}
						}
						echo "</td>";

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
		
		/*echo "<pre>";
		var_dump($all_fields);
		echo "</pre>";	*/
	}
	
}
