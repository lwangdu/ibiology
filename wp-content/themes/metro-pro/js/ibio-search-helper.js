// JavaScript Document

/* Accept a click on the checkbox DIVs as a checkbox click */
jQuery(document).ready(function () {
	
	
	function toggleEngSub(){
		var cbx = jQuery('#p_eng_sub');
		if (cbx && cbx.is(":checked")) {
			cbx.prop("checked", false);
		}
		else {
			cbx.prop("checked", true);
		}
	}
	
	function toggleEduRes(){
		var cbx = jQuery('#p_edu_res');
		if (cbx && cbx.is(":checked")) {
			cbx.prop("checked", false);
		}
		else {
			cbx.prop("checked", true);
		}
	}
	
	jQuery("#p_eng_sub_holder").click(function () {
		toggleEngSub();
	});
	jQuery("#p_eng_sub_holder label").click(function () {
		toggleEngSub();
	});
	jQuery("#p_edu_res_holder").click(function () {
		toggleEduRes();
	});
	jQuery("#p_edu_res_holder label").click(function () {
		toggleEduRes();
	});
	
	
	jQuery('input[type=checkbox]').click(function (e) {
		e.stopPropagation();
	});

});

 