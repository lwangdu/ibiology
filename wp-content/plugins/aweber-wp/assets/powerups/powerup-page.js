/* jshint asi: true */
jQuery( document ).ready(function($){
	
	$('.fca_eoi_settings_text_input').click(function() {
		$(this).select()
	})
	
	$('th').click(function(){
		$(this).next().find('input').click()
	})
   
})