jQuery(document).ready(function($) {

	//find the first video and make it active (but we will need to check for the parameter value to make the right one active later)
	$( '.videos .part-1' ).addClass( 'active' );
	
	$( '.videos-nav li').click( function( e ) {
		$( '.videos .active').removeClass('active');
		$( '.videos .' + $(e.target).data('select') ).addClass ('active');
	} );
});