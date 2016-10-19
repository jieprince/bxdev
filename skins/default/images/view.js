$(document).ready(function(e) {
    
});

// --- 下载 ---
function downurlGet(id) {
	$('#downurlGetMask').css({'left':$(window).width()/2-$('#downurlGetMask').outerWidth()/2+'px','top':$(window).height()/2-$('#downurlGetMask').outerHeight()/2+$(window).scrollTop()+'px','opacity':'0'}).show().animate({opacity:'.7'}, 200, function() {
		$.ajax({
			type:'POST',
			cache:false,
			data:{id:id},
			url:'ajax.php?ac=downurlget',
			success: function(j) {
				j = $.parseJSON(j);
				window.location.href = j.url;
				$('#downurlGetMask').fadeOut();
			}
		});
	});
}