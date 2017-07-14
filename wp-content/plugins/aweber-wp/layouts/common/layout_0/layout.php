<?php

function fca_eoi_layout_descriptor_0( $name, $layout_id, $texts ) {
	require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-layout.php';
	$layout = new EasyOptInsLayout( $layout_id );
	$class = $layout->layout_class;

	return array(

		'name' => __( $name ),

		'editables' => array(

			// Added to the fieldset "Form Background"
			'form' => array(
				'.fca_eoi_layout_0.' . $class => array(
					'background-color' => array( __( 'Form Background Color' ), '#FFF' ),
					'border-color' => array( __( 'Border Color' ), '#000' ),
					'width' => array( __( 'Width' ), '100%' ),
					'text-align' => array( __( 'Alignment' ), 'center' ),
				),
			),

			// Added to the fieldset "Headline"
			'headline' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_headline_copy_wrapper div' => array(
					'font-size' => array( __('Font Size'), '25px'),
					'color' => array( __('Font Color'), '#000'),
				),
			),
			'description' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_description_copy_wrapper p, ' .
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_description_copy_wrapper div' => array(
					'font-size' => array( __('Font Size'), '14px'),
					'color' => array( __('Font Color'), '#000'),
				),
			),
			'name_field' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_name_field_wrapper, ' .
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_name_field_wrapper input' => array(
					'color' => array( __( 'Font Color' ), '#000' ),
					'font-size' => array( __( 'Font Size' ), '14px'),
					
				),
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_name_field_wrapper' => array(
					'border-color' => array( __('Border Color'), '#000'),
					'width' => array( __( 'Width' ), '100%'),
				),
			),
			'email_field' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_email_field_wrapper, ' .
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_email_field_wrapper input' => array(
					'color' => array( __( 'Font Color' ), '#000' ),
					'font-size' => array( __( 'Font Size' ), '14px'),
					
				),
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_email_field_wrapper' => array(
					'border-color' => array( __( 'Border Color' ), '#000'),
					'width' => array( __( 'Width' ), '100%'),
				),
			),
			'button' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_submit_button_wrapper input' => array(
					'font-size' => array( __('Font Size'), '14px'),
					'color' => array( __( 'Font Color' ), '#FFF' ),
					'background-color' => array( __( 'Button Background Color' ), '#000' ),
					'hover-color' => array( __( 'Button Hover Color' ), '#933B00' ),
					'width' => array( __( 'Width' ), '100%'),
				),
			),
			'privacy' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_privacy_copy_wrapper div' => array(
					'font-size' => array( __('Font Size'), '14px'),
					'color' => array( __('Font Color'), '#FFF'),
				),
			),
			'fatcatapps' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a, ' .
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a:hover' => array(
					'color' => array( __('Font Color'), '#3197e1'),
				),
			),
		)
	);
}