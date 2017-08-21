jQuery(document).ready(function($) {

	//find the first video and make it active (but we will need to check for the parameter value to make the right one active later)
	$( '.videos .part-1' ).addClass( 'active' );
	
	$( '.videos-nav li').click( function( e ) {
		e.preventDefault();	
		$( '.videos .active').removeClass('active');
		$( '.videos .' + $(this).data('select') ).addClass ('active');
	} );
	
	$( '.toggle' ).click( function( e ) {
			//console.log( e.target );
		  $( "#" + $(e.target).data('toggle') ).toggle(500);
	});
	
});