$(document).ready(function(e) {
    // --- login btn ---
	$('#loginBtn').on('click', function() {
		if(!$('#usernameTxt').is(':disabled')) {
			$(this).css({'opacity':'.5'});
			$('#usernameTxt,#passwordTxt').prop('disabled', true);
			$.ajax({
				type: 'POST',
				cache: false,
				url: 'ajax.php?ac=login',
				data: {username:$('#usernameTxt').val(),password:$('#passwordTxt').val()},
				success: function(j) {
					j = $.parseJSON(j);
					if(j.result > 0) {
						window.location.href = 'disk.php';
					} else {
						alert(j.msg);
						$('#usernameTxt,#passwordTxt').prop('disabled', false);
						$('#loginBtn').css({'opacity':'1'});
					}
				},
				error: function() {
					alert('网络连接失败');
					$('#usernameTxt,#passwordTxt').prop('disabled', false);
					$('#loginBtn').css({'opacity':'1'});
				}
			});
		}
	});
	$('#usernameTxt,#passwordTxt').on('keypress', function(e) {
		if(e.keyCode == 13) $('#loginBtn').trigger('click');
	});
	// --- 轮换 ---
	$('#shows').children('div:not(.selected)').hide();
	if($('#shows').children('div').size()>1) {
		setInterval(function() {
			$next = ($('#shows').children('.selected').next().size() == 0) ? $('#shows').children(':eq(0)') : $('#shows').children('.selected').next();
			$('#shows').children('.selected').removeClass('selected').fadeOut(1000);
			$next.addClass('selected').fadeIn(1000);
		}, 3000);
	}
});