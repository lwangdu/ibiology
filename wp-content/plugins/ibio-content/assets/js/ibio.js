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
    });

    $('.toggle').click(function (e) {
        //console.log( e.target );
        $("#" + $(e.target).data('toggle')).toggle(500);
    });

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


// Prep YouTube videos in iFrames on the single talk page
var playerCurrentlyPlaying = null;
var players = new Array();

window.onYouTubeIframeAPIReady = function(){
    jQuery('.single-video .content').each(function () {
        var player_id = jQuery(this).children('iframe').attr("id");
        players[player_id] = new YT.Player(player_id, {
           events:{
               'onStateChange' : function(event){
                   console.log(event.data);
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
                   console.log( "REady with the video player #" + player_id)
               }

           }

        });


         console.log(jQuery(this).children('iframe').attr("id"));

    });
}
