$(function() {
	var moveLeft = 0;
	var moveDown = 0;
    
    $('.profileHover').hoverIntent({
    	over: hoverOn,
    	out: hoverOff,
    	timeout: 100,
    });

    $('.profileHover').mousemove(function(e) {
        var target = '#' + ($(this).attr('data-popbox'));
         
        leftD = e.pageX + parseInt(moveLeft);
        maxRight = leftD + $(target).outerWidth();
        windowLeft = $(window).width() - 40;
        windowRight = 0;
        maxLeft = e.pageX - (parseInt(moveLeft) + $(target).outerWidth() + 20);
         
        if(maxRight > windowLeft && maxLeft > windowRight)
        {
            leftD = maxLeft;
        }
     
        topD = e.pageY - parseInt(moveDown);
        maxBottom = parseInt(e.pageY + parseInt(moveDown) + 20);
        windowBottom = parseInt(parseInt($(document).scrollTop()) + parseInt($(window).height()));
        maxTop = topD;
        windowTop = parseInt($(document).scrollTop());
        if(maxBottom > windowBottom)
        {
            topD = windowBottom - $(target).outerHeight() - 20;
        } else if(maxTop < windowTop){
            topD = windowTop + 20;
        }
     
        $(target).css('top', topD).css('left', leftD);
     
     
    });
 
});
var profile;
function hoverOn() {
	profile = '';
    var target = '#' + ($(this).attr('data-popbox'));
    
    var id = $(this).text();
    console.log(id);

    $.ajax({
		type: 'POST',
		url: "../getProfile/getProfile.php",
		data: {id:id},
		dataType: "json",
		async: false,
        cache: false,
        timeout: 30000,
        error: function(){
            return true;
        },
        success: function(result){ 
            profile = result;
    	}
	});

    $('.popbox h2').text(profile.displayName);
    var biggerImageUrl = profile.image.url
    biggerImageUrl = biggerImageUrl.slice(0,-2);
    biggerImageUrl += "150";

    $('.popbox p').html('<img src="'+biggerImageUrl+'" alt="Profile Picture">');
    console.log();

    $(target).show();
    moveLeft = $(this).outerWidth();
    moveDown = ($(target).outerHeight() / 2);
}
function hoverOff() {
	var target = '#' + ($(this).attr('data-popbox'));
    $(target).hide();
}