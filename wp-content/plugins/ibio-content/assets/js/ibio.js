
// Prep YouTube videos in iFrames on the single talk page
var playerCurrentlyPlaying = null;
var players = new Array();


jQuery(document).ready(function($) {

    //find the selected part and make it active.
    var part = window.location.hash.substr(1);
    if (part == '') {
        part = 'part-1';
    }

    $('.videos .' + part).addClass('active');

    $('.videos-nav li').click(function (e) {
        e.preventDefault();
        $('.videos .active').removeClass('active');
        $('.videos .' + $(this).data('select')).addClass('active');
        window.location.hash = '#' + $(this).data('select');
        if ( playerCurrentlyPlaying ){
            players[playerCurrentlyPlaying].pauseVideo();
            playerCurrentlyPlaying = null;
        }
    });

    $('.toggle').click(function (e) {
        e.preventDefault();
        $("#" + $(e.target).data('toggle')).toggle(250, function(data) {
            var api = $("#" + $(e.target).data('toggle') +' .scroll-pane').data('jsp');
            if (api) api.reinitialise();
            $(e.target).toggleClass('open');
        });
        console.log( e.target );
    });

    $('.scroll-pane').jScrollPane({
        showArrows: true,
        horizontalGutter: 10,
        contentWidth: 500
    }).bind(
        'mousewheel',
        function(e)
        {
            e.preventDefault();
        }
    );;

    //$('[data-toggle="tab"]').tab();

    $('.expandable').each(function (i, v) {

        $(this).attr('id', 'expandable-' + i);
        $(this).append('<div class="control"><a class="expand more-link" data-target="#expandable-' + i + '">See More</a></div>');
    });

    /****  Create Expandable sections ****/

    $('.expand').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        if ($(this).hasClass('isopen')) {
            $(e.target).text('See More');
            $($(e.target).data('target')).animate({height: '200px'}, 500, function (t) {
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
    });

});

window.onYouTubeIframeAPIReady = function(){
    jQuery('.single-video .content').each(function () {
        var player_id = jQuery(this).children('iframe').attr("id");
        players[player_id] = new YT.Player(player_id, {
           events:{
               'onStateChange' : function(event){
                   if (event.data == YT.PlayerState.PLAYING) {
                       if (playerCurrentlyPlaying != null && playerCurrentlyPlaying != player_id) {
                           jQuery('#'+playerCurrentlyPlaying).parents('.single-video').removeClass('playing');
                           players[playerCurrentlyPlaying].pauseVideo();
                       }
                       playerCurrentlyPlaying = player_id;
                       // hide the title so it doesn't display on top of the video
                        jQuery('#'+player_id).parents('.single-video').addClass('playing');



                   }
               },
               'onReady': function(){

               }

           }

        });


    });
}
