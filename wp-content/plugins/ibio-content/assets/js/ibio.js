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

	$( '.expandable' ).each( function( i, v ) {

		$(this).attr('id', 'expandable-' + i);
		$(this).append('<div class="control"><a class="expand more-link" data-target="#expandable-' + i +'">See More</a></div>');
    });

	$( '.expand').click( function(e){
		e.preventDefault();
        e.stopPropagation();
        if ($(this).hasClass('isopen')) {
            $(e.target).text('See More');
            $($(e.target).data('target')).animate({height:'200px'}, 500, function(t) {
            	$(this).toggleClass('expanded');
            });
        } else {
            $(e.target).text('See Less');
            $($(e.target).data('target')).animate({height: '600px'}, 500, function (t) {
                $(this).height('auto');
                $(this).toggleClass('expanded');
            });
        }
		$(this).toggleClass('isopen');
    })

});