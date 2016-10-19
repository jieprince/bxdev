function perpImg(src, func) {
	$('<img src="'+src+'">').appendTo('#perpimg').on('load', func);
}
$.fn.extend({     
	center:function() {     
		this.css({'left':$(window).width()/2-this.outerWidth()/2+'px','top':$(window).height()/2-this.outerHeight()/2+'px'});
		return this;
	}     
});
$(document).ready(function(e) {
	$('#ie').html('Okey, please wait...');
	perpImg('images/start-logo.png',function() {
		$('#tb,#ie').remove();
		$('#startlogo').show().css({'animation':'startlogoAni 1s linear forwards'}).center()
		setTimeout(function() {
			$('#startlogoLight').css({'animation':'startlogoLightAni 4s ease infinite'});
		}, 1000);
	});
}).on('contextmenu', function() {
	return false;
});
$(window).resize(function() {
	$('.center').each(function() {
        $(this).center();
    });
});