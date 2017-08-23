jQuery(document).ready(function($) {

	//find the selected part and make it active.
	var part = window.location.hash.substr(1);
	if ( part == '' ){ part = 'part-1'; }
	
	$( '.videos .' + part ).addClass( 'active' );
	
	$( '.videos-nav li').click( function( e ) {
		e.preventDefault();	
		$( '.videos .active').removeClass('active');
		$( '.videos .' + $(this).data('select') ).addClass ('active');
		window.location.hash = '#' + $(this).data('select');
	} );
	
	$( '.toggle' ).click( function( e ) {
			//console.log( e.target );
		  $( "#" + $(e.target).data('toggle') ).toggle(500);
	});
	
});